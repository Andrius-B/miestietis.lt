<?php

namespace NFQ\MiestietisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('MiestietisBundle:Main:index.html.twig', array(
                // ...
            ));    }

}
