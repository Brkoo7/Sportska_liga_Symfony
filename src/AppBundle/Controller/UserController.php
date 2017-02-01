<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\Team;
use AppBundle\Entity\Game;
use AppBundle\Entity\UserTeam;
use AppBundle\Entity\User;

class UserController extends Controller
{
	/**
     * @Route("/user/{userId}", name="AppBundle_User_userProfile")
     */
    public function userProfileAction(int $userId)
    {
    	if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute('AppBundle_Admin_adminPage');
        }
        
        $user = $this->get('app.user_repository')->findOneById($userId);

        /**
         * Dohvatiti timove koje korisnik prati
         */
        $teamsForUser = $this->get('app.userteam_repository')->showFollowingTeamsForUser($userId);
        
        return $this->render('AppBundle:User:userProfile.html.twig', [
            'user' => $user,
            'teams' => $teamsForUser
            ]);   
    }
    /**
     * @Route("/ajax/changeTeam", name="AppBundle_User_changeTeam")
     */
    public function changeTeamAction(Request $request)
    {
        $teamId = (int)$request->query->get('teamId');
        
        /** Dohvati raspored utakmica za odabrani tim */
        $schedules = $this->get('app.game_repository')
                ->showScheduleForTeam($teamId);
        $results = $this->get('app.game_repository')->showResultForTeam($teamId);

        return $this->render('AppBundle:League:contentForTeam.html.twig', [
                'schedules' => $schedules,
                'results' => $results
            ]);
    }

    /**
     * @Route("/ajax/followTeam", name="AppBundle_User_followTeam")
     */
    public function followTeamAction(Request $request)
    {
        $userId = (int)$request->query->get('userId');
        $teamId = (int)$request->query->get('teamId');
        
        /**
         * Spremi u bazu par userId - teamId - korisnik prati odabrani klub
         */
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->get('app.user_repository')->find($userId);
        $team = $this->get('app.team_repository')->findOneById($teamId);

        $userFollowTeam = new UserTeam();
        $userFollowTeam->setuserName($user);
        $userFollowTeam->setteamName($team);
        
        $entityManager->persist($userFollowTeam);
        $entityManager->flush();
        $message = "Ukini pretplatu";

        return $this->render('AppBundle:User:message.html.twig', [
                'message' => $message
            ]);
    }

    /**
     * @Route("/ajax/unFollowTeam", name="AppBundle_User_unFollowTeam")
     */
    public function unFollowTeamAction(Request $request)
    {
        $userId = (int)$request->query->get('userId');
        $teamId = (int)$request->query->get('teamId');
        
        $entityManager = $this->getDoctrine()->getManager();

        /**
         * Nađi u bazi UserTeam sa vrijednostima poslanih userId i teamId
         */
        $userTeamId = $this->get('app.userteam_repository')->unFollowTeamForUser($userId, $teamId);   
        $userTeam = $this->get('app.userteam_repository')->findOneById($userTeamId[0]['id']);

        $entityManager->remove($userTeam);
        $entityManager->flush();
        $message = "Pretplati me";

        return $this->render('AppBundle:User:message.html.twig', [
                'message' => $message
            ]);
    }

    /**
     * @Route("/ajax/user-data", name="AppBundle_User_userData", condition="request.isXmlHttpRequest()")
     */
    public function userDataAction()
    {
        $userData = ['id' => false, 'name' => false, 'loggedIn' => false, 'isAdmin' => false];

        $authorisation = $this->get('security.authorization_checker');
        if ($authorisation->isGranted('ROLE_USER') && $authorisation->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();
            $userData['name'] = $user->getUsername();
            $userData['id'] = $user->getId();
            $userData['loggedIn'] = true;
        } elseif ($authorisation->isGranted('ROLE_ADMIN') && $authorisation->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $user = $this->getUser();
                $userData['name'] = $user->getUsername();
                $userData['id'] = $user->getId();
                $userData['loggedIn'] = true;
                $userData['isAdmin'] = true;
        }

        return new JsonResponse($userData); 
    }
}