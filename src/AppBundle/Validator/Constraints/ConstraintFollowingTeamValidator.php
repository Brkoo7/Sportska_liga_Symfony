<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
class ConstraintFollowingTeamValidator extends ConstraintValidator
{
	private $entityManager;
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

    public function validate($value, Constraint $constraint)
    {
        if ($this->hasUserAlreadyFollowTeam($value)) {
            $this->context->buildViolation($constraint->messageSameName)
            	->setParameter('%string%', $value)
                ->addViolation();
        }
    }
    private function hasUserAlreadyFollowTeam($name)
    {
    	return $this->entityManager->getRepository('AppBundle:User_Team')->findOneByName($name) ? true : false;
    }
}