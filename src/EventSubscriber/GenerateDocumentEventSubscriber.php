<?php

declare(strict_types=1);

namespace Drupal\farm_sli\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\farm_sli\Event\GenerateDocumentEvent;
use Drupal\farm_sli\Placeholder\ListBlockPlaceholder;
use Drupal\farm_sli\Placeholder\ListStringPlaceholder;
use Drupal\farm_sli\Placeholder\StringPlaceholder;
use Drupal\farm_sli\SliHelper;
use Drupal\plan\Entity\PlanInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An event subscriber for the GenerateDocumentEvent.
 */
class GenerateDocumentEventSubscriber implements EventSubscriberInterface {

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      GenerateDocumentEvent::EVENT_NAME => ['onDocumentGenerate'],
    ];
  }

  /**
   * React to the GenerateDocumentEvent.
   *
   * @param \Drupal\farm_sli\Event\GenerateDocumentEvent $event
   *   The GenerateDocumentEvent object.
   */
  public function onDocumentGenerate(GenerateDocumentEvent $event) {

    // Load the event context.
    $context = $event->getContext();

    // Only proceed if a test plan is included in the context.
    if (empty($context['plan']) || !($context['plan'] instanceof PlanInterface && $context['plan']->bundle() == 'sli_rcp')) {
      return;
    }
    $plan = $context['plan'];

    // Build placeholders.
    $placeholders = [];

    // Load values from the farm organization associated with the plan (if
    // available).
    if (!$plan->get('farm')->isEmpty()) {
      $farm = $plan->get('farm')->referencedEntities()[0];
      $organization_label = $farm->label();
    }
    $placeholders[] = new StringPlaceholder('organization_label', $organization_label ?? '');

    // Load values from the property land assets associated with the plan (if
    // available).
    if (!$plan->get('property')->isEmpty()) {
      $property = $plan->get('property')->referencedEntities()[0];
      $property_label = $property->label();
      $property_description = $property->get('notes')->value;
      $property_apns = implode(', ', array_map(function ($value) {
        return $value['value'];
      }, $property->get('sli_apn')->getValue()));
      $riparian_areas = $property->get('sli_riparian_areas')->value;
      $native_wildlife = $property->get('sli_native_wildlife')->value;
    }
    $placeholders[] = new StringPlaceholder('property_label', $property_label ?? '');
    $placeholders[] = new StringPlaceholder('property_description', $property_description ?? '');
    $placeholders[] = new StringPlaceholder('property_apns', $property_apns ?? '');
    $placeholders[] = new StringPlaceholder('riparian_areas', $riparian_areas ?? '');
    $placeholders[] = new StringPlaceholder('native_wildlife', $native_wildlife ?? '');

    // Load values from the intake log associated with the plan (if available).
    if (!$plan->get('intake')->isEmpty()) {
      $intake = $plan->get('intake')->referencedEntities()[0];

      // Stakeholder name and type.
      $intake_stakeholder_name = $intake->get('intake_stakeholder_name')->value;
      $intake_stakeholder_type = SliHelper::stakeholderTypes()[$intake->get('intake_stakeholder_type')->value]->render();

      // Property owner and acreage.
      $intake_property_owner = $intake->get('intake_property_owner')->value;
      $intake_property_acreage = $intake->get('intake_property_acreage')->value;

      // Disadvantaged groups.
      $socially_disadvantaged = implode(', ', array_map(function ($value) {
        return SliHelper::stakeholderGroups()[$value['value']]->render();
      }, $intake->get('intake_stakeholder_group')->getValue()));

      // Land use.
      $intake_land_use = array_map(function ($value) {
        return SliHelper::landUses()[$value['value']]->render();
      }, $intake->get('intake_property_use')->getValue());

      // Stakeholder goals.
      $intake_stakeholder_goals = array_map(function ($item) use ($intake) {
        $value = $item['value'];
        if ($value == 'other') {
          return 'Other: ' . $intake->get('intake_goals_other')->value;
        }
        return SliHelper::goals()[$value]->render();
      }, $intake->get('intake_goals')->getValue());

      // Stakeholder concerns.
      $intake_stakeholder_concerns = array_map(function ($item) use ($intake) {
        $value = $item['value'];
        if ($value == 'other') {
          return 'Other: ' . $intake->get('intake_concerns_other')->value;
        }
        return SliHelper::concerns()[$value]->render();
      }, $intake->get('intake_concerns')->getValue());
    }
    $placeholders[] = new StringPlaceholder('intake_stakeholder_name', $intake_stakeholder_name ?? '');
    $placeholders[] = new StringPlaceholder('intake_stakeholder_type', $intake_stakeholder_type ?? '');
    $placeholders[] = new StringPlaceholder('intake_property_owner', $intake_property_owner ?? '');
    $placeholders[] = new StringPlaceholder('intake_property_acreage', $intake_property_acreage ?? '');
    $placeholders[] = new StringPlaceholder('socially_disadvantaged', $socially_disadvantaged ?? '');
    $placeholders[] = new ListStringPlaceholder('intake_land_use', $intake_land_use ?? []);
    $placeholders[] = new ListStringPlaceholder('intake_stakeholder_goals', $intake_stakeholder_goals ?? []);
    $placeholders[] = new ListStringPlaceholder('intake_stakeholder_concerns', $intake_stakeholder_concerns ?? []);

    // Load values from practice implementation plans associated with the plan
    // to build a set of repeating blocks for each practice, within repeating
    // blocks for each ecosite.
    if (!$plan->get('practice_implementation_plan')->isEmpty()) {

      // Load all practice plans, indexed by ecosite.
      $practices_by_ecosite = array_reduce($plan->get('practice_implementation_plan')->referencedEntities(), function ($carry, $plan) {
        $ecosite_id = $plan->get('land')->first()->target_id;
        $carry[$ecosite_id][] = $plan;
        return $carry;
      }, []);

      // Iterate through each ecosite and build placeholders.
      $ecosites = [];
      foreach ($practices_by_ecosite as $ecosite_id => $plans) {

        // Load the ecosite land asset.
        $land_asset = $this->entityTypeManager->getStorage('asset')->load($ecosite_id);

        // Iterate through the practice plans and build placeholders.
        $ecosite_practices = [];
        foreach ($plans as $plan) {
          $practice_info = SliHelper::practices()[$plan->get('sli_practice')->value];
          $practice_name = $practice_info['label']->render();
          if (!empty($practice_info['nrcs_code'])) {
            $practice_name .= ' (NRCS code ' . $practice_info['nrcs_code'] . ')';
          }
          $ecosite_practices[] = [
            new StringPlaceholder('practice_name', $practice_name),
            new StringPlaceholder('practice_overview', $plan->get('notes')->value ?? ''),
            new ListStringPlaceholder('practice_benefits', $practice_info['benefits']),
            new ListStringPlaceholder('practice_resources', $practice_info['resources']),
          ];
        }

        // Build placeholders for each ecosite.
        $ecosites[] = [
          new StringPlaceholder('ecosite_name', $land_asset->label()),
          new StringPlaceholder('ecosite_type', SliHelper::landTypes()[$land_asset->get('land_type')->value]->render()),
          new StringPlaceholder('ecosite_overview', $land_asset->get('notes')->value ?? ''),
          new ListBlockPlaceholder('ecosite_practices', $ecosite_practices),
        ];
      }
    }
    $placeholders[] = new ListBlockPlaceholder('ecosites', $ecosites ?? []);

    // Add placeholders to the event.
    $event->addPlaceholders($placeholders);
  }

}
