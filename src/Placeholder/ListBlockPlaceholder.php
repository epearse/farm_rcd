<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Placeholder;

/**
 * List of blocks placeholder.
 *
 * This populates a repeating block of placeholders from an array of arrays
 * of other placeholders. This can be used recursively to create nested
 * repeating blocks of placeholders.
 *
 * The placeholder must look like this inside the template:
 *
 * ${search}
 * ${placeholder1}
 * ${placeholder2}
 * ${placeholder3}
 * ...
 * ${/search}
 *
 * Change "search" to the desired search string (same as the $search
 * attribute), and include one or more additional placeholders within the
 * block. Each of these will be replaced recursively from the placeholders
 * defined in the $replace attribute array, which should be an array of arrays
 * of placeholder objects.
 *
 * For example:
 *
 * $replace = [
 *   [
 *     new StringPlaceholder('placeholder1-item1', '...'),
 *     new ListStringPlaceholder('placeholder2-item1', [...]),
 *     new ListBlockPlaceholder('placeholder3-item1', [...]),
 *   ],
 *   [
 *     new StringPlaceholder('placeholder1-item2', '...'),
 *     new ListStringPlaceholder('placeholder2-item2', [...]),
 *     new ListBlockPlaceholder('placeholder3-item2', [...]),
 *   ],
 * ];
 *
 * @todo This doesn't make sense... I'm not going to be creating $search strings like "placeholderX-itemY" in all the sub-items, am I? Maybe I am... ?
 */
class ListBlockPlaceholder extends PlaceholderBase {

  public function __construct(
    string $search,
    public array $replace,
  ) {
    parent::__construct($search);
  }

}
