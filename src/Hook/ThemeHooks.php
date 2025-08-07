<?php

namespace Drupal\farm_sli\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Theme hook implementations for farm_sli.
 */
class ThemeHooks {

  /**
   * Implements hook_preprocess_page().
   */
  #[Hook('preprocess_page')]
  public function preprocessPage(&$variables): void {

    // Disable the breadcrumb region for anonymous users.
    if (\Drupal::currentUser()->isAnonymous()) {
      unset($variables['page']['breadcrumb']);
    }
  }

}
