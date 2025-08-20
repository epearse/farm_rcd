<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_sli\Functional;

use Drupal\farm_sli\SliAllowedValues;

/**
 * Tests the intake form functionality.
 */
class IntakeFormTest extends SliTestBase {

  /**
   * Define form fields and example data.
   *
   * @param int|null $step
   *   The step to filter by.
   *
   * @return array
   *   Returns an array of fields and data for each step.
   */
  protected function fieldData($step = NULL) {
    $field_data = [
      1 => [
        'edit-stakeholder-personal-name' => 'Jane Doe',
        'edit-stakeholder-personal-email' => 'jane@example.com',
        'edit-stakeholder-personal-phone' => '555-5555',
        'edit-stakeholder-address-street' => '123 Fake Street',
        'edit-stakeholder-address-city' => 'Fakeville',
        'edit-stakeholder-address-zip' => '123456',
        'edit-stakeholder-address-type' => 'landowner',
        'edit-stakeholder-stakeholder-own-or-lease-own' => 'own',
        'edit-stakeholder-stakeholder-group-beginning' => TRUE,
        'edit-stakeholder-stakeholder-group-female' => TRUE,
        'edit-stakeholder-stakeholder-group-veteran' => TRUE,
        'edit-stakeholder-stakeholder-group-black' => TRUE,
        'edit-stakeholder-stakeholder-group-native' => TRUE,
        'edit-stakeholder-stakeholder-group-hispanic' => TRUE,
        'edit-stakeholder-stakeholder-group-asian' => TRUE,
        'edit-stakeholder-stakeholder-group-pacific' => TRUE,
        'edit-stakeholder-stakeholder-group-na' => TRUE,
        'edit-stakeholder-stakeholder-group-optout' => TRUE,
        'edit-stakeholder-stakeholder-share-rcds-yes' => 'yes',
      ],
      2 => [
        'edit-property-info-farm-name' => 'Sunflower Farm',
        'edit-property-info-acreage' => 100,
        'edit-property-info-has-address-yes' => 'yes',
        'edit-property-info-street' => '124 Fake Street',
        'edit-property-info-city' => 'New Fakeville',
        'edit-property-info-zip' => '123457',
        'edit-property-land-use-land-use-grazing' => TRUE,
        'edit-property-land-use-land-use-vineyards' => TRUE,
        'edit-property-land-use-land-use-orchards' => TRUE,
        'edit-property-land-use-land-use-rowcrops' => TRUE,
        'edit-property-land-use-land-use-natural' => TRUE,
        'edit-property-land-use-land-use-other' => TRUE,
        'edit-property-land-use-grazing-acreage' => 10,
        'edit-property-land-use-vineyards-acreage' => 20,
        'edit-property-land-use-orchards-acreage' => 30,
        'edit-property-land-use-rowcrops-acreage' => 40,
        'edit-property-land-use-natural-acreage' => 50,
        'edit-property-land-use-other' => 'Alpacas',
        'edit-property-land-use-other-acreage' => 60,
        'edit-property-land-use-crop-type' => 'Bananas',
      ],
      3 => [
        'edit-goals-goals-goals-succession' => TRUE,
        'edit-goals-goals-goals-reduce-debt' => TRUE,
        'edit-goals-goals-goals-expand-enterprises' => TRUE,
        'edit-goals-goals-goals-new-enterprises' => TRUE,
        'edit-goals-goals-goals-profitability' => TRUE,
        'edit-goals-goals-goals-reduce-costs' => TRUE,
        'edit-goals-goals-goals-property' => TRUE,
        'edit-goals-goals-goals-brand' => TRUE,
        'edit-goals-goals-goals-sustainability' => TRUE,
        'edit-goals-goals-goals-other' => TRUE,
        'edit-goals-goals-other' => 'I have big plans!',
        'edit-goals-goals-comments' => 'I need help prioritizing my goals.',
      ],
      4 => [
        'edit-interests-interests-resource-interests-rangeland-erosion' => TRUE,
        'edit-interests-interests-resource-interests-cropland-erosion' => TRUE,
        'edit-interests-interests-resource-interests-roads' => TRUE,
        'edit-interests-interests-resource-interests-bank-erosion' => TRUE,
        'edit-interests-interests-resource-interests-cover' => TRUE,
        'edit-interests-interests-resource-interests-livestock-concentration' => TRUE,
        'edit-interests-interests-resource-interests-runoff' => TRUE,
        'edit-interests-interests-resource-interests-wildfire' => TRUE,
        'edit-interests-interests-resource-interests-plants' => TRUE,
        'edit-interests-interests-resource-interests-wildlife' => TRUE,
        'edit-interests-interests-resource-interests-weeds' => TRUE,
        'edit-interests-interests-resource-interests-predators' => TRUE,
        'edit-interests-interests-resource-interests-water-regulations' => TRUE,
        'edit-interests-interests-resource-interests-water-capacity' => TRUE,
        'edit-interests-interests-resource-interests-alt-water' => TRUE,
        'edit-interests-interests-resource-interests-climate-resilience' => TRUE,
        'edit-interests-interests-resource-interests-carbon-farming' => TRUE,
        'edit-interests-interests-comments' => 'I may have too many interests.',
      ],
    ];
    if (!is_null($step)) {
      return $field_data[$step];
    }
    return $field_data;
  }

