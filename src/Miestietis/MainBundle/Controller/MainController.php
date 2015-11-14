<?php

namespace Miestietis\MainBundle\Controller;

use Miestietis\MainBundle\Entity\Problema;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class MainController extends Controller
{
    public function indexAction()
    {

        $problems = [];

        for($i = 6; $i<9; $i++) {
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
        return $this->render('MiestietisMainBundle:Main:index.html.twig', array('problems' => $problems,
            'user' => $this->getUser()
                // ...
            ));
    }

}


