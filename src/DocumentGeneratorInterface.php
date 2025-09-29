<?php

declare(strict_types=1);

namespace Drupal\farm_sli;

use Drupal\file\FileInterface;
use Drupal\plan\Entity\PlanInterface;

/**
 * Document generator logic.
 */
interface DocumentGeneratorInterface {

  /**
   * Generate document.
   *
   * @param \Drupal\plan\Entity\PlanInterface $plan
   *   The plan entity to generate document from.
   * @param string|null $filename
   *   The filename to save. If this is null, a default filename will be
   *   generated.
   *
   * @return \Drupal\file\FileInterface
   *   Returns a file entity.
   */
  public function generate(PlanInterface $plan, ?string $filename = NULL): FileInterface;

}
