<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Dashboard hook implementations for farm_rcd.
 */
class DashboardHooks {

  /**
   * Implements hook_farm_dashboard_panes().
   */
  #[Hook('farm_dashboard_panes')]
  public function farmDashboardPanes(): array {
    return [

      // Add a farm organization search block to the dashboard.
      'farm_search' => [
        'view' => 'farm_rcd_farm_organizations',
        'view_display_id' => 'block',
      ],

      // Add pending intakes block to the dashboard.
      'pending_intakes' => [
        'view' => 'farm_rcd_intakes',
        'view_display_id' => 'block',
      ],
    ];
  }

  /**
   * Implements hook_farm_dashboard_panes_alter().
   */
  #[Hook('farm_dashboard_panes_alter')]
  public function farmDashboardPanesAlter(array &$panes): void {

    // Remove dashboard panes.
    $remove_panes = [
      'dashboard_map',
      'upcoming_tasks',
      'late_tasks',
      'metrics',
    ];
    foreach ($remove_panes as $pane) {
      if (!empty($panes[$pane])) {
        unset($panes[$pane]);
      }
    }
  }

}
