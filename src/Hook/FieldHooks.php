<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Hook;

use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_field\FarmFieldFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Field hook implementations for farm_sli.
 */
class FieldHooks {

  use AutowireTrait;
  use StringTranslationTrait;

  public function __construct(
    #[Autowire(service: 'farm_field.factory')]
    protected FarmFieldFactoryInterface $farmFieldFactory,
  ) {}

  /**
   * Implements hook_farm_entity_bundle_field_info().
   */
  #[Hook('farm_entity_bundle_field_info')]
  public function farmEntityBundleFieldInfo(EntityTypeInterface $entity_type, string $bundle) {
    $fields = [];

    // Add an APN bundle field to land assets.
    if ($entity_type->id() == 'asset' && $bundle == 'land') {
      $options = [
        'type' => 'string',
        'label' => $this->t('APN'),
        'multiple' => TRUE,
      ];
      $fields['sli_apn'] = $this->farmFieldFactory->bundleFieldDefinition($options);
    }

    return $fields;
  }

}
