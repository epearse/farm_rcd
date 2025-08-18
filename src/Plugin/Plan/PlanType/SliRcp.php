<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Plugin\Plan\PlanType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\PlanType;
use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;

/**
 * Provides the SLI resource conservation plan type.
 */
#[PlanType(
  id: 'sli_rcp',
  label: new TranslatableMarkup('Resource conservation'),
)]
class SliRcp extends FarmPlanType {

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
        'description' => $this->t('Associates the resource conservation plan with a farm organization.'),
        'target_type' => 'organization',
        'target_bundle' => 'farm',
        'cardinality' => 1,
        'required' => TRUE,
      ],

      // Intake log.
      'intake' => [
        'type' => 'entity_reference',
        'label' => $this->t('Intake'),
        'description' => $this->t('Links the plan to an SLI intake log.'),
        'target_type' => 'log',
        'target_bundle' => 'sli_intake',
        'cardinality' => 1,
        'required' => TRUE,
      ],

    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
