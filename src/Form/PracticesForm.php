<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_rcd\RcdHelper;
use Drupal\plan\Entity\Plan;
use Drupal\plan\Entity\PlanInterface;

/**
 * Practices form.
 */
class PracticesForm extends PlanningWorkflowFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_rcd_practice_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ?PlanInterface $plan = NULL) {
    $form = parent::buildForm($form, $form_state, $plan);

    $form['practices'] = [
      '#type' => 'details',
      '#title' => $this->t('Conservation Practices'),
      '#description' => $this->t('Propose conservation practices for each land use area. A Practice Implementation Plan will be created for each practice with its own status and logs for tracking implementation details.'),
    ];

    // Require that a property with land asset children is associated with the
    // plan first.
    if (empty($this->landAssets)) {
      $form['practices']['#markup'] = $this->t('Land use areas must be created before conservations practices can be added.');
      return $form;
    }

    // Open if the status is "planning" and there are no practice
    // implementation plans associated with the plan.
    else {
      $form['practices']['#open'] = $this->plan->get('status')->value == 'planning' && empty($this->practicePlans);
    }

    // Build vertical tabs for each practice form.
    $form['practices']['tabs'] = [
      '#type' => 'vertical_tabs',
    ];

    // Provide simplified edit forms for practice implementation plans.
    foreach ($this->practicePlans as $id => $practicePlan) {

      // Details wrapper.
      $form['practices'][$id] = $this->buildPracticePlanForm($practicePlan);
      $form['practices'][$id]['#type'] = 'details';
      $form['practices'][$id]['#title'] = $practicePlan->label();
      $form['practices'][$id]['#description'] = $this->t('Practice implementation plan: <a href=":uri">%label</a>', [':uri' => $practicePlan->toUrl()->toString(), '%label' => $practicePlan->label()]);
      $form['practices'][$id]['#group'] = 'practices][tabs';
    }

    // Add a new practice implementation plan.
    $form['practices']['add'] = $this->buildPracticePlanForm();
    $form['practices']['add']['#type'] = 'details';
    $form['practices']['add']['#title'] = $this->t('+ Add practice');
    $form['practices']['add']['#description'] = $this->t('Create a new practice implementation plan.');

    // If there are practice implementation plans, show the add form in
    // vertical tabs. Otherwise, leave it ungrouped and open it by default.
    if (!empty($this->practicePlans)) {
      $form['practices']['add']['#group'] = 'practices][tabs';
    }
    else {
      $form['practices']['add']['#open'] = TRUE;
    }

