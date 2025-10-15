<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Hook;

use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_field\FarmFieldFactoryInterface;

/**
 * Field hook implementations for farm_sli.
 */
class FieldHooks {

  use AutowireTrait;
  use StringTranslationTrait;

  public function __construct(
    protected FarmFieldFactoryInterface $farmFieldFactory,
  ) {}

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
      $fields['sli_apn'] = $this->farmFieldFactory->bundleFieldDefinition($options);

      // Riparian areas.
      $options = [
        'type' => 'string_long',
        'label' => $this->t('Riparian areas'),
      ];
      $fields['sli_riparian_areas'] = $this->farmFieldFactory->bundleFieldDefinition($options);

      // Native wildlife.
      $options = [
        'type' => 'string_long',
        'label' => $this->t('Native wildlife'),
      ];
      $fields['sli_native_wildlife'] = $this->farmFieldFactory->bundleFieldDefinition($options);
    }

    return $fields;
  }

}
