<?php

declare(strict_types=1);

namespace Drupal\farm_rcd;

/**
 * Define conservation practices.
 *
 * @internal
 */
class ConservationPractices {

  /**
   * Get the definition of a specific conservation practice.
   *
   * @param string $name
   *   The practice machine name.
   *
   * @return array|null
   *   Returns a conservation practice definition.
   */
  public static function get(string $name): ?array {
    $definitions = static::definitions();
    if (isset($definitions[$name])) {
      return $definitions[$name];
    }
    return NULL;
  }

  /**
   * Define all conservation practices.
   *
   * @return array
   *   Returns an array of conservation practice definitions.
   */
  public static function definitions() {
    return [
      'brush_mgmt' => [
        'label' => t('Brush Management'),
        'description' => 'The management or removal of woody (non-herbaceous or succulent) plants including those that are invasive and noxious. Brush management will be designed to achieve the desired plant community based on species composition, structure, density, and canopy (or foliar) cover or height. Brush management will be applied in a manner to achieve the desired control of the target woody species and protection of desired species. This will be accomplished by mechanical, chemical, burning, or biological methods, either alone or in combination. When prescribed burning is used as a method, CPS Prescribed Burning (Code 338) will also be applied.',
        'nrcs_code' => '314',
        'unit' => 'ac',
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
        'description' => 'Establishing and maintaining permanent vegetative cover. This practice may be used to conserve and stabilize archaeological and historic sites. This practice does not apply to plantings for forage production or to critical area plantings that require special measures to ensure successful establishment. If the soil and water resource concerns meet minimum planning criteria and the only purpose of the planting is to provide wildlife habitat, Wildlife Habitat Planting (CPS 420) may be implemented.',
        'nrcs_code' => '327',
        'unit' => 'ac',
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
        'description' => 'An artificial wetland ecosystem with hydrophytic vegetation for biological treatment of water.',
        'nrcs_code' => '656',
        'unit' => 'ac',
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
        'description' => 'This practice is used when orchards, vineyards, or other perennial crops are established on sloping land. Planting on the contour conserves and protects soil, water, and related natural resources.',
        'nrcs_code' => '331',
        'unit' => 'ac',
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
        'description' => 'Cover crops include grasses, legumes, and other forms planted for seasonal vegetative cover. Cover crops are established as part of a cropping system between production crops in rotation. Cover crops may be inter-seeded into production crops or planted in the alleyways of perennial trees or vine crops. Select species and planting dates that will not adversely affect crop yield or interfere with the harvest process.',
        'nrcs_code' => '340',
        'unit' => 'ac',
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
        'description' => 'Establishing permanent vegetation on sites that have, or are expected to have, high erosion rates, and on sites that have physical, chemical, or biological conditions that prevent the establishment of vegetation with normal seeding/planting methods. This practice applies to highly disturbed areas such as—
• Active or abandoned mined lands.
• Urban restoration sites.
• Construction areas.
• Conservation practice construction sites.
• Areas needing stabilization before or after natural disasters such as floods, hurricanes, tornadoes, and wildfires.
• Eroded banks of natural channels, banks of newly constructed channels, and lake shorelines.
• Other areas degraded by human activities or natural events.',
        'nrcs_code' => '342',
        'unit' => 'ac',
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
        'description' => 'A strip or area of herbaceous vegetation that removes contaminants from overland flow. Filter strips are established where environmentally sensitive areas need to be protected from sediment, other suspended solids, and dissolved contaminants in runoff.',
        'nrcs_code' => '393',
        'unit' => 'ac',
        'benefits' => [
          'Reduce suspended solids and associated contaminants in runoff and excessive sediment in surface waters',
          'Reduce dissolved contaminant loadings in runoff',
          'Reduce suspended solids and associated contaminants in irrigation tailwater and excessive sediment in surface waters',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Filter_Strip_393_CPS.pdf',
        ],
      ],
      'forest_stand_mgmt' => [
        'label' => t('Forest Stand Management'),
        'description' => 'The manipulation of species composition, stand structure, or stand density by cutting or killing selected trees or understory vegetation to achieve desired forest conditions or obtain ecosystem services.',
        'nrcs_code' => '666',
        'unit' => 'ac',
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
        'description' => 'A strip or appropriately sized block of land on which the vegetation, debris, and litter have been reduced and/or modified to control or diminish the spread of fire. This practice applies to all lands where protection from wildfire or facilitation of prescribed fire is needed.',
        'nrcs_code' => '383',
        'unit' => 'ac',
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
        'description' => 'A shaped or graded channel that is established with suitable vegetation to convey surface water at a non-erosive velocity using a broad and shallow cross section to a stable outlet. This practice is applied in areas where added water conveyance capacity and vegetative protection are needed to prevent erosion and improve runoff water quality resulting from concentrated surface flow.',
        'nrcs_code' => '412',
        'unit' => 'ac',
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
        'description' => 'Establishment of dense perennial vegetation in a linear design to achieve a conservation purpose. Hedgerows must retain sufficient vertical structure throughout the year to achieve the desired function. In all cases, the width of the hedgerow must be sufficient to achieve the stated purpose. This may necessitate the establishment of more than one row of plants.',
        'nrcs_code' => '422',
        'unit' => 'ft',
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
        'description' => 'The process of determining and controlling the volume, frequency, and application rate of irrigation water. Develop an irrigation water management (IWM) plan that defines when irrigation is needed (timing) and the amount and rate of water to apply for each irrigation event.',
        'nrcs_code' => '449',
        'unit' => 'ac',
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
        'description' => 'Two keyline design features are swales and rip lines. Swales are trenches dug along the contour lines of the landscape that have a berm, or higher wall of soil, on the downhill side of the swale. Swales slow and spread water to dry areas, with the berm acting as a steering bank to control surges caused by heavy precipitation. Thin and shallow rip lines are dug just above swales to collect precipitation, which allows water to infiltrate into the soil reducing runoff and soil erosion during heavy precipitation events. The ranches share the tools necessary to implement keyline design, including the technology required to map contour lines. In the dry climate of northern New Mexico, keyline design and treatment methods require at least five years of ongoing management, including maintaining swales and creating deeper rip lines, to see measurable increases in vegetation and soil organic matter.',
        'nrcs_code' => '',
        'unit' => 'ac',
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
        'description' => 'Applying plant residues or other suitable materials to the land surface. The selection of mulching materials will depend primarily on the purpose(s) for the mulch application, site conditions, and the material’s availability. The mulch materials may consist of natural or artificial materials of sufficient dimension (depth or thickness) and durability to achieve the intended purpose for the required time period.',
        'nrcs_code' => '484',
        'unit' => 'ac',
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
        'description' => 'Management of the rate, source, placement, and timing of plant nutrients and soil amendments while reducing environmental impacts. Applies to all fields where plant nutrients and soil amendments are applied. Does not apply to one-time nutrient applications at establishment of permanent vegetation. Develop a nutrient management plan for nitrogen (N), phosphorus (P), and potassium (K), which accounts for all known measurable sources and removal of these nutrients. Sources of nutrients include, but are not limited to, commercial fertilizers (including starter and in-furrow starter/pop-up fertilizer), animal manures, legume fixation credits, green manures, plant or crop residues, compost, organic by-products, municipal and industrial biosolids, wastewater, organic materials, estimated plant available soil nutrients, and irrigation water.',
        'nrcs_code' => '590',
        'unit' => 'ac',
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
      'pasture_hay_planting' => [
        'label' => t('Pasture and Hay Planting'),
        'description' => 'Establishing adapted and compatible species, varieties, or cultivars of perennial herbaceous plants suitable for pasture or hay production. This practice applies on all lands suitable for the one-time establishment of perennial species for forage production that will likely persist for 5 years. This practice does not apply to the establishment of annually planted and mechanically harvested food, fiber, or oilseed crops planted on designated cropland.',
        'nrcs_code' => '512',
        'unit' => 'ac',
        'benefits' => [
          'Improve or maintain livestock nutrition and health',
          'Provide or increase forage supply during periods of low forage production',
          'Reduce soil erosion',
          'Improve water quality',
          'Improve air quality',
          'Improve soil health',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Pasture_and_Hay_Planting_512_NHCP_CPS_2020.pdf',
        ],
      ],
      'prescribed_burn' => [
        'label' => t('Prescribed Burn'),
        'description' => 'Prescribed burning is applying a planned fire to a predetermined area of land.',
        'nrcs_code' => '338',
        'unit' => 'ac',
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
        'description' => 'Managing vegetation with grazing and browsing animals to achieve specific ecological, economic, and management objectives. This practice applies to all lands where grazing and browsing animals are managed. Manage livestock numbers and grazing periods to adjust the intensity, frequency, timing, duration, and distribution of grazing and browsing animals to meet the planned objectives for plant communities, the animals, and the associated resources. This includes adjusting animal numbers, grazing periods, and movements based on the rate of plant growth, available forage, livestock forage demand, or other desired objectives (e.g., degree of forage utilization, targeted plant height or standing biomass, residual forage mass, or animal performance).',
        'nrcs_code' => '528',
        'unit' => 'ac',
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
        'description' => 'The seeding and establishment of herbaceous and woody species for the improvement of vegetation composition and productivity of the plant community to meet management goals.',
        'nrcs_code' => '550',
        'unit' => 'ac',
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
        'description' => 'This practice includes tillage methods commonly referred to as mulch tillage where a majority of the soil surface is disturbed by non-inversion tillage operations such as vertical tillage, chiseling, and discing, and also includes tillage/planting systems with relatively minimal soil disturbance.',
        'nrcs_code' => '345',
        'unit' => 'ac',
        'benefits' => [
          'Increase soil organic matter and tilth',
          'Increase productivity as the constant supply of organic material left on the soil surface is decomposed by a healthy population of earth worms and other organisms',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Residue_And_Tillage_Management_Reduced_Till_345_PS_Sept_2016.pdf',
        ],
      ],
      'natural_restoration' => [
        'label' => t('Restoration of Rare or Declining Natural Communities'),
        'description' => 'Reestablishment of abiotic (physical and chemical) and biotic (biological) conditions necessary to support rare or declining natural assemblages of native plants and animals. Applied on all lands, including degraded aquatic, terrestrial, or wetland sites, that historically supported a functional rare or declining (dwindling or imperiled) native plant or animal community, where restoration is needed to achieve identified abiotic and biotic target conditions. This practice can also be applied to efforts to restore natural communities of local cultural importance.',
        'nrcs_code' => '643',
        'unit' => 'ac',
        'benefits' => [
          'Restores the physical conditions and/or unique plant community on sites that partially support, or once supported, a rare or declining natural community',
          'Addresses resource concerns of a degraded plant condition and/or inadequate wildlife habitat',
          'Promotes ecological, geomorphic, or hydrologic processes such as ponding, scour, sediment deposition, streambank stabilization, groundwater recharge, and riparian vegetation establishment',
          'Increased ponding and establishment of backwaters promotes greater connectivity of aquatic habitat',
          'Dam structures may obstruct debris, sediment and pollutants',
          'Increased groundwater recharge as a result of slower water discharge',
          'Decreased intensity of flooding and headcutting of channels as a result of slower water discharge',
          'Raised water levels from dams creates habitat conditions for riparian vegetation',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-09/Restoration_Of_Rare_Or_Declining_Natural_Communities_643_CPS-3-17Final.pdf',
        ],
      ],
      'riparian_forest_buffer' => [
        'label' => t('Riparian Forest Buffer'),
        'description' => 'An area predominantly covered by trees and/or shrubs located adjacent to and up-gradient from a watercourse or water body.',
        'nrcs_code' => '391',
        'unit' => 'ac',
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
        'description' => 'Grasses, sedges, rushes, ferns, legumes, and forbs tolerant of intermittent flooding or saturated soils, established or managed as the dominant vegetation in the transitional zone between upland and aquatic habitats.',
        'nrcs_code' => '390',
        'unit' => 'ac',
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
        'description' => 'A structure or system of structures to collect, control, and convey precipitation runoff from a roof. This practice applies to roof runoff from precipitation that needs to be diverted away from a contaminated area, collected and conveyed to a stable outlet or infiltration area, or collected and captured for other uses such as evaporative cooling systems, livestock water, or irrigation',
        'nrcs_code' => '558',
        'unit' => '',
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
        'description' => 'Establishment and/or management of desired trees and forages on the same land unit. This practice may be applied on any area that is suitable for the desired forages, trees, and livestock.',
        'nrcs_code' => '381',
        'unit' => 'ac',
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
        'label' => t('Soil Carbon Amendment'),
        'description' => 'Application of carbon-based amendments derived from plant materials or treated animal byproducts. This practice applies to areas of Crop, Pasture, Range, Forest, Associated Agriculture Lands, Developed Land, and Farmstead where organic carbon amendment applications will improve soil conditions.',
        'nrcs_code' => '336',
        'unit' => 'ac',
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
        'description' => 'Improve, restore, or maintain the ecological functions of a stream and its adjacent floodplain and riparian area. This applies to all streams and their associated backwaters, floodplains, wetlands, and riparian areas with impaired habitat.
This practice does not apply to—
• The management of fish and wildlife habitat on wetlands enhanced under this standard.
• Streambed or bank stabilization; instead, use Conservation Practice Standard (CPS) Streambank and Shoreline Protection (Code 580), or CPS Channel Bed Stabilization (Code 584).
This practice may be used in conjunction with other practices to address multiple resource concerns at the site.',
        'nrcs_code' => '395',
        'unit' => 'ac',
        'benefits' => [
          'Improve or manage stream habitat by evaluating and addressing factors that impair stream function and structure',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-10/Stream_Habitat_Improvement_And_Management_395_CPS.pdf',
        ],
      ],
      'streambank_shoreline_improvement' => [
        'label' => t('Streambank/Shoreline Improvement'),
        'description' => 'Treatment(s) used to stabilize and protect banks of streams or constructed channels and shorelines of lakes, reservoirs, or estuaries. This practice applies to streambanks of natural or constructed channels and shorelines of lakes, reservoirs, or estuaries susceptible to erosion. It does not apply to erosion problems on main ocean fronts, beaches, or similar areas of complexity.',
        'nrcs_code' => '580',
        'unit' => 'ft',
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
        'description' => 'A structure in a water management system that conveys water, controls the direction or rate of flow, maintains a desired water surface elevation, or measures water.  Applies to structures that convey water from one elevation to a lower elevation within, to, or from a water conveyance system such as a ditch, channel, canal, or pipeline.Typical structures include drops, chutes, turnouts, surface water inlets, head gates, pump boxes, and stilling basins.',
        'nrcs_code' => '587',
        'unit' => '',
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
        'description' => 'A structure installed to replace or modify a missing or deficient wildlife habitat component. This practice applies to all lands where planting or managing vegetation fails to meet the short-term needs of the species or guild under consideration.',
        'nrcs_code' => '649',
        'unit' => '',
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
        'description' => 'Establishing woody plants by planting, by direct seeding, or through natural regeneration.',
        'nrcs_code' => '612',
        'unit' => 'ac',
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
        'description' => 'Provide and manage upland habitats and connectivity within the landscape for wildlife. This practice applies to land where the decision maker has identified an objective for conserving a wild animal species, guild, suite or ecosystem and land within the range of targeted wildlife species and capable of supporting the desired habitat.',
        'nrcs_code' => '645',
        'unit' => 'ac',
        'benefits' => [
          'Treating upland wildlife habitat concerns identified during the conservation planning process that enable movement, or provide shelter, cover, food in proper amounts, locations and times to sustain wild animals that inhabit uplands during a portion of their life cycle',
        ],
        'resources' => [
          'https://www.nrcs.usda.gov/sites/default/files/2022-11/645-NHCP-CPS-Upland-Wildlife-Habitat-Management-2022.pdf',
        ],
      ],
      'water_sediment_control_basin' => [
        'label' => t('Water and Sediment Control Basin'),
        'description' => 'An earth embankment or a combination ridge and channel constructed across the slope of a minor drainageway. This practice applies to sites where:
• The topography is generally irregular.
• Gully erosion is a problem.
• Other conservation practices typically control sheet and rill erosion.
• Runoff and sediment damages land and works of improvement.
• Stable outlets are available.
Do not use this standard in place of a terrace. Use NRCS Conservation Practice Standards (CPS) Terrace (Code 600) or CPS Diversion (Code 362) where the ridge or channel extends beyond the detention basin or level embankment.',
        'nrcs_code' => '638',
        'unit' => '',
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
        'description' => 'Establishing wildlife habitat by planting herbaceous vegetation or shrubs. This practice applies to all lands where inadequate wildlife habitat is identified as a primary resource concern and a plant community inventory or wildlife habitat evaluation indicates a benefit in altering the current vegetative conditions (species diversity, richness, structure, and pattern) by establishing herbaceous plants or shrubs. The use of annuals that persist over the life of the practice, and annuals that serve as a nurse crop to support the establishment of the persistent vegetative species are appropriate under this conservation practice.',
        'nrcs_code' => '420',
        'unit' => 'ac',
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
        'description' => 'Establishing, enhancing, or renovating windbreaks, also known as shelterbelts, which are single or multiple rows of trees and/or shrubs in linear or curvilinear configurations. On all lands except forest land, apply this practice to establish, enhance, or renovate windbreaks where rows of woody plants are desired and suited for the intended purposes. Apply this practice to any existing windbreaks that are no longer functioning properly for the intended purpose, or where renovation can extend the functional life of a windbreak.',
        'nrcs_code' => '380',
        'unit' => 'ft',
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
        'description' => '',
        'nrcs_code' => '',
        'unit' => '',
        'benefits' => [],
        'resources' => [],
      ],
    ];
  }

}
