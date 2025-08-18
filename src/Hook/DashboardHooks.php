<?php

namespace Drupal\farm_sli\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Dashboard hook implementations for farm_sli.
 */
class DashboardHooks {

  /**
   * Implements hook_farm_dashboard_panes().
   */
  #[Hook('farm_dashboard_panes')]
  public function FarmDashboardPanes(): array {
    return [

      // Add a farm organization search block to the dashboard.
      'farm_search' => [
        'view' => 'farm_sli_farm_organizations',
        'view_display_id' => 'block',
      ],

      // Add pending intakes block to the dashboard.
      'pending_intakes' => [
        'view' => 'farm_sli_intakes',
        'view_display_id' => 'block',
      ],
    ];
  }

  /**
   * Implements hook_farm_dashboard_panes_alter().
   */
  #[Hook('farm_dashboard_panes_alter')]
  public function FarmDashboardPanesAlter(array &$panes): void {

    // Remove dashboard panes.
    $remove_panes = [
      'upcoming_tasks',
      'late_tasks',
    ];
    foreach ($remove_panes as $pane) {
      if (!empty($panes[$pane])) {
        unset ($panes[$pane]);
      }
    }
  }

}
