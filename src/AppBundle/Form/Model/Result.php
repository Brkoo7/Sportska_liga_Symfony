<?php
namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AcmeAssert;

/**
 * @AcmeAssert\ConstraintResult(groups={"addingResult"})
 */
class Result
{
	public $games;

	/**
     * @Assert\Range(
     *      min = 0,
     *      max = 30,
     *      minMessage = "Broj postignutih golova ne može biti negativan",
     *      maxMessage = "Broj postignutih pogotaka ne može biti veći od {{ limit }} golova"
     * )
     */
	public $homeGoals;
	/**
     * @Assert\Range(
     *      min = 0,
     *      max = 30,
     *      minMessage = "Broj postignutih golova ne može biti negativan",
     *      maxMessage = "Broj postignutih pogotaka ne može biti veći od {{ limit }} golova"
     * )
     */
	public $awayGoals;
}