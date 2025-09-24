<?php

declare(strict_types=1);

namespace Drupal\farm_sli;

/**
 * Define allowed values RCP fields.
 */
class SliAllowedValues {

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
   * Goals.
   *
   * @return array
   *   Returns an array of allowed value options.
   */
  public static function goals() {
    return [
      'succession' => t('Pass on the farm/ranch to the next generation'),
      'reduce_debt' => t('Reduce family/farm debt'),
      'expand_enterprises' => t('Expand farm/ranch enterprises'),
      'new_enterprises' => t('Develop new enterprises'),
      'profitability' => t('Increase farm/ranch profitability'),
      'reduce_costs' => t('Reduce operating costs'),
      'property' => t('Purchase or lease more ranch/farm property'),
      'brand' => t('Build a climate-smart/green brand or story for your farm or ranch'),
      'sustainability' => t('Improve land sustainability'),
      'other' => t('Other'),
    ];
  }

  /**
   * Interests.
   *
   * @return array
   *   Returns an array of allowed value options.
   */
  public static function interests() {
    return [
      'rangeland_erosion' => t('Manage rangeland to protect soil from erosion and increase production'),
      'cropland_erosion' => t('Manage cropland, pastureland, or forestland to protect soil from erosion and increase production'),
      'roads' => t('Manage ranch roads to reduce movement of sediment into streams and other water bodies'),
      'bank_erosion' => t('Reduce erosion of streambanks and gullies'),
      'cover' => t('Manage to increase tree cover and/or ground cover in riparian areas or along streams'),
      'livestock_concentration' => t('Reduce concentration of livestock in or near streams, wetlands, or other water bodies'),
      'runoff' => t('Manage to reduce entry of sediment, nutrients, and pathogens to streams or wetlands'),
      'wildfire' => t('Reduce wildfire hazard'),
      'plants' => t('Maintain or enhance oak woodland, native grass, or other plant communities'),
      'wildlife' => t('Maintain or enhance wildlife or fisheries habitat or other aquatic resources'),
      'weeds' => t('Reduce/manage invasive weeds'),
      'predators' => t('Reduce/manage predator impacts on the ranching operation'),
      'water_regulations' => t('Meet water quality regulations'),
      'water_capacity' => t('Improve water holding capacity of your soil, increase forage production'),
      'alt_water' => t('Utilize alternative water storage, water conservation strategies'),
      'climate_resilience' => t('Increase farm resilience to drought, flood and other climate impacts'),
      'carbon_farming' => t('Be part of the climate change solution through carbon farming'),
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
      'bda' => t('Beaver Dam Analog (BDA)'),
      'biochar' => t('Bio-char'),
      'brush_mgmt' => t('Brush Management'),
      'conservation_cover' => t('Conservation Cover'),
      'constructed_wetland' => t('Constructed Wetland'),
      'contour_orchard_perennials' => t('Contour Orchard and Perennials'),
      'cover_crop' => t('Cover Crop'),
      'critical_area_planting' => t('Critical Area Planting'),
      'filter_strip' => t('Filter Strip'),
      'forage_biomass_planting' => t('Forage Biomass Planting'),
      'forest_stand_mgmt' => t('Forest Stand Management'),
      'fuel_break' => t('Fuel Break'),
      'grassed_waterway' => t('Grassed Waterway'),
      'hedgerow_planting' => t('Hedgerow Planting'),
      'irrigation_water_mgmt' => t('Irrigation Water Management'),
      'keyline_plow' => t('Keyline Plow'),
      'mulching' => t('Mulching'),
      'nutrient_mgmt' => t('Nutrient Management'),
      'pollinator_habitat' => t('Pollinator Habitat Enhancement'),
      'prescribed_burn' => t('Prescribed Burn'),
      'prescribed_grazing' => t('Prescribed Grazing'),
      'range_planting' => t('Range Planting'),
      'residue_tillage_mgmt_no_till' => t('Residue and Tillage Management, No Till'),
      'riparian_forest_buffer' => t('Riparian Forest Buffer'),
      'riparian_herbaceous_planting' => t('Riparian Herbaceous Planting'),
      'roof_runoff_strucfture' => t('Roof Runoff Structure'),
      'silvopasture' => t('Silvopasture'),
      'soil_carbon_amendment' => t('Soil Carbon Amendment (e.g. compost)'),
      'stream_habitat_mgmt' => t('Stream Habitat Improvement and Management'),
      'streambank_shoreline_improvement' => t('Streambank/Shoreline Improvement'),
      'structure_water_control' => t('Structure for Water Control'),
      'structure_wildlife' => t('Structures for Wildlife'),
      'tree_shrub_establishment' => t('Tree/Shrub Establishment'),
      'upland_wildlife_mgmt' => t('Upland Wildlife Management'),
      'water_sediment_control_basin' => t('Water and Sediment Control Basin'),
      'wildlife_habitat_planting' => t('Wildlife Habitat Planting'),
      'windbreak_shelterbelt' => t('Windreak/Shelterbelt'),
      'other' => t('Other'),
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
