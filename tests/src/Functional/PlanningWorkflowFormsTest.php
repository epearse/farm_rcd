<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_rcd\Functional;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Tests\farm_rcd\Traits\PhpWordTestingTrait;
use Drupal\farm_rcd\RcdHelper;
use Drupal\plan\Entity\PlanInterface;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

/**
 * Tests the planning workflow forms.
 */
class PlanningWorkflowFormsTest extends RcdTestBase {

  use PhpWordTestingTrait;

  /**
   * Asset storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected EntityStorageInterface $assetStorage;

  /**
   * File storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected EntityStorageInterface $fileStorage;

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
    $this->fileStorage = \Drupal::entityTypeManager()->getStorage('file');
    $this->logStorage = \Drupal::entityTypeManager()->getStorage('log');
    $this->organizationStorage = \Drupal::entityTypeManager()->getStorage('organization');
    $this->planStorage = \Drupal::entityTypeManager()->getStorage('plan');
  }

  /**
   * Test planning workflow forms.
   */
  public function testPlanningWorkflowForms() {
    $this->doTestPropertyForm();
    $this->doTestEcositesForm();
    $this->doTestSiteAssessmentsForm();
    $this->doTestPracticesForm();
    $this->doTestDocumentForm();
    $this->doTestStatusForm();
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
      'type' => 'rcd_intake',
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
      'type' => 'rcd_rcp',
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
    $this->assertSession()->fieldValueEquals('property[form][riparian_areas]', '');
    $this->assertSession()->fieldValueEquals('property[form][native_wildlife]', '');
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

    // Fill in the property description, riparian areas, native wildlife,
    // boundary, and APN, and then submit the form.
    $this->getSession()->getPage()->fillField('property[form][description]', 'Lorem ipsum');
    $this->getSession()->getPage()->fillField('property[form][riparian_areas]', 'Dolor sit amet');
    $this->getSession()->getPage()->fillField('property[form][native_wildlife]', 'Consectetur adipiscing elit');
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
    $this->assertEquals('rcd_property', $property->get('land_type')->value);
    $this->assertEquals('Parcel 123', $property->label());
    $this->assertEquals('Lorem ipsum', $property->get('notes')->value);
    $this->assertEquals('Dolor sit amet', $property->get('rcd_riparian_areas')->value);
    $this->assertEquals('Consectetur adipiscing elit', $property->get('rcd_native_wildlife')->value);
    $this->assertEquals('POINT(-155.59217843773246 19.472231748612728)', $property->get('intrinsic_geometry')->value);
    $apns = $property->get('rcd_apn')->getValue();
    $this->assertEquals('ABC123', $apns[0]['value']);

    // Reload the plan entity view display and confirm that the property's
    // information is populated in the form.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->fieldValueEquals('property[form][label]', 'Parcel 123');
    $this->assertSession()->fieldValueEquals('property[form][description]', 'Lorem ipsum');
    $this->assertSession()->fieldValueEquals('property[form][riparian_areas]', 'Dolor sit amet');
    $this->assertSession()->fieldValueEquals('property[form][native_wildlife]', 'Consectetur adipiscing elit');
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
    $this->getSession()->getPage()->fillField('property[form][riparian_areas]', 'Updated riparian areas');
    $this->getSession()->getPage()->fillField('property[form][native_wildlife]', 'Updated native wildlife');
    $this->getSession()->getPage()->fillField('property[form][boundary][value]', '');
    $this->getSession()->getPage()->fillField('property[form][apn][0]', 'XYZ123');
    $this->getSession()->getPage()->pressButton('Save property information');
    $this->assertSession()->pageTextContains('Property description saved.');

    // Confirm that the property was updated correctly.
    /** @var \Drupal\asset\Entity\AssetInterface $property */
    $property = $this->assetStorage->load($property->id());
    $this->assertEquals('Updated label', $property->label());
    $this->assertEquals('Updated description', $property->get('notes')->value);
    $this->assertEquals('Updated riparian areas', $property->get('rcd_riparian_areas')->value);
    $this->assertEquals('Updated native wildlife', $property->get('rcd_native_wildlife')->value);
    $this->assertEquals('', $property->get('intrinsic_geometry')->value);
    $apns = $property->get('rcd_apn')->getValue();
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
    $apns = $property->get('rcd_apn')->getValue();
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
    $apns = $property->get('rcd_apn')->getValue();
    $this->assertCount(1, $apns);
    $this->assertEquals('ABC123', $apns[0]['value']);
  }

