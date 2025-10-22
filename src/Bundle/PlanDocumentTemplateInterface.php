<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Bundle;

use Drupal\farm_sli\Placeholder\PlaceholderInterface;

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
   * Provide placeholders for templates.
   *
   * @return PlaceholderInterface[]
   *   Returns an array of placeholders.
   */
  public function placeholders(): array;

}
