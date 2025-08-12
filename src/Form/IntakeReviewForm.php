<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Form;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\log\Entity\LogInterface;

/**
 * Form that renders quick forms.
 *
 * @ingroup farm
 */
class IntakeReviewForm extends FormBase {

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

    // Assign intake owner dropdown.
    $form['owner'] = [
      '#type' => 'select',
      '#title' => $this->t('Assign ownership'),
      '#description' => $this->t('Who will be responsible for this intake?'),
      '#options' => [],
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
