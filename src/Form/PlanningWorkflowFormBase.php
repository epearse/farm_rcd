<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Form;

use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\asset\Entity\AssetInterface;
use Drupal\log\Entity\LogInterface;
use Drupal\organization\Entity\OrganizationInterface;
use Drupal\plan\Entity\PlanInterface;

/**
 * Base form class for planning workflow forms.
 */
abstract class PlanningWorkflowFormBase extends FormBase {

  use AutowireTrait;

  /**
   * Plan entity.
   *
   * @var \Drupal\plan\Entity\PlanInterface|null
   */
  protected ?PlanInterface $plan = NULL;

  /**
   * Intake log.
   *
   * @var \Drupal\log\Entity\LogInterface|null
   */
  protected ?LogInterface $intake = NULL;

  /**
   * Farm organization.
   *
   * @var \Drupal\organization\Entity\OrganizationInterface|null
   */
  protected ?OrganizationInterface $farm = NULL;

  /**
   * Property land asset.
   *
   * @var \Drupal\asset\Entity\AssetInterface|null
   */
  protected ?AssetInterface $property = NULL;

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ?PlanInterface $plan = NULL) {
    $form = ['#tree' => TRUE];
    $this->loadPlanEntities($plan);
    return $form;
  }

  /**
   * Load entities relevant to the plan.
   *
   * @param \Drupal\plan\Entity\PlanInterface|null $plan
   *   The plan entity, or null.
   */
  protected function loadPlanEntities(?PlanInterface $plan = NULL) {

    // Save the plan, or bail if it is null.
    if (is_null($plan)) {
      return;
    }
    $this->plan = $plan;

    // If the plan has an intake associated with it, load the log.
    if (!$plan->get('intake')->isEmpty()) {
      $this->intake = $plan->get('intake')->referencedEntities()[0];
    }

    // If the plan has a farm associated with it, load the organization.
    if (!$plan->get('farm')->isEmpty()) {
      $this->farm = $plan->get('farm')->referencedEntities()[0];
    }

    // If the plan has a property associated with it, load the asset.
    if (!$plan->get('property')->isEmpty()) {
      $this->property = $plan->get('property')->referencedEntities()[0];
    }
  }

}
