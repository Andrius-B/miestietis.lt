<?php

namespace Miestietis\MainBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Miestietis\MainBundle\Form\InitiativeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class MainController extends Controller
{
    public function indexAction(Request $request)
    {
        
        $ob_former = $this->get('ob_formation');
        $counter = $this->get('counter');

        $user = $this->getUser();
        $session =  $this->container->get('session');

        // Form 10 latest problems and count their comments
        $problems = $ob_former->getProblems(10, 0);
        $session->set('problems', 10);
        $problemComments = $counter->problemCommentCount($problems);

        // Form top 10 problems and count their comments
        $topProblems = $ob_former->getTopProblems(10);
        $topComments = $counter->problemCommentCount($topProblems);

        // Form 10 latest initiatives and count their comments and participants
        $initiatives = $ob_former->getInitiatives(10, 0);
        $session->set('initiatives', 10);
        $initiativeComments = $counter->initiativeCommentCount($initiatives);
        $participants = $counter->joinCount($initiatives);

        // Process problem status and tooltip values
        $this->get('item_type')->itemType($problems, $initiatives, $topProblems, $user, $this->get('security.authorization_checker'));


        //initiative form set-up
        $initiative = $ob_former->formInitiative();
        $form = $this->createForm(new InitiativeType(), $initiative);
        $form->handleRequest($request);

        return $this->render('MiestietisMainBundle:Main:index.html.twig', array(
            'problems' => $problems,
            'topProblems' => $topProblems,
            'initiatives' => $initiatives,
            'user' => $user,
            'initiativeForm' => $form->createView(),
            'problemCommentCount' => $problemComments,
            'initiativeCommentCount' => $initiativeComments,
            'topProblemCommentCount' => $topComments,
            'participantCount' => $participants
                // ...
            ));
    }


}
