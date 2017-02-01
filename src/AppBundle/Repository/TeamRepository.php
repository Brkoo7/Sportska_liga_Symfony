<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{
	public function isExistAlreadyTeam($name)
	{
		$dql = 'SELECT t FROM AppBundle:Team AS t WHERE t.name = :name';

			return $this->getEntityManager()->createQuery($dql)->setParameter('name', $name)->getResult() ? true: false;
	}
}