  /**
   * Test ecosites form.
   */
  public function doTestEcositesForm() {

    // Create a farm organization.
    /** @var \Drupal\organization\Entity\OrganizationInterface $farm */
    $farm = $this->organizationStorage->create([
      'type' => 'farm',
      'name' => $this->randomMachineName(),
    ]);
    $farm->save();

    // Create a resource conservation plan associated with the farm.
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = $this->planStorage->create([
      'type' => 'rcd_rcp',
      'name' => $this->randomMachineName(),
      'farm' => [$farm],
    ]);
    $plan->save();

    // Go to the plan entity view display and confirm that the ecosite form is
    // present, but not accessible yet.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Ecological Site Descriptions');
    $this->assertSession()->pageTextContains('A property description must be created before ecological site descriptions can be added.');
    $this->assertSession()->pageTextNotContains('+ Add ecosite');
    $this->assertSession()->responseNotContains('Save land assets');

    // Create a property land asset associated with the farm and plan.
    /** @var \Drupal\asset\Entity\AssetInterface $property */
    $property = $this->assetStorage->create([
      'type' => 'land',
      'land_type' => 'rcd_property',
      'name' => $this->randomMachineName(),
      'farm' => [$farm],
    ]);
    $property->save();
    $plan->set('property', [$property]);
    $plan->save();

    // Reload the form and confirm that the ecosite form is accessible.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextNotContains('A property description must be created before ecological site descriptions can be added.');
    $this->assertSession()->pageTextContains('+ Add ecosite');
    $this->assertSession()->responseContains('Save land assets');

    // Fill in the form and submit it.
    $this->getSession()->getPage()->fillField('ecosites[add][type]', 'rcd_row_crops');
    $this->getSession()->getPage()->fillField('ecosites[add][label]', 'Corn field');
    $this->getSession()->getPage()->fillField('ecosites[add][description]', 'See corn, say corn!');
    $this->getSession()->getPage()->fillField('ecosites[add][boundary][value]', 'POINT(-155.60893291251693 19.431635160410153)');
    $this->getSession()->getPage()->pressButton('Save land assets');

    // Confirm that a message was shown to the user.
    $this->assertSession()->pageTextContains('Land assets saved.');

    // Confirm that a land asset was created as a child of the property with
    // all expected details filled in.
    /** @var \Drupal\asset\Entity\AssetInterface[] $land_assets */
    $land_assets = $this->assetStorage->loadByProperties([
      'type' => 'land',
      'parent' => $property->id(),
      'farm' => $farm->id(),
    ]);
    $this->assertCount(1, $land_assets);
    $land_asset = reset($land_assets);
    $this->assertEquals('rcd_row_crops', $land_asset->get('land_type')->value);
    $this->assertEquals('Corn field', $land_asset->label());
    $this->assertEquals('See corn, say corn!', $land_asset->get('notes')->value);
    $this->assertEquals('POINT(-155.60893291251693 19.431635160410153)', $land_asset->get('intrinsic_geometry')->value);

    // Confirm that the saved asset was added to the form, and fields are
    // pre-filled.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Land asset: Corn field');
    $this->assertSession()->fieldValueEquals('ecosites[' . $land_asset->id() . '][type]', 'rcd_row_crops');
    $this->assertSession()->fieldValueEquals('ecosites[' . $land_asset->id() . '][label]', 'Corn field');
    $this->assertSession()->fieldValueEquals('ecosites[' . $land_asset->id() . '][description]', 'See corn, say corn!');
    $this->assertSession()->fieldValueEquals('ecosites[' . $land_asset->id() . '][boundary][value]', 'POINT(-155.60893291251693 19.431635160410153)');

    // Edit the asset's fields and submit the form.
    $this->getSession()->getPage()->fillField('ecosites[' . $land_asset->id() . '][type]', 'rcd_orchard');
    $this->getSession()->getPage()->fillField('ecosites[' . $land_asset->id() . '][label]', 'Apple orchard');
    $this->getSession()->getPage()->fillField('ecosites[' . $land_asset->id() . '][description]', 'History haunts him who does not honour it.');
    $this->getSession()->getPage()->fillField('ecosites[' . $land_asset->id() . '][boundary][value]', '');
    $this->getSession()->getPage()->pressButton('Save land assets');
    $this->assertSession()->pageTextContains('Land assets saved.');
    $this->assertSession()->pageTextContains('Land asset: Apple orchard');

    // Confirm that the new values were saved to the asset.
    /** @var \Drupal\asset\Entity\AssetInterface $land_asset */
    $land_asset = $this->assetStorage->load($land_asset->id());
    $this->assertEquals('rcd_orchard', $land_asset->get('land_type')->value);
    $this->assertEquals('Apple orchard', $land_asset->label());
    $this->assertEquals('History haunts him who does not honour it.', $land_asset->get('notes')->value);
    $this->assertEquals('', $land_asset->get('intrinsic_geometry')->value);
  }

