<?php

declare(strict_types=1);

namespace Drupal\farm_sli_test\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\farm_sli_test\Bundle\TestPlan;

/**
 * Bundle hook implementations for farm_sli_test.
 */
class BundleHooks {

  /**
   * Implements hook_entity_bundle_info_alter().
   */
  #[Hook('entity_bundle_info_alter')]
  public function entityBundleInfoAlter(array &$bundles): void {

    // Set the bundle class for test plans.
    if (isset($bundles['plan']['test'])) {
      $bundles['plan']['test']['class'] = TestPlan::class;
    }
  }

}