  /**
   * Test intake form.
   */
  public function testIntakeForm() {

    // Log out the user.
    $this->drupalLogout();

    // Confirm that the form is available to anonymous users.
    $this->drupalGet('/intake');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Intake Form');

    // Log in the user.
    $this->drupalLogin($this->user);

    // Confirm that the form is available to the authenticated user.
    $this->drupalGet('/intake');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Intake Form');

    // Confirm that intro text is present.
    $this->assertSession()->pageTextContains('Please complete this form to express interest in adopting sustainable practices on your land.');

    // Confirm that the Next button is present, but not the Back button.
    $this->assertSession()->responseContains('value="Next"');
    $this->assertSession()->responseNotContains('value="Back"');

    // Press the Next button.
    $this->getSession()->getPage()->pressButton('Next');

    // Confirm that both the Next and Back buttons are present.
    $this->assertSession()->responseContains('value="Next"');
    $this->assertSession()->responseContains('value="Back"');

    // Confirm that the step 1 information is present.
    $this->assertSession()->pageTextContains('Stakeholder information');
    $this->assertSession()->pageTextContains('Step 1 of 4');
    $this->assertSession()->pageTextContains('25%');

    // Fill in all step 1 fields.
    foreach ($this->fieldData(1) as $id => $value) {
      $this->getSession()->getPage()->fillField($id, $value);
    }

    // Press the Back button.
    $this->getSession()->getPage()->pressButton('Back');

    // Confirm that intro text is present.
    $this->assertSession()->pageTextContains('Please complete this form to express interest in adopting sustainable practices on your land.');

    // Press the Next button.
    $this->getSession()->getPage()->pressButton('Next');

    // Confirm that all step 1 field values were preserved.
    foreach ($this->fieldData(1) as $id => $value) {
      $this->assertEquals($value, $this->getSession()->getPage()->findField($id)->getValue());
    }

    // Press the Next button.
    $this->getSession()->getPage()->pressButton('Next');

    // Confirm that the step 2 information is present.
    $this->assertSession()->pageTextContains('Property description');
    $this->assertSession()->pageTextContains('Step 2 of 4');
    $this->assertSession()->pageTextContains('50%');

    // Fill in all step 2 fields.
    foreach ($this->fieldData(2) as $id => $value) {
      $this->getSession()->getPage()->fillField($id, $value);
    }

    // Press the Back button.
    $this->getSession()->getPage()->pressButton('Back');

    // Confirm that we're back on step 1.
    $this->assertSession()->pageTextContains('Step 1');

    // Confirm that all step 1 field values were preserved.
    foreach ($this->fieldData(1) as $id => $value) {
      $this->assertEquals($value, $this->getSession()->getPage()->findField($id)->getValue());
    }

    // Press the Next button.
    $this->getSession()->getPage()->pressButton('Next');

    // Confirm that we're back on step 2.
    $this->assertSession()->pageTextContains('Step 2');

    // Confirm that all step 2 field values were preserved.
    foreach ($this->fieldData(2) as $id => $value) {
      $this->assertEquals($value, $this->getSession()->getPage()->findField($id)->getValue());
    }

    // Press the Next button.
    $this->getSession()->getPage()->pressButton('Next');

    // Confirm that the step 3 information is present.
    $this->assertSession()->pageTextContains('Stakeholder goals');
    $this->assertSession()->pageTextContains('Step 3 of 4');
    $this->assertSession()->pageTextContains('75%');

    // Fill in all step 3 fields.
    foreach ($this->fieldData(3) as $id => $value) {
      $this->getSession()->getPage()->fillField($id, $value);
    }

    // Press the Back button.
    $this->getSession()->getPage()->pressButton('Back');

    // Confirm that we're back on step 2.
    $this->assertSession()->pageTextContains('Step 2');

    // Confirm that all step 2 field values were preserved.
    foreach ($this->fieldData(2) as $id => $value) {
      $this->assertEquals($value, $this->getSession()->getPage()->findField($id)->getValue());
    }

    // Press the Next button.
    $this->getSession()->getPage()->pressButton('Next');

    // Confirm that we're back on step 3.
    $this->assertSession()->pageTextContains('Step 3');

    // Confirm that all step 3 field values were preserved.
    foreach ($this->fieldData(3) as $id => $value) {
      $this->assertEquals($value, $this->getSession()->getPage()->findField($id)->getValue());
    }

    // Press the Next button.
    $this->getSession()->getPage()->pressButton('Next');

    // Confirm that the step 4 information is present.
    $this->assertSession()->pageTextContains('Resource interests');
    $this->assertSession()->pageTextContains('Step 4 of 4');
    $this->assertSession()->pageTextContains('100%');

    // Fill in all step 4 fields.
    foreach ($this->fieldData(4) as $id => $value) {
      $this->getSession()->getPage()->fillField($id, $value);
    }

    // Press the Back button.
    $this->getSession()->getPage()->pressButton('Back');

    // Confirm that we're back on step 3.
    $this->assertSession()->pageTextContains('Step 3');

    // Confirm that all step 3 field values were preserved.
    foreach ($this->fieldData(3) as $id => $value) {
      $this->assertEquals($value, $this->getSession()->getPage()->findField($id)->getValue());
    }

    // Press the Next button.
    $this->getSession()->getPage()->pressButton('Next');

    // Confirm that we're back on step 4.
    $this->assertSession()->pageTextContains('Step 4');

    // Confirm that all step 4 field values were preserved.
    foreach ($this->fieldData(4) as $id => $value) {
      $this->assertEquals($value, $this->getSession()->getPage()->findField($id)->getValue());
    }

    // Press the Next button.
    $this->getSession()->getPage()->pressButton('Next');

    // Confirm that the review step information is present.
    $this->assertSession()->pageTextContains('Review');
    $this->assertSession()->pageTextContains('100%');

    // Confirm that some of the log data is previewed.
    $this->assertSession()->pageTextContains('Stakeholder name');
    $this->assertSession()->pageTextContains('Jane Doe');

    // Press the Back button.
    $this->getSession()->getPage()->pressButton('Back');

    // Confirm that we're back on step 4.
    $this->assertSession()->pageTextContains('Step 4');

    // Confirm that all step 4 field values were preserved.
    foreach ($this->fieldData(4) as $id => $value) {
      $this->assertEquals($value, $this->getSession()->getPage()->findField($id)->getValue());
    }

    // Press the Next button.
    $this->getSession()->getPage()->pressButton('Next');

    // Confirm that we're back on the review step.
    $this->assertSession()->pageTextContains('Review');

    // Submit the form.
    $this->getSession()->getPage()->pressButton('Submit');

    // Confirm that an intake log was created.
    $logs = \Drupal::entityTypeManager()->getStorage('log')->loadMultiple();
    $this->assertCount(1, $logs);
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = reset($logs);

    // Confirm that the log type is sli_intake.
    $this->assertEquals('sli_intake', $log->bundle());

    // Confirm that the log status is pending.
    $this->assertEquals('pending', $log->get('status')->value);

    // Confirm all the log fields were saved correctly.
    $field_data = $this->fieldData();
    $expected = [
      'intake_stakeholder_name' => $field_data[1]['edit-stakeholder-personal-name'],
      'intake_stakeholder_email' => $field_data[1]['edit-stakeholder-personal-email'],
      'intake_stakeholder_phone' => $field_data[1]['edit-stakeholder-personal-phone'],
      'intake_stakeholder_street' => $field_data[1]['edit-stakeholder-address-street'],
      'intake_stakeholder_city' => $field_data[1]['edit-stakeholder-address-city'],
      'intake_stakeholder_zip' => $field_data[1]['edit-stakeholder-address-zip'],
      'intake_stakeholder_type' => $field_data[1]['edit-stakeholder-address-type'],
      'intake_stakeholder_own_or_lease' => $field_data[1]['edit-stakeholder-stakeholder-own-or-lease-own'],
      'intake_stakeholder_lease_exp' => NULL,
      'intake_property_owner' => '',
      'intake_stakeholder_group' => array_keys(SliAllowedValues::stakeholderGroups()),
      'intake_farm_name' => $field_data[2]['edit-property-info-farm-name'],
      'intake_property_acreage' => $field_data[2]['edit-property-info-acreage'],
      'intake_property_street' => $field_data[2]['edit-property-info-street'],
      'intake_property_city' => $field_data[2]['edit-property-info-city'],
      'intake_property_zip' => $field_data[2]['edit-property-info-zip'],
      'intake_property_parcel_gps' => '',
      'intake_property_use' => array_keys(SliAllowedValues::landUses()),
      'intake_property_use_grazing_ac' => $field_data[2]['edit-property-land-use-grazing-acreage'],
      'intake_property_use_vineyard_ac' => $field_data[2]['edit-property-land-use-vineyards-acreage'],
      'intake_property_use_orchard_ac' => $field_data[2]['edit-property-land-use-orchards-acreage'],
      'intake_property_use_rowcrop_ac' => $field_data[2]['edit-property-land-use-rowcrops-acreage'],
      'intake_property_use_natural_ac' => $field_data[2]['edit-property-land-use-natural-acreage'],
      'intake_property_use_other' => $field_data[2]['edit-property-land-use-other'],
      'intake_property_use_other_ac' => $field_data[2]['edit-property-land-use-other-acreage'],
      'intake_property_use_crop_type' => $field_data[2]['edit-property-land-use-crop-type'],
      'intake_goals' => array_keys(SliAllowedValues::goals()),
      'intake_goals_other' => $field_data[3]['edit-goals-goals-other'],
      'intake_goals_comments' => $field_data[3]['edit-goals-goals-comments'],
      'intake_interests' => array_keys(SliAllowedValues::interests()),
      'intake_interests_comments' => $field_data[4]['edit-interests-interests-comments'],
      'intake_rcd_sharing_allowed' => TRUE,
    ];
    foreach ($expected as $field => $value) {
      if (is_array($value)) {
        $field_values = $log->get($field)->getValue();
        $this->assertEquals(count($value), count($field_values));
        foreach ($field_values as $i => $field_value) {
          $this->assertEquals($value[$i], $field_value['value']);
        }
      }
      else {
        if (is_null($value)) {
          $this->assertTrue($log->get($field)->isEmpty());
        }
        else {
          $this->assertEquals($value, $log->get($field)->value);
        }
      }
    }

    // Test fields that were not covered by the first submission.
    $field_data = $this->fieldData();
    $field_data[1]['edit-stakeholder-stakeholder-own-or-lease-own'] = 'lease';
    $field_data[1]['edit-stakeholder-stakeholder-lease-expiration'] = '08/19/2025';
    $field_data[1]['edit-stakeholder-stakeholder-property-owner'] = 'My sister';
    $field_data[1]['edit-stakeholder-stakeholder-share-rcds-yes'] = 'no';
    $field_data[2]['edit-property-info-has-address-yes'] = 'no';
    $field_data[2]['edit-property-info-parcel-gps'] = '1234567890';
    $this->drupalGet('/intake');
    $this->getSession()->getPage()->pressButton('Next');
    foreach ([1, 2, 3, 4] as $step) {
      foreach ($field_data[$step] as $id => $value) {
        $this->getSession()->getPage()->fillField($id, $value);
      }
      $this->getSession()->getPage()->pressButton('Next');
    }
    $this->getSession()->getPage()->pressButton('Submit');
    $logs = \Drupal::entityTypeManager()->getStorage('log')->loadMultiple();
    $this->assertCount(2, $logs);
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = end($logs);
    $this->assertEquals('lease', $log->get('intake_stakeholder_own_or_lease')->value);
    $this->assertEquals(1755525600, $log->get('intake_stakeholder_lease_exp')->value);
    $this->assertEquals('My sister', $log->get('intake_property_owner')->value);
    $this->assertEquals(FALSE, $log->get('intake_rcd_sharing_allowed')->value);
    $this->assertEquals('1234567890', $log->get('intake_property_parcel_gps')->value);
  }

}
