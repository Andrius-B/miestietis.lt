<?php

namespace Miestietis\MainBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Miestietis\MainBundle\Form\InitiativeType;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    public function indexAction(Request $request)
    {
        $ob_former = $this->get('ob_formation');

        $user = $this->getUser();
        $problems = $ob_former->getProblems(10, 0);
        $initiatives = $ob_former->getInitiatives(10, 0);

        /* Process problem status and tooltip values   ---> okay, but why????*/
        $checker = $this->get('security.authorization_checker');
        $this->get('item_type')->itemType($problems, $user, $checker);

        //initiative form set-up
        $initiative = $ob_former->formInitiative();
        $form = $this->createForm(new InitiativeType(), $initiative);
        $form->handleRequest($request);

        return $this->render('MiestietisMainBundle:Main:index.html.twig', array('problems' => $problems,
            'initiatives' => $initiatives,
            'user' => $user,
            'initiativeForm' => $form->createView()
                // ...
            ));
    }


}
