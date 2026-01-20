<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Hook;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\farm_rcd\ConservationPractices;

/**
 * Form hook implementations for farm_rcd.
 */
class FormHooks {

  /**
   * Implements hook_form_BASE_FORM_ID_alter().
   */
  #[Hook('form_asset_form_alter')]
  public function formAssetFormAlter(&$form, FormStateInterface $form_state, $form_id) {

    // Only show the "APN", "Riparian areas", and "Wildlife" fields if the land
    // type is "rcd_property".
    if (isset($form['land_type']) && isset($form['rcd_apn']) && isset($form['rcd_wildlife'])) {
      $form['rcd_apn']['#states']['visible'] = [':input[name="land_type"]' => ['value' => 'rcd_property']];
      $form['rcd_riparian_areas']['#states']['visible'] = [':input[name="land_type"]' => ['value' => 'rcd_property']];
      $form['rcd_wildlife']['#states']['visible'] = [':input[name="land_type"]' => ['value' => 'rcd_property']];
    }
  }

  /**
   * Implements hook_form_BASE_FORM_ID_alter().
   */
  #[Hook('form_plan_form_alter')]
  public function formPlanFormAlter(&$form, FormStateInterface $form_state, $form_id) {

    // Only show the "Other practice name" field if the selected practice is
    // "Other".
    if (isset($form['rcd_practice']) && isset($form['rcd_practice_other'])) {
      $form['rcd_practice_other']['#states']['visible'] = [':input[name="rcd_practice"]' => ['value' => 'other']];
    }

    // Conditionally show/hide the acreage/linear feet fields based on the
    // practice type. Show both if the practice is "other".
    $area_practices = array_filter(ConservationPractices::definitions(), function ($practice) {
      return $practice['unit'] == 'ac';
    });
    $linear_practices = array_filter(ConservationPractices::definitions(), function ($practice) {
      return $practice['unit'] == 'ft';
    });
    if (isset($form['rcd_acres']) && isset($form['rcd_linear_feet']) && isset($form['rcd_practice'])) {
      $form['rcd_acres']['#states']['visible'] = [
        ':input[name="rcd_practice"]' => array_merge(array_map(function ($key, $practice) {
          return ['value' => $key];
        }, array_keys($area_practices), $area_practices), [['value' => 'other']]),
      ];
      $form['rcd_linear_feet']['#states']['visible'] = [
        ':input[name="rcd_practice"]' => array_merge(array_map(function ($key, $practice) {
          return ['value' => $key];
        }, array_keys($linear_practices), $linear_practices), [['value' => 'other']]),
      ];
    }
  }

}
