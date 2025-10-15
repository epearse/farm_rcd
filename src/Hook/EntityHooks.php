<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\farm_rcd\ConservationPractices;
use Drupal\plan\Entity\PlanInterface;

/**
 * Entity hook implementations for farm_rcd.
 */
class EntityHooks {

  /**
   * Implements hook_plan_presave().
   */
  #[Hook('plan_presave')]
  public function planPresave(PlanInterface $plan) {

    // If this is not a practice implementation plan, bail.
    if ($plan->bundle() != 'rcd_practice_implementation') {
      return;
    }

    // Only save the measurement (acres/feet) that is relevant to the practice.
    $practice = $plan->get('rcd_practice')->value;
    $practice_info = ConservationPractices::get($practice);
    if ($practice_info['unit'] == 'ac') {
      $plan->set('rcd_linear_feet', NULL);
    }
    elseif ($practice_info['unit'] == 'ft') {
      $plan->set('rcd_acres', NULL);
    }
  }

}
