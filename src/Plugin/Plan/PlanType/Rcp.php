<?php

declare(strict_types=1);

namespace Drupal\farm_rcp\Plugin\Plan\PlanType;

use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;

/**
 * Provides the resource conservation plan type.
 *
 * @PlanType(
 *   id = "rcp",
 *   label = @Translation("Resource conservation"),
 * )
 */
class Rcp extends FarmPlanType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = [];
    $field_info = [
      'farm' => [
        'type' => 'entity_reference',
        'label' => $this->t('Farm'),
        'description' => $this->t('Associates the resource conservation plan with a farm organization.'),
        'target_type' => 'organization',
        'target_bundle' => 'farm',
        'cardinality' => 1,
        'required' => TRUE,
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
