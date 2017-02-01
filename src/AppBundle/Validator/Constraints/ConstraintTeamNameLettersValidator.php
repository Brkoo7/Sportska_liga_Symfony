<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConstraintTeamNameLettersValidator extends ConstraintValidator
{
	private $entityManager;
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}
    
    public function validate($value, Constraint $constraint)
    {
        if (!$this->teamHasOnlyLetters($value)) {
            $this->context->buildViolation($constraint->messageLetters)->addViolation();
        }
    }  
    
    private function teamHasOnlyLetters($name)
    {
        return preg_match('/^[a-zA-Z šđčćžŠĐČĆŽ]+$/', $name, $matches) ? true : false;
    }
}