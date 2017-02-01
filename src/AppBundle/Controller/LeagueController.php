<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Team;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Form\Model\UserTeam as UserTeam;
use AppBundle\Entity\UserTeam as EntityUserTeam;
use AppBundle\Form\Type\UserTeamType;
use AppBundle\Form\Model\SearchParameters;
use AppBundle\Form\Type\SearchParametersType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LeagueController extends Controller
{

    /**
     * @Route("/home", name="AppBundle_League_homePage")
     * @Route("/", name="AppBundle_League_homePage")
     */
    public function homePageAction(Request $request)
    {
        $leagueTeams = $this->get('app.team_repository')->findAll();
        

        $table = $this->get('app.service.league_table')->makeAndFillLeagueTable($leagueTeams);

        $response = $this->render('AppBundle:League:homePage.html.twig', [
            'table' => $table
        ]);
        $response->setETag(md5($response->getContent()));
        $response->setPublic();

        $response->isNotModified($request);

        return $response;   
    }

    /**
     * @Route("/league/schedule", name="AppBundle_League_scheduleList")
     */
    public function scheduleListAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $schedules = $this->get('app.game_repository')->showScheduleForAllTeams();
        
        return $this->render('AppBundle:League:scheduleList.html.twig', [
            'schedules' => $schedules
        ]);
    }

    /**
     * @Route("/league/results", name="AppBundle_League_resultList")
     */
    public function resultListAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $results = $this->get('app.game_repository')->showResultsForAllTeams();

        /**
         * GET Forma za filtriranje rezultata po datumu
         */
        $form = $this->get('form.factory')->createNamed(null, SearchParametersType::class, new SearchParameters());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $results = $this->get('app.game_repository')->findByParameters($form->getData());
        }

        return $this->render('AppBundle:League:resultList.html.twig', [
            'results' => $results,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/league/team/{teamId}", name="AppBundle_League_teamsPageIndividual", requirements={"teamId": "\d+"})
     */
    public function teamsPageIndividualAction(Request $request, int $teamId)
    {
        $team = $this->get('app.team_repository')->find($teamId);
        $schedules = $this->get('app.game_repository')->showScheduleForTeam($teamId);
        $results = $this->get('app.game_repository')->showResultForTeam($teamId);
        if(!$team) {
            throw $this->createNotFoundException(sprintf('Team with id "%s" not found.',
                $teamId));
        }

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $auth_checker = $this->get('security.authorization_checker');

        /**
         * Provjera autentifikacije korisnika
         *
         * Ako je korisnik autentificiran odnosno ima rolu USER provjeri u bazi da li autentificirani korisnik prati trenutno renderirani klub. Ukoliko postoji par User-Team u bazi onda Twigu reci da prikaže da korisnik prati već klub. U suprotnom ponudi korisniku opciju pracenja kluba
         */   
        if ($auth_checker->isGranted('ROLE_USER')) {
            $pair = $this->get('app.userteam_repository')
                ->checkForPair($user->getId(), $team->getId());
        }
        else
            $pair = false;
        
        return $this->render('AppBundle:League:teamsPageIndividual.html.twig', [
            'team' => $team,
            'pair' => $pair,
            'user' => $user,
            'results' => $results,
            'schedules' => $schedules
        ]);
    }


    /**
     * Metoda za stvaranje tablice koja poziva servis za stvaranje tablice
     */
    public function tableAction(Request $request)
    {   
        $leagueTeams = $this->get('app.team_repository')->findAll();

        $table = $this->get('app.service.league_table')->makeAndFillLeagueTable($leagueTeams);

        $response = $this->render('AppBundle:League:table.html.twig', [
            'teams' => $table
        ]);

        return $response;
    }

    /**
     * Renderiranje navigacijskog izbornika
     *
     * Izbornik klubova se treba prikazati na svakoj stranici tako da ga je najbolje pozvati iz glavnog layout
     * Twiga. Na taj način sprječavamo da moramo prikazu uvijek vraćati timove
     */
    public function listTeamsNavAction()
    {
        $teams = $this->get('app.team_repository')->findAll();

        return $this->render('AppBundle:League:listTeamsNav.html.twig', [
            'teams' => $teams
            ]);
    }

    /**
     * @Route("/ajax/getSortingTable", name="AppBundle_League_sortTableByGoalDifference")
     */
    public function sortTableByGoalDifference(Request $request)
    {
        $sortType = (string)$request->query->get('sortType');

        $leagueTeams = $this->get('app.team_repository')->findAll();

        $table = $this->get('app.service.league_table')->makeAndFillLeagueTable($leagueTeams, $sortType);

        $response = $this->render('AppBundle:League:table.html.twig', [
            'table' => $table
        ]);

        return $response;   
    }
}