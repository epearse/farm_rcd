<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the PracticeMeasurement constraint.
 */
class PracticeMeasurementValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $value, Constraint $constraint) {
    /** @var \Drupal\Core\Field\Plugin\Field\FieldType\DecimalItem $value */
    /** @var \Drupal\farm_rcd\Plugin\Validation\Constraint\PracticeMeasurement $constraint */

    // Get the asset entity.
    /** @var \Drupal\asset\Entity\AssetInterface|null $asset */
    $asset = $value->getParent()->getValue();
    if (is_null($asset)) {
      return;
    }

    // If both the rcd_acres and rcd_linear_feet have values, add a violation.
    if (!$asset->get('rcd_acres')->isEmpty() && !$asset->get('rcd_linear_feet')->isEmpty()) {
      $this->context->addViolation($constraint->message);
    }
  }

}
