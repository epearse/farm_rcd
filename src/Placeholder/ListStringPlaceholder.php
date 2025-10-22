<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Placeholder;

/**
 * List of strings placeholder plugin.
 *
 * This populates a bulleted list in the document from an array of strings.
 *
 * The placeholder must look like this inside the template:
 *
 * ${search}
 *   - ${item}
 * ${/search}
 *
 * Change "search" to the desired search string (same as the $search
 * attribute), and ensure there is a single bullet point with "${item}" in it.
 * This item will be cloned and replaced with each string in the $replace
 * attribute array.
 */
class ListStringPlaceholder extends PlaceholderBase {

  public function __construct(
    string $search,
    public array $replace,
  ) {
    parent::__construct($search);
  }

}
