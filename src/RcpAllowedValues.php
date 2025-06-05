<?php

declare(strict_types=1);

namespace Drupal\farm_rcp;

/**
 * Define allowed values RCP fields.
 */
class RcpAllowedValues {

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

}
