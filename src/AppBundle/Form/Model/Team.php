<?php
namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AcmeAssert;

class Team 
{
	/**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 15,
     *      minMessage = "Ime kluba mora imati barem {{ limit }} znakova",
     *      maxMessage = "Ime kluba ne može biti veće od {{ limit }} znakova"
     * )
     * @AcmeAssert\ConstraintTeamNameLetters
     * @AcmeAssert\ConstraintTeamNameLetters(groups={"editTeam"})
     * @AcmeAssert\ConstraintTeamName
     */
	public $name;

	/**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      max = 30,
     *      minMessage = "Adresa kluba mora imati barem {{ limit }} znakova",
     *      maxMessage = "Adresa kluba ne može biti veća od {{ limit }} znakova"
     * )
     */
	public $address;
}