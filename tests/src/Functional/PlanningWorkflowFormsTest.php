<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_sli\Functional;

use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Tests the planning workflow forms.
 */
class PlanningWorkflowFormsTest extends SliTestBase {

  /**
   * Asset storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected EntityStorageInterface $assetStorage;

  /**
   * Log storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected EntityStorageInterface $logStorage;

  /**
   * Organization storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected EntityStorageInterface $organizationStorage;

  /**
   * Plan storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected EntityStorageInterface $planStorage;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    // Load entity type storages.
    $this->assetStorage = \Drupal::entityTypeManager()->getStorage('asset');
    $this->logStorage = \Drupal::entityTypeManager()->getStorage('log');
    $this->organizationStorage = \Drupal::entityTypeManager()->getStorage('organization');
    $this->planStorage = \Drupal::entityTypeManager()->getStorage('plan');
  }

  /**
   * Test planning workflow forms.
   */
  public function testPlanningWorkflowForms() {
    $this->doTestPropertyForm();
  }

  /**
   * Test property form.
   */
  public function doTestPropertyForm() {

    // Create a farm organization.
    /** @var \Drupal\organization\Entity\OrganizationInterface $farm */
    $farm = $this->organizationStorage->create([
      'type' => 'farm',
      'name' => $this->randomMachineName(),
    ]);
    $farm->save();

    // Create an intake log with minimum data for these tests.
    /** @var \Drupal\log\Entity\LogInterface $intake */
    $intake = $this->logStorage->create([
      'type' => 'sli_intake',
      'intake_property_street' => '123 Fake Street',
      'intake_property_city' => 'Fake City',
      'intake_property_state' => 'AL',
      'intake_property_zip' => '123456',
      'intake_property_parcel_gps' => 'Parcel 123',
    ]);
    $intake->save();

    // Create a resource conservation plan associated with the farm and intake.
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = $this->planStorage->create([
      'type' => 'sli_rcp',
      'name' => $this->randomMachineName(),
      'farm' => [$farm],
      'intake' => [$intake],
    ]);
    $plan->save();

    // Go to the plan entity view display.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);

    // Confirm that the plan name and farm name are present.
    $this->assertSession()->pageTextContains($plan->label());
    $this->assertSession()->pageTextContains($farm->label());

    // Confirm that the property form is present.
    $this->assertSession()->pageTextContains('Property Description');
    $this->assertSession()->responseContains('Save property information');

    // Confirm that the "Property label" field is pre-populated from the intake
    // address information, and the other fields are empty.
    $this->assertSession()->fieldValueEquals('property[form][label]', '123 Fake Street, Fake City, AL, 123456');
    $this->assertSession()->fieldValueEquals('property[form][description]', '');
    $this->assertSession()->fieldValueEquals('property[form][boundary][value]', '');
    $this->assertSession()->fieldValueEquals('property[form][apn][0]', '');

    // Remove property address information from the intake log, reload the
    // form, and confirm that the property parcel/GPS field was used to
    // pre-populate the property label.
    $intake->set('intake_property_street', '');
    $intake->set('intake_property_street', '');
    $intake->set('intake_property_street', '');
    $intake->set('intake_property_street', '');
    $intake->save();
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->fieldValueEquals('property[form][label]', 'Parcel 123');

    // Fill in the property description, boundary, and APN, and then submit the
    // form.
    $this->getSession()->getPage()->fillField('property[form][description]', 'Lorem ipsum');
    $this->getSession()->getPage()->fillField('property[form][boundary][value]', 'POINT(-155.59217843773246 19.472231748612728)');
    $this->getSession()->getPage()->fillField('property[form][apn][0]', 'ABC123');
    $this->getSession()->getPage()->pressButton('Save property information');

    // Confirm that a message was shown to the user.
    $this->assertSession()->pageTextContains('Property description saved.');

    // Confirm that a property was created and linked to the plan.
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = $this->planStorage->load($plan->id());
    $properties = $plan->get('property')->referencedEntities();
    $this->assertNotEmpty($properties[0]);

    // Confirm that the property was saved with the correct values.
    /** @var \Drupal\asset\Entity\AssetInterface $property */
    $property = $properties[0];
    $this->assertEquals('land', $property->bundle());
    $this->assertEquals('sli_property', $property->get('land_type')->value);
    $this->assertEquals('Parcel 123', $property->label());
    $this->assertEquals('Lorem ipsum', $property->get('notes')->value);
    $this->assertEquals('POINT(-155.59217843773246 19.472231748612728)', $property->get('intrinsic_geometry')->value);
    $apns = $property->get('sli_apn')->getValue();
    $this->assertEquals('ABC123', $apns[0]['value']);

    // Reload the plan entity view display and confirm that the property's
    // information is populated in the form.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->fieldValueEquals('property[form][label]', 'Parcel 123');
    $this->assertSession()->fieldValueEquals('property[form][description]', 'Lorem ipsum');
    $this->assertSession()->fieldValueEquals('property[form][boundary][value]', 'POINT(-155.59217843773246 19.472231748612728)');
    $this->assertSession()->fieldValueEquals('property[form][apn][0]', 'ABC123');

    // Unlink the property from the plan.
    $plan->set('property', []);
    $plan->save();

    // Reload the plan entity view display.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);

    // Confirm that the "new or existing" radios are shown.
    $this->assertSession()->pageTextContains('Select an existing property');
    $this->assertSession()->pageTextContains('Create a new property');

    // Select "existing", then select the property, then submit the form.
    $this->getSession()->getPage()->fillField('property[new_or_existing]', 'existing');
    $this->getSession()->getPage()->fillField('property[existing_property]', $property->id());
    $this->getSession()->getPage()->pressButton('Save property information');
    $this->assertSession()->pageTextContains('Property description saved.');

    // Confirm that the property was linked to the plan.
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = $this->planStorage->load($plan->id());
    $properties = $plan->get('property')->referencedEntities();
    $this->assertNotEmpty($properties[0]);
    $this->assertEquals($property->id(), $properties[0]->id());

    // Reload the plan entity view display.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);

    // Change the property information and submit the form.
    $this->getSession()->getPage()->fillField('property[form][label]', 'Updated label');
    $this->getSession()->getPage()->fillField('property[form][description]', 'Updated description');
    $this->getSession()->getPage()->fillField('property[form][boundary][value]', '');
    $this->getSession()->getPage()->fillField('property[form][apn][0]', 'XYZ123');
    $this->getSession()->getPage()->pressButton('Save property information');
    $this->assertSession()->pageTextContains('Property description saved.');

    // Confirm that the property was updated correctly.
    /** @var \Drupal\asset\Entity\AssetInterface $property */
    $property = $this->assetStorage->load($property->id());
    $this->assertEquals('Updated label', $property->label());
    $this->assertEquals('Updated description', $property->get('notes')->value);
    $this->assertEquals('', $property->get('intrinsic_geometry')->value);
    $apns = $property->get('sli_apn')->getValue();
    $this->assertEquals('XYZ123', $apns[0]['value']);

    // Reload the plan entity view display.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);

    // Click the "Add another APN" button, fill in the second APN field, and
    // submit.
    $this->getSession()->getPage()->pressButton('Add another APN');
    $this->getSession()->getPage()->fillField('property[form][apn][1]', 'ABC123');
    $this->getSession()->getPage()->pressButton('Save property information');
    $this->assertSession()->pageTextContains('Property description saved.');

    // Confirm that both APNs are saved.
    /** @var \Drupal\asset\Entity\AssetInterface $property */
    $property = $this->assetStorage->load($property->id());
    $apns = $property->get('sli_apn')->getValue();
    $this->assertCount(2, $apns);
    $this->assertEquals('XYZ123', $apns[0]['value']);
    $this->assertEquals('ABC123', $apns[1]['value']);

    // Reload the plan entity view display.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);

    // Clear out the first APN and submit.
    $this->getSession()->getPage()->fillField('property[form][apn][0]', '0');
    $this->getSession()->getPage()->pressButton('Save property information');
    $this->assertSession()->pageTextContains('Property description saved.');

    // Confirm that only the second APN exists.
    /** @var \Drupal\asset\Entity\AssetInterface $property */
    $property = $this->assetStorage->load($property->id());
    $apns = $property->get('sli_apn')->getValue();
    $this->assertCount(1, $apns);
    $this->assertEquals('ABC123', $apns[0]['value']);
  }

}
