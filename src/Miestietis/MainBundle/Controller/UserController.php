<?php

namespace Miestietis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function createAction()
    {
        return $this->render('MiestietisMainBundle:User:create.html.twig', array(
                // ...
            ));    }

    public function deleteAction()
    {
        return $this->render('MiestietisMainBundle:User:delete.html.twig', array(
                // ...
            ));    }

}
