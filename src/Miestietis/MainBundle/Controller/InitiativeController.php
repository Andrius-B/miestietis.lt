<?php

namespace Miestietis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class InitiativeController extends Controller
{
    /**
     * @param string $title
     *
     * @return Response
     */
    public function displayAction($title)
    {
        $repo = $this->getDoctrine()->getRepository('MiestietisMainBundle:Initiative');
        $initiative = $repo->findOneBy(['title' => str_replace('_', ' ', $title)]);
        return $this->render('MiestietisMainBundle:Main:initiative.html.twig', [
            'initiative' => $initiative,
            'user' => $this->getUser(),
        ]);
    }
}