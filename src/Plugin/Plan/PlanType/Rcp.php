<?php

declare(strict_types=1);

namespace Drupal\farm_rcp\Plugin\Plan\PlanType;

use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;
use Drupal\farm_rcp\RcpAllowedValues;

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

      // RCD.
      'rcp_rcd' => [
        'type' => 'string',
        'label' => $this->t('Resource Conservation District'),
      ],

      // Stakeholder information.
      'rcp_stakeholder_name' => [
        'type' => 'string',
        'label' => $this->t('Stakeholder name'),
      ],
      'rcp_stakeholder_email' => [
        'type' => 'email',
        'label' => $this->t('Stakeholder email'),
      ],
      'rcp_stakeholder_phone' => [
        'type' => 'string',
        'label' => $this->t('Stakeholder phone'),
      ],
      'rcp_stakeholder_street' => [
        'type' => 'string',
        'label' => $this->t('Stakeholder street'),
      ],
      'rcp_stakeholder_city' => [
        'type' => 'string',
        'label' => $this->t('Stakeholder city'),
      ],
      'rcp_stakeholder_zip' => [
        'type' => 'string',
        'label' => $this->t('Stakeholder zip'),
      ],
      'rcp_stakeholder_type' => [
        'type' => 'list_string',
        'label' => $this->t('Stakeholder type'),
        'allowed_values' => RcpAllowedValues::stakeholderTypes(),
      ],
      'rcp_stakeholder_own_or_lease' => [
        'type' => 'list_string',
        'label' => $this->t('Own or lease'),
        'allowed_values' => [
          'own' => $this->t('Own'),
          'lease' => $this->t('Lease'),
        ],
      ],
      'rcp_stakeholder_group' => [
        'type' => 'list_string',
        'label' => $this->t('Stakeholder group'),
        'allowed_values' => RcpAllowedValues::stakeholderGroups(),
        'multiple' => TRUE,
      ],

      // Property information.
      'rcp_property_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Property acreage'),
        'min' => 0,
      ],
      'rcp_property_street' => [
        'type' => 'string',
        'label' => $this->t('Property street'),
      ],
      'rcp_property_city' => [
        'type' => 'string',
        'label' => $this->t('Property city'),
      ],
      'rcp_property_zip' => [
        'type' => 'string',
        'label' => $this->t('Property zip'),
      ],
      'rcp_property_parcel_gps' => [
        'type' => 'string',
        'label' => $this->t('Parcel number or GPS coordinates'),
      ],
      'rcp_property_land_use' => [
        'type' => 'list_string',
        'label' => $this->t('Land use'),
        'allowed_values' => RcpAllowedValues::landUses(),
        'multiple' => TRUE,
      ],
      'rcp_property_land_use_grazing_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Grazing land use acreage'),
        'min' => 0,
      ],
      'rcp_property_land_use_vineyards_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Vineyards land use acreage'),
        'min' => 0,
      ],
      'rcp_property_land_use_orchards_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Orchards land use acreage'),
        'min' => 0,
      ],
      'rcp_property_land_use_rowcrops_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Row crops land use acreage'),
        'min' => 0,
      ],
      'rcp_property_land_use_natural_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Natural land use acreage'),
        'min' => 0,
      ],
      'rcp_property_land_use_other' => [
        'type' => 'string',
        'label' => $this->t('Other land use'),
      ],
      'rcp_property_land_use_other_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Other land use acreage'),
        'min' => 0,
      ],

      // Goals.
      'rcp_goals' => [
        'type' => 'list_string',
        'label' => $this->t('Goals'),
        'allowed_values' => RcpAllowedValues::goals(),
        'multiple' => TRUE,
      ],
      'rcp_goals_other' => [
        'type' => 'string',
        'label' => $this->t('Other goals'),
      ],
      'rcp_goals_comments' => [
        'type' => 'string_long',
        'label' => $this->t('Additional goal comments'),
      ],

      // Interests.
      'rcp_interests' => [
        'type' => 'list_string',
        'label' => $this->t('Interests'),
        'allowed_values' => RcpAllowedValues::interests(),
        'multiple' => TRUE,
      ],
      'rcp_interests_comments' => [
        'type' => 'string_long',
        'label' => $this->t('Additional interests comments'),
      ],

      // Allow sharing with other RCPs.
      'rcp_sharing_allowed' => [
        'type' => 'boolean',
        'label' => $this->t('Allow sharing the application information with other RCDs'),
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
