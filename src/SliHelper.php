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
        'nrcs_code' => 'E643D',
      ],
      'biochar' => [
        'label' => t('Bio-char'),
        'nrcs_code' => '336',
      ],
      'brush_mgmt' => [
        'label' => t('Brush Management'),
        'nrcs_code' => '314',
      ],
      'conservation_cover' => [
        'label' => t('Conservation Cover'),
        'nrcs_code' => '327',
      ],
      'constructed_wetland' => [
        'label' => t('Constructed Wetland'),
        'nrcs_code' => '656',
      ],
      'contour_orchard_perennials' => [
        'label' => t('Contour Orchard and Perennials'),
        'nrcs_code' => '331',
      ],
      'cover_crop' => [
        'label' => t('Cover Crop'),
        'nrcs_code' => '340',
      ],
      'critical_area_planting' => [
        'label' => t('Critical Area Planting'),
        'nrcs_code' => '342',
      ],
      'filter_strip' => [
        'label' => t('Filter Strip'),
        'nrcs_code' => '393',
      ],
      'forage_biomass_planting' => [
        'label' => t('Forage Biomass Planting'),
        'nrcs_code' => 'E512B',
      ],
      'forest_stand_mgmt' => [
        'label' => t('Forest Stand Management'),
        'nrcs_code' => '666',
      ],
      'fuel_break' => [
        'label' => t('Fuel Break'),
        'nrcs_code' => '383',
      ],
      'grassed_waterway' => [
        'label' => t('Grassed Waterway'),
        'nrcs_code' => '412',
      ],
      'hedgerow_planting' => [
        'label' => t('Hedgerow Planting'),
        'nrcs_code' => '422',
      ],
      'irrigation_water_mgmt' => [
        'label' => t('Irrigation Water Management'),
        'nrcs_code' => '449',
      ],
      'keyline_plow' => [
        'label' => t('Keyline Plow'),
        'nrcs_code' => '',
      ],
      'mulching' => [
        'label' => t('Mulching'),
        'nrcs_code' => '484',
      ],
      'nutrient_mgmt' => [
        'label' => t('Nutrient Management'),
        'nrcs_code' => '590',
      ],
      'pollinator_habitat' => [
        'label' => t('Pollinator Habitat Enhancement'),
        'nrcs_code' => '',
      ],
      'prescribed_burn' => [
        'label' => t('Prescribed Burn'),
        'nrcs_code' => '338',
      ],
      'prescribed_grazing' => [
        'label' => t('Prescribed Grazing'),
        'nrcs_code' => '528',
      ],
      'range_planting' => [
        'label' => t('Range Planting'),
        'nrcs_code' => '550',
      ],
      'residue_tillage_mgmt_no_till' => [
        'label' => t('Residue and Tillage Management, No Till'),
        'nrcs_code' => '345',
      ],
      'riparian_forest_buffer' => [
        'label' => t('Riparian Forest Buffer'),
        'nrcs_code' => '391',
      ],
      'riparian_herbaceous_planting' => [
        'label' => t('Riparian Herbaceous Planting'),
        'nrcs_code' => '390',
      ],
      'roof_runoff_strucfture' => [
        'label' => t('Roof Runoff Structure'),
        'nrcs_code' => '558',
      ],
      'silvopasture' => [
        'label' => t('Silvopasture'),
        'nrcs_code' => '381',
      ],
      'soil_carbon_amendment' => [
        'label' => t('Soil Carbon Amendment (e.g. compost)'),
        'nrcs_code' => '336',
      ],
      'stream_habitat_mgmt' => [
        'label' => t('Stream Habitat Improvement and Management'),
        'nrcs_code' => '395',
      ],
      'streambank_shoreline_improvement' => [
        'label' => t('Streambank/Shoreline Improvement'),
        'nrcs_code' => '580',
      ],
      'structure_water_control' => [
        'label' => t('Structure for Water Control'),
        'nrcs_code' => '587',
      ],
      'structure_wildlife' => [
        'label' => t('Structures for Wildlife'),
        'nrcs_code' => '649',
      ],
      'tree_shrub_establishment' => [
        'label' => t('Tree/Shrub Establishment'),
        'nrcs_code' => '612',
      ],
      'upland_wildlife_mgmt' => [
        'label' => t('Upland Wildlife Management'),
        'nrcs_code' => '645',
      ],
      'water_sediment_control_basin' => [
        'label' => t('Water and Sediment Control Basin'),
        'nrcs_code' => '638',
      ],
      'wildlife_habitat_planting' => [
        'label' => t('Wildlife Habitat Planting'),
        'nrcs_code' => '420',
      ],
      'windbreak_shelterbelt' => [
        'label' => t('Windbreak/Shelterbelt'),
        'nrcs_code' => '380',
      ],
      'other' => [
        'label' => t('Other'),
        'nrcs_code' => '',
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
