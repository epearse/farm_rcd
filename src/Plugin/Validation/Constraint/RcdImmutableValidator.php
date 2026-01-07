<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Plugin\Validation\Constraint;

use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the RcdImmutable constraint.
 */
class RcdImmutableValidator extends ConstraintValidator implements ContainerInjectionInterface {

  use AutowireTrait;

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $value, Constraint $constraint) {
    /** @var \Drupal\Core\Field\FieldItemInterface $value */
    /** @var \Drupal\farm_rcd\Plugin\Validation\Constraint\RcdImmutable $constraint */

    // Get the parent entity.
    /** @var \Drupal\Core\Entity\FieldableEntityInterface|null $entity */
    $entity = $value->getParent()->getValue();
    if (is_null($entity)) {
      return;
    }

    // If the entity is new, bail.
    if ($entity->isNew()) {
      return;
    }

    // Load the original unchanged entity from the database.
    /** @var \Drupal\Core\Entity\FieldableEntityInterface $original */
    $original = $this->entityTypeManager->getStorage($entity->getEntityTypeId())->load($entity->id());

    // If the field value is changing, add a violation.
    if ($entity->get($value->getName())->getValue() != $original->get($value->getName())->getValue()) {
      $this->context->addViolation($constraint->message, ['%field' => $value->getDataDefinition()->getLabel()]);
    }
  }

}
