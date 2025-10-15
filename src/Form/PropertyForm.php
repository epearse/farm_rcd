<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\asset\Entity\AssetInterface;
use Drupal\plan\Entity\PlanInterface;

/**
 * Property form.
 */
class PropertyForm extends PlanningWorkflowFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_sli_property_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ?PlanInterface $plan = NULL) {
    $form = parent::buildForm($form, $form_state, $plan);

    // Form details.
    $form['property'] = [
      '#type' => 'details',
      '#title' => $this->t('Property Description'),
      '#description' => $this->t('The plan must be associated with a land asset that describes the property. This property may be associated with multiple plans, and the description will be shared with all of them.'),
    ];

    // Open if the status is "planning" and there is no property associated
    // with the plan.
    $form['property']['#open'] = $this->plan->get('status')->value == 'planning' && is_null($this->property);

    // If plan does not have a property associated with it, but the farm has
    // property land asset(s) already, provide the option to select one.
    $existing_properties = [];
    if (is_null($this->property)) {

      // Load existing properties.
      $existing_properties = $this->entityTypeManager->getStorage('asset')->loadByProperties([
        'type' => 'land',
        'land_type' => 'sli_property',
        'farm' => $this->farm->id(),
      ]);

      // If there are existing properties, add a radio for selecting an
      // existing property or creating a new one.
      if (!empty($existing_properties)) {
        $form['property']['new_or_existing'] = [
          '#type' => 'radios',
          '#title' => $this->t('New or existing property'),
          '#title_display' => 'invisible',
          '#description' => $this->t('There are existing properties associated with the farm. Would you like to select one of the existing properties, or create a new one?'),
          '#options' => [
            'existing' => $this->t('Select an existing property'),
            'new' => $this->t('Create a new property'),
          ],
          '#default_value' => 'existing',
          '#ajax' => [
            'callback' => '::propertyAssetFormAjaxCallback',
            'wrapper' => 'property-form-container',
          ],
        ];

        // Select an existing property.
        $existing_property_options = array_combine(
          array_keys($existing_properties),
          array_map(function ($asset) {
            return $asset->label();
          }, $existing_properties),
        );
        $form['property']['existing_property'] = [
          '#type' => 'select',
          '#title' => $this->t('Select existing property'),
          '#options' => $existing_property_options,
          '#states' => [
            'required' => [
              ':input[name="property[new_or_existing]"]' => ['value' => 'existing'],
            ],
            'visible' => [
              ':input[name="property[new_or_existing]"]' => ['value' => 'existing'],
            ],
          ],
        ];
      }
    }

    // Property form container.
    $form['property']['form'] = [
      '#type' => 'container',
      '#prefix' => '<div id="property-form-container">',
      '#suffix' => '</div>',
    ];

    // Display a form for adding or editing property details, if:
    if (
      // 1. There is a property associated with the plan (allow editing).
      !is_null($this->property) ||
      // 2. There is no property associated with the plan, and no existing
      // properties to choose form.
      empty($existing_properties) ||
      // 3. The user selected "new" in the new_or_existing radios.
      $form_state->getValue(['property', 'new_or_existing']) == 'new'
    ) {
      $form['property']['form'] += $this->buildPropertyAssetForm($form_state);
    }

