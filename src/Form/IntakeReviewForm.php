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

    // If all checks have passed, allow access.
    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

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

    // Decision radios.
    $form['decision'] = [
      '#type' => 'radios',
      '#title' => $this->t('Decision'),
      '#options' => [
        'continue' => $this->t('Continue'),
        'postpone' => $this->t('Postpone'),
        'abandon' => $this->t('Abandon'),
      ],
      '#required' => TRUE,
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

  }

}
