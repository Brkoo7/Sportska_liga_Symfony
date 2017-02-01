<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintResult extends Constraint
{
    public $message = 'Ne može se unijeti rezultat utakmice koja još nije igrana';
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}