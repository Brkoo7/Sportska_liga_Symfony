<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeamRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="Team")
 */
class Team
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string")
	 */
	private $name;

	/**
	 * @ORM\Column(type="string")
	 */
	private $address;

	/** @ORM\Column(type="datetime") */
    private $modified;


	public function getName() : string
	{
		return $this->name;
	}
	public function getId() : int
	{
		return $this->id;
	}
	public function setName(string $name)
	{
		$this->name = $name;
	}
	public function setAddress(string $address)
	{
		$this->address = $address;
	}
	public function getAddress() : string
	{
		return $this->address;
	}

	/**
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 */
	public function updateModified()
    {
        $this->modified = new \DateTimeImmutable('now');
    }
}