    // Submit button.
    $form['practices']['actions'] = [
      '#type' => 'actions',
      '#weight' => 1000,
    ];
    $form['practices']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save conservation practices'),
    ];

    return $form;
  }

  /**
   * Practice implementation plan subform.
   *
   * @param \Drupal\plan\Entity\PlanInterface|null $plan
   *   The plan entity or NULL.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  protected function buildPracticePlanForm(?PlanInterface $plan = NULL) {

    // Plan ID (if available).
    $form['plan_id'] = [
      '#type' => 'value',
      '#value' => !is_null($plan) ? $plan->id() : NULL,
    ];

    // Location land asset reference.
    // If this is an existing practice implementation plan, show links to land
    // assets, but do not allow editing.
    if (!is_null($plan)) {
      /** @var \Drupal\asset\Entity\AssetInterface[] $land_assets */
      $land_assets = $plan->get('land')->referencedEntities();
      $form['location'] = [
        '#type' => 'checkboxes',
        '#title' => $this->t('Land use area'),
        '#options' => array_combine(
          array_map(function ($asset) {
            return $asset->id();
          }, $land_assets),
          array_map(function ($asset) {
            return $asset->toLink()->toString();
          }, $land_assets),
        ),
        '#default_value' => array_map(function ($asset) {
          return $asset->id();
        }, $land_assets),
        '#required' => TRUE,
        '#disabled' => TRUE,
      ];
    }
    else {
      $form['location'] = [
        '#type' => 'select',
        '#title' => $this->t('Land use area'),
        '#options' => array_combine(
          array_keys($this->landAssets),
          array_map(function ($asset) {
            return $asset->toLink()->toString();
          }, $this->landAssets),
        ),
      ];

      // Add a null option to the beginning and default to that.
      // Drupal core only adds this if the field is required and doesn't have a
      // null default value. We do this to ensure that the form can be submitted
      // without requiring the new location details. See #states below.
      $form['location']['#options'] = [NULL => '- Select -'] + $form['location']['#options'];
      $form['location']['#default_value'] = NULL;
    }

    // Build the name of the location field for #states below.
    $location_name = !is_null($plan) ? 'practices[' . $plan->id() . '][location]' : 'practices[add][location]';

    // Practice.
    $form['practice'] = [
      '#type' => 'select',
      '#title' => $this->t('Practice'),
      '#options' => array_map(function ($practice) {
        $label = $practice['label']->render();
        if (!empty($practice['nrcs_code'])) {
          $label .= ' (NRCS code ' . $practice['nrcs_code'] . ')';
        }
        return $label;
      }, RcdHelper::practices()),
      '#default_value' => $plan ? $plan->get('rcd_practice')->value : NULL,
      '#states' => [
        'required' => [
          ':input[name="' . $location_name . '"]' => ['filled' => TRUE],
        ],
      ],
    ];

    // Notes.
    $form['notes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Notes'),
      '#default_value' => $plan ? $plan->get('notes')->value : '',
    ];

    // Status.
    // Load available status options from a mock plan, if necessary.
    if (is_null($plan)) {
      $plan = Plan::create([
        'type' => 'rcd_practice_implementation',
        'status' => 'planning',
      ]);
    }
    /** @var \Drupal\state_machine\Plugin\Field\FieldType\StateItem $state_item */
    $state_item = $plan->get('status')->first();
    $form['status'] = [
      '#type' => 'select',
      '#title' => $this->t('Status'),
      '#options' => $state_item->getPossibleOptions(),
      '#default_value' => $plan->get('status')->value,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Filter submitted values to those with a numeric key (representing the
    // practice plan ID), or "add" (for adding a new practice plan).
    $practice_values = array_filter($form_state->getValue('practices'), function ($key) {
      return is_numeric($key) || $key === 'add';
    }, ARRAY_FILTER_USE_KEY);

    // If the "add" location field is empty, a new practice plan will not be
    // created.
    if (empty($practice_values['add']['location'])) {
      unset($practice_values['add']);
    }

    // For each set of values, generate/update and validate the practice plan.
    $plans = [];
    foreach ($practice_values as $values) {
      $plan = $this->generatePracticePlan($values);
      $violations = $plan->validate();
      if ($violations->count() > 0) {
        $form_state->setErrorByName('', $this->t('The practice implementation plan did not pass validation.'));
        foreach ($violations as $violation) {
          $this->messenger()->addWarning($violation->getMessage());
        }
        return;
      }
      $plans[] = $plan;
    }

    // Save the plans to form state storage.
    $form_state->setStorage(['plans' => $plans]);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Load the practice plans from storage.
    $storage = $form_state->getStorage();
    $practice_plans = $storage['plans'] ?? [];

    // If there are no plans, bail.
    if (empty($practice_plans)) {
      $this->messenger()->addWarning($this->t('No practice implementation plans saved.'));
      return;
    }

    // Save the practice plans.
    foreach ($practice_plans as $plan) {
      $plan->save();
    }

    // Save them to the resource conservation plan.
    $this->plan->set('practice_implementation_plan', $practice_plans);
    $this->plan->save();

    // Show a message.
    $this->messenger()->addMessage($this->t('Practice implementation plans saved.'));
  }

  /**
   * Generate/update a practice plan entity from submitted values.
   *
   * @param array $values
   *   Submitted values from $form_state->getValue().
   *
   * @return \Drupal\plan\Entity\PlanInterface|null
   *   Returns an unsaved practice plan entity, or null if something goes
   *   wrong.
   */
  protected function generatePracticePlan(array $values): ?PlanInterface {

    // If a plan ID is included, load it.
    // Otherwise, start a new one.
    $plan_storage = $this->entityTypeManager->getStorage('plan');
    if (!empty($values['plan_id'])) {
      $plan = $plan_storage->load($values['plan_id']);
    }
    else {
      $asset_storage = $this->entityTypeManager->getStorage('asset');
      $land = $asset_storage->load($values['location']);
      $plan = $plan_storage->create([
        'type' => 'rcd_practice_implementation',
        'farm' => [$this->farm],
        'land' => [$land],
      ]);
    }

    // Set the name of the plan based on the land asset and practice.
    // Ensure the name is under 255 characters (we need to do this because the
    // user can't).
    $name = $plan->get('land')->referencedEntities()[0]->label() . ': ' . RcdHelper::practices()[$values['practice']]['label'];
    $name = mb_strimwidth($name, 0, 255, '…');
    $plan->set('name', $name);

    // Fill in the plan details from form values.
    $plan->set('rcd_practice', $values['practice']);
    $plan->set('notes', $values['notes']);
    $plan->set('status', $values['status']);

    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    return $plan;
  }

}
