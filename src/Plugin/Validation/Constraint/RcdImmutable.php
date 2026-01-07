<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Plugin\Validation\Constraint;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Validation\Attribute\Constraint;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

/**
 * Prevents a field from being changed.
 */
#[Constraint(
  id: 'RcdImmutable',
  label: new TranslatableMarkup('Field is immutable', ['context' => 'Validation']),
)]
class RcdImmutable extends SymfonyConstraint {

  /**
   * The default violation message.
   *
   * @var string
   */
  public string $message = '%field cannot be changed after it has been saved.';

}
