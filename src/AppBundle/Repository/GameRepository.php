<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Form\Model\SearchParameters;

class GameRepository extends EntityRepository
{
	public function showScheduleForTeam(int $id)
	{
		$dql = 'SELECT g, h, a FROM AppBundle:Game AS g JOIN g.homeTeam AS h JOIN g.awayTeam AS a
	        WHERE (g.homeTeam = :id OR g.awayTeam = :id) ORDER BY g.date_ DESC';

			return $this->getEntityManager()->createQuery($dql)->setParameter('id', $id)->getResult();
	}

	public function showScheduleForAllTeams()
	{
		$dql = 'SELECT g, h, a FROM AppBundle:Game AS g JOIN g.homeTeam AS h 
		    JOIN g.awayTeam AS a  ORDER BY g.date_ DESC';

		return $this->getEntityManager()->createQuery($dql)->getResult();
	}

	public function showResultForTeam(int $id)
	{
		$dql = 'SELECT g, h, a FROM AppBundle:Game AS g JOIN g.homeTeam AS h JOIN g.awayTeam AS a
	        WHERE (g.homeTeam = :id OR g.awayTeam = :id) AND (g.homeGoals IS NOT NULL AND g.awayGoals IS NOT NULL) ORDER BY g.date_ DESC';

			return $this->getEntityManager()->createQuery($dql)->setParameter('id', $id)->getResult();
	}

	public function showResultsForAllTeams()
	{
		$dql = 'SELECT g, h, a FROM AppBundle:Game as g JOIN g.homeTeam AS h
			JOIN g.awayTeam AS a WHERE (g.homeGoals IS NOT NULL AND g.awayGoals IS NOT NULL) ORDER BY g.date_ DESC';
		return $this->getEntityManager()->createQuery($dql)->getResult();
	}


	public function showPlayedGamesForTeam(int $id)
	{
		$dql = 'SELECT COUNT(g.id) FROM AppBundle:Game AS g
	        WHERE (g.homeTeam =:id OR g.awayTeam =:id) 
	        AND (g.homeGoals IS NOT NULL AND g.awayGoals IS NOT NULL)';

		return $this->getEntityManager()->createQuery($dql)->setParameter('id', $id)->getResult();
	}
	public function showWinsForTeam(int $id)
	{
		$dql = 'SELECT COUNT(g.id) FROM AppBundle:Game AS g
	        WHERE (g.homeTeam =:id AND (g.homeGoals > g.awayGoals))
	        OR (g.awayTeam =:id AND (g.awayGoals > g.homeGoals))';

		return $this->getEntityManager()->createQuery($dql)->setParameter('id', $id)->getResult();
	}

	public function showTieForTeam(int $id)
	{
		$dql = 'SELECT COUNT(g.id) FROM AppBundle:Game AS g
	        WHERE (g.homeTeam =:id AND (g.homeGoals = g.awayGoals))
	        OR (g.awayTeam =:id AND (g.awayGoals = g.homeGoals))';

		return $this->getEntityManager()->createQuery($dql)->setParameter('id', $id)->getResult();
	}

	public function showScoredAndReceivedGoalsWhereTeamIsHome(int $id)
	{
		$dql = 'SELECT SUM(g.homeGoals), SUM(g.awayGoals) FROM AppBundle:Game AS g
	        WHERE g.homeTeam = :id AND g.homeGoals IS NOT NULL AND g.awayGoals IS NOT NULL';

		return $this->getEntityManager()->createQuery($dql)->setParameter('id', $id)->getResult();
	}

	public function showScoredAndReceivedGoalsWhereTeamIsAway(int $id)
	{
		$dql = 'SELECT SUM(g.awayGoals), SUM(g.homeGoals) FROM AppBundle:Game AS g
	        WHERE g.awayTeam = :id AND g.homeGoals IS NOT NULL AND g.awayGoals IS NOT NULL';

		return $this->getEntityManager()->createQuery($dql)->setParameter('id', $id)->getResult();
	}

	public function findByParameters(SearchParameters $searchParameters)
    {
        $dql = 'SELECT g FROM AppBundle:Game AS g';
        $where = [];
        $parameters = [];

        if (!empty($searchParameters->dateFrom)) {
            $where[] = ' g.date_ >= :dateFrom';
            $parameters['dateFrom'] = $searchParameters->dateFrom;
        }
        if (!empty($searchParameters->dateTo)) {
            $where[] = ' g.date_ <= :dateTo ';
            $parameters['dateTo'] = $searchParameters->dateTo;
        }
        if ($where) {
            $dql .= " WHERE " . implode(' AND ', $where) . " ";
        }
        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameters($parameters)
            ->getResult();
    }
	
	public function createGameForEnterResultQueryBuilder()
	{
		return $this->createQueryBuilder('g')->where('g.homeGoals is null')
			->andWhere('g.awayGoals is null')->orderBy('g.date_', 'DESC');
	}
}