<?php
namespace AppBundle\Validator\Constraints;

use AppBundle\Repository\TeamRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConstraintTeamNameValidator extends ConstraintValidator
{
	private $entityManager;
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

    public function validate($value, Constraint $constraint)
    {
        // $validationGroup = $constraint->groups[0];
        
        if ($this->isThereIdenticalTeamName($value)) {
            $this->context->buildViolation($constraint->messageSameName)
                    ->setParameter('%string%', $value)
                    ->addViolation();
            }
    }

    private function isThereIdenticalTeamName($name)
    {
    	return $this->entityManager->getRepository('AppBundle:Team')->isExistAlreadyTeam($name);
    }
   
}