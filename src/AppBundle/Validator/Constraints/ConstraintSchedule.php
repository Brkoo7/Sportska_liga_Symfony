<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintSchedule extends Constraint
{
    public $message = 'Tim ne može igrati sam protiv sebe';
    public $message1 = 'Ne mogu klubovi igrati više od jedne utakmice u jednom danu';
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}