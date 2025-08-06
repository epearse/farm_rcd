<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Plugin\Plan\PlanType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\PlanType;
use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;

/**
 * Provides the resource conservation profile plan type.
 */
#[PlanType(
  id: 'rcp',
  label: new TranslatableMarkup('Resource conservation profile')),
]
class Rcp extends FarmPlanType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = [];
    $field_info = [

      // Farm organization entity.
      'farm' => [
        'type' => 'entity_reference',
        'label' => $this->t('Farm'),
        'description' => $this->t('Associates the resource conservation profile with a farm organization.'),
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
