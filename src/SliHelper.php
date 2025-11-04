<?php

declare(strict_types=1);

namespace Drupal\farm_sli;

/**
 * Define allowed values RCP fields.
 *
 * @internal
 */
class SliHelper {

  /**
   * Stakeholder types.
   *
   * @return array
   *   Returns an array of allowed value options.
   */
  public static function stakeholderTypes() {
    return [
      'manager' => t('Manager'),
      'municipality' => t('Municipality'),
      'landowner' => t('Landowner'),
      'lessee' => t('Lessee'),
    ];
  }

  /**
   * Stakeholder groups.
   *
   * @return array
   *   Returns an array of allowed value options.
   */
  public static function stakeholderGroups() {
    return [
      'beginning' => t('Beginning farmer or rancher (less than 10 years)'),
      'female' => t('Female'),
      'veteran' => t('Veteran'),
      'black' => t('Black or African American'),
      'native' => t('American Indian or Alaska Native'),
      'hispanic' => t('Hispanic or Latino'),
      'asian' => t('Asian'),
      'pacific' => t('Pacific Islander'),
      'na' => t('Not applicable'),
      'optout' => t('Prefer not to answer'),
    ];
  }

  /**
   * Land uses.
   *
   * @return array
   *   Returns an array of allowed value options.
   */
  public static function landUses() {
    return [
      'grazing' => t('Grazing'),
      'vineyards' => t('Vineyards'),
      'orchards' => t('Orchards'),
      'rowcrops' => t('Row crops'),
      'natural' => t('Natural lands'),
      'other' => t('Other'),
    ];
  }

  /**
   * Land types.
   *
   * @return array
   *   Returns an array of allowed value options.
   */
  public static function landTypes() {
    return [
      'sli_row_crops' => t('Row crops'),
      'sli_orchard' => t('Orchard'),
      'sli_vineyard' => t('Vineyard'),
      'sli_grazing' => t('Grazing'),
      'sli_pastureland' => t('Pastureland'),
      'sli_natural_area' => t('Natural area'),
      'sli_riparian_area' => t('Riparian area'),
      'other' => t('Other'),
    ];
  }

  /**
   * Goals.
   *
   * @return array
   *   Returns an array of allowed value options.
   */
  public static function goals() {
    return [
      'stewardship' => t('Improve my land stewardship'),
      'funding' => t('Access funding for land management practices'),
      'regulation' => t('Get help with permitting, regulations, or a related issue'),
      'economic' => t('Improve economic standing of my farm/ranch'),
      'other' => t('Other'),
    ];
  }

  /**
   * Concerns.
   *
   * @return array
   *   Returns an array of allowed value options.
   */
  public static function concerns() {
    return [
      'soil' => t('Soil'),
      'water' => t('Water'),
      'animals' => t('Animals (wildlife or livestock)'),
      'plants' => t('Plants (crops or native vegetation)'),
      'air' => t('Air'),
      'human' => t('Human'),
      'energy' => t('Energy'),
      'other' => t('Other'),
    ];
  }

