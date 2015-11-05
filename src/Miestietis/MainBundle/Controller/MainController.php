<?php

namespace Miestietis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $problems = [];

        for($i = 1; $i<4; $i++) {
            $problems[] = $this->getDoctrine()
                ->getRepository('MiestietisMainBundle:Problema')
                ->find($i);
        }

        return $this->render('MiestietisMainBundle:Main:index.html.twig', array('problems' => $problems
                // ...
            ));
    }

}
