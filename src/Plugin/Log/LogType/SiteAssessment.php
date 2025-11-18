<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Plugin\Log\LogType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\LogType;
use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the RCD site assessment log type.
 */
#[LogType(
  id: 'rcd_site_assessment',
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
      'rcd_land_use_history' => [
        'type' => 'string_long',
        'label' => $this->t('Land use history'),
      ],

      // Existing infrastructure.
      'rcd_infrastructure' => [
        'type' => 'string_long',
        'label' => $this->t('Existing infrastructure'),
      ],

      // Priority environmental concerns.
      'rcd_priority_concerns' => [
        'type' => 'string_long',
        'label' => $this->t('Priority environmental concerns'),
      ],

      // Watersheds.
      'rcd_watershed' => [
        'type' => 'entity_reference',
        'label' => $this->t('Watersheds'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'rcd_watershed',
        'multiple' => TRUE,
        'auto_create' => TRUE,
      ],

      // Groundwater basins.
      'rcd_groundwater_basin' => [
        'type' => 'entity_reference',
        'label' => $this->t('Groundwater basins'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'rcd_groundwater_basin',
        'multiple' => TRUE,
        'auto_create' => TRUE,
      ],

      // Wildlife species.
      'rcd_wildlife_species' => [
        'type' => 'entity_reference',
        'label' => $this->t('Wildlife species'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'rcd_wildlife_species',
        'multiple' => TRUE,
        'auto_create' => TRUE,
      ],

      // Plant species.
      'rcd_plant_species' => [
        'type' => 'entity_reference',
        'label' => $this->t('Plant species'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'rcd_plant_species',
        'multiple' => TRUE,
        'auto_create' => TRUE,
      ],

      // Landowner objectives.
      'rcd_landowner_objectives' => [
        'type' => 'string_long',
        'label' => $this->t('Landowner objectives'),
      ],

      // Soil rating.
      'rcd_soil_rating' => [
        'type' => 'integer',
        'label' => $this->t('Soil rating'),
        'description' => $this->t('Please rate the condition of this resource between 1-5, where 1 is terrible and 5 is excellent.'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Soil baseline conditions.
      'rcd_soil_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Soil baseline conditions'),
      ],

      // Soil goals.
      'rcd_soil_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Soil goals'),
      ],

      // Soil strategy.
      'rcd_soil_strategy' => [
        'type' => 'string_long',
        'label' => $this->t('Soil strategy to achieve goals'),
      ],

      // Water rating.
      'rcd_water_rating' => [
        'type' => 'integer',
        'label' => $this->t('Water rating'),
        'description' => $this->t('Please rate the condition of this resource between 1-5, where 1 is terrible and 5 is excellent.'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Water baseline conditions.
      'rcd_water_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Water baseline conditions'),
      ],

      // Water goals.
      'rcd_water_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Water goals'),
      ],

      // Water strategy.
      'rcd_water_strategy' => [
        'type' => 'string_long',
        'label' => $this->t('Water strategy to achieve goals'),
      ],

      // Plant/vegetation rating.
      'rcd_plant_rating' => [
        'type' => 'integer',
        'label' => $this->t('Plant/vegetation rating'),
        'description' => $this->t('Please rate the condition of this resource between 1-5, where 1 is terrible and 5 is excellent.'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Plant/vegetation baseline conditions.
      'rcd_plant_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Plant/vegetation baseline conditions'),
      ],

      // Plant/vegetation goals.
      'rcd_plant_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Plant/vegetation goals'),
      ],

      // Plant/vegetation strategy.
      'rcd_plant_strategy' => [
        'type' => 'string_long',
        'label' => $this->t('Plant/vegetation strategy to achieve goals'),
      ],

      // Aquatic habitat rating.
      'rcd_aquatic_rating' => [
        'type' => 'integer',
        'label' => $this->t('Aquatic habitat rating'),
        'description' => $this->t('Please rate the condition of this resource between 1-5, where 1 is terrible and 5 is excellent.'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Aquatic habitat baseline conditions.
      'rcd_aquatic_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Aquatic habitat baseline conditions'),
      ],

      // Aquatic habitat goals.
      'rcd_aquatic_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Aquatic habitat goals'),
      ],

      // Aquatic habitat strategy.
      'rcd_aquatic_strategy' => [
        'type' => 'string_long',
        'label' => $this->t('Aquatic habitat strategy to achieve goals'),
      ],

      // Livestock rating.
      'rcd_livestock_rating' => [
        'type' => 'integer',
        'label' => $this->t('Livestock rating'),
        'description' => $this->t('Please rate the condition of this resource between 1-5, where 1 is terrible and 5 is excellent.'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Livestock baseline conditions.
      'rcd_livestock_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Livestock baseline conditions'),
      ],

      // Livestock goals.
      'rcd_livestock_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Livestock goals'),
      ],

      // Livestock strategy.
      'rcd_livestock_strategy' => [
        'type' => 'string_long',
        'label' => $this->t('Livestock strategy to achieve goals'),
      ],

      // Wildlife rating.
      'rcd_wildlife_rating' => [
        'type' => 'integer',
        'label' => $this->t('Wildlife rating'),
        'description' => $this->t('Please rate the condition of this resource between 1-5, where 1 is terrible and 5 is excellent.'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Wildlife baseline conditions.
      'rcd_wildlife_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Wildlife baseline conditions'),
      ],

      // Wildlife goals.
      'rcd_wildlife_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Wildlife goals'),
      ],

      // Wildlife strategy.
      'rcd_wildlife_strategy' => [
        'type' => 'string_long',
        'label' => $this->t('Wildlife strategy to achieve goals'),
      ],

      // Infrastructure rating.
      'rcd_infrastructure_rating' => [
        'type' => 'integer',
        'label' => $this->t('Infrastructure rating'),
        'description' => $this->t('Please rate the condition of this resource between 1-5, where 1 is terrible and 5 is excellent.'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 5,
      ],

      // Infrastructure baseline conditions.
      'rcd_infrastructure_baseline' => [
        'type' => 'string_long',
        'label' => $this->t('Infrastructure baseline conditions'),
      ],

      // Infrastructure goals.
      'rcd_infrastructure_goals' => [
        'type' => 'string_long',
        'label' => $this->t('Infrastructure goals'),
      ],

      // Infrastructure strategy.
      'rcd_infrastructure_strategy' => [
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
