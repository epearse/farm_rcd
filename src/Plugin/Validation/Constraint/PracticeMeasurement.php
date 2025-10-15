<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Plugin\Validation\Constraint;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Validation\Attribute\Constraint;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

/**
 * Checks that only a single practice measurement is present.
 */
#[Constraint(
  id: 'PracticeMeasurement',
  label: new TranslatableMarkup('Practice measurement', ['context' => 'Validation']),
)]
class PracticeMeasurement extends SymfonyConstraint {

  /**
   * The default violation message.
   *
   * @var string
   */
  public string $message = 'Practices can not have both an acreage and linear feet measurement.';

}
