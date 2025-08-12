<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Plugin\Menu\LocalAction;

use Drupal\Core\Menu\LocalActionDefault;

/**
 * Creates an action link to review intake logs.
 */
class ReviewIntakeAction extends LocalActionDefault {

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return parent::getCacheTags() + ['log_list:sli_intake'];
  }

}
