<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_rcd\Traits;

use PhpOffice\PhpWord\Element\AbstractElement;
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
   * @param string|null $name
   *   An optional name for the string.
   */
  protected function assertDocContainsText(PhpWord $doc, string $search, ?string $name = NULL): void {
    $message = 'Search for string in document: ' . $search;
    if (!is_null($name)) {
      $message .= ' (' . $name . ')';
    }
    $this->assertTrue($this->searchDoc($doc, $search), $message);
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
    foreach ($doc->getSections() as $section) {
      foreach ($section->getElements() as $element) {
        if ($this->searchDocElement($element, $search)) {
          return TRUE;
        }
        if (method_exists($element, 'getElements')) {
          foreach ($element->getElements() as $sub_element) {
            if ($this->searchDocElement($sub_element, $search)) {
              return TRUE;
            }
          }
        }
      }
    }
    return FALSE;
  }

  /**
   * Search a PhpWord element for a text string.
   *
   * @param \PhpOffice\PhpWord\Element\AbstractElement $element
   *   The element to search.
   * @param string $search
   *   The text to search for.
   *
   * @return bool
   *   Returns TRUE if the search string was found, FALSE otherwise.
   */
  protected function searchDocElement(AbstractElement $element, string $search): bool {
    if (method_exists($element, 'getText')) {
      $text = $element->getText();
      if (stripos($text, $search) !== FALSE) {
        return TRUE;
      }
    }
    return FALSE;
  }

}
