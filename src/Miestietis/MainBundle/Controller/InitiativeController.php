<?php

namespace Miestietis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InitiativeController extends Controller
{
    /**
     * @param Request $request
     * @param string  $initiative
     *
     * @return Response
     */
    public function displayAction(Request $request, $initiative)
    {
        $id = explode('_', $initiative);
        $id = (int) array_pop($id);
        $repo = $this->getDoctrine()->getRepository('MiestietisMainBundle:Initiative');
        $initiative = $repo->find($id);
        return $this->render('MiestietisMainBundle:Main:initiative.html.twig', [
            'initiative' => $initiative,
            'initiativeCommentCount' => $this->get('counter')->initiativeCommentCount([$initiative]),
            'user' => $this->getUser(),
        ]);
    }
}