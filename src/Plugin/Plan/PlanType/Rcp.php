<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Plugin\Plan\PlanType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\entity\BundleFieldDefinition;
use Drupal\farm_entity\Attribute\PlanType;
use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;

/**
 * Provides the RCD resource conservation plan type.
 */
#[PlanType(
  id: 'rcd_rcp',
  label: new TranslatableMarkup('Resource conservation plan'),
)]
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
        'description' => $this->t('Associates the resource conservation plan with a farm organization.'),
        'target_type' => 'organization',
        'target_bundle' => 'farm',
        'required' => TRUE,
      ],

      // Property land asset.
      'property' => [
        'type' => 'entity_reference',
        'label' => $this->t('Property'),
        'description' => $this->t('Associates the resource conservation plan with a property land asset.'),
        'target_type' => 'asset',
        'target_bundle' => 'land',
      ],

      // Intake log.
      'intake' => [
        'type' => 'entity_reference',
        'label' => $this->t('Intake'),
        'description' => $this->t('Links the plan to an RCD intake log.'),
        'target_type' => 'log',
        'target_bundle' => 'rcd_intake',
      ],

    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }

    // Add a hidden field for linking practice implementation plans.
    // This is done without farm_field.factory because it does not support
    // plan entity reference fields.
    $field = BundleFieldDefinition::create('entity_reference');
    $field->setLabel('Practice implementation plans');
    $field->setDescription('Links this plan to one or more practice implementation plans.');
    $field->setSetting('target_type', 'plan');
    $field->setSetting('handler', 'default:plan');
    $field->setSetting('handler_settings', [
      'target_bundles' => [
        'rcd_practice_implementation' => 'rcd_practice_implementation',
      ],
      'sort' => [
        'field' => '_none',
      ],
      'auto_create' => FALSE,
      'auto_create_bundle' => '',
    ]);
    $field->setCardinality(-1);
    $field->setDisplayOptions('form', ['region' => 'hidden']);
    $field->setDisplayOptions('view', ['region' => 'hidden']);
    $fields['practice_implementation_plan'] = $field;

    return $fields;
  }

}
