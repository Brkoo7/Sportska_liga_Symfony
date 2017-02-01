<?php
namespace AppBundle\Validator\Constraints;
use Symfony\Component\Validator\Constraint;
/**
 * @Annotation
 */
class ConstraintMatchDate extends Constraint
{
    public $message = 'Datum "Od" mora biti manji od datuma "Do"';
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}