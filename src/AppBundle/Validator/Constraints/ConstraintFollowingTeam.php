<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
/**
 * @Annotation
 */
class ConstraintFollowingTeam extends Constraint
{
    public $messageSameName = 'Korisnik već prati tim naziva "%string%"';
}