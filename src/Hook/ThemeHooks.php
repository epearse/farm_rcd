<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Hook;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\farm_sli\Form\IntakeReviewForm;

/**
 * Theme hook implementations for farm_sli.
 */
class ThemeHooks {

  use StringTranslationTrait;

  /**
   * Implements hook_preprocess_page().
   */
  #[Hook('preprocess_page')]
  public function preprocessPage(&$variables): void {

    // Disable the breadcrumb region for anonymous users.
    if (\Drupal::currentUser()->isAnonymous()) {
      unset($variables['page']['breadcrumb']);
    }
  }

  /**
   * Implements hook_ENTITY_TYPE_view().
   */
  #[Hook('log_view')]
  public function logView(array &$build, EntityInterface $entity, EntityViewDisplayInterface $display, $view_mode): void {

    // Only modify intake logs in full view mode.
    if (!($entity->bundle() == 'sli_intake' && $view_mode == 'full')) {
      return;
    }

    // Add a button for reviewing the intake.
    $build['review_intake'] = [
      '#type' => 'link',
      '#title' => $this->t('Review Intake'),
      '#url' => Url::fromRoute('farm_sli.intake_review', ['log' => $entity->id()]),
      '#attributes' => [
        'class' => ['button', 'use-ajax'],
        'data-dialog-type' => 'dialog',
        'data-dialog-renderer' => 'off_canvas',
      ],
      '#access' => IntakeReviewForm::access(\Drupal::currentUser(), $entity),
    ];
  }

  /**
   * Implements hook_entity_extra_field_info().
   */
  #[Hook('entity_extra_field_info')]
  public function entityExtraFieldInfo(): array {

    // Expose the review_intake field on intake logs.
    return [
      'log' => [
        'sli_intake' => [
          'display' => [
            'review_intake' => [
              'label' => $this->t('Review intake button'),
              'description' => $this->t('Button for reviewing an intake log.'),
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

    // Place the "Review Intake" button in the second region.
    if ($entity_type == 'log') {
      return [
        'second' => [
          'review_intake',
        ],
      ];
    }
    return [];
  }

}
