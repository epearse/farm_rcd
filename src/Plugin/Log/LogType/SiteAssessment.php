<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Plugin\Log\LogType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\LogType;
use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the SLI site assessment log type.
 */
#[LogType(
  id: 'sli_site_assessment',
  label: new TranslatableMarkup('Site assessment'),
)]
class SiteAssessment extends FarmLogType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = [];
    $field_info = [

      // Land use history.
      'sli_land_use_history' => [
        'type' => 'string_long',
        'label' => $this->t('Land use history'),
      ],

      // Existing infrastructure.
      'sli_infrastructure' => [
        'type' => 'string_long',
        'label' => $this->t('Existing infrastructure'),
      ],

      // Priority environmental concerns.
      'sli_priority_concerns' => [
        'type' => 'string_long',
        'label' => $this->t('Priority environmental concerns'),
      ],

      // Watersheds.
      'sli_watershed' => [
        'type' => 'entity_reference',
        'label' => $this->t('Watersheds'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'sli_watershed',
        'multiple' => TRUE,
        'auto_create' => TRUE,
      ],

      // Groundwater basins.
      'sli_groundwater_basin' => [
        'type' => 'entity_reference',
        'label' => $this->t('Groundwater basins'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'sli_groundwater_basin',
        'multiple' => TRUE,
        'auto_create' => TRUE,
      ],

      // Wildlife species.
      'sli_wildlife_species' => [
        'type' => 'entity_reference',
        'label' => $this->t('Wildlife species'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'sli_wildlife_species',
        'multiple' => TRUE,
        'auto_create' => TRUE,
      ],

      // Plant species.
      'sli_plant_species' => [
        'type' => 'entity_reference',
        'label' => $this->t('Plant species'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'sli_plant_species',
        'multiple' => TRUE,
        'auto_create' => TRUE,
      ],

      // Landowner objectives.
      'sli_landowner_objectives' => [
        'type' => 'string_long',
        'label' => $this->t('Landowner objectives'),
      ],

      // Soil rating.
      'sli_soil_rating' => [
        'type' => 'integer',
        'label' => $this->t('Soil rating'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Soil baseline conditions.
      'sli_soil_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Soil baseline conditions'),
      ],

      // Soil goals.
      'sli_soil_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Soil goals'),
      ],

      // Soil strategy.
      'sli_soil_strategy' => [
        'type' => 'string_long',
        'label' => $this->t('Soil strategy to achieve goals'),
      ],

      // Water rating.
      'sli_water_rating' => [
        'type' => 'integer',
        'label' => $this->t('Water rating'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Water baseline conditions.
      'sli_water_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Water baseline conditions'),
      ],

      // Water goals.
      'sli_water_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Water goals'),
      ],

      // Water strategy.
      'sli_water_strategy' => [
        'type' => 'string_long',
        'label' => $this->t('Water strategy to achieve goals'),
      ],

      // Plant/vegetation rating.
      'sli_plant_rating' => [
        'type' => 'integer',
        'label' => $this->t('Plant/vegetation rating'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Plant/vegetation baseline conditions.
      'sli_plant_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Plant/vegetation baseline conditions'),
      ],

      // Plant/vegetation goals.
      'sli_plant_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Plant/vegetation goals'),
      ],

      // Plant/vegetation strategy.
      'sli_plant_strategy' => [
        'type' => 'string_long',
        'label' => $this->t('Plant/vegetation strategy to achieve goals'),
      ],

      // Aquatic habitat rating.
      'sli_aquatic_rating' => [
        'type' => 'integer',
        'label' => $this->t('Aquatic habitat rating'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Aquatic habitat baseline conditions.
      'sli_aquatic_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Aquatic habitat baseline conditions'),
      ],

      // Aquatic habitat goals.
      'sli_aquatic_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Aquatic habitat goals'),
      ],

      // Aquatic habitat strategy.
      'sli_aquatic_strategy' => [
        'type' => 'string_long',
        'label' => $this->t('Aquatic habitat strategy to achieve goals'),
      ],

      // Livestock rating.
      'sli_livestock_rating' => [
        'type' => 'integer',
        'label' => $this->t('Livestock rating'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Livestock baseline conditions.
      'sli_livestock_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Livestock baseline conditions'),
      ],

      // Livestock goals.
      'sli_livestock_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Livestock goals'),
      ],

      // Livestock strategy.
      'sli_livestock_strategy' => [
        'type' => 'string_long',
        'label' => $this->t('Livestock strategy to achieve goals'),
      ],

      // Wildlife rating.
      'sli_wildlife_rating' => [
        'type' => 'integer',
        'label' => $this->t('Wildlife rating'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Wildlife baseline conditions.
      'sli_wildlife_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Wildlife baseline conditions'),
      ],

      // Wildlife goals.
      'sli_wildlife_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Wildlife goals'),
      ],

      // Wildlife strategy.
      'sli_wildlife_strategy' => [
        'type' => 'string_long',
        'label' => $this->t('Wildlife strategy to achieve goals'),
      ],

      // Infrastructure rating.
      'sli_infrastructure_rating' => [
        'type' => 'integer',
        'label' => $this->t('Infrastructure rating'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Infrastructure baseline conditions.
      'sli_infrastructure_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Infrastructure baseline conditions'),
      ],

      // Infrastructure goals.
      'sli_infrastructure_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Infrastructure goals'),
      ],

      // Infrastructure strategy.
      'sli_infrastructure_strategy' => [
        'type' => 'string_long',
        'label' => $this->t('Infrastructure strategy to achieve goals'),
      ],

    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
