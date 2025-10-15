<?php

/**
 * @file
 * Post update functions for farm_rcd module.
 */

declare(strict_types=1);

/**
 * Add acreage and linear feet measurements to practice implementation plans.
 */
function farm_rcd_post_update_practice_measurements(&$sandbox) {

  // Acreage.
  $options = [
    'type' => 'decimal',
    'label' => t('Acreage'),
    'description' => t('How many acres will this practice cover?'),
  ];
  $field_definition = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('rcd_acres', 'plan', 'farm_rcd', $field_definition);

  // Linear feet.
  $options = [
    'type' => 'decimal',
    'label' => t('Linear feet'),
    'description' => t('How many linear feet will this practice cover?'),
  ];
  $field_definition = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('rcd_linear_feet', 'plan', 'farm_rcd', $field_definition);
}
