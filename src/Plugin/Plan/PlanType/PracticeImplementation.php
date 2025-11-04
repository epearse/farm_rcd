<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Plugin\Plan\PlanType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\PlanType;
use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;
use Drupal\farm_sli\SliHelper;

/**
 * Provides the SLI practice implementation plan type.
 */
#[PlanType(
  id: 'sli_practice_implementation',
  label: new TranslatableMarkup('Practice implementation plan'),
)]
class PracticeImplementation extends FarmPlanType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {

    // Inherit the default asset and log reference fields.
    $fields = parent::buildFieldDefinitions();

    // Add additional fields.
    $field_info = [

      // Farm organization entity.
      'farm' => [
        'type' => 'entity_reference',
        'label' => $this->t('Farm'),
        'description' => $this->t('Associates the practice implementation plan with a farm organization.'),
        'target_type' => 'organization' ,
        'target_bundle' => 'farm',
        'required' => TRUE,
      ],

      // Land asset.
      'land' => [
        'type' => 'entity_reference',
        'label' => $this->t('Land asset'),
        'description' => $this->t('Associates the practice implementation plan with a land asset.'),
        'target_type' => 'asset',
        'target_bundle' => 'land',
        'required' => TRUE,
      ],

      // Conservation practice.
      'sli_practice' => [
        'type' => 'list_string',
        'label' => $this->t('Conservation practice'),
        'description' => $this->t('Specify the conservation practice that this plan intends to implement.'),
        'allowed_values' => array_map(function ($practice) {
          $label = $practice['label']->render();
          if (!empty($practice['nrcs_code'])) {
            $label .= ' (NRCS code ' . $practice['nrcs_code'] . ')';
          }
          return $label;
        }, SliHelper::practices()),
        'required' => TRUE,
      ],

      // Funding source.
      'sli_funding_source' => [
        'type' => 'string',
        'label' => $this->t('Funding source'),
        'description' => $this->t('Describe where the funding for this practice came from.'),
      ],

    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
