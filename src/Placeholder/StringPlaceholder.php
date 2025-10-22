<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Placeholder;

/**
 * String placeholder.
 *
 * This replaces a single placeholder with a string.
 *
 * The placeholder must look like this inside the template:
 *
 * ${search}
 *
 * Change "search" to the desired search string (same as the $search
 * attribute). This will be replaced with the $replace attribute string.
 */
class StringPlaceholder extends PlaceholderBase {

  public function __construct(
    string $search,
    public string $replace,
  ) {
    parent::__construct($search);
  }

}
