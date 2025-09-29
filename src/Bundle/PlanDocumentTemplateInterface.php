<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Bundle;

/**
 * Document template methods specific to a plan type.
 */
interface PlanDocumentTemplateInterface {

  /**
   * Provide the filename of the plan's template file.
   *
   * @return string
   *   The template filename.
   */
  public function templateFilename(): string;

  /**
   * Provide value replacements for PhpWord templates.
   *
   * @return array
   *   Returns an array of string replacements keyed by placeholder.
   */
  public function valueReplacements(): array;

}
