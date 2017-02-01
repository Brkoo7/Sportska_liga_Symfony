<?php
namespace AppBundle\Form\Model;
use AppBundle\Validator\Constraints as AcmeAssert;
/**
 * @AcmeAssert\ConstraintMatchDate
 */
class SearchParameters
{
    public $dateFrom;
    public $dateTo;
}