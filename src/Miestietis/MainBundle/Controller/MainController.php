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

        $problems = [];

        for($i = 1; $i<3; $i++) {
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
        $initiative = new Initiative();
        $form = $this->createForm(new InitiativeType(), $initiative);
        $form->handleRequest($request);

//        if($form->isValid()){ //patikrina ar bent vienas formos laukas uzpildytas
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($initiative);//paruosia siuntimui i DB
//            $em->flush();//nusiuncia i DB
//            return $this->redirectToRoute('/');//nuveda i main page kad panaikintu requesta
//        }

        return $this->render('MiestietisMainBundle:Main:index.html.twig', array('problems' => $problems,
            'user' => $this->getUser(),
            'initiativeForm' => $form->createView()
                // ...
            ));
    }

}


