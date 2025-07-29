<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Plugin\Log\LogType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\LogType;
use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;
use Drupal\farm_sli\SliAllowedValues;

/**
 * Provides the SLI Intake log type.
 */
#[LogType(
  id: 'sli_intake',
  label: new TranslatableMarkup('Intake'),
)]
class Intake extends FarmLogType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = [];
    $field_info = [

      // Stakeholder information.
      'intake_stakeholder_name' => [
        'type' => 'string',
        'label' => $this->t('Stakeholder name'),
      ],
      'intake_stakeholder_email' => [
        'type' => 'email',
        'label' => $this->t('Stakeholder email'),
      ],
      'intake_stakeholder_phone' => [
        'type' => 'string',
        'label' => $this->t('Stakeholder phone'),
      ],
      'intake_stakeholder_street' => [
        'type' => 'string',
        'label' => $this->t('Stakeholder street'),
      ],
      'intake_stakeholder_city' => [
        'type' => 'string',
        'label' => $this->t('Stakeholder city'),
      ],
      'intake_stakeholder_zip' => [
        'type' => 'string',
        'label' => $this->t('Stakeholder zip'),
      ],
      'intake_stakeholder_type' => [
        'type' => 'list_string',
        'label' => $this->t('Stakeholder type'),
        'allowed_values' => SliAllowedValues::stakeholderTypes(),
      ],
      'intake_stakeholder_own_or_lease' => [
        'type' => 'list_string',
        'label' => $this->t('Own or lease'),
        'allowed_values' => [
          'own' => $this->t('Own'),
          'lease' => $this->t('Lease'),
        ],
      ],
      'intake_stakeholder_lease_expiration' => [
        'type' => 'timestamp',
        'label' => $this->t('Lease expiration'),
      ],
      'intake_property_owner' => [
        'type' => 'string',
        'label' => $this->t('Property owner'),
      ],
      'intake_stakeholder_group' => [
        'type' => 'list_string',
        'label' => $this->t('Stakeholder group'),
        'allowed_values' => SliAllowedValues::stakeholderGroups(),
        'multiple' => TRUE,
      ],

      // Property information.
      'intake_property_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Property acreage'),
        'min' => 0,
      ],
      'intake_property_street' => [
        'type' => 'string',
        'label' => $this->t('Property street'),
      ],
      'intake_property_city' => [
        'type' => 'string',
        'label' => $this->t('Property city'),
      ],
      'intake_property_zip' => [
        'type' => 'string',
        'label' => $this->t('Property zip'),
      ],
      'intake_property_parcel_gps' => [
        'type' => 'string',
        'label' => $this->t('Parcel number or GPS coordinates'),
      ],
      'intake_property_land_use' => [
        'type' => 'list_string',
        'label' => $this->t('Land use'),
        'allowed_values' => SliAllowedValues::landUses(),
        'multiple' => TRUE,
      ],
      'intake_property_land_use_grazing_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Grazing land use acreage'),
        'min' => 0,
      ],
      'intake_property_land_use_vineyards_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Vineyards land use acreage'),
        'min' => 0,
      ],
      'intake_property_land_use_orchards_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Orchards land use acreage'),
        'min' => 0,
      ],
      'intake_property_land_use_rowcrops_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Row crops land use acreage'),
        'min' => 0,
      ],
      'intake_property_land_use_natural_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Natural land use acreage'),
        'min' => 0,
      ],
      'intake_property_land_use_other' => [
        'type' => 'string',
        'label' => $this->t('Other land use'),
      ],
      'intake_property_land_use_other_acreage' => [
        'type' => 'integer',
        'label' => $this->t('Other land use acreage'),
        'min' => 0,
      ],

      // Goals.
      'intake_goals' => [
        'type' => 'list_string',
        'label' => $this->t('Goals'),
        'allowed_values' => SliAllowedValues::goals(),
        'multiple' => TRUE,
      ],
      'intake_goals_other' => [
        'type' => 'string',
        'label' => $this->t('Other goals'),
      ],
      'intake_goals_comments' => [
        'type' => 'string_long',
        'label' => $this->t('Additional goal comments'),
      ],

      // Interests.
      'intake_interests' => [
        'type' => 'list_string',
        'label' => $this->t('Interests'),
        'allowed_values' => SliAllowedValues::interests(),
        'multiple' => TRUE,
      ],
      'intake_interests_comments' => [
        'type' => 'string_long',
        'label' => $this->t('Additional interests comments'),
      ],

      // Allow sharing with other RCDs.
      'intake_rcd_sharing_allowed' => [
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
