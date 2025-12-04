<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\asset\Entity\AssetInterface;
use Drupal\farm_rcd\RcdOptionLists;
use Drupal\plan\Entity\PlanInterface;

/**
 * Locations form.
 */
class LocationsForm extends PlanningWorkflowFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_rcd_locations_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ?PlanInterface $plan = NULL, ?AssetInterface $property = NULL) {
    $form = parent::buildForm($form, $form_state, $plan);

    // Form details.
    $form['locations'] = [
      '#type' => 'details',
      '#title' => $this->t('Land Use Areas'),
      '#description' => $this->t('Each property must have one or more land use areas (represented as child land assets of the property). These land use areas may be associated with multiple plans, and their descriptions will be shared with all of them.'),
    ];

    // Require that a property is associated with the plan first.
    if (is_null($this->property)) {
      $form['locations']['#markup'] = $this->t('A property description must be created before land use areas can be added.');
      return $form;
    }

    // Open if the status is "planning" and there are no land assets associated
    // with the property.
    else {
      $form['locations']['#open'] = $this->plan->get('status')->value == 'planning' && empty($this->landAssets);
    }

    // Build vertical tabs for each location form.
    $form['locations']['tabs'] = [
      '#type' => 'vertical_tabs',
    ];

    // Provide simplified edit forms for land assets.
    foreach ($this->landAssets as $id => $asset) {

      // Details wrapper.
      $form['locations'][$id] = $this->buildLandAssetForm($asset);
      $form['locations'][$id]['#type'] = 'details';
      $form['locations'][$id]['#title'] = $asset->label();
      $form['locations'][$id]['#description'] = $this->t('Land asset: <a href=":uri">%label</a>', [':uri' => $asset->toUrl()->toString(), '%label' => $asset->label()]);
      $form['locations'][$id]['#group'] = 'locations][tabs';
    }

    // Add a new land asset.
    $form['locations']['add'] = $this->buildLandAssetForm();
    $form['locations']['add']['#type'] = 'details';
    $form['locations']['add']['#title'] = $this->t('+ Add land use area');
    $form['locations']['add']['#description'] = $this->t('Create a new land asset to represent a land use area associated with this property.');

    // If there are land assets, show the add form in vertical tabs.
    // Otherwise, leave it ungrouped and open it by default.
    if (!empty($this->landAssets)) {
      $form['locations']['add']['#group'] = 'locations][tabs';
    }
    else {
      $form['locations']['add']['#open'] = TRUE;
    }

    // Submit button.
    $form['locations']['actions'] = [
      '#type' => 'actions',
      '#weight' => 1000,
    ];
    $form['locations']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save land assets'),
    ];

    return $form;
  }

  /**
   * Land asset subform.
   *
   * @param \Drupal\asset\Entity\AssetInterface|null $asset
   *   The asset entity or NULL.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  protected function buildLandAssetForm(?AssetInterface $asset = NULL) {

    // Asset ID (if available).
    $form['asset_id'] = [
      '#type' => 'value',
      '#value' => !is_null($asset) ? $asset->id() : NULL,
    ];

    // Land type.
    $form['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Type'),
      '#options' => RcdOptionLists::landTypes(),
      '#required' => !is_null($asset),
      '#default_value' => !is_null($asset) ? $asset->get('land_type')->value : NULL,
    ];

    // If there is no asset, add a null option to the beginning.
    // Drupal core only adds this if the field is required and doesn't have a
    // null default value. We do this to ensure that the form can be submitted
    // without requiring the new location details. See #states below.
    if (is_null($asset)) {
      $form['type']['#options'] = [NULL => '- Select -'] + $form['type']['#options'];
    }

    // Build the name of the type field for #states below.
    $type_name = !is_null($asset) ? 'locations[' . $asset->id() . '][type]' : 'locations[add][type]';

    // Label.
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => !is_null($asset) ? $asset->get('name')->value : '',
      '#states' => [
        'required' => [
          ':input[name="' . $type_name . '"]' => ['filled' => TRUE],
        ],
      ],
    ];

    // Description.
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => !is_null($asset) ? $asset->get('notes')->value : '',
      '#states' => [
        'required' => [
          ':input[name="' . $type_name . '"]' => ['filled' => TRUE],
        ],
      ],
    ];

    // Boundary.
    $form['boundary'] = [
      '#type' => 'farm_map_input',
      '#title' => $this->t('Boundary'),
      '#description' => $this->t('Draw the boundary using the map below, or paste geometry data (WKT, KML, or GeoJSON) into the box below the map.'),
      '#display_raw_geometry' => TRUE,
      '#default_value' => !is_null($asset) ? $asset->get('geometry')->value : '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Filter submitted values to those with a numeric key (representing the
    // asset ID), or "add" (for adding a new asset).
    $location_values = array_filter($form_state->getValue('locations'), function ($key) {
      return is_numeric($key) || $key === 'add';
    }, ARRAY_FILTER_USE_KEY);

    // If the "add" type is empty, a new asset will not be created.
    if (empty($location_values['add']['type'])) {
      unset($location_values['add']);
    }

    // For each set of values, generate/update and validate the land asset.
    $assets = [];
    foreach ($location_values as $values) {
      $asset = $this->generateLandAsset($values);
      $violations = $asset->validate();
      if ($violations->count() > 0) {
        $form_state->setErrorByName('', $this->t('The land asset did not pass validation.'));
        foreach ($violations as $violation) {
          $this->messenger()->addWarning($violation->getMessage());
        }
        return;
      }
      $assets[] = $asset;
    }

    // Save the assets to form state storage.
    $form_state->setStorage(['assets' => $assets]);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Load the land assets from storage.
    $storage = $form_state->getStorage();
    $land_assets = $storage['assets'] ?? [];

    // If there are no assets, bail.
    if (empty($land_assets)) {
      $this->messenger()->addWarning($this->t('No land assets saved.'));
      return;
    }

    // Save the land assets.
    foreach ($land_assets as $asset) {
      $asset->save();
    }

    // Show a message.
    $this->messenger()->addMessage($this->t('Land assets saved.'));
  }

  /**
   * Generate/update a land asset entity from submitted values.
   *
   * @param array $values
   *   Submitted values from $form_state->getValue().
   *
   * @return \Drupal\asset\Entity\AssetInterface|null
   *   Returns an unsaved land asset entity, or null if something goes
   *   wrong.
   */
  protected function generateLandAsset(array $values): ?AssetInterface {

    // If an asset ID is included, load it.
    // Otherwise, start a new one.
    $asset_storage = $this->entityTypeManager->getStorage('asset');
    if (!empty($values['asset_id'])) {
      $asset = $asset_storage->load($values['asset_id']);
    }
    else {
      $asset = $asset_storage->create([
        'type' => 'land',
        'parent' => [$this->property],
        'farm' => [$this->farm],
      ]);
    }

    // Fill in the property details from form values.
    $asset->set('land_type', $values['type']);
    $asset->set('name', $values['label']);
    $asset->set('notes', $values['description']);
    $asset->set('intrinsic_geometry', ['value' => $values['boundary']]);

    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    return $asset;
  }

}
