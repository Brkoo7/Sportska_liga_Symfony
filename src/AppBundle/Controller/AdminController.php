<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Model\Team as FormTeam;
use AppBundle\Form\Model\Result as FormResult;
use AppBundle\Form\Model\Game as FormGame;
use AppBundle\Entity\Team as EntityTeam;
use AppBundle\Entity\Game as EntityGame;
use AppBundle\Form\Type\TeamType;
use AppBundle\Form\Type\GameType;
use AppBundle\Form\Type\ResultType;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="AppBundle_Admin_adminPage")
     */
    public function adminAction(Request $request)
    {
        return $this->render('AppBundle:Admin:admin.html.twig');
    }

    /**
     * @Route("/admin/teams", name="AppBundle_Admin_adminTeams")
     */
    public function adminTeamsAction(Request $request)
    {
        $formTeam = new FormTeam();
        // $form = $this->createForm(TeamType::class, $formTeam, array('validation_groups'=>'addingTeam'));
        $form = $this->createForm(TeamType::class, $formTeam);

        $form->handleRequest($request);

        /**
         * varijabla koja kaže jesmo li na stranici za editiranje
         *
         * Ukoliko je zastavica postavljena na false prikazuje se forma za dodavanje kluba, a ukoliko je zastavica postavljena na true prikazat ce se forma za editiranje kluba. Implementacija je potrebna radi toga što je forma za editiranje na istoj stranici kao i forma za dodavanje
         *
         * @var boolean zastavica koja služi za određivanje da li smo na stranici za editiranje 
         */
        $editFlag = false;
        
        if ($form->isSubmitted() && $form->isValid()) {
            $formTeam = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $team = new EntityTeam();
            $team->setName($formTeam->name);
            $team->setAddress($formTeam->address);

            $entityManager->persist($team);
            $entityManager->flush();

            return $this->redirectToRoute('AppBundle_Admin_adminTeams'); 
        }

        $teams = $this->get('app.team_repository')->findAll();

        return $this->render('AppBundle:Admin:adminTeams.html.twig', [
            'form' => $form->createView(),
            'teams' => $teams,
            'editFlag' => $editFlag,
        ]);   
    }

    /**
     * @Route("/admin/schedules", name="AppBundle_Admin_adminSchedules")
     */
    public function adminSchedulesAction(Request $request)
    {
        $formGame = new FormGame();
        $form = $this->createForm(GameType::class, $formGame, array('validation_groups'=>'addingSchedule'));
        $form->handleRequest($request);

        /**
         * @see AdminController::adminTeamsAction
         */
        $editFlag = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $formGame = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $game = new EntityGame();
            $homeTeam = $this->get('app.team_repository')->findOneByName($formGame->homeTeam);
            $awayTeam = $this->get('app.team_repository')->findOneByName($formGame->awayTeam);
            $game->setHomeTeam($homeTeam);
            $game->setAwayTeam($awayTeam);
            $game->setDate($formGame->date_);
            
            $entityManager->persist($game);
            $entityManager->flush();
        }
        $schedules = $this->get('app.game_repository')->showScheduleForAllTeams();

        return $this->render('AppBundle:Admin:adminSchedules.html.twig', [
            'form' => $form->createView(),
            'schedules' => $schedules,
            'editFlag' => $editFlag
        ]);
    }

    /**
     * @Route("/admin/results", name="AppBundle_Admin_adminResults")
     */
    public function adminResultsAction(Request $request)
    {
        $formResult = new FormResult();
        $form = $this->createForm(ResultType::class, $formResult, array('validation_groups'=>'addingResult'));
        $form->handleRequest($request);

        /**
         * @see AdminController::adminTeamsAction
         */
        $editFlag = false;
        
        if($form->isSubmitted() && $form->isValid())
        {
            $formResult = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            
            /**
             * Dohvaća utakmicu iz rasporeda
             */
            $result = $this->get('app.game_repository')->find($formResult->games);

            $result->setHomeGoals($formResult->homeGoals);
            $result->setAwayGoals($formResult->awayGoals);

            $entityManager->flush();
        }

        $results = $this->get('app.game_repository')->showResultsForAllTeams();
         
        return $this->render('AppBundle:Admin:adminResults.html.twig', [
            'form' => $form->createView(),
            'results' => $results,
            'editFlag' => $editFlag
        ]);
    }

    /**
     * @Route("/admin/team/edit/{teamId}", name="AppBundle_Admin_adminEditTeam")
     */
    public function adminEditTeamAction(Request $request, int $teamId)
    {
        $formTeam = new FormTeam();

        /**
         * Nađi klub za poslani slug u ruti
         */
        $team = $this->get('app.team_repository')->find($teamId);

        $formTeam->name = $team->getName();
        $formTeam->address = $team->getAddress();

        /**
         * Popuni formu sa podacima kluba, te editiraj po potrebi
         */
        $form = $this->createForm(TeamType::class, $formTeam, array('validation_groups'=>'editTeam'));
        
        $form->handleRequest($request);

        /**
         * @see AdminController::adminTeamsAction
         */
        $editFlag = true;

        if ($form->isSubmitted() && $form->isValid()) {
            $formTeam = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            
            $team->setName($formTeam->name);
            $team->setAddress($formTeam->address);

            $entityManager->flush();
            return $this->redirectToRoute('AppBundle_Admin_adminTeams'); 
        }
        $teams = $this->get('app.team_repository')->findAll();

        return $this->render('AppBundle:Admin:adminEditTeam.html.twig', [
            'form' => $form->createView(),
            'teams' => $teams,
            'team' => $team,
            'editFlag' => $editFlag
        ]);
    }


    /**
     * @Route("/admin/schedule/edit/{scheduleId}", name="AppBundle_Admin_adminEditSchedule")
     */
    public function adminEditScheduleAction(Request $request, int $scheduleId)
    {
        $formGame = new FormGame();
        $schedule = $this->get('app.game_repository')->findOneById($scheduleId);
        $formGame->date_ = $schedule->getDate_();

        /**
         * Popuni formu sa podacima rasporeda utakmice, te editiraj po potrebi
         */
        $form = $this->createForm(GameType::class, $formGame);
        $form->handleRequest($request);

        /**
         * @see AdminController::adminTeamsAction
         */
        $editFlag = true;

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $formGame = $form->getData();
            $schedule->setDate($formGame->date_);
            $entityManager->flush();

            return $this->redirectToRoute('AppBundle_Admin_adminSchedules'); 
        }

        $schedules = $this->get('app.game_repository')->showScheduleForAllTeams();

        return $this->render('AppBundle:Admin:adminEditSchedule.html.twig', [
            'form' => $form->createView(),
            'schedules' => $schedules,
            'editFlag' => $editFlag,
            'schedule' => $schedule
        ]);
    }

    /**
     * @Route("/admin/result/edit/{resultId}", name="AppBundle_Admin_adminEditResult")
     */
    public function adminEditResultAction(Request $request, int $resultId)
    {
       
        $formResult = new FormResult();
        $result = $this->get('app.game_repository')->findOneById($resultId);
        $formResult->games = $result;
        $formResult->homeGoals = $result->getHomeGoals();
        $formResult->awayGoals = $result->getAwayGoals();

        /**
         * Popuni formu sa podacima rezultata utakmice, te editiraj po potrebi
         */
        $form = $this->createForm(ResultType::class, $formResult);
        $form->handleRequest($request);

        /**
         * @see AdminController::adminTeamsAction
         */
        $editFlag = true;

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $formResult = $form->getData();

            $result->setHomeGoals($formResult->homeGoals);
            $result->setAwayGoals($formResult->awayGoals);
            $entityManager->flush();

            return $this->redirectToRoute('AppBundle_Admin_adminResults'); 
        }
        $results = $this->get('app.game_repository')->showResultsForAllTeams();

        return $this->render('AppBundle:Admin:adminEditResult.html.twig', [
            'form' => $form->createView(),
            'results' => $results,
            'editFlag' => $editFlag,
            'result' => $result
        ]);
    }

     /**
     * @Route("/admin/teams/delete/{teamId}", name="AppBundle_Admin_adminDeleteTeam")
     */
    public function adminDeleteTeamAction(int $teamId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $team = $this->get('app.team_repository')->findOneById($teamId);

        $entityManager->remove($team);
        $entityManager->flush();

        return $this->redirectToRoute('AppBundle_Admin_adminTeams'); 
    }

    /**
     * @Route("/admin/schedules/delete/{scheduleId}", name="AppBundle_Admin_adminDeleteSchedule")
     */
    public function adminDeleteScheduleAction(int $scheduleId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $schedule = $this->get('app.game_repository')->findOneById($scheduleId);

        $entityManager->remove($schedule);
        $entityManager->flush();

        return $this->redirectToRoute('AppBundle_Admin_adminSchedules');
    }

    /**
     * @Route("/admin/results/delete/{resultId}", name="AppBundle_Admin_adminDeleteResult")
     */
    public function adminDeleteResultAction(int $resultId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $result = $this->get('app.game_repository')->findOneById($resultId);

        /**
         * Brisanje utakmice iz baze
         *
         * Kada izbrišemo utakmicu iz baze, utakmica ostaje u rasporedu kako bi smo mogli korisniku dati na uvid raspored lige.  
         Ukoliko hoćemo izbrisati kompletno utakmicu brišemo je iz rasporeda
         Nemožemo dodati rezultat utakmice koja nije umetnuta u raspored lige
         */
        $result->setHomeGoals(null);
        $result->setAwayGoals(null);

        $entityManager->flush();

        return $this->redirectToRoute('AppBundle_Admin_adminResults');
    }
}