  /**
   * Test site assessments form.
   */
  public function doTestSiteAssessmentsForm() {

    // Create a farm organization.
    /** @var \Drupal\organization\Entity\OrganizationInterface $farm */
    $farm = $this->organizationStorage->create([
      'type' => 'farm',
      'name' => $this->randomMachineName(),
    ]);
    $farm->save();

    // Create a property land asset associated with the farm.
    /** @var \Drupal\asset\Entity\AssetInterface $property */
    $property = $this->assetStorage->create([
      'type' => 'land',
      'land_type' => 'rcd_property',
      'name' => $this->randomMachineName(),
      'farm' => [$farm],
    ]);
    $property->save();

    // Create a resource conservation plan associated with the farm and
    // property.
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = $this->planStorage->create([
      'type' => 'rcd_rcp',
      'name' => $this->randomMachineName(),
      'farm' => [$farm],
      'property' => [$property],
    ]);
    $plan->save();

    // Go to the plan entity view display and confirm that the site assessments
    // form present but is not accessible yet.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Site Assessments');
    $this->assertSession()->pageTextContains('Ecological site descriptions must be created before site assessments can be made. ');
    $this->assertSession()->pageTextNotContains('+ Add site assessment');
    $this->assertSession()->responseNotContains('Save site assessments');

    // Create a land asset to represent an ecosite.
    /** @var \Drupal\asset\Entity\AssetInterface $ecosite */
    $ecosite = $this->assetStorage->create([
      'type' => 'land',
      'land_type' => 'rcd_row_crops',
      'name' => $this->randomMachineName(),
      'parent' => [$property],
      'farm' => [$farm],
    ]);
    $ecosite->save();

    // Reload the form and confirm that the site assessments form is
    // accessible.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextNotContains('Ecological site descriptions must be created before site assessments can be made.');
    $this->assertSession()->pageTextContains('+ Add site assessment');
    $this->assertSession()->responseContains('Save site assessments');

    // Fill in the form and submit it.
    $this->getSession()->getPage()->fillField('assessments[add][ecosite]', $ecosite->id());
    $this->getSession()->getPage()->fillField('assessments[add][land_use_history]', 'land use history');
    $this->getSession()->getPage()->fillField('assessments[add][infrastructure]', 'existing infrastructure');
    $this->getSession()->getPage()->fillField('assessments[add][priority_concerns]', 'priority environmental concerns');
    // @todo Test taxonomy terms (Tagify requires JavaScript).
    $this->getSession()->getPage()->fillField('assessments[add][notes]', 'The site has been assessed.');
    $resources = [
      'soil',
      'water',
      'plant',
      'aquatic',
      'livestock',
      'wildlife',
      'infrastructure',
    ];
    foreach ($resources as $resource) {
      $this->getSession()->getPage()->fillField('assessments[add][resources][' . $resource . '][rating]', '5');
      $this->getSession()->getPage()->fillField('assessments[add][resources][' . $resource . '][baseline]', $resource . ' baseline');
      $this->getSession()->getPage()->fillField('assessments[add][resources][' . $resource . '][goals]', $resource . ' goals');
      $this->getSession()->getPage()->fillField('assessments[add][resources][' . $resource . '][strategy]', $resource . ' strategy');
    }
    $this->getSession()->getPage()->pressButton('Save site assessments');

    // Confirm that a message was shown to the user.
    $this->assertSession()->pageTextContains('Site assessment logs saved.');

    // Confirm that a site assessment log was created with all expected details
    // filled in.
    /** @var \Drupal\log\Entity\LogInterface[] $logs */
    $logs = $this->logStorage->loadByProperties(['type' => 'rcd_site_assessment']);
    $this->assertCount(1, $logs);
    $log = reset($logs);
    $this->assertEquals($ecosite->id(), $log->get('location')->referencedEntities()[0]->id());
    $this->assertEquals(date('m/d/Y') . ' ' . $ecosite->label(), $log->label());
    $this->assertEquals('done', $log->get('status')->value);
    $this->assertEquals('land use history', $log->get('rcd_land_use_history')->value);
    $this->assertEquals('existing infrastructure', $log->get('rcd_infrastructure')->value);
    $this->assertEquals('priority environmental concerns', $log->get('rcd_priority_concerns')->value);
    // @todo Test taxonomy terms (Tagify requires JavaScript).
    $this->assertEquals('The site has been assessed.', $log->get('notes')->value);
    foreach ($resources as $resource) {
      $this->assertEquals(5, $log->get('rcd_' . $resource . '_rating')->value);
      $this->assertEquals($resource . ' baseline', $log->get('rcd_' . $resource . '_baseline')->value);
      $this->assertEquals($resource . ' goals', $log->get('rcd_' . $resource . '_goals')->value);
      $this->assertEquals($resource . ' strategy', $log->get('rcd_' . $resource . '_strategy')->value);
    }

    // Confirm that the saved log was added to the form, and fields are
    // pre-filled.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Site assessment log: ' . date('m/d/Y') . ' ' . $ecosite->label());
    $this->assertSession()->fieldValueEquals('assessments[' . $log->id() . '][ecosite][' . $ecosite->id() . ']', $ecosite->id());
    $this->assertSession()->fieldValueEquals('assessments[' . $log->id() . '][date]', date('Y-m-d'));
    $this->assertSession()->fieldValueEquals('assessments[' . $log->id() . '][land_use_history]', 'land use history');
    $this->assertSession()->fieldValueEquals('assessments[' . $log->id() . '][infrastructure]', 'existing infrastructure');
    $this->assertSession()->fieldValueEquals('assessments[' . $log->id() . '][priority_concerns]', 'priority environmental concerns');
    // @todo Test taxonomy terms (Tagify requires JavaScript).
    $this->assertSession()->fieldValueEquals('assessments[' . $log->id() . '][notes]', 'The site has been assessed.');
    foreach ($resources as $resource) {
      $this->assertSession()->fieldValueEquals('assessments[' . $log->id() . '][resources][' . $resource . '][rating]', '5');
      $this->assertSession()->fieldValueEquals('assessments[' . $log->id() . '][resources][' . $resource . '][baseline]', $resource . ' baseline');
      $this->assertSession()->fieldValueEquals('assessments[' . $log->id() . '][resources][' . $resource . '][goals]', $resource . ' goals');
      $this->assertSession()->fieldValueEquals('assessments[' . $log->id() . '][resources][' . $resource . '][strategy]', $resource . ' strategy');
    }

    // Edit the log's fields and submit the form.
    $this->getSession()->getPage()->fillField('assessments[' . $log->id() . '][date]', date('Y-m-d', strtotime('tomorrow')));
    $this->getSession()->getPage()->fillField('assessments[' . $log->id() . '][land_use_history]', 'land use history!');
    $this->getSession()->getPage()->fillField('assessments[' . $log->id() . '][infrastructure]', 'existing infrastructure!');
    $this->getSession()->getPage()->fillField('assessments[' . $log->id() . '][priority_concerns]', 'priority environmental concerns!');
    // @todo Test taxonomy terms (Tagify requires JavaScript).
    $this->getSession()->getPage()->fillField('assessments[' . $log->id() . '][notes]', 'The site has been assessed!');
    foreach ($resources as $resource) {
      $this->getSession()->getPage()->fillField('assessments[' . $log->id() . '][resources][' . $resource . '][rating]', '1');
      $this->getSession()->getPage()->fillField('assessments[' . $log->id() . '][resources][' . $resource . '][baseline]', $resource . ' baseline!');
      $this->getSession()->getPage()->fillField('assessments[' . $log->id() . '][resources][' . $resource . '][goals]', $resource . ' goals!');
      $this->getSession()->getPage()->fillField('assessments[' . $log->id() . '][resources][' . $resource . '][strategy]', $resource . ' strategy!');
    }
    $this->getSession()->getPage()->pressButton('Save site assessments');
    $this->assertSession()->pageTextContains('Site assessment logs saved.');
    $this->assertSession()->pageTextContains('Site assessment log: ' . date('m/d/Y', strtotime('tomorrow')) . ' ' . $ecosite->label());

    // Confirm that the new values were saved to the asset.
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = $this->logStorage->load($log->id());
    $this->assertEquals(strtotime(date('m/d/Y', strtotime('tomorrow'))), $log->get('timestamp')->value);
    $this->assertEquals('land use history!', $log->get('rcd_land_use_history')->value);
    $this->assertEquals('existing infrastructure!', $log->get('rcd_infrastructure')->value);
    $this->assertEquals('priority environmental concerns!', $log->get('rcd_priority_concerns')->value);
    // @todo Test taxonomy terms (Tagify requires JavaScript).
    $this->assertEquals('The site has been assessed!', $log->get('notes')->value);
    foreach ($resources as $resource) {
      $this->assertEquals(1, $log->get('rcd_' . $resource . '_rating')->value);
      $this->assertEquals($resource . ' baseline!', $log->get('rcd_' . $resource . '_baseline')->value);
      $this->assertEquals($resource . ' goals!', $log->get('rcd_' . $resource . '_goals')->value);
      $this->assertEquals($resource . ' strategy!', $log->get('rcd_' . $resource . '_strategy')->value);
    }
  }

