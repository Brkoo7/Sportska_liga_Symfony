<?php
namespace AppBundle\Validator\Constraints;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConstraintMatchDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!empty($value->dateFrom) && !empty($value->dateTo)
            && $value->dateFrom>=$value->dateTo) {
            $this->context->buildViolation($constraint->message)
                ->atPath('dateFrom')
                ->addViolation();
        }
    }
}