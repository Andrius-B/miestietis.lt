<?php

namespace Miestietis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{

    public function indexAction()
    {

        return $this->render('MiestietisMainBundle:Main:index.html.twig', array(
                'name' => "Marius"
            ));    }

}
