<?php

namespace Miestietis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxController extends Controller
{
    public function problemAction(Request $request){
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $picture = $request->request->get('picture');
        $user = $this->getUser();
        $data = array('name' => $name, 'description' => $description, 'picture' => $picture);
        $db_handler = $this->get('db_handler');
        $db_handler->insertProblem($name, $description, $picture, $user);

        $response = new JsonResponse($data, 200);
        return $response;//$data;
    }

}
