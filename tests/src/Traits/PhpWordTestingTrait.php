<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_sli\Traits;

use PhpOffice\PhpWord\PhpWord;

/**
 * Provides methods for testing PhpWord documents.
 */
trait PhpWordTestingTrait {

  /**
   * Assert that a PhpWord document contains a text string.
   *
   * @param \PhpOffice\PhpWord\PhpWord $doc
   *   The document to search.
   * @param string $search
   *   The text to search for.
   */
  protected function assertDocContainsText(PhpWord $doc, string $search): void {
    $this->assertTrue($this->searchDoc($doc, $search), 'Search for string in document: ' . $search);
  }

  /**
   * Assert that a PhpWord document does not contain a text string.
   *
   * @param \PhpOffice\PhpWord\PhpWord $doc
   *   The document to search.
   * @param string $search
   *   The text to search for.
   */
  protected function assertDocNotContainsText(PhpWord $doc, string $search): void {
    $this->assertFalse($this->searchDoc($doc, $search), 'Search for string in document: ' . $search);
  }

  /**
   * Search a PhpWord document for a text string.
   *
   * @param \PhpOffice\PhpWord\PhpWord $doc
   *   The document to search.
   * @param string $search
   *   The text to search for.
   *
   * @return bool
   *   Returns TRUE if the search string was found, FALSE otherwise.
   */
  protected function searchDoc(PhpWord $doc, string $search): bool {
    $found = FALSE;
    foreach ($doc->getSections() as $section) {
      foreach ($section->getElements() as $element) {
        if (method_exists($element, 'getElements')) {
          foreach ($element->getElements() as $textElement) {
            if (method_exists($textElement, 'getText')) {
              $text = $textElement->getText();
              if (stripos($text, $search) !== FALSE) {
                $found = TRUE;
                break 3;
              }
            }
          }
        }
      }
    }
    return $found;
  }

}
