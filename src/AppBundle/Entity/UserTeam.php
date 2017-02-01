<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserTeamRepository")
 * @ORM\Table(name="UserTeam")
 */
class UserTeam
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
	private $userName;

	/**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     */
	private $teamName;

	
	public function getuserName()
	{
		return $this->userName;
	}
	public function getteamName()
	{
		return $this->teamName;
	}
	public function setuserName(User $userName)
	{
		$this->userName = $userName;
	}
	public function setteamName(Team $teamName)
	{
		$this->teamName = $teamName;
	}
	public function getId()
	{
		return $this->id;
	}	
}