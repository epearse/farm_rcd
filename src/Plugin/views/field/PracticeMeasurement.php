<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Plugin\views\field;

use Drupal\views\Attribute\ViewsField;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A field that displays a practice implementation plan's measurement.
 *
 * @ingroup views_field_handlers
 */
#[ViewsField("rcd_practice_measurement")]
class PracticeMeasurement extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function query() {

  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $entity = $this->getEntity($values);
    return 'hey!';
  }

}
