<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserTeamRepository extends EntityRepository
{
	public function showFollowingTeamsForUser($userId)
	{
		$dql = 'SELECT ut FROM AppBundle:UserTeam AS ut WHERE ut.userName = :id';

			return $this->getEntityManager()->createQuery($dql)->setParameter('id', $userId)->getResult();
	}
	public function checkForPair($userId, $teamId)
	{
		$dql = 'SELECT ut FROM AppBundle:UserTeam AS ut WHERE (ut.userName = :userId AND ut.teamName = :teamId)';

			return $this->getEntityManager()->createQuery($dql)
				->setParameter('userId', $userId)->setParameter('teamId', $teamId)->getResult() ? true : false;
	}
	public function unFollowTeamForUser($userId, $teamId)
	{
		$dql = 'SELECT ut.id FROM AppBundle:UserTeam AS ut WHERE ( ut.userName = :nameId AND ut.teamName = :teamId )';

			return $this->getEntityManager()->createQuery($dql)
				->setParameter('nameId', $userId)->setParameter('teamId', $teamId)->getResult();
	}
}