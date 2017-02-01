<?php
namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AcmeAssert;


/**
 * @AcmeAssert\ConstraintSchedule(groups={"addingSchedule"})
 */
class Game
{
	public $homeTeam;
	public $awayTeam;
	public $date_;
}