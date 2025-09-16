<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Hook;

use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_sli\Form\IntakeReviewForm;

/**
 * Theme hook implementations for farm_sli.
 */
class ThemeHooks implements ContainerInjectionInterface {

  use AutowireTrait;
  use StringTranslationTrait;

  public function __construct(
    protected AccountInterface $currentUser,
    protected FormBuilderInterface $formBuilder,
  ) {}

  /**
   * Implements hook_menu_local_actions_alter().
   */
  #[Hook('menu_local_actions_alter')]
  public function menuLocalActionsAlter(array &$local_actions): void {

    // Remove actions from dashboard.
    $actions = [
      'farm.actions:farm.add.asset',
      'farm.actions:farm.add.log',
      'farm.actions:farm.add.organization',
      'farm.actions:farm.add.plan',
    ];
    foreach ($actions as $action) {
      if (isset($local_actions[$action])) {
        $key = array_search('farm.dashboard', $local_actions[$action]['appears_on']);
        if ($key !== FALSE) {
          unset($local_actions[$action]['appears_on'][$key]);
        }
      }
    }
  }

  /**
   * Implements hook_preprocess_page().
   */
  #[Hook('preprocess_page')]
  public function preprocessPage(&$variables): void {

    // Disable the breadcrumb region for anonymous users.
    if ($this->currentUser->isAnonymous()) {
      unset($variables['page']['breadcrumb']);
    }
  }

  /**
   * Implements hook_ENTITY_TYPE_view().
   */
  #[Hook('log_view')]
  public function logView(array &$build, EntityInterface $entity, EntityViewDisplayInterface $display, $view_mode): void {
    /** @var \Drupal\log\Entity\LogInterface $entity */

    // Only modify intake logs in full view mode.
    if (!($entity->bundle() == 'sli_intake' && $view_mode == 'full')) {
      return;
    }

    // Add intake review form.
    $build['review_intake'] = [
      '#type' => 'details',
      '#title' => $this->t('Review intake'),
      '#open' => TRUE,
      '#access' => IntakeReviewForm::access($this->currentUser, $entity),
      'form' => $this->formBuilder->getForm('Drupal\farm_sli\Form\IntakeReviewForm', $entity),
    ];
  }

  /**
   * Implements hook_entity_extra_field_info().
   */
  #[Hook('entity_extra_field_info')]
  public function entityExtraFieldInfo(): array {

    // Expose the intake review form on intake logs.
    return [
      'log' => [
        'sli_intake' => [
          'display' => [
            'review_intake' => [
              'label' => $this->t('Intake review form'),
              'description' => $this->t('Form for reviewing an intake log.'),
              'weight' => -100,
            ],
          ],
        ],
      ],
    ];
  }

  /**
   * Implements hook_farm_ui_theme_region_items().
   */
  #[Hook('farm_ui_theme_region_items')]
  public function farmUiThemeRegionItems(string $entity_type): array {

    // Place the intake review form in the top region.
    if ($entity_type == 'log') {
      return [
        'top' => [
          'review_intake',
        ],
      ];
    }
    return [];
  }

}
