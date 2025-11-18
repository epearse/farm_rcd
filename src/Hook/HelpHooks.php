<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Help hook implementations for farm_rcd.
 */
class HelpHooks {

  use StringTranslationTrait;

  /**
   * Implements hook_help().
   */
  #[Hook('help')]
  public function help(string $route_name, RouteMatchInterface $route_match): string|\Stringable|array|null {

    // Intake review form.
    if ($route_name == 'farm_rcd.intake_review') {
      return $this->t('Use this form to review an intake, assign ownership, and decide whether to continue or abandon.');
    }

    return NULL;
  }

}
