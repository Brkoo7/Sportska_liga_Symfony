<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintTeamName extends Constraint
{
    public $messageSameName = 'Tim naziva "%string%" već postoji';
}