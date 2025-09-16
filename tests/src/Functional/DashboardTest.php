<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_sli\Functional;

/**
 * Tests the SLI dashboard functionality.
 */
class DashboardTest extends SliTestBase {

  /**
   * Test SLI dashboard panes.
   */
  public function testSliDashboard() {

    // Confirm that dashboard loads.
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);

    // Confirm that the Upcoming/Late Tasks/Metrics blocks were removed.
    $this->assertSession()->responseNotContains('farm-map-dashboard');
    $this->assertSession()->pageTextNotContains('Upcoming tasks');
    $this->assertSession()->pageTextNotContains('Late tasks');
    $this->assertSession()->pageTextNotContains('Metrics');

    // Confirm that the "Add Asset/Log/Organization/Plan" buttons were removed.
    $this->assertSession()->pageTextNotContains('Add Asset');
    $this->assertSession()->pageTextNotContains('Add Log');
    $this->assertSession()->pageTextNotContains('Add Organization');
    $this->assertSession()->pageTextNotContains('Add Plan');

    // Confirm that the "Add Intake" button was added.
    $this->assertSession()->pageTextContains('Add Intake');

    // Confirm that the "Properties" block was added.
    $this->assertSession()->pageTextContains('Properties');
    $this->assertSession()->pageTextContains('Search for property by name.');
    $this->assertSession()->pageTextContains('View all properties');

    // Confirm that the "Pending intakes" block was added.
    $this->assertSession()->pageTextContains('Pending intakes');
    $this->assertSession()->pageTextContains('No pending intakes.');
    $this->assertSession()->pageTextContains('View all intakes');
  }

}
