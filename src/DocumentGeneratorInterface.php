<?php

declare(strict_types=1);

namespace Drupal\farm_rcd;

use Drupal\file\FileInterface;

/**
 * Document generator logic.
 */
interface DocumentGeneratorInterface {

  /**
   * Generate document.
   *
   * @param string $template_path
   *   The path to the document template.
   * @param string|null $filename
   *   The filename to save. If this is null, a default filename will be
   *   generated.
   * @param array $context
   *   Optional context used for generating the document.
   *
   * @return \Drupal\file\FileInterface
   *   Returns a file entity.
   */
  public function generate(string $template_path, ?string $filename = NULL, array $context = []): FileInterface;

}
