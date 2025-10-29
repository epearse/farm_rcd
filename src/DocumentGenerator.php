<?php

declare(strict_types=1);

namespace Drupal\farm_sli;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\farm_sli\Event\GenerateDocumentEvent;
use Drupal\farm_sli\Placeholder\ListBlockPlaceholder;
use Drupal\farm_sli\Placeholder\ListStringPlaceholder;
use Drupal\farm_sli\Placeholder\PlaceholderInterface;
use Drupal\farm_sli\Placeholder\StringPlaceholder;
use Drupal\file\FileInterface;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Document generator logic.
 */
class DocumentGenerator implements DocumentGeneratorInterface {

  public function __construct(
    protected EventDispatcherInterface $eventDispatcher,
    protected ConfigFactoryInterface $configFactory,
    protected FileSystemInterface $fileSystem,
    protected EntityTypeManagerInterface $entityTypeManager,
    protected AccountInterface $currentUser,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function generate(string $template_path, ?string $filename = NULL, array $context = []): FileInterface {

    // Create a TemplateProcessor object from the template file.
    $template = new TemplateProcessor($template_path);

    // If a logo is available, add it.
    // Otherwise, remove the placeholder.
    $logo_path = $this->configFactory->get('farm_sli.settings')->get('logo_path');
    if (!empty($logo_path)) {
      $template->setImageValue('logo', $logo_path);
    }
    else {
      $template->setValue('logo', '');
    }

    // Create and dispatch a GenerateDocumentEvent.
    $event = new GenerateDocumentEvent($context);
    $this->eventDispatcher->dispatch($event, GenerateDocumentEvent::EVENT_NAME);

    // Perform placeholder replacement.
    $this->replacePlaceholders($template, $event->getPlaceholders());

    // If a filename was not provided, generate one.
    if (is_null($filename)) {
      $filename = 'document-' . date('c') . '.docx';
    }

    // Prepare the file directory.
    $default_file_scheme = $this->configFactory->get('system.file')->get('default_scheme') ?? 'public';
    $directory = $default_file_scheme . '://docs';
    $this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);

    // Build the file URI.
    $uri = "$directory/$filename";

    // Save the file.
    $template->saveAs($uri);

    // Create and return a file entity.
    /** @var \Drupal\file\FileInterface $file */
    $file = $this->entityTypeManager->getStorage('file')->create(['uri' => $uri]);
    $file->setOwnerId($this->currentUser->id());
    $file->setTemporary();
    $file->save();
    return $file;
  }

  /**
   * Perform placeholder replacements in a template.
   *
   * @param TemplateProcessor $template
   *   The template processor.
   * @param PlaceholderInterface[] $placeholders
   *   The placeholders.
   * @param string $suffix
   *   A suffix to append to the search string.
   *
   * @throws Exception
   */
  protected function replacePlaceholders(TemplateProcessor $template, array $placeholders, string $suffix = '') {
    foreach ($placeholders as $i => $placeholder) {

      // Build the search string with suffix.
      $search_string = $placeholder->search . $suffix;

      // Replace a simple string.
      if ($placeholder instanceof StringPlaceholder) {
        $escaped_value = htmlspecialchars($placeholder->replace);
        $template->setValue($search_string, $escaped_value);
      }

      // Replace a bulleted list of strings.
      elseif ($placeholder instanceof ListStringPlaceholder) {
        $template->cloneBlock($search_string, count($placeholder->replace), TRUE, TRUE);
        foreach ($placeholder->replace as $delta => $item) {
          $escaped_value = htmlspecialchars($item);
          $template->setValue('item' . $suffix . '#' . ($delta + 1), $escaped_value);
        }
      }

      // Replace a repeating block (may contain nested replacements).
      elseif ($placeholder instanceof ListBlockPlaceholder) {
        $template->cloneBlock($search_string, count($placeholder->replace), TRUE, TRUE);
        foreach ($placeholder->replace as $delta => $block_placeholders) {
          $this->replacePlaceholders($template, $block_placeholders, $suffix . '#' . ($delta + 1));
        }
      }

      // Throw an unsupported type error.
      else {
        throw new Exception('Unsupported placeholder type: ' . $placeholder::class);
      }
    }
  }

}
