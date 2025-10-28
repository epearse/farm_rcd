<?php

declare(strict_types=1);

namespace Drupal\farm_sli_test\Bundle;

use Drupal\farm_sli\Bundle\PlanDocumentTemplateInterface;
use Drupal\farm_sli\Placeholder\ListBlockPlaceholder;
use Drupal\farm_sli\Placeholder\ListStringPlaceholder;
use Drupal\farm_sli\Placeholder\StringPlaceholder;
use Drupal\plan\Entity\Plan;

/**
 * Test plan document template methods.
 */
class TestPlan extends Plan implements PlanDocumentTemplateInterface {

  /**
   * {@inheritdoc}
   */
  public function module(): string {
    return 'farm_sli_test';
  }

  /**
   * {@inheritdoc}
   */
  public function templateFilename(): string {
    return 'template.docx';
  }

  /**
   * {@inheritdoc}
   */
  public function placeholders(): array {
    $placeholders = [];

    // Simple string.
    $placeholders[] = new StringPlaceholder('string', 'Replaced String');

    // Simple bulleted list of string.
    $placeholders[] = new ListStringPlaceholder('list', [
      'Replaced List Item 1',
      'Replaced List Item 2',
    ]);

    // Strings inside a repeating block.
    $placeholders[] = new ListBlockPlaceholder('block_strings', [
      [
        new StringPlaceholder('block_string', 'Replaced Block String 1'),
      ],
      [
        new StringPlaceholder('block_string', 'Replaced Block String 2'),
      ]
    ]);

    // Lists inside a repeating block.
    $placeholders[] = new ListBlockPlaceholder('block_lists', [
      [
        new ListStringPlaceholder('block_list', [
          'Replaced Block List Item 1',
          'Replaced Block List Item 2',
        ]),
      ],
      [
        new ListStringPlaceholder('block_list', [
          'Replaced Block List Item 3',
          'Replaced Block List Item 4',
        ]),
      ]
    ]);

    // Repeating blocks inside a repeating block.
    $placeholders[] = new ListBlockPlaceholder('block_blocks', [
      [
        new ListBlockPlaceholder('block_block', [
          [
            new StringPlaceholder('block_block_string', 'Replaced Nested Block String 1'),
            new ListStringPlaceholder('block_block_list', [
              'Replaced Nested Block List Item 1',
              'Replaced Nested Block List Item 2',
            ]),
          ],
          [
            new StringPlaceholder('block_block_string', 'Replaced Nested Block String 2'),
            new ListStringPlaceholder('block_block_list', [
              'Replaced Nested Block List Item 3',
              'Replaced Nested Block List Item 4',
            ]),
          ],
        ]),
      ],
      [
        new ListBlockPlaceholder('block_block', [
          [
            new StringPlaceholder('block_block_string', 'Replaced Nested Block String 3'),
            new ListStringPlaceholder('block_block_list', [
              'Replaced Nested Block List Item 5',
              'Replaced Nested Block List Item 6',
            ]),
          ],
          [
            new StringPlaceholder('block_block_string', 'Replaced Nested Block String 4'),
            new ListStringPlaceholder('block_block_list', [
              'Replaced Nested Block List Item 7',
              'Replaced Nested Block List Item 8',
            ]),
          ],
        ]),
      ]
    ]);

    return $placeholders;
  }

}
