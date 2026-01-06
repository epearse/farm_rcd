<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Views hook implementations for farm_rcd.
 */
class ViewsHooks {

  use StringTranslationTrait;

  /**
   * Implements hook_views_data_alter().
   */
  #[Hook('views_data_alter')]
  public function viewsDataAlter(array &$data) {

    // Add practice measurement field to plans.
    if (isset($data['plan_field_data'])) {
      $data['plan_field_data']['practice_measurement'] = [
        'title' => $this->t('Practice measurement'),
        'field' => [
          'id' => 'rcd_practice_measurement',
          'additional fields' => [
            'rcd_acres',
            'rcd_linear_feet'
          ],
        ],
      ];
    }
  }

}
