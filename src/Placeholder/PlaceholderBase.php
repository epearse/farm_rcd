<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Placeholder;

/**
 * Placeholder base.
 */
class PlaceholderBase implements PlaceholderInterface {

  public function __construct(
    public string $search,
  ) {}

}