  /**
   * Test practices form.
   */
  public function doTestPracticesForm() {

    // Create a farm organization.
    /** @var \Drupal\organization\Entity\OrganizationInterface $farm */
    $farm = $this->organizationStorage->create([
      'type' => 'farm',
      'name' => $this->randomMachineName(),
    ]);
    $farm->save();

    // Create a property land asset associated with the farm.
    /** @var \Drupal\asset\Entity\AssetInterface $property */
    $property = $this->assetStorage->create([
      'type' => 'land',
      'land_type' => 'rcd_property',
      'name' => $this->randomMachineName(),
      'farm' => [$farm],
    ]);
    $property->save();

    // Create a resource conservation plan associated with the farm and
    // property.
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = $this->planStorage->create([
      'type' => 'rcd_rcp',
      'name' => $this->randomMachineName(),
      'farm' => [$farm],
      'property' => [$property],
    ]);
    $plan->save();

    // Go to the plan entity view display and confirm that the practices form
    // is present but not accessible yet.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Conservation Practices');
    $this->assertSession()->pageTextContains('Ecological site descriptions must be created before conservations practices can be added.');
    $this->assertSession()->pageTextNotContains('+ Add practice');
    $this->assertSession()->responseNotContains('Save conservation practices');

    // Create a land asset to represent an ecosite.
    /** @var \Drupal\asset\Entity\AssetInterface $ecosite */
    $ecosite = $this->assetStorage->create([
      'type' => 'land',
      'land_type' => 'rcd_row_crops',
      'name' => $this->randomMachineName(),
      'parent' => [$property],
      'farm' => [$farm],
    ]);
    $ecosite->save();

    // Reload the form and confirm that the site assessments form is
    // accessible.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextNotContains('Ecological site descriptions must be created before conservations practices can be added.');
    $this->assertSession()->pageTextContains('+ Add practice');
    $this->assertSession()->responseContains('Save conservation practices');

    // Fill in the form and submit it.
    $this->getSession()->getPage()->fillField('practices[add][ecosite]', $ecosite->id());
    $this->getSession()->getPage()->fillField('practices[add][practice]', 'other');
    $this->getSession()->getPage()->fillField('practices[add][notes]', 'Plant lots of sunflowers.');
    $this->getSession()->getPage()->fillField('practices[add][status]', 'implementing');
    $this->getSession()->getPage()->pressButton('Save conservation practices');

    // Confirm that a message was shown to the user.
    $this->assertSession()->pageTextContains('Practice implementation plans saved.');

    // Confirm that a practice implementation plan was created with all
    // expected details filled in, linked to the resource conservation plan.
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = $this->planStorage->load($plan->id());
    /** @var \Drupal\plan\Entity\PlanInterface[] $practice_plans */
    $practice_plans = $plan->get('practice_implementation_plan')->referencedEntities();
    $this->assertCount(1, $practice_plans);
    $practice_plan = reset($practice_plans);
    $this->assertEquals('rcd_practice_implementation', $practice_plan->bundle());
    $this->assertEquals($farm->id(), $practice_plan->get('farm')->referencedEntities()[0]->id());
    $this->assertEquals($ecosite->id(), $practice_plan->get('land')->referencedEntities()[0]->id());
    $this->assertEquals($ecosite->label() . ': Other', $practice_plan->label());
    $this->assertEquals('other', $practice_plan->get('rcd_practice')->value);
    $this->assertEquals('Plant lots of sunflowers.', $practice_plan->get('notes')->value);
    $this->assertEquals('implementing', $practice_plan->get('status')->value);

    // Confirm that the saved practice plan was added to the form, and fields
    // are pre-filled.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Practice implementation plan: ' . $ecosite->label() . ': Other');
    $this->assertSession()->fieldValueEquals('practices[' . $practice_plan->id() . '][ecosite][' . $ecosite->id() . ']', $ecosite->id());
    $this->assertSession()->fieldValueEquals('practices[' . $practice_plan->id() . '][practice]', 'other');
    $this->assertSession()->fieldValueEquals('practices[' . $practice_plan->id() . '][notes]', 'Plant lots of sunflowers.');
    $this->assertSession()->fieldValueEquals('practices[' . $practice_plan->id() . '][status]', 'implementing');

    // Edit the practice plan's fields and submit the form.
    $this->getSession()->getPage()->fillField('practices[' . $practice_plan->id() . '][practice]', 'cover_crop');
    $this->getSession()->getPage()->fillField('practices[' . $practice_plan->id() . '][notes]', 'Plant lots of tillage radish.');
    $this->getSession()->getPage()->fillField('practices[' . $practice_plan->id() . '][status]', 'review');
    $this->getSession()->getPage()->pressButton('Save conservation practices');
    $this->assertSession()->pageTextContains('Practice implementation plans saved.');
    $this->assertSession()->pageTextContains('Practice implementation plan: ' . $ecosite->label() . ': Cover Crop');

    // Confirm that the new values were saved to the practice plan.
    /** @var \Drupal\plan\Entity\PlanInterface $practice_plan */
    $practice_plan = $this->planStorage->load($practice_plan->id());
    $this->assertEquals($farm->id(), $practice_plan->get('farm')->referencedEntities()[0]->id());
    $this->assertEquals($ecosite->id(), $practice_plan->get('land')->referencedEntities()[0]->id());
    $this->assertEquals($ecosite->label() . ': Cover Crop', $practice_plan->label());
    $this->assertEquals('cover_crop', $practice_plan->get('rcd_practice')->value);
    $this->assertEquals('Plant lots of tillage radish.', $practice_plan->get('notes')->value);
    $this->assertEquals('review', $practice_plan->get('status')->value);
  }

