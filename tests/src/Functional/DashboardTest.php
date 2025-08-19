<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_sli\Functional;

use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Tests the SLI dashboard functionality.
 */
class DashboardTest extends FarmBrowserTestBase {

  /**
   * Test user.
   *
   * @var \Drupal\user\Entity\User|bool
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_sli',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create and login a user with necessary permissions.
    $this->user = $this->createUser(['access farm dashboard']);
    $this->drupalLogin($this->user);
  }

  /**
   * Test SLI dashboard panes.
   */
  public function testSliDashboard() {

    // Confirm that dashboard loads.
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);

    // Confirm that the Upcoming/Late Tasks blocks were removed.
    $this->assertSession()->pageTextNotContains('Upcoming tasks');
    $this->assertSession()->pageTextNotContains('Late tasks');

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