    // Submit button.
    $form['property']['actions'] = [
      '#type' => 'actions',
      '#weight' => 1000,
    ];
    $form['property']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save property information'),
    ];

    return $form;
  }

  /**
   * Property asset form.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the parent form.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  protected function buildPropertyAssetForm(FormStateInterface $form_state) {

    // Property label.
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Property label'),
      '#description' => $this->t('Provide a property label to differentiate it from other properties associated with the farm.'),
      '#default_value' => $this->property ? $this->property->get('name')->value : '',
      '#required' => TRUE,
    ];

    // If a property doesn't exist, attempt to populate the property label.
    if (is_null($this->property)) {
      $form['label']['#default_value'] = $this->generatePropertyName();
    }

    // Property description.
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Property description'),
      '#description' => $this->t('Provide a property description to be included in the resource conservation plan.'),
      '#default_value' => $this->property ? $this->property->get('notes')->value : '',
      '#required' => TRUE,
    ];

    // Riparian areas.
    $form['riparian_areas'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Riparian areas'),
      '#description' => $this->t('Describe the riparian areas on this property.'),
      '#default_value' => $this->property ? $this->property->get('sli_riparian_areas')->value : '',
      '#required' => TRUE,
    ];

    // Native wildlife.
    $form['native_wildlife'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Native wildlife'),
      '#description' => $this->t('Describe the native wildlife on this property.'),
      '#default_value' => $this->property ? $this->property->get('sli_native_wildlife')->value : '',
      '#required' => TRUE,
    ];

    // Property boundary.
    $form['boundary'] = [
      '#type' => 'farm_map_input',
      '#title' => $this->t('Boundary'),
      '#description' => $this->t('Draw the boundary using the map below, or paste geometry data (WKT, KML, or GeoJSON) into the box below the map.'),
      '#display_raw_geometry' => TRUE,
      '#default_value' => $this->property ? $this->property->get('intrinsic_geometry')->value : '',
    ];

    // APNs.
    $form['apn'] = [
      '#type' => 'fieldset',
      '#title' => $this->t("APNs (Assessor's Parcel Numbers)"),
      '#description' => $this->t('Enter the property APN(s). If there are multiple, click "Add another APN" and enter each one separately.'),
      '#prefix' => '<div id="apns-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];

    // Build APN fields.
    $saved_apns = [];
    if (!is_null($this->property)) {
      $saved_apns = array_map(function ($value) {
        return $value['value'];
      }, $this->property->get('sli_apn')->getValue());
    }
    $num_apns = $form_state->get('num_apns');
    if ($num_apns === NULL) {
      $num_apns = count($saved_apns) > 0 ? count($saved_apns) : 1;
      $form_state->set('num_apns', $num_apns);
    }
    for ($i = 0; $i < $num_apns; $i++) {
      $form['apn'][$i] = [
        '#type' => 'textfield',
        '#title' => $this->t('APN'),
        '#title_display' => 'invisible',
        '#default_value' => $saved_apns[$i] ?? '',
      ];
    }

    // Add another APN button.
    $form['apn']['add'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add another APN'),
      '#submit' => ['::addApn'],
      '#ajax' => [
        'callback' => '::addApnAjaxCallback',
        'wrapper' => 'apns-fieldset-wrapper',
      ],
    ];

    return $form;
  }

  /**
   * Generate property name.
   *
   * @return string
   *   Returns a name for the property.
   */
  protected function generatePropertyName(): string {

    // If an intake log is not available, use the farm name.
    if (is_null($this->intake)) {
      return $this->farm->label();
    }

    // If an intake log is available, first try to use the property address,
    // then the parcel or GPS information.
    if (!$this->intake->get('intake_property_street')->isEmpty()) {
      $intake = $this->intake;
      return implode(', ', array_map(function ($field) use ($intake) {
        return $intake->get($field)->value;
      }, [
        'intake_property_street',
        'intake_property_city',
        'intake_property_state',
        'intake_property_zip',
      ]));
    }
    elseif (!$this->intake->get('intake_property_parcel_gps')->isEmpty()) {
      return $this->intake->get('intake_property_parcel_gps')->value;
    }

    return '';
  }

  /**
   * Callback for property asset form.
   */
  public function propertyAssetFormAjaxCallback(array &$form, FormStateInterface $form_state) {
    return $form['property']['form'];
  }

  /**
   * Callback for "Add another APN" button.
   */
  public function addApnAjaxCallback(array &$form, FormStateInterface $form_state) {
    return $form['property']['form']['apn'];
  }

  /**
   * Submit handler for the "Add another APN" button.
   *
   * Increments the APN counter and trigger a rebuild.
   */
  public function addApn(array &$form, FormStateInterface $form_state) {
    $num_apns = $form_state->get('num_apns');
    $num_apns++;
    $form_state->set('num_apns', $num_apns);
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // If we are adding an existing property to the plan, no validation is
    // needed.
    if ($form_state->getValue(['property', 'new_or_existing']) == 'existing') {
      return;
    }

    // Generate and validate the property land asset.
    $property = $this->generateProperty($form_state);
    $violations = $property->validate();
    if ($violations->count() > 0) {
      $form_state->setErrorByName('', $this->t('The property description did not pass validation.'));
      foreach ($violations as $violation) {
        $this->messenger()->addWarning($violation->getMessage());
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // If an existing property was selected, add it to the plan.
    if ($form_state->getValue(['property', 'new_or_existing']) == 'existing' && !empty($form_state->getValue(['property', 'existing_property']))) {
      $property = $this->entityTypeManager->getStorage('asset')->load($form_state->getValue(['property', 'existing_property']));
    }

    // Otherwise, try to save the property.
    else {
      $property = $this->generateProperty($form_state);
      $property->setRevisionLogMessage($this->t('Updated via <a href=":uri">@label</a>.', [':uri' => $this->plan->toUrl()->toString(), '@label' => $this->plan->label()])->render());
      $property->save();
    }

    // Associate the property with the plan.
    if (!is_null($property)) {
      $this->plan->set('property', $property);
      $this->plan->save();
    }

    // Show a message.
    $this->messenger()->addMessage($this->t('Property description saved.'));
  }

  /**
   * Generate/update a property land asset entity from submitted values.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return \Drupal\asset\Entity\AssetInterface|null
   *   Returns an unsaved land asset entity, or null if something goes
   *   wrong.
   */
  protected function generateProperty(FormStateInterface $form_state): ?AssetInterface {

    // Start with the property associated with the plan, if available.
    // Otherwise, start a new one.
    $property = $this->property;
    if (is_null($property)) {
      $property = $this->entityTypeManager->getStorage('asset')->create([
        'type' => 'land',
        'land_type' => 'sli_property',
        'farm' => [$this->farm],
      ]);
    }

    // Fill in the property details from form values.
    // The values will be empty in certain circumstances (eg: when ajax is
    // being used to show the property form for the first time), so we
    // generate a default name for property first to avoid a validation
    // warning.
    $property->set('name', $this->generatePropertyName());
    $values = $form_state->getValue(['property', 'form']);
    if (!empty($values)) {
      $property->set('name', $values['label']);
      $property->set('notes', $values['description']);
      $property->set('sli_riparian_areas', $values['riparian_areas']);
      $property->set('sli_native_wildlife', $values['native_wildlife']);
      $property->set('intrinsic_geometry', ['value' => $values['boundary']]);
      $apns = array_filter($values['apn'] ?? [], function ($value) {
        return is_string($value) && !empty($value);
      });
      $property->set('sli_apn', $apns);
    }

    return $property;
  }

}
