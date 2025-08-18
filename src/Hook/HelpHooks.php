<?php

namespace Drupal\farm_sli\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Help hook implementations for farm_sli.
 */
class HelpHooks {

  use StringTranslationTrait;

  /**
   * Implements hook_help().
   */
  #[Hook('help')]
  public function help(string $route_name, RouteMatchInterface $route_match): string|\Stringable|array|null  {

    // Intake review form.
    if ($route_name == 'farm_sli.intake_review') {
      return $this->t('Use this form to review an intake, assign ownership, and decide whether to continue or abandon.');
    }

    return NULL;
  }

}
