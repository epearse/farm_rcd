<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_sli\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests for the document generator service.
 */
class DocumentGeneratorTest extends KernelTestBase {

  /**
   * Document generator service.
   *
   * @var \Drupal\farm_sli\DocumentGeneratorInterface
   */
  protected $documentGenerator;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'entity',
    'farm_entity',
    'farm_farm',
    'farm_field',
    'farm_land',
    'farm_log',
    'farm_map',
    'farm_sli',
    'file',
    'log',
    'options',
    'organization',
    'plan',
    'state_machine',
    'system',
    'taxonomy',
    'text',
    'user',
    'views',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('file');
    $this->installEntitySchema('organization');
    $this->installEntitySchema('plan');
    $this->installConfig([
      'farm_farm',
      'farm_sli',
    ]);
    $this->documentGenerator = \Drupal::service('sli.document.generator');
  }

  /**
   * Test the document generator service.
   */
  public function testDocumentGenerator() {

    // Create a farm organization.
    $farm = \Drupal::entityTypeManager()->getStorage('organization')->create([
      'type' => 'farm',
      'name' => $this->randomMachineName(),
    ]);
    $farm->save();

    // Create a resource conservation plan.
    /** @var \Drupal\plan\Entity\PlanInterface $plan */
    $plan = \Drupal::entityTypeManager()->getStorage('plan')->create([
      'type' => 'sli_rcp',
      'name' => $this->randomMachineName(),
      'farm' => [$farm],
    ]);
    $plan->save();

    // Generate a document from the plan.
    $file = $this->documentGenerator->generate($plan, 'test-filename.docx');
    $default_schema = \Drupal::configFactory()->get('system.file')->get('default_scheme');
    $this->assertEquals($default_schema . '://docs/test-filename.docx', $file->getFileUri());
    $this->assertEquals('application/vnd.openxmlformats-officedocument.wordprocessingml.document', $file->getMimeType());
  }

}
