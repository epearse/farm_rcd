<?php

namespace Drupal\farm_sli\Plugin\Plan\PlanType;

use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;

/**
 * Provides the SLI Conservation plan type.
 *
 * @PlanType(
 *   id = "sli_conservation",
 *   label = @Translation("Conservation"),
 * )
 */
class Conservation extends FarmPlanType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    return [];
  }

}
