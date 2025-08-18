<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Form;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\log\Entity\LogInterface;
use Drupal\organization\Entity\Organization;
use Drupal\organization\Entity\OrganizationInterface;
use Drupal\plan\Entity\Plan;
use Drupal\plan\Entity\PlanInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form that renders quick forms.
 *
 * @ingroup farm
 */
class IntakeReviewForm extends FormBase {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The quick form instance manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_sli_intake_review_form';
  }

  /**
   * Checks access for the form.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   * @param \Drupal\log\Entity\LogInterface $log
   *   The log entity.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public static function access(AccountInterface $account, LogInterface $log) {

    // If the log is not an intake, deny access.
    if ($log->bundle() != 'sli_intake') {
      return AccessResult::forbidden();
    }

    // If the user does not have view access to the log, deny access.
    if (!$log->access('view', $account)) {
      return AccessResult::forbidden();
    }

    // If the log does not have a status of "pending", deny access.
    if ($log->get('status')->value != 'pending') {
      return AccessResult::forbidden();
    }

    // If all checks have passed, allow access.
    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ?LogInterface $log = NULL) {

    // Save the intake log for future use.
    $form['intake'] = [
      '#type' => 'value',
      '#value' => $log,
    ];

    // Build a list of active managers.
    $users = $this->entityTypeManager->getStorage('user')->loadByProperties([
      'roles' => 'farm_manager',
      'status' => TRUE,
    ]);
    $owner_options = array_combine(
      array_keys($users),
      array_map(function ($user) {
        return $user->label();
      }, $users),
    );

    // Assign intake owner dropdown.
    $form['owner'] = [
      '#type' => 'select',
      '#title' => $this->t('Assign ownership'),
      '#description' => $this->t('Who will be responsible for this intake?'),
      '#options' => $owner_options,
      '#required' => TRUE,
    ];

    // Default to the current user, if they exist in the list.
    if (array_key_exists($this->currentUser()->id(), $owner_options)) {
      $form['owner']['#default_value'] = $this->currentUser()->id();
    }

    // Decision radios.
    $form['decision'] = [
      '#type' => 'radios',
      '#title' => $this->t('Decision'),
      '#options' => [
        'continue' => $this->t('Continue'),
        'abandon' => $this->t('Abandon'),
      ],
      '#required' => TRUE,
    ];

    // Load the farm/ranch name from the intake, if available.
    $farm_name = '';
    if (!$log->get('intake_farm_name')->isEmpty()) {
      $farm_name = $log->get('intake_farm_name')->value;
    }

    // Autocomplete for selecting an existing farm organization.
    $form['existing_farm'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Assign to existing farm/ranch'),
      '#description' => $this->t('Search for an existing farm/ranch to associate this with.'),
      '#target_type' => 'organization',
      '#states' => [
        'visible' => [
          ':input[name="decision"]' => ['value' => 'continue'],
          ':input[name="new_farm"]' => ['checked' => FALSE],
        ],
        'required' => [
          ':input[name="decision"]' => ['value' => 'continue'],
          ':input[name="new_farm"]' => ['checked' => FALSE],
        ],
      ],
    ];

    // If an existing farm organization exists, pre-populate the autocomplete.
    $farms = $this->entityTypeManager->getStorage('organization')->loadByProperties(['name' => $farm_name]);
    if (!empty($farms)) {
      $form['existing_farm']['#default_value'] = reset($farms);
    }

    // Checkbox to create a new farm organization
    $form['new_farm'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Create a new farm/ranch'),
      '#description' => $this->t('If an existing farm/ranch does not exist, create a new one.'),
      '#states' => [
        'visible' => [
          ':input[name="decision"]' => ['value' => 'continue'],
        ],
      ],
    ];

    // New farm organization name.
    $form['farm_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Farm/ranch name'),
      '#default_value' => $farm_name,
      '#states' => [
        'required' => [
          ':input[name="new_farm"]' => ['checked' => TRUE],
        ],
        'visible' => [
          ':input[name="new_farm"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Checkbox to create a new Resource Conservation Plan.
    $form['create_plan'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Start a Resource Conservation Plan'),
      '#default_value' => TRUE,
      '#required' => TRUE,
      '#states' => [
        'required' => [
          ':input[name="decision"]' => ['value' => 'continue'],
        ],
        'visible' => [
          ':input[name="decision"]' => ['value' => 'continue'],
        ],
      ],
    ];

    // Create form actions with submit button.
    $form['actions'] = [
      '#type' => 'actions',
      '#weight' => 1000,
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#states' => [
        'visible' => [
          ':input[name="decision"]' => [
            ['value' => 'continue'],
            'or',
            ['value' => 'abandon'],
          ],
        ],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // If the intake is being abandoned, we can skip the rest of validation.
    if ($form_state->getValue('decision') == 'abandon') {
      return;
    }

    // Load the intake log.
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = $form_state->getValue('intake');

    // Load form state storage.
    $storage = $form_state->getStorage();

    // Generate and validate a new farm organization, or load an existing one,
    // and store it in form state storage.
    $organization = NULL;
    if (!empty($form_state->getValue('new_farm'))) {
      $organization = $this->generateOrganization($form_state->getValue('farm_name'));
      $violations = $organization->validate();
      if ($violations->count() > 0) {
        $form_state->setErrorByName('', $this->t('A validation error occurred. Please contact the system administrator.'));
        return;
      }
    }
    else {

      // Attempt to load the existing farm.
      $farm_id = $form_state->getValue('existing_farm');
      if (!empty($farm_id)) {
        $organization = $this->entityTypeManager->getStorage('organization')->load($farm_id);
      }

      // If the farm organization could not be loaded, throw an error.
      if (is_null($organization)) {
        $form_state->setErrorByName('existing_farm', $this->t('An existing farm/ranch by that name could not be found. Please select a valid farm/ranch from the dropdown that appears while typing a name, or create a new farm/ranch.'));
        return;
      }
    }
    $storage['organization'] = $organization;

    // Generate and validate a resource conservation plan, if necessary, and
    // store it in form state storage.
    if (!empty($form_state->getValue('create_plan'))) {
      $plan = $this->generatePlan($organization, $log);
      $violations = $plan->validate();
      if ($violations->count() > 0) {
        $form_state->setErrorByName('', $this->t('A validation error occurred. Please contact the system administrator.'));
        return;
      }
      $storage['plan'] = $plan;
    }

    // Save form state storage.
    $form_state->setStorage($storage);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Load the intake log.
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = $form_state->getValue('intake');

    // Assign log ownership.
    /** @var \Drupal\user\UserInterface $owner */
    $owner = $this->entityTypeManager->getStorage('user')->load($form_state->getValue('owner'));
    if (!empty($owner)) {
      $log->set('owner', $owner);
    }

    // Transition the intake log status.
    /** @var \Drupal\state_machine\Plugin\Field\FieldType\StateItemInterface $state_item */
    $state_item = $log->get('status')->first();
    $target_status = $form_state->getValue('decision') == 'continue' ? 'done' : 'abandoned';
    $transition = $state_item->getWorkflow()->findTransition($state_item->getOriginalId(), $target_status);
    $state_item->applyTransition($transition);

    // Set a revision message.
    $log->setNewRevision(TRUE);
    $log->setRevisionLogMessage($this->t('Intake reviewed by @current_user, assigned to @owner, marked as @status.', ['@current_user' => $this->currentUser()->getDisplayName(), '@owner' => $owner->getDisplayName(), '@status' => $target_status]));

    // Save the log.
    $log->save();

    // If the intake is being abandoned, redirect to the dashboard and stop here.
    if ($form_state->getValue('decision') == 'abandon') {
      $form_state->setRedirect('farm.dashboard');
      return;
    }

    // Load generated organization and plan from form state storage, if
    // available.
    $storage = $form_state->getStorage();
    /** @var \Drupal\organization\Entity\OrganizationInterface $organization */
    $organization = !empty($storage['organization']) ? $storage['organization'] : NULL;
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = !empty($storage['plan']) ? $storage['plan'] : NULL;

    // Save new farm organization, if necessary, and display a message to the
    // user.
    if (!empty($form_state->getValue('new_farm')) && !empty($organization)) {
      $organization->save();
      $this->messenger()->addStatus($this->t('Farm created: <a href=":uri">%name</a>', [':uri' => $organization->toUrl()->toString(), '%name' => $organization->label()]));
    }

    // Save the plan, if necessary, and display a message to the user.
    if (!empty($form_state->getValue('create_plan')) && !empty($plan)) {
      $plan->save();
      $this->messenger()->addStatus($this->t('Plan created: <a href=":uri">%name</a>', [':uri' => $plan->toUrl()->toString(), '%name' => $plan->label()]));
    }

    // Redirect to the plan.
    $form_state->setRedirect('entity.plan.canonical', ['plan' => $plan->id()]);
  }

  /**
   * Generate a farm organization entity.
   *
   * @param string $name
   *   The farm organization name.
   *
   * @return \Drupal\organization\Entity\OrganizationInterface|null
   *   Returns an unsaved farm organization entity, or null if something goes wrong.
   */
  protected function generateOrganization(string $name): ?OrganizationInterface {
    return Organization::create([
      'type' => 'farm',
      'name' => $name,
    ]);
  }

  /**
   * Generate a sli_rcp plan entity.
   *
   * @param \Drupal\organization\Entity\OrganizationInterface $farm
   *   The farm organization entity.
   * @param \Drupal\log\Entity\LogInterface $intake
   *   The intake log entity.
   *
   * @return \Drupal\plan\Entity\PlanInterface|null
   *   Returns an unsaved farm organization entity, or null if something goes wrong.
   */
  protected function generatePlan(OrganizationInterface $farm, LogInterface $intake): ?PlanInterface {
    return Plan::create([
      'type' => 'sli_rcp',
      'name' => $farm->label() . ' RCP',
      'farm' => $farm,
      'intake' => $intake,
    ]);
  }

}
