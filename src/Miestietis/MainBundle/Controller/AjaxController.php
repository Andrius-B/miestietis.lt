<?php

namespace Miestietis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


class AjaxController extends Controller
{
    public function problemAction(Request $request)
    {
        if (!$request->isXmlHttpRequest())
        {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        // Getting everything from the request
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $file = $request->files->get('file');
        //Getting the loged in user object
        $user = $this->getUser();

        //Setting a unique file name
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        //Moving the file
        $fileDir = $this->container->getParameter('kernel.root_dir').'/../web/public/images/problems/';
        $file->move($fileDir, $fileName);

        //Forming a response
        $data = array('name' => $name, 'description' => $description, 'picture' => $fileName);
        //Persisting problem to a database
        $db_handler = $this->get('db_handler');
        $db_handler->insertProblem($name, $description, $fileName, $user);

        $response = new JsonResponse($data, 200);
        return $response;//$data;
    }

    public function initiativeEditAction(Request $request){
        $description = $request->request->get('description');
        $date = $request->request->get('date');
        $probId = intval($request->request->get('probId'));
        $initId = intval($request->request->get('initId'));
        $problem = $this->getDoctrine()
            ->getRepository('MiestietisMainBundle:Problema')
            ->find($probId);
        $initiative = $this->getDoctrine()
            ->getRepository('MiestietisMainBundle:Initiative')
            ->find($initId);
        $data = array('description' => $description, 'date'=>$date, 'probId'=>$problem);

        //using the database service insert to DB
        $db_handler = $this->get('db_handler');
        $db_handler->editInitiative($description, $date, $initiative);
        $response = new JsonResponse($data, 200);
        return $response;//$data;
    }

    public function initiativeAction(Request $request)
    {
        if (!$request->isXmlHttpRequest())
        {
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

        if($this->getDoctrine()->getRepository('MiestietisMainBundle:Initiative')->findBy(array('problem_id'=>$problem)) != null)
        {
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
    public function upvoteAction(Request $request)
    {
        $probId = intval($request->request->get('probId'));
        $problem = $this->getDoctrine()
            ->getRepository('MiestietisMainBundle:Problema')
            ->find($probId);
        $user = $this->getUser();
        $db_handler = $this->get('db_handler');
        $votes = $problem->getVotes();
        if(!$problem->getUpvotedBy()->contains($user))
        {
            $votes = $db_handler->upvoteProblem($problem, $user);
        }
        $data = array('probId'=>$probId, 'votes'=>$votes);
        $response = new JsonResponse($data, 200);
        return $response;
    }

    public function historyAction(Request $request)
    {
        if (!$request->isXmlHttpRequest())
        {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        $user = $this->getUser();
        $items = $this->getDoctrine()
            ->getRepository('MiestietisMainBundle:Problema')
            ->findBy(array('user_id'=>$user));
        $template = $this->renderView('history.html.twig', array('items' => $items));
        $response = new Response($template, 200);
        return $response;

    }
}
