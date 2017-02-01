<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConstraintResultValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
    	$date_now = date("Y-m-d");
    	$date_result = date_format($value->games->getDate_(),"Y-m-d");
        if (!empty($value->games) && ($date_result > $date_now )) {
            $this->context->buildViolation($constraint->message)
                ->atPath('games')
                ->addViolation();
        }
    }
}