  /**
   * Practices.
   *
   * @return array
   *   Returns an array of allowed value options.
   */
  public static function practices() {
    return [
      'bda' => [
        'label' => t('Beaver Dam Analog (BDA)'),
      ],
      'biochar' => [
        'label' => t('Bio-char'),
      ],
      'brush_mgmt' => [
        'label' => t('Brush Management'),
      ],
      'conservation_cover' => [
        'label' => t('Conservation Cover'),
      ],
      'constructed_wetland' => [
        'label' => t('Constructed Wetland'),
      ],
      'contour_orchard_perennials' => [
        'label' => t('Contour Orchard and Perennials'),
      ],
      'cover_crop' => [
        'label' => t('Cover Crop'),
      ],
      'critical_area_planting' => [
        'label' => t('Critical Area Planting'),
      ],
      'filter_strip' => [
        'label' => t('Filter Strip'),
      ],
      'forage_biomass_planting' => [
        'label' => t('Forage Biomass Planting'),
      ],
      'forest_stand_mgmt' => [
        'label' => t('Forest Stand Management'),
      ],
      'fuel_break' => [
        'label' => t('Fuel Break'),
      ],
      'grassed_waterway' => [
        'label' => t('Grassed Waterway'),
      ],
      'hedgerow_planting' => [
        'label' => t('Hedgerow Planting'),
      ],
      'irrigation_water_mgmt' => [
        'label' => t('Irrigation Water Management'),
      ],
      'keyline_plow' => [
        'label' => t('Keyline Plow'),
      ],
      'mulching' => [
        'label' => t('Mulching'),
      ],
      'nutrient_mgmt' => [
        'label' => t('Nutrient Management'),
      ],
      'pollinator_habitat' => [
        'label' => t('Pollinator Habitat Enhancement'),
      ],
      'prescribed_burn' => [
        'label' => t('Prescribed Burn'),
      ],
      'prescribed_grazing' => [
        'label' => t('Prescribed Grazing'),
      ],
      'range_planting' => [
        'label' => t('Range Planting'),
      ],
      'residue_tillage_mgmt_no_till' => [
        'label' => t('Residue and Tillage Management, No Till'),
      ],
      'riparian_forest_buffer' => [
        'label' => t('Riparian Forest Buffer'),
      ],
      'riparian_herbaceous_planting' => [
        'label' => t('Riparian Herbaceous Planting'),
      ],
      'roof_runoff_strucfture' => [
        'label' => t('Roof Runoff Structure'),
      ],
      'silvopasture' => [
        'label' => t('Silvopasture'),
      ],
      'soil_carbon_amendment' => [
        'label' => t('Soil Carbon Amendment (e.g. compost)'),
      ],
      'stream_habitat_mgmt' => [
        'label' => t('Stream Habitat Improvement and Management'),
      ],
      'streambank_shoreline_improvement' => [
        'label' => t('Streambank/Shoreline Improvement'),
      ],
      'structure_water_control' => [
        'label' => t('Structure for Water Control'),
      ],
      'structure_wildlife' => [
        'label' => t('Structures for Wildlife'),
      ],
      'tree_shrub_establishment' => [
        'label' => t('Tree/Shrub Establishment'),
      ],
      'upland_wildlife_mgmt' => [
        'label' => t('Upland Wildlife Management'),
      ],
      'water_sediment_control_basin' => [
        'label' => t('Water and Sediment Control Basin'),
      ],
      'wildlife_habitat_planting' => [
        'label' => t('Wildlife Habitat Planting'),
      ],
      'windbreak_shelterbelt' => [
        'label' => t('Windreak/Shelterbelt'),
      ],
      'other' => [
        'label' => t('Other'),
      ],
    ];
  }

  /**
   * States.
   *
   * @return array
   *   Returns an array of allowed value options.
   */
  public static function states() {
    return [
      'AL' => t('Alabama'),
      'AK' => t('Alaska'),
      'AZ' => t('Arizona'),
      'AR' => t('Arkansas'),
      'CA' => t('California'),
      'CO' => t('Colorado'),
      'CT' => t('Connecticut'),
      'DE' => t('Delaware'),
      'FL' => t('Florida'),
      'GA' => t('Georgia'),
      'HI' => t('Hawaii'),
      'ID' => t('Idaho'),
      'IL' => t('Illinois'),
      'IN' => t('Indiana'),
      'IA' => t('Iowa'),
      'KS' => t('Kansas'),
      'KY' => t('Kentucky'),
      'LA' => t('Louisiana'),
      'ME' => t('Maine'),
      'MD' => t('Maryland'),
      'MA' => t('Massachusetts'),
      'MI' => t('Michigan'),
      'MN' => t('Minnesota'),
      'MS' => t('Mississippi'),
      'MO' => t('Missouri'),
      'MT' => t('Montana'),
      'NE' => t('Nebraska'),
      'NV' => t('Nevada'),
      'NH' => t('New Hampshire'),
      'NJ' => t('New Jersey'),
      'NM' => t('New Mexico'),
      'NY' => t('New York'),
      'NC' => t('North Carolina'),
      'ND' => t('North Dakota'),
      'OH' => t('Ohio'),
      'OK' => t('Oklahoma'),
      'OR' => t('Oregon'),
      'PA' => t('Pennsylvania'),
      'RI' => t('Rhode Island'),
      'SC' => t('South Carolina'),
      'SD' => t('South Dakota'),
      'TN' => t('Tennessee'),
      'TX' => t('Texas'),
      'UT' => t('Utah'),
      'VT' => t('Vermont'),
      'VA' => t('Virginia'),
      'WA' => t('Washington'),
      'WV' => t('West Virginia'),
      'WI' => t('Wisconsin'),
      'WY' => t('Wyoming'),
    ];
  }

}
