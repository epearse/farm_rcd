<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Hook;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Form hook implementations for farm_sli.
 */
class FormHooks {

  /**
   * Implements hook_form_BASE_FORM_ID_alter().
   */
  #[Hook('form_asset_form_alter')]
  public function formAssetFormAlter(&$form, FormStateInterface $form_state, $form_id) {

    // Only show the "APN" field if the land type is "sli_property".
    if (isset($form['land_type']) && isset($form['sli_apn'])) {
      $form['sli_apn']['#states']['visible'] = [':input[name="land_type"]' => ['value' => 'sli_property']];
    }
  }

}
