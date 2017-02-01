<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Team;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 * @ORM\Table(name="Game")
 */
class Game
{
   	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="home_team", referencedColumnName="id")
     */
	private $homeTeam;

	/**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="away_team", referencedColumnName="id")
     */
	private $awayTeam;


	/**
	 * @ORM\Column(type="integer")
	 */
	private $homeGoals;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $awayGoals;

	
	/**
	 * @ORM\Column(type="date")
	 */
	private $date_;

	public function getId()
	{
		return $this->id;
	}
	public function getHomeTeam()
	{
		return $this->homeTeam;
	}
	public function getAwayTeam() : Team
	{
		return $this->awayTeam;
	}
	public function getDate_()
	{
		return $this->date_;
	}
	public function getHomeGoals()
	{
		return $this->homeGoals;
	}
	public function getAwayGoals()
	{
		return $this->awayGoals;
	}
	public function setHomeTeam(Team $homeTeam)
	{
		$this->homeTeam = $homeTeam;
	}
	public function setAwayTeam(Team $awayTeam)
	{
		$this->awayTeam = $awayTeam;
	}
	public function setHomeGoals($homeGoals)
	{
		$this->homeGoals = $homeGoals;
	}
	public function setAwayGoals($awayGoals)
	{
		$this->awayGoals = $awayGoals;
	}
	public function setDate($date)
	{
		$this->date_ = $date;
	}

	public function __toString() {
   		$myText = $this->homeTeam->getName() . ' - ' . $this->awayTeam->getName() . ' | ' . $this->date_->format('Y-m-d');
   		return $myText;
	}
		
}