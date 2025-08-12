<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Form;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\log\Entity\LogInterface;
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
  }

}
