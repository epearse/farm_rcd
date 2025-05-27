<?php

declare(strict_types=1);

namespace Drupal\farm_rcp\Plugin\Plan\PlanType;

use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;

/**
 * Provides the resource conservation plan type.
 *
 * @PlanType(
 *   id = "rcp",
 *   label = @Translation("Resource conservation"),
 * )
 */
class Rcp extends FarmPlanType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    return [];
  }

}
