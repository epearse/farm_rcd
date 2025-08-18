<?php

namespace Drupal\farm_sli\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Dashboard hook implementations for farm_sli.
 */
class DashboardHooks {

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
