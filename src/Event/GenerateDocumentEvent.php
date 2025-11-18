<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Event;

use Drupal\Component\EventDispatcher\Event;

/**
 * An event that is dispatched when a document is generated.
 */
class GenerateDocumentEvent extends Event {

  const EVENT_NAME = 'generate_document_event';

  public function __construct(
    protected array $context,
    protected array $placeholders = [],
  ) {}

  /**
   * Get context.
   *
   * @return array
   *   Returns the context array.
   */
  public function getContext() {
    return $this->context;
  }

  /**
   * Get placeholders.
   *
   * @return \Drupal\farm_rcd\Placeholder\PlaceholderInterface[]
   *   Returns a list of PlaceholderInterface objects.
   */
  public function getPlaceholders(): array {
    return $this->placeholders;
  }

  /**
   * Add placeholders.
   *
   * @param \Drupal\farm_rcd\Placeholder\PlaceholderInterface[] $placeholders
   *   Adds an array of PlaceholderInterface objects.
   */
  public function addPlaceholders(array $placeholders) {
    $this->placeholders = array_merge($this->placeholders, $placeholders);
  }

}
