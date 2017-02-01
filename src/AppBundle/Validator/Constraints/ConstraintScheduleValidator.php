<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Repository\GameRepository;

class ConstraintScheduleValidator extends ConstraintValidator
{
	private $entityManager;
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}
    public function validate($value, Constraint $constraint)
    {
        if (!empty($value->homeTeam) && !empty($value->awayTeam)
            && $value->homeTeam == $value->awayTeam) {
            $this->context->buildViolation($constraint->message)
                ->atPath('homeTeam')
                ->addViolation();
        }
        elseif ($this->hasMultipleSchedulePerDay($value)) {
        	$this->context->buildViolation($constraint->message1)
                ->atPath('homeTeam')
                ->addViolation();
        }

    }
    private function hasMultipleSchedulePerDay($game)
    {

        $homeTeam = $this->entityManager->getRepository('AppBundle:Team')->findOneByName($game->homeTeam);
        $awayTeam = $this->entityManager->getRepository('AppBundle:Team')->findOneByName($game->awayTeam);
        
        $isScheduleExistForTeams = $this->entityManager->getRepository('AppBundle:Game')->findOneBy(array('homeTeam' => $homeTeam, 'awayTeam' => $awayTeam, 'date_' => $game->date_)) ? true : false;

        $isScheduleExistForInverseTeams = $this->entityManager->getRepository('AppBundle:Game')->findOneBy(array('homeTeam' => $awayTeam, 'awayTeam' => $homeTeam, 'date_' => $game->date_)) ? true : false;

    	return $isScheduleExistForTeams || $isScheduleExistForInverseTeams;
    }
}