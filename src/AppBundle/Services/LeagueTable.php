<?php

namespace AppBundle\Services;

use AppBundle\Repository\GameRepository;

class LeagueTable
{
	private $gameRepository;

	public function __construct(GameRepository $gameRepository)
	{
		$this->gameRepository = $gameRepository;
	}

	/**
     * Izrada tablice lige
     * 
     * @param  Team[] $leagueTeams Svi timovi
     * @param  string $sortBy Tip sortiranja - default - points
     * @return array[][] $table Tablica lige
     */
	public function makeAndFillLeagueTable(array $leagueTeams, $sortBy = 'points')
	{
		$table = [];
        
        for ($i=0; $i<count($leagueTeams); $i++) { 
            /** @var int Spremanje Id kluba */
            $teamId = $leagueTeams[$i]->getId();

            $table[$i]['id'] = $teamId;
            $table[$i]['name'] = $leagueTeams[$i]->getName();
            $table[$i]['played'] = $this->gameRepository->showPlayedGamesForTeam($teamId)[0][1];

            $table[$i]['wins'] = $this->gameRepository->showWinsForTeam($teamId)[0][1];
            $table[$i]['tie'] = $this->gameRepository->showTieForTeam($teamId)[0][1];
            $table[$i]['loses'] = $table[$i]['played'] - $table[$i]['wins'] - $table[$i]['tie'];

            $table[$i]['scored'] = $this->showScoredAndReceivedGoalsForTeam($teamId)[0];
            $table[$i]['received'] = $this->showScoredAndReceivedGoalsForTeam($teamId)[1];
            $table[$i]['difference'] = $this->showScoredAndReceivedGoalsForTeam($teamId)[2];
            $table[$i]['points'] = $table[$i]['wins'] * 3 + $table[$i]['tie'] * 1; 
        }

        switch($sortBy) {
        	case 'goalDifference':
        		usort($table, function ($a, $b) {
            		return $b['difference'] - $a['difference'];
        		});
        		break;
        	case 'received':
        		usort($table, function ($a, $b) {
            		return $b['received'] - $a['received'];
        		});
        		break;
        	default:
        		usort($table, function ($a, $b) {
            		return $b['points'] - $a['points'];
        		});
        }
       
        return $table;
	}

    /**
     * Metoda koja vraća postignute, primljene i razliku golova za klub
     * 
     * @param  integer $teamId ID kluba
     * @return array $goals Spremanje postignutih, primljenih, i razlike golova kluba
     */
	public function showScoredAndReceivedGoalsForTeam($teamId)
	{
		/** @var array Dohvaća postignute i primljene golove kluba koji je domaći klub */
        $goalsWhereTeamIsHome = $this->gameRepository->showScoredAndReceivedGoalsWhereTeamIsHome($teamId);

        /** @var array Dohvaća postignute i primljene golove kluba koji je gostujući klub */
		$goalsWhereTeamIsAway = $this->gameRepository->showScoredAndReceivedGoalsWhereTeamIsAway($teamId);

        /** @var array Koristi se za spremanje postignutih, primljenih, i razlike golova kluba */
		$goals = [];

		$goalsScored = $goalsWhereTeamIsHome[0][1] + $goalsWhereTeamIsAway[0][1];
		$goalsReceived = $goalsWhereTeamIsHome[0][2] + $goalsWhereTeamIsAway[0][2];
		$goals[0] = $goalsScored;
		$goals[1] = $goalsReceived;
		$goals[2] = $goalsScored - $goalsReceived;
		
		return $goals;
	}
}