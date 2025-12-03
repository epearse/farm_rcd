<?php

declare(strict_types=1);

namespace Drupal\farm_rcd;

/**
 * Define allowed values RCP fields.
 *
 * @internal
 */
class RcdHelper {

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
      'rcd_row_crops' => t('Row crops'),
      'rcd_orchard' => t('Orchard'),
      'rcd_vineyard' => t('Vineyard'),
      'rcd_grazing' => t('Grazing'),
      'rcd_pastureland' => t('Pastureland'),
      'rcd_natural_area' => t('Natural area'),
      'rcd_riparian_area' => t('Riparian area'),
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
        'benefits' => [
          'Promotes ecological, geomorphic, and hydrologic processes such as ponding, scour, sediment deposition, streambank stabilization, groundwater recharge, and riparian vegetation establishment',
          'Increased ponding and establishment of backwaters promotes greater connectivity of aquatic habitat',
          'Dam structures may obstruct debris, sediment and pollutants',
          'Increased groundwater recharge as a result of slower water discharge',
          'Decreased intensity of flooding and headcutting of channels as a result of slower water discharge',
          'Raised water levels from dams creates habitat conditions for riparian vegetation',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/low-tech-process-based-restoration-to-enhance-floodplain-connectivity-e643d',
        ],
      ],
      'biochar' => [
        'label' => t('Soil Carbon Amendment (Biochar)'),
        'nrcs_code' => '336',
        'benefits' => [
          'Improve or maintain soil organic matter',
          'Sequester carbon and enhance soil carbon (C) stocks',
          'Improve soil aggregate stability',
          'Improve habitat for soil organisms',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-11/336-NHCP-CPS-Soil-Carbon-Amendment-2022.pdf',
        ],
      ],
      'brush_mgmt' => [
        'label' => t('Brush Management'),
        'nrcs_code' => '314',
        'benefits' => [
          'Create the desired plant community consistent with the ecological site or a desired state within the site description',
          'Restore or release desired vegetative cover to protect soils, control erosion, reduce sediment, improve water quality, or enhance hydrology',
          'Maintain, modify, or enhance fish and wildlife habitat',
          'Improve forage accessibility, quality, and quantity for livestock and wildlife',
          'Manage fuel loads to achieve desired conditions',
          'Pervasive plant species are controlled to a desired level of treatment that will ultimately contribute to creation or maintenance of an ecological site description "steady state" addressing the need for forage, wildlife habitat, and/or water quality',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Brush_Management_314_CPS-3-17Final.pdf',
        ],
      ],
      'conservation_cover' => [
        'label' => t('Conservation Cover'),
        'nrcs_code' => '327',
        'benefits' => [
          'Reduce sheet, rill, and wind erosion',
          'Reduce sediment transport to surface water',
          'Reduce ground and surface water quality degradation by nutrients',
          'Reduce emissions of particulate matter',
          'Provide wildlife, pollinator, and other beneficial organism habitat',
          'Improve soil health by maintaining or increasing soil organic matter quantity',
          'Improve soil health by increasing soil aggregate stability',
          'Improve soil health by enhancing habitat for soil organisms',
          'Improve soil health by reducing compaction',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2024-06/327-nhcp-cps-conservation-cover-2024.pdf',
        ],
      ],
      'constructed_wetland' => [
        'label' => t('Constructed Wetland'),
        'nrcs_code' => '656',
        'benefits' => [
          'Treat wastewater or contaminated runoff from agricultural processing, livestock, or aquaculture facilities',
          'Improve water quality of storm water runoff, tile drainage outflow, or other waterflows.',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Constructed_Wetland_656_NHCP_CPS_2020.pdf',
        ],
      ],
      'contour_orchard_perennials' => [
        'label' => t('Contour Orchard and Perennials'),
        'nrcs_code' => '331',
        'benefits' => [
          'Reduce sheet and rill soil erosion',
          'Reduce transport of excessive sediment and other associated contaminants',
          'Improve water use efficiency with improved infiltration',
        ],
        'resources' => [
          'https://nrcs.usda.gov/sites/default/files/2022-09/Contour_Orchard_and_Other_Perennial_Crops_331_Overview.pdf',
        ],
      ],
      'cover_crop' => [
        'label' => t('Cover Crop'),
        'nrcs_code' => '340',
        'benefits' => [
          'Reduce sheet, rill, and wind erosion',
          'Maintain or increase soil organic matter',
          'Improve soil aggregate stability',
          'Improve habitat for soil organisms',
          'Reduce water quality degradation by utilizing excess soil nutrients',
          'Reduce weed and plant pest pressure',
          'Improve moisture management',
          'Reduce soil compaction',
          'Supply nitrogen to the subsequent crop',
          'Improve habitat for pollinators, beneficial organisms, or natural enemies of crop pests',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2024-06/340-nhcp-cps-cover-crop-2024.pdf',
        ],
      ],
      'critical_area_planting' => [
        'label' => t('Critical Area Planting'),
        'nrcs_code' => '342',
        'benefits' => [
          'Stabilize areas with existing or expected high rates of soil erosion by wind or water',
          'Stabilize stream and channel banks, pond and other shorelines, earthen features of structural conservation practices',
          'Stabilize areas such as sand dunes and riparian areas',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Critical_Area_Planting_342_CPS.pdf',
        ],
      ],
      'filter_strip' => [
        'label' => t('Filter Strip'),
        'nrcs_code' => '393',
        'benefits' => [
          'Reduce suspended solids and associated contaminants in runoff and excessive sediment in surface waters',
          'Reduce dissolved contaminant loadings in runoff',
          'Reduce suspended solids and associated contaminants in irrigation tailwater and excessive sediment in surface waters',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Filter_Strip_393_CPS.pdf',
        ],
      ],
      'forage_biomass_planting' => [
        'label' => t('Forage Biomass Planting'),
        'nrcs_code' => 'E512B',
        'benefits' => [
          'Increase available forage for grazing livestock',
          'Increase soil cover and reduce soil erosion',
          'Increase organic material in soil',
          'Increase soil health',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2024-12/E512B-Forage%20and%20biomass%20planting%20to%20reduce%20soil%20erosion%20or%20increase%20organic%20matter%20to%20build%20soil%20health.pdf',
        ],
      ],
      'forest_stand_mgmt' => [
        'label' => t('Forest Stand Management'),
        'nrcs_code' => '666',
        'benefits' => [
          'Improve and sustain forest health and productivity',
          'Reduce damage from pests and moisture stress Initiate forest stand regeneration',
          'Reduce fire risk and hazard and facilitate prescribed burning',
          'Restore or maintain natural plant communities',
          'Improve wildlife and pollinator habitat',
          'Alter quantity, quality, and timing of water yield',
          'Increase or maintain carbon storage',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Forest_Stand_Improvement_666_CPS.pdf',
        ],
      ],
      'fuel_break' => [
        'label' => t('Fuel Break'),
        'nrcs_code' => '383',
        'benefits' => [
          'Significantly reduce the spread of wildfire resulting from excessive biomass accumulations',
          'Facilitate the management of plant productivity and health with prescribed fire',
          'Facilitate the improvement of fish and wildlife habitat and/or livestock forage quality or quantity by facilitating prescribed fire',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/383_NHCP_CPS_Fuel_Break_2021_0.pdf',
        ],
      ],
      'grassed_waterway' => [
        'label' => t('Grassed Waterway'),
        'nrcs_code' => '412',
        'benefits' => [
          'Convey runoff from terraces, diversions, or other water concentrations without causing erosion or flooding',
          'Prevent gully formation',
          'Protect/improve water quality',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Grassed_Waterway_412_CPS_9_2020.pdf',
        ],
      ],
      'hedgerow_planting' => [
        'label' => t('Hedgerow Planting'),
        'nrcs_code' => '422',
        'benefits' => [
          'Provide habitat including food, cover, shelter or habitat connectivity for terrestrial or aquatic wildlife',
          'Provide cover for beneficial invertebrates as a component of pest management',
          'Filter, intercept, or adsorb airborne particulate matter, chemical drift, or odors',
          'Provide visual or physical screens and barriers',
          'Increase carbon storage in biomass and soils',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2024-01/422_NHCP_CPS_Hedgerow_Planting_2023_0.pdf',
        ],
      ],
      'irrigation_water_mgmt' => [
        'label' => t('Irrigation Water Management'),
        'nrcs_code' => '449',
        'benefits' => [
          'Improve irrigation water use efficiency',
          'Minimize irrigation-induced soil erosion',
          'Protect surface and ground water quality',
          'Manage salts in the crop root zone Manage air, soil, or plant microclimate',
          'Improve poor plant productivity and health',
          'Reduce energy use',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Irrigation_Water_Management_449_CPS_9_2020.pdf',
        ],
      ],
      'keyline_plow' => [
        'label' => t('Keyline Plow'),
        'nrcs_code' => '',
        'benefits' => [
          'Loosens the sub-soil without inverting the soil',
          'The small ridges created by the plow on the soil surface facilitate the movement of water downwards through the soil profile and direct the movement of water across the land',
          'The plow also facilitates the transport of organic matter deeper into the soil horizon',
          'Keyline systems capture significant quantities of water that would otherwise run off, and store it in the soil',
          'Builds soil fertility due to increased water availability and increased biological activity which further improves moisture-holding capacity',
        ],
        'resources' => [
          'https://www.fws.gov/project/nature-based-solutions-restoring-rangelands-keyline-design',
          'https://agwaterstewards.org/practices/keyline_design/',
        ],
      ],
      'mulching' => [
        'label' => t('Mulching'),
        'nrcs_code' => '484',
        'benefits' => [
          'Improve the efficiency of moisture management',
          'Reduce irrigation energy used in farming/ranching practices and field operations',
          'Improve the efficient use of irrigation water',
          'Prevent excessive bank erosion from water conveyance channels',
          'Reduce concentrated flow erosion',
          'Reduce sheet, rill, & wind erosion',
          'Improve plant productivity and health',
          'Maintain or increase organic matter content',
          'Reduce emissions of particulate matter',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Mulching_CPS_484_Oct_2017.pdf',
        ],
      ],
      'nutrient_mgmt' => [
        'label' => t('Nutrient Management'),
        'nrcs_code' => '590',
        'benefits' => [
          'Improve plant health and productivity',
          'Reduce excess nutrients in surface and ground water',
          'Reduce emissions of objectionable odors',
          'Reduce emissions of particulate matter (PM) and PM precursors',
          'Reduce emissions of greenhouse gases (GHG)',
          'Reduce emissions of ozone precursors',
          'Reduce the risk of potential pathogens from manure, biosolids, or compost application from reaching surface and ground water',
          'Improve or maintain soil organic matter',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Nutrient_Management_590_NHCP_CPS_2017.pdf',
        ],
      ],
      'pollinator_habitat' => [
        'label' => t('Pollinator Habitat Enhancement'),
        'nrcs_code' => '',
        'benefits' => [
          'Increase diversity and abundance of nectar plants for native pollinators',
          'Improve soil health by maintaining or increasing soil organic matter quantity',
          'Improve soil health by increasing soil aggregate stability',
          'Improve soil health by enhancing habitat for soil organisms',
          'Improve soil health by reducing compaction',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/resources/guides-and-instructions/resources-to-help-pollinators',
        ],
      ],
      'prescribed_burn' => [
        'label' => t('Prescribed Burn'),
        'nrcs_code' => '338',
        'benefits' => [
          'Manage undesirable vegetation and reduce plant pressure caused by pests, pathogens, and diseases',
          'Reduce the various risks associated with wildfire',
          'Improve terrestrial habitat for wildlife and invertebrates (pollinators)',
          'Improve plant and seed production, quantity, and/or quality',
          'Improve livestock-forage balance by enhancing plant productivity and the distribution of grazing and browsing animals',
          'Improve habitat for soil organisms, thereby enhancing soil health',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Prescribed_Burning_338_Overview_10_2020.pdf',
        ],
      ],
      'prescribed_grazing' => [
        'label' => t('Prescribed Grazing'),
        'nrcs_code' => '528',
        'benefits' => [
          'Improve or maintain desirable species composition, structure, productivity, health and/or vigor of plants and plant communities',
          'Improve or maintain the quantity, quality, and/or balance of forages to meet the nutritional needs and ensure the health and performance of grazing and browsing animals',
          'Reduce or eliminate the transportation of sediment, nutrients, pathogens, or chemicals to surface and groundwater',
          'Improve or maintain upland hydrology, riparian dynamics, or watershed function to reduce surface or groundwater depletion and improve naturally available moisture',
          'Reduce runoff and compaction and enhance or maintain key soil health components, such as soil organic matter, aggregate stability, habitat for soil organisms, water infiltration, and water holding capacity',
          'Prevent or reduce sheet, rill, classic gully, ephemeral gully, bank, or wind erosion',
          'Improve or maintain terrestrial or aquatic habitat for wildlife, fish, invertebrates, or other organisms',
          'Manage biomass accumulation for the desired fuel load to reduce wildfire risk or to facilitate prescribed burning',
          'Reduce plant pest pressure from invasive and/or undesirable plants and other pests as part of an integrated plan',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2025-07/528-nhcp-cps-grazing-management-2025-rev.pdf',
        ],
      ],
      'range_planting' => [
        'label' => t('Range Planting'),
        'nrcs_code' => '550',
        'benefits' => [
          'Restore a plant community to a state similar to the ecological site description reference state for the site or another desired plant community',
          'Provide or improve forages for livestock',
          'Provide or improve forage, browse, or cover for wildlife',
          'Reduce erosion by wind and water',
          'Improve water quality and quantity',
          'Restore hydrologic function',
          'Increase and/or stabilize carbon balance and sequestration',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Range_Planting_550_NHCP_CPS_2022.pdf',
        ],
      ],
      'residue_tillage_mgmt_no_till' => [
        'label' => t('Residue and Tillage Management, No Till'),
        'nrcs_code' => '345',
        'benefits' => [
          'Increase soil organic matter and tilth',
          'Increase productivity as the constant supply of organic material left on the soil surface is decomposed by a healthy population of earth worms and other organisms',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Residue_And_Tillage_Management_Reduced_Till_345_PS_Sept_2016.pdf',
        ],
      ],
      'riparian_forest_buffer' => [
        'label' => t('Riparian Forest Buffer'),
        'nrcs_code' => '391',
        'benefits' => [
          'Reduce transport of sediment to surface water, and reduce transport of pathogens, chemicals, pesticides, and nutrients to surface and ground water',
          'Improve the quantity and quality of terrestrial and aquatic habitat for wildlife, invertebrate species, fish, and other organisms',
          'Maintain or increase total carbon stored in soils and/or perennial biomass to reduce atmospheric concentrations of greenhouse gasses',
          'Lower elevated stream water temperatures',
          'Restore diversity, structure, and composition of riparian plant communities',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Riparian_Forest_Buffer_391_Overview_10_2020.pdf',
        ],
      ],
      'riparian_herbaceous_planting' => [
        'label' => t('Riparian Herbaceous Planting'),
        'nrcs_code' => '390',
        'benefits' => [
          'Provide or improve food and cover for fish, wildlife and livestock',
          'Improve and maintain water quality',
          'Establish and maintain habitat corridors',
          'Increase water storage on floodplains',
          'Reduce erosion and improve stability to stream banks and shorelines',
          'Increase net carbon storage in the biomass and soil',
          'Enhance pollen, nectar, and nesting habitat for pollinators',
          'Restore, improve or maintain the desired plant communities',
          'Dissipate stream energy and trap sediment',
          'Enhance stream bank protection as part of stream bank soil bioengineering practices',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-11/390-NHCP-CPS-Riparian-Herbaceous-Cover-2022.pdf',
        ],
      ],
      'roof_runoff_strucfture' => [
        'label' => t('Roof Runoff Structure'),
        'nrcs_code' => '558',
        'benefits' => [
          'Protect surface water quality by excluding roof runoff from contaminated areas',
          'Prevent erosion from roof runoff',
          'Increase infiltration of roof runoff',
          'Capture roof runoff for on-farm use',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Roof_Runoff_Structure_558_NHCP_CPS_2021.pdf',
        ],
      ],
      'silvopasture' => [
        'label' => t('Silvopasture'),
        'nrcs_code' => '381',
        'benefits' => [
          'Provide forage, shade, and/or shelter for livestock',
          'Improve the productivity and health of trees/shrubs and forages',
          'Improve water quality',
          'Reduce erosion',
          'Enhance wildlife habitat',
          'Improve biological diversity',
          'Improve soil quality',
          'Increase carbon sequestration and storage',
          'Provide for beneficial organisms and pollinators',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Silvopasture-381-CPS-May-2016.pdf',
        ],
      ],
      'soil_carbon_amendment' => [
        'label' => t('Soil Carbon Amendment (e.g. compost)'),
        'nrcs_code' => '336',
        'benefits' => [
          'Improve or maintain soil organic matter',
          'Sequester carbon and enhance soil carbon (C) stocks',
          'Improve soil aggregate stability',
          'Improve habitat for soil organisms',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-11/336-NHCP-CPS-Soil-Carbon-Amendment-2022.pdf',
        ],
      ],
      'stream_habitat_mgmt' => [
        'label' => t('Stream Habitat Improvement and Management'),
        'nrcs_code' => '395',
        'benefits' => [
          'Improve or manage stream habitat by evaluating and addressing factors that impair stream function and structure',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-10/Stream_Habitat_Improvement_And_Management_395_CPS.pdf',
        ],
      ],
      'streambank_shoreline_improvement' => [
        'label' => t('Streambank/Shoreline Improvement'),
        'nrcs_code' => '580',
        'benefits' => [
          'Prevent the loss of land or damage to land uses or facilities adjacent to the banks of streams or constructed channels and shorelines of lakes, reservoirs, or estuaries - this includes the protection of known historical, archaeological, and traditional cultural properties',
          'Maintain the flow capacity of streams or channels',
          'Reduce the offsite or downstream effects of sediment resulting from bank erosion',
          'Improve or enhance the stream corridor or shoreline for fish and wildlife habitat, aesthetics, or recreation',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-10/Streambank_Shoreline_Protection_580_CPS_10_2020.pdf',
        ],
      ],
      'structure_water_control' => [
        'label' => t('Structure for Water Control'),
        'nrcs_code' => '587',
        'benefits' => [
          'Convey water from one elevation to a lower elevation within, to, or from a water conveyance system such as a ditch, channel, canal, or pipeline',
          'Control the elevation of water in drainage or irrigation ditches',
          'Control the division or measurement of irrigation water',
          'Keep trash, debris or weed seeds from entering pipelines',
          'Control the direction of channel flow resulting from tides and high water or backflow from flooding',
          'Control the water table level, remove surface or subsurface water from adjoining land, flood land for frost protection, or manage water levels for wildlife or recreation',
          'Convey water over, under, or along a ditch, canal, road, railroad, or other barriers',
          'Modify water flow to provide habitat for fish, wildlife, and other aquatic animals',
          'Provide silt management in ditches or canals',
          'Supplement a resource management system on land where organic waste or commercial fertilizer is applied',
          'Create, restore, or enhance wetland hydrology',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-10/Structure_for_Water_Control_587_CPS_Oct_2017.pdf',
        ],
      ],
      'structure_wildlife' => [
        'label' => t('Structures for Wildlife'),
        'nrcs_code' => '649',
        'benefits' => [
          'Enhance or sustain non-domesticated wildlife; or modify existing structures that pose a hazard to wildlife',
          'Provide loafing, escape, nesting, rearing, roosting, perching and/or basking habitat - examples are nesting islands, nesting boxes, roosting boxes, rock piles, perching structures and brush piles',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-10/Structures_for_Wildlife_649_CPS.pdf',
        ],
      ],
      'tree_shrub_establishment' => [
        'label' => t('Tree/Shrub Establishment'),
        'nrcs_code' => '612',
        'benefits' => [
          'Maintain or improve desirable plant diversity, productivity, and health by establishing woody plants',
          'Improve water quality by reducing excess nutrients and other pollutants in runoff and ground water',
          'Restore or maintain native plant communities',
          'Control erosion',
          'Create or improve habitat for target wildlife species, beneficial organisms, or pollinator species compatible with ecological characteristics of the site',
          'Sequester and store carbon',
          'Conserve energy',
          'Provide livestock shelter',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-12/612-NHCP-CPS-Tree-Shrub-Establishment-2022.pdf',
        ],
      ],
      'upland_wildlife_mgmt' => [
        'label' => t('Upland Wildlife Management'),
        'nrcs_code' => '645',
        'benefits' => [
          'Treating upland wildlife habitat concerns identified during the conservation planning process that enable movement, or provide shelter, cover, food in proper amounts, locations and times to sustain wild animals that inhabit uplands during a portion of their life cycle',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-11/645-NHCP-CPS-Upland-Wildlife-Habitat-Management-2022.pdf',
        ],
      ],
      'water_sediment_control_basin' => [
        'label' => t('Water and Sediment Control Basin'),
        'nrcs_code' => '638',
        'benefits' => [
          'Reduce gully erosion',
          'Trap sediment',
          'Reduce and manage runoff',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2023-08/638_NHCP_CPS_Water_and_Sediment_Control_Basin_2023.pdf',
        ],
      ],
      'wildlife_habitat_planting' => [
        'label' => t('Wildlife Habitat Planting'),
        'nrcs_code' => '420',
        'benefits' => [
          'Improve degraded wildlife habitat for the target wildlife species or guild',
          'Establish wildlife habitat that resembles the historic, desired, and reference native plant community',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-10/Wildlife_Habitat_Planting_420_NHCP_CPS_2018.pdf',
        ],
      ],
      'windbreak_shelterbelt' => [
        'label' => t('Windbreak/Shelterbelt'),
        'nrcs_code' => '380',
        'benefits' => [
          'Reduce soil erosion from wind',
          'Enhance plant health and productivity by protecting plants from wind-related damage',
          'Manage snow distribution to improve moisture utilization by plants',
          'Manage snow distribution to reduce obstacles, ponding, and flooding that impacts other resources, animals, structures, and humans',
          'Improve moisture management by reducing transpiration and evaporation losses and improving irrigation efficiency',
          'Provide shelter from wind, snow, and excessive heat, to protect animals, structures, and humans',
          'Improve air quality by intercepting airborne particulate matter, chemicals, and odors, and/or by reducing airflow across contaminant or dust sources',
          'Reduce energy use in heating and cooling buildings, and in relocating snow',
          'Increase carbon storage in biomass and soils',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-10/Windbreak-Shelterbelt_Establishment_380_NHCP_CPS_2021.pdf',
        ],
      ],
      'other' => [
        'label' => t('Other'),
        'nrcs_code' => '',
        'benefits' => [],
        'resources' => [],
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
