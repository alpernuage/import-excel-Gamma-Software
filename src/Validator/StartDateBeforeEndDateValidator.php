<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class StartDateBeforeEndDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        /* @var App\Validator\StartDateBeforeEndDate $constraint */

        if (null === $value || '' === $value) {
            return;
        }
        $startYear = $this->context->getObject()->getStartYear();

        if ($value < $startYear) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
