<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintTeamNameLetters extends Constraint
{
	public $messageLetters = 'Ime tima mora sadržavati samo slova';
}