<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_sli\Functional;

use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Base class for SLI functional tests.
 */
class SliTestBase extends FarmBrowserTestBase {

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

    // Create and login a user with the Manager role.
    $this->user = $this->createUser();
    $this->user->addRole('farm_manager');
    $this->drupalLogin($this->user);
  }

}
