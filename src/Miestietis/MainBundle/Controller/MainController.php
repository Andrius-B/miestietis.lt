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
        /*$i = new extra();
        $a = $i->findOneBy(array('username' => 895797333822283));
        echo $a;
        exit();*/

        //initiative form'os set-up
        $initiative = new Initiative();
        $initiative->setVotes(0);
        $initiative->setIsActive(true);
        $initiative->setRegistrationDate(date("Y-m-d"));
        $form = $this->createForm(new InitiativeType(), $initiative);
        $form->handleRequest($request);

        return $this->render('MiestietisMainBundle:Main:index.html.twig', array('problems' => $problems,
            'user' => $this->getUser(),
            'initiativeForm' => $form->createView()
                // ...
            ));
    }


}
