<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\plan\Entity\PlanInterface;

/**
 * Status form.
 */
class StatusForm extends PlanningWorkflowFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_rcd_status_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ?PlanInterface $plan = NULL) {
    $form = parent::buildForm($form, $form_state, $plan);

    $form['status'] = [
      '#type' => 'details',
      '#title' => $this->t('Planning Status'),
      '#description' => $this->t('When a final document is uploaded and shared with the stakeholder, this plan can be marked as done. Practice implementation plans will be used to track the progress of each practice that the stakeholder chooses to proceed with. Alternatively, this plan can be abandoned at any time.'),
    ];

    // Open if the status is "planning" and there are files attached.
    $form['status']['#open'] = $this->plan->get('status')->value == 'planning' && !$this->plan->get('file')->isEmpty();

    // Do not open if the "open" query parameter is set.
    // @todo https://github.com/farmier/farm_rcd/issues/57
    if ($this->getRequest()->query->has('open')) {
      $form['status']['#open'] = FALSE;
    }

    // Comments.
    $form['status']['comments'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Comments'),
      '#description' => $this->t('Briefly describe the reason for this status change. This is required when abandoning a plan or returning it to planning status.'),
    ];

    // Submit buttons.
    $form['status']['actions'] = [
      '#type' => 'actions',
      '#weight' => 1000,
    ];

    // Mark plan as done.
    // Visible if the plan status is "planning" and files are attached.
    $form['status']['actions']['done'] = [
      '#type' => 'submit',
      '#value' => $this->t('Mark plan as done'),
      '#submit' => [[$this, 'submitDone']],
      '#access' => $this->plan->get('status')->value == 'planning' && !$this->plan->get('file')->isEmpty(),
    ];

    // Abandon plan.
    // Visible if the plan status is "planning".
    $form['status']['actions']['abandon'] = [
      '#type' => 'submit',
      '#value' => $this->t('Abandon plan'),
      '#validate' => [[$this, 'validateComments']],
      '#submit' => [[$this, 'submitAbandon']],
      '#access' => $this->plan->get('status')->value == 'planning',
    ];

    // Revert status to planning.
    // Visible if the plan status is "done" or "abandoned".
    $form['status']['actions']['planning'] = [
      '#type' => 'submit',
      '#value' => $this->t('Revert status to planning'),
      '#validate' => [[$this, 'validateComments']],
      '#submit' => [[$this, 'submitPlanning']],
      '#access' => in_array($this->plan->get('status')->value, ['done', 'abandoned']),
    ];

    return $form;
  }

  /**
   * Validate function for checking that comments were provided.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function validateComments(array &$form, FormStateInterface $form_state) {
    if (empty($form_state->getValue(['status', 'comments']))) {
      $form_state->setError($form['status']['comments'], $this->t('An explanation for this status change must be provided.'));
    }
  }

  /**
   * Submit function for changing the plan status to "done".
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitDone(array &$form, FormStateInterface $form_state) {
    $this->changeStatus('done', $form_state->getValue(['status', 'comments']));
  }

  /**
   * Submit function for changing the plan status to "abandoned".
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitAbandon(array &$form, FormStateInterface $form_state) {
    $this->changeStatus('abandoned', $form_state->getValue(['status', 'comments']));
  }

  /**
   * Submit function for changing the plan status to "planning".
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitPlanning(array &$form, FormStateInterface $form_state) {
    $this->changeStatus('planning', $form_state->getValue(['status', 'comments']));
  }

  /**
   * Change the plan status and set a revision log message.
   *
   * @param string $status
   *   The status to change to.
   * @param string $comments
   *   Comments to append to the revision log message.
   */
  protected function changeStatus(string $status, string $comments) {
    $this->plan->set('status', $status);
    $this->plan->setNewRevision(TRUE);
    $message = $this->t('Plan status changed to "@status".', ['@status' => $status]);
    $revision_log = $message->render();
    if (!empty($comments)) {
      $revision_log .= ' ' . $comments;
    }
    $this->plan->setRevisionLogMessage($revision_log);
    $this->plan->save();
    $this->messenger()->addMessage($message);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
