<?php

namespace Miestietis\MainBundle\Controller;

use Miestietis\MainBundle\Entity\Problema;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Miestietis\MainBundle\Form\InitiativeType;
use Miestietis\MainBundle\Entity\Initiative;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController extends Controller
{
    public function indexAction(Request $request)
    {

        $problems = [];

        for($i = 2; $i<5; $i++) {
            $problems[] = $this->getDoctrine()
                ->getRepository('MiestietisMainBundle:Problema')
                ->find($i);
        }
        if($problems[0] == null){
            $problems[0] = 0;
        }
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
        /*if($form->isValid()){
            var_dump($initiative);
            die(1);
        }*/

        return $this->render('MiestietisMainBundle:Main:index.html.twig', array('problems' => $problems,
            'user' => $this->getUser(),
            'initiativeForm' => $form->createView()
                // ...
            ));
    }


}