  /**
   * Test document form.
   */
  public function doTestDocumentForm() {

    // Create an intake log with minimum data for these tests.
    /** @var \Drupal\log\Entity\LogInterface $intake */
    $intake = $this->logStorage->create([
      'type' => 'rcd_intake',
      'intake_stakeholder_name' => $this->randomMachineName(),
      'intake_stakeholder_type' => 'landowner',
      'intake_property_owner' => $this->randomMachineName(),
      'intake_property_acreage' => 100,
      'intake_stakeholder_group' => array_keys(RcdHelper::stakeholderGroups()),
      'intake_property_use' => array_keys(RcdHelper::landUses()),
      'intake_goals' => array_keys(RcdHelper::goals()),
      'intake_concerns' => array_keys(RcdHelper::concerns()),
    ]);
    $intake->save();

    // Create a farm organization.
    /** @var \Drupal\organization\Entity\OrganizationInterface $farm */
    $farm = $this->organizationStorage->create([
      'type' => 'farm',
      'name' => $this->randomMachineName(),
    ]);
    $farm->save();

    // Create a property land asset associated with the farm.
    /** @var \Drupal\asset\Entity\AssetInterface $property */
    $property = $this->assetStorage->create([
      'type' => 'land',
      'land_type' => 'rcd_property',
      'name' => $this->randomMachineName(),
      'farm' => [$farm],
      'notes' => $this->randomMachineName(),
      'rcd_apn' => [
        $this->randomMachineName(),
        $this->randomMachineName(),
      ],
    ]);
    $property->save();

    // Create a resource conservation plan associated with the farm, property,
    // and intake.
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = $this->planStorage->create([
      'type' => 'rcd_rcp',
      'name' => 'Test RCP',
      'farm' => [$farm],
      'property' => [$property],
      'intake' => [$intake],
    ]);
    $plan->save();

    // Create a land asset to represent an ecosite.
    /** @var \Drupal\asset\Entity\AssetInterface $ecosite */
    $ecosite = $this->assetStorage->create([
      'type' => 'land',
      'land_type' => 'rcd_row_crops',
      'name' => $this->randomMachineName(),
      'parent' => [$property],
      'farm' => [$farm],
    ]);
    $ecosite->save();

    // Create a practice implementation plan and link it to the resource
    // conservation plan.
    $practice_plan = $this->planStorage->create([
      'type' => 'rcd_practice_implementation',
      'name' => $this->randomMachineName(),
      'farm' => [$farm],
      'land' => [$ecosite],
      'rcd_practice' => 'other',
    ]);
    $practice_plan->save();
    $plan->set('practice_implementation_plan', [$practice_plan]);
    $plan->save();

    // Go to the plan entity view display and confirm that the document form is
    // present.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Prepare Document');
    $this->assertSession()->responseContains('Generate document from template');
    $this->assertSession()->responseContains('Save documents');

    // Confirm that no files exist.
    $files = $this->fileStorage->loadMultiple();
    $this->assertEmpty($files);

    // Click the "Generate" button and confirm that a file was created.
    $this->getSession()->getpage()->pressButton('Generate document from template');
    $this->assertSession()->pageTextContains('Document created:');
    /** @var \Drupal\file\FileInterface[] $files */
    $files = $this->fileStorage->loadMultiple();
    $this->assertCount(1, $files);
    $file = reset($files);
    $this->assertEquals('private://docs/test-rcp.docx', $file->getFileUri());

    // Confirm that submitting without a file shows a warning message.
    $this->getSession()->getpage()->pressButton('Save documents');
    $this->assertSession()->pageTextContains('No document uploaded.');

    // Get the real path to the file.
    $real_path = \Drupal::service('stream_wrapper_manager')->getViaUri($file->getFileUri())->realpath();

    // Load the file with PhpWord and confirm that it contains expected text.
    $doc = IOFactory::load($real_path);
    $this->assertDocPopulated($doc, $plan);

    // Upload the file back to the document form.
    $this->getSession()->getPage()->attachFileToField('files[document]', $real_path);
    $this->getSession()->getpage()->pressButton('Save documents');
    $this->assertSession()->pageTextContains('Document uploaded.');

    // Confirm that the file was saved and attached to plan.
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = $this->planStorage->load($plan->id());
    /** @var \Drupal\file\FileInterface[] $files */
    $files = $plan->get('file')->referencedEntities();
    $this->assertCount(1, $files);
    /** @var \Drupal\file\FileInterface $file */
    $file = reset($files);
    $this->assertEquals('private://rcp/' . date('Y-m-d') . '/test-rcp.docx', $file->getFileUri());
    $this->assertEquals(1, $file->get('status')->value);

    // Reload the plan and confirm that the file is displayed on the page.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($file->getFilename());

    // Mark the plan as done.
    $plan->set('status', 'done');
    $plan->save();

    // Confirm that files can not be uploaded.
    $this->getSession()->getPage()->attachFileToField('files[document]', $real_path);
    $this->getSession()->getpage()->pressButton('Save documents');
    $this->assertSession()->pageTextContains('Documents can only be uploaded to plans that are in the planning stage. This plan has been marked as done.');
  }

