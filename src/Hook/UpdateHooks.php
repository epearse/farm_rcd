<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Update hook implementations for farm_rcd.
 */
class UpdateHooks {

  /**
   * Implements hook_farm_update_managed_config().
   */
  #[Hook('farm_update_managed_config')]
  public function farmUpdateManagedConfig() {

    // Declare all of this module's config as managed.
    return [
      'core.entity_view_display.log.rcd_intake.rcd_intake_preview',
      'core.entity_view_mode.log.rcd_intake_preview',
      'farm_land.land_type.rcd_grazing',
      'farm_land.land_type.rcd_natural_area',
      'farm_land.land_type.rcd_orchard',
      'farm_land.land_type.rcd_pastureland',
      'farm_land.land_type.rcd_property',
      'farm_land.land_type.rcd_riparian_area',
      'farm_land.land_type.rcd_row_crops',
      'farm_land.land_type.rcd_vineyard',
      'farm_map.layer_style.land_type_rcd_grazing',
      'farm_map.layer_style.land_type_rcd_natural_area',
      'farm_map.layer_style.land_type_rcd_orchard',
      'farm_map.layer_style.land_type_rcd_pastureland',
      'farm_map.layer_style.land_type_rcd_property',
      'farm_map.layer_style.land_type_rcd_riparian_area',
      'farm_map.layer_style.land_type_rcd_row_crops',
      'farm_map.layer_style.land_type_rcd_vineyard',
      'farm_rcd.mail',
      'log.type.rcd_intake',
      'log.type.rcd_site_assessment',
      'plan.type.rcd_practice_implementation',
      'plan.type.rcd_rcp',
      'taxonomy.vocabulary.rcd_groundwater_basin',
      'taxonomy.vocabulary.rcd_plant_species',
      'taxonomy.vocabulary.rcd_watershed',
      'taxonomy.vocabulary.rcd_wildlife_species',
      'user.role.rcd_staff',
      'views.view.farm_rcd_farm_organizations',
      'views.view.farm_rcd_intakes',
      'views.view.farm_rcd_farm_plan',
    ];
  }

}
