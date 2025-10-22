<?php

declare(strict_types=1);

namespace Drupal\farm_sli;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\farm_sli\Bundle\PlanDocumentTemplateInterface;
use Drupal\file\FileInterface;
use Drupal\plan\Entity\PlanInterface;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * Document generator logic.
 */
class DocumentGenerator implements DocumentGeneratorInterface {

  public function __construct(
    protected ModuleHandlerInterface $moduleHandler,
    protected ConfigFactoryInterface $configFactory,
    protected FileSystemInterface $fileSystem,
    protected EntityTypeManagerInterface $entityTypeManager,
    protected AccountInterface $currentUser,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function generate(PlanInterface $plan, ?string $filename = NULL): FileInterface {

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

    // Generate the document using a template and replacement variables, if
    // available.
    if ($plan instanceof PlanDocumentTemplateInterface) {

      // Create a TemplateProcessor from the plan's template file.
      $module_path = $this->moduleHandler->getModule('farm_sli')->getPath();
      $template = new TemplateProcessor($module_path . '/templates/' . $plan->templateFilename());

      // If a logo is available, add it.
      // Otherwise, remove the placeholder.
      $logo_path = $this->configFactory->get('farm_sli.settings')->get('logo_path');
      if (!empty($logo_path)) {
        $template->setImageValue('logo', $logo_path);
      }
      else {
        $template->setValue('logo', '');
      }

      // Escape all special characters in the replacement values.
      $replacement_values = self::escapeSpecialCharacters($plan->valueReplacements());

      // Load string replacement values from the plan (filter out non-string
      // values) and replace placeholders in the template.
      $template->setValues(array_filter($replacement_values, function ($value) {
        return is_string($value);
      }));

      // Load bulleted list replacement values from the plan (filter out values
      // that are not arrays of strings) and add bulleted lists in the template.
      $list_replacements = array_filter($replacement_values, function ($value) {
        return is_array($value) && array_sum(array_map('is_string', $value)) === count($value);
      });
      foreach ($list_replacements as $placeholder => $items) {
        $template->cloneBlock($placeholder, count($items), TRUE, TRUE);
        foreach ($items as $delta => $item) {
          $template->setValue('item#' . ($delta + 1), $item);
        }
      }

      // Save the file.
      $template->saveAs($uri);
    }

    // Otherwise, generate an empty document.
    else {
      $doc = new PhpWord();
      $doc->addSection();
      ob_start();
      $doc->save('php://output', 'Word2007');
      $contents = ob_get_contents();
      ob_end_clean();
      file_put_contents($uri, $contents);
    }

    // Create and return a file entity.
    /** @var \Drupal\file\FileInterface $file */
    $file = $this->entityTypeManager->getStorage('file')->create(['uri' => $uri]);
    $file->setOwnerId($this->currentUser->id());
    $file->setTemporary();
    $file->save();
    return $file;
  }

  /**
   * Recursively escapes all special characters within strings in an array.
   *
   * @param array $input
   *   Input array.
   *
   * @return mixed
   *   Returns the input array with all strings escaped.
   */
  public static function escapeSpecialCharacters(array $input) {
    foreach ($input as $key => $value) {
      if (is_array($value)) {
        $input[$key] = self::escapeSpecialCharacters($value);
      }
      elseif (is_string($value)) {
        $input[$key] = htmlspecialchars($value);
      }
    }
    return $input;
  }

}
