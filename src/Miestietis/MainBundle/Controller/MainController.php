<?php

namespace Miestietis\MainBundle\Controller;

use Miestietis\MainBundle\Entity\Problema;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Miestietis\MainBundle\Form\InitiativeType;
use Miestietis\MainBundle\Entity\Initiative;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    public function indexAction(Request $request)
    {
        $problems = $this->getDoctrine()
            ->getRepository('MiestietisMainBundle:Problema')
            ->findAll();

        /* Process problem status and tooltip values */
        $this->itemType($problems);

        //initiative form'os set-up
        $initiative = new Initiative();
        $initiative->setVotes(0);
        $initiative->setIsActive(true);
        $initiative->setRegistrationDate(date("Y-m-d"));
        $form = $this->createForm(new InitiativeType(), $initiative);
        $form->handleRequest($request);

        return $this->render('MiestietisMainBundle:Main:index.html.twig', array('problems' => $problems,
            'user' => $this->getUser(),
            'initiativeForm' => $form->createView(),
                // ...
            ));
    }

    public function itemType($p)
    {
        $user = $this->getUser();
        foreach($p as $problem)
        {
            if($problem->getUpvotedBy()->contains($user))
            {
                $problem->status = 'disabled';
                $problem->tooltip = 'Pritarti galite tik vieną kartą!';
            } else {
                $problem->status = '';
                $problem->tooltip = 'Pritariu problemai';
                if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                    $problem->tooltip = 'Norėdami pritarti turite prisijungti';
                }
            }
        }
    }
}