  /**
   * Test status form.
   */
  public function doTestStatusForm() {

    // Create a farm organization.
    /** @var \Drupal\organization\Entity\OrganizationInterface $farm */
    $farm = $this->organizationStorage->create([
      'type' => 'farm',
      'name' => $this->randomMachineName(),
    ]);
    $farm->save();

    // Create a resource conservation plan associated with the farm.
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = $this->planStorage->create([
      'type' => 'rcd_rcp',
      'name' => 'Test RCP',
      'farm' => [$farm],
    ]);
    $plan->save();

    // Define button text.
    $done_button = 'Mark plan as done';
    $abandon_button = 'Abandon plan';
    $planning_button = 'Revert status to planning';

    // Go to the plan entity view display and confirm that the abandon button
    // is visible, but done and planning buttons are not.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->responseContains('Planning Status');
    $this->assertSession()->responseContains('Comments');
    $this->assertSession()->responseContains($abandon_button);
    $this->assertSession()->responseNotContains($done_button);
    $this->assertSession()->responseNotContains($planning_button);

    // Upload an existing file to the document form.
    /** @var \Drupal\file\FileInterface[] $files */
    $files = $this->fileStorage->loadMultiple();
    $this->assertNotEmpty($files);
    $file = reset($files);
    $real_path = \Drupal::service('stream_wrapper_manager')->getViaUri($file->getFileUri())->realpath();
    $this->getSession()->getPage()->attachFileToField('files[document]', $real_path);
    $this->getSession()->getpage()->pressButton('Save documents');
    $this->assertSession()->pageTextContains('Document uploaded.');

    // Reload the plan and confirm that done and abandon buttons are visible,
    // but the planning button is not.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->responseContains($abandon_button);
    $this->assertSession()->responseContains($done_button);
    $this->assertSession()->responseNotContains($planning_button);

    // Click the done button, confirm that a message was shown to the user, the
    // plan status was changed, and a revision log message was added.
    $this->getSession()->getPage()->pressButton($done_button);
    $this->assertSession()->pageTextContains('Plan status changed to "done".');
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = $this->planStorage->load($plan->id());
    $this->assertEquals('done', $plan->get('status')->value);
    $this->assertEquals('Plan status changed to "done".', $plan->getRevisionLogMessage());

    // Reload the plan and confirm that done and abandon buttons are not
    // visible, but the planning button is.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->responseNotContains($abandon_button);
    $this->assertSession()->responseNotContains($done_button);
    $this->assertSession()->responseContains($planning_button);

    // Click the planning button and confirm that validation failed.
    $this->getSession()->getPage()->pressButton($planning_button);
    $this->assertSession()->pageTextContains('An explanation for this status change must be provided.');

    // Add a comment, click the planning button, and confirm that a message was
    // shown to the user, the plan status changed, and a log revision message
    // was added.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->getSession()->getPage()->fillField('status[comments]', 'Lorem ipsum.');
    $this->getSession()->getPage()->pressButton($planning_button);
    $this->assertSession()->pageTextContains('Plan status changed to "planning".');
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = $this->planStorage->load($plan->id());
    $this->assertEquals('planning', $plan->get('status')->value);
    $this->assertEquals('Plan status changed to "planning". Lorem ipsum.', $plan->getRevisionLogMessage());

    // Click the abandon button and confirm that validation failed.
    $this->getSession()->getPage()->pressButton($abandon_button);
    $this->assertSession()->pageTextContains('An explanation for this status change must be provided.');

    // Add a comment, click the abandon button, and confirm that a message was
    // shown to the user, the plan status changed, and a log revision message
    // was added.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->getSession()->getPage()->fillField('status[comments]', 'Dolor sit amet.');
    $this->getSession()->getPage()->pressButton($abandon_button);
    $this->assertSession()->pageTextContains('Plan status changed to "abandoned".');
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = $this->planStorage->load($plan->id());
    $this->assertEquals('abandoned', $plan->get('status')->value);
    $this->assertEquals('Plan status changed to "abandoned". Dolor sit amet.', $plan->getRevisionLogMessage());

    // Reload the plan and confirm that done and abandon buttons are not
    // visible, but the planning button is.
    $this->drupalGet('/plan/' . $plan->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->responseNotContains($abandon_button);
    $this->assertSession()->responseNotContains($done_button);
    $this->assertSession()->responseContains($planning_button);
  }

  /**
   * Check that a document contains all expected text.
   *
   * @param \PhpOffice\PhpWord\PhpWord $doc
   *   The document to search.
   * @param \Drupal\plan\Entity\PlanInterface $plan
   *   The plan entity.
   */
  protected function assertDocPopulated(PhpWord $doc, PlanInterface $plan) {

    // Confirm that there are no placeholders remaining in the document.
    $this->assertDocNotContainsText($doc, '${');

    // Test that expected strings do exist.
    $expected_strings = [
      $plan->get('farm')->referencedEntities()[0]->label(),
    ];
    foreach ($expected_strings as $string) {
      $this->assertDocContainsText($doc, $string);
    }
  }

}
