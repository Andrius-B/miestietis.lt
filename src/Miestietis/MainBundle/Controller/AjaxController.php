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

    public function initiativeAction(Request $request){
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        $status = null;
        $description = $request->request->get('description');
        $date = $request->request->get('date');
        $user = $this->getUser();
        $probId = intval($request->request->get('probId'));

        $problem = $this->getDoctrine()
            ->getRepository('MiestietisMainBundle:Problema')
            ->find($probId);

        if($this->getDoctrine()->getRepository('MiestietisMainBundle:Initiative')->findBy(array('problem_id'=>$problem)) != null){
            $status = 'Ši problema jau turi iniciatyvą!';
            $data = array('description' => $description, 'date'=>$date, 'probId'=>$problem, 'status'=>$status);
            $response = new JsonResponse($data, 200);
            return $response;
        }

        $data = array('description' => $description, 'date'=>$date, 'probId'=>$problem, 'status'=>$status);
        //using the database service insert to DB
        $db_handler = $this->get('db_handler');
        $db_handler->insertInitiative($description, $date, $problem, $user);
        $response = new JsonResponse($data, 200);
        return $response;//$data;
    }
    public function upvoteAction(Request $request){
        $probId = intval($request->request->get('probId'));
        $problem = $this->getDoctrine()
            ->getRepository('MiestietisMainBundle:Problema')
            ->find($probId);
        $user = $this->getUser();
        $db_handler = $this->get('db_handler');
        $votes = $db_handler->upvoteProblem($problem, $user  );
        $data = array('probId'=>$probId, 'votes'=>$votes);
        $response = new JsonResponse($data, 200);
        return $response;//$data;
    }
}
