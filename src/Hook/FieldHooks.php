<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Hook;

use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_field\FarmFieldFactoryInterface;

/**
 * Field hook implementations for farm_rcd.
 */
class FieldHooks {

  use AutowireTrait;
  use StringTranslationTrait;

  public function __construct(
    protected FarmFieldFactoryInterface $farmFieldFactory,
  ) {}

  /**
   * Implements hook_base_field_info_alter().
   */
  #[Hook('base_field_info_alter')]
  public function baseFieldInfoAlter(&$fields, EntityTypeInterface $entity_type) {
    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */

    // Require the farm reference field on plans.
    if ($entity_type->id() == 'plan' && isset($fields['farm'])) {
      $fields['farm']->setRequired(TRUE);
    }
  }

  /**
   * Implements hook_farm_entity_bundle_field_info().
   */
  #[Hook('farm_entity_bundle_field_info')]
  public function farmEntityBundleFieldInfo(EntityTypeInterface $entity_type, string $bundle) {
    $fields = [];

    // Add bundle fields to land assets.
    if ($entity_type->id() == 'asset' && $bundle == 'land') {

      // APN.
      $options = [
        'type' => 'string',
        'label' => $this->t('APN'),
        'multiple' => TRUE,
      ];
      $fields['rcd_apn'] = $this->farmFieldFactory->bundleFieldDefinition($options);

      // Riparian areas.
      $options = [
        'type' => 'string_long',
        'label' => $this->t('Riparian areas'),
      ];
      $fields['rcd_riparian_areas'] = $this->farmFieldFactory->bundleFieldDefinition($options);

      // Wildlife.
      $options = [
        'type' => 'string_long',
        'label' => $this->t('Wildlife'),
      ];
      $fields['rcd_wildlife'] = $this->farmFieldFactory->bundleFieldDefinition($options);
    }

    return $fields;
  }

}
