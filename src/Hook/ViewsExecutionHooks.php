<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Hook\Order\Order;
use Drupal\views\ViewExecutable;

/**
 * Views execution hook implementations for farm_rcd.
 */
class ViewsExecutionHooks {

  /**
   * Implements hook_views_pre_view().
   */
  #[Hook('views_pre_view', order: Order::Last)]
  public function viewsPreView(ViewExecutable $view, $display_id, array &$args) {

    // Only run in the plan view.
    if ($view->id() != 'farm_plan') {
      return;
    }

    // Get all filters.
    $filters = $view->display_handler->getOption('filters');

    // Customize default target_start_date and target_end_date filters.
    if (isset($filters['rcd_target_start_date_value']) && isset($filters['rcd_target_end_date_value'])) {

      // Copy the filters, and remove them from the list.
      $start_date_filter = $filters['rcd_target_start_date_value'];
      $end_date_filter = $filters['rcd_target_end_date_value'];
      unset($filters['rcd_target_start_date_value']);
      unset($filters['rcd_target_end_date_value']);

      // Expose operator.
      $start_date_filter['expose']['use_operator'] = TRUE;
      $end_date_filter['expose']['use_operator'] = TRUE;

      // Set default operator.
      $start_date_filter['operator'] = "between";
      $end_date_filter['operator'] = "between";

      // Append updated filters to the list.
      $filters['rcd_target_start_date_value'] = $start_date_filter;
      $filters['rcd_target_end_date_value'] = $end_date_filter;

      // Save the filters.
      $view->display_handler->overrideOption('filters', $filters);
    }
  }

}
