<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\views\ViewExecutable;

/**
 * Views execution hook implementations for farm_rcd.
 */
class ViewsExecutionHooks {

  use StringTranslationTrait;

  /**
   * Implements hook_views_pre_view().
   */
  #[Hook('views_pre_view')]
  public function viewsPreView(ViewExecutable $view, $display_id, array &$args) {

    // Alter the farm_plan View.
    if ($view->id() == 'farm_plan') {

      // Only alter the page_type display.
      if ($display_id != 'page_type') {
        return;
      }

      // Bail if not a view of rcd_practice_implementation plans.
      if (!in_array('rcd_practice_implementation', $args)) {
        return;
      }

      // Add a field for the practice measurement.
      $table = 'plan_field_data';
      $field = 'practice_measurement';
      $field_options = [
        // @todo
      ];
      //$view->addHandler($display_id, 'field', $table, $field, $field_options);
    }
  }

}
