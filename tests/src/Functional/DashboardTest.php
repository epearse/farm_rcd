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
    $this->assertSession()->pageTextNotContains('Upcoming tasks');
    $this->assertSession()->pageTextNotContains('Late tasks');
    $this->assertSession()->pageTextNotContains('Metrics');

    // Confirm that the "Farms/Ranches" block was added.
    $this->assertSession()->pageTextContains('Farms/Ranches');
    $this->assertSession()->pageTextContains('Search for farms/ranches by name.');
    $this->assertSession()->pageTextContains('View all farms/ranches');

    // Confirm that the "Pending intakes" block was added.
    $this->assertSession()->pageTextContains('Pending intakes');
    $this->assertSession()->pageTextContains('No pending intakes.');
    $this->assertSession()->pageTextContains('View all intakes');
  }

}
