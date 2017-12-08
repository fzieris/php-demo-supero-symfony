<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validierer für Buchbarkeitsbedingung.
 */
class BookableValidator extends ConstraintValidator {
    public function validate($value, Constraint $constraint) {
        if ($value != null && !$value->isBookable()) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ name }}', $value->getName())
                ->addViolation();
        }
    }
}