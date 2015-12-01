<?php

namespace Miestietis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


class AjaxController extends Controller
{
//    private $db_handler;
//    private $user;
//    public function __construct(){
//        if($this->db_handler == null ) {
//            $this->db_handler = $this->get('db_handler');
//        }
//        if($this->user == null){
//            $this->user = $this->getUser();
//        }
//
//    }
    public function problemAction(Request $request)
    {
        if (!$request->isXmlHttpRequest())
        {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        // Getting everything from the request
        $db_handler = $this->get('db_handler');
        $user = $this->getUser();

        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $file = $request->files->get('file');
        //Getting the loged in user object


        //Setting a unique file name
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        //Moving the file
        $fileDir = $this->container->getParameter('kernel.root_dir').'/../web/public/images/problems/';
        $file->move($fileDir, $fileName);

        //Forming a response
        $data = array('name' => $name, 'description' => $description, 'picture' => $fileName);
        //Persisting problem to a database

        $db_handler->insertProblem($name, $description, $fileName, $user);

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
        $user = $this->getUser();
        $description = $request->request->get('description');
        $date = $request->request->get('date');
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
        $user = $this->getUser();
        $probId = intval($request->request->get('probId'));
        $problem = $this->getDoctrine()
            ->getRepository('MiestietisMainBundle:Problema')
            ->find($probId);
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
    public function commentLoadAction(Request $request){
        if (!$request->isXmlHttpRequest())
        {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        $db_handler = $this->get('db_handler');
        $item = $request->request->get('item');
        $item_id = $request->request->get('id');
        $comments = $db_handler->getCommentsById($item_id, $item);
        if($comments != 0) {
            $return = new JsonResponse($comments, 200);
        }else{
            return 0;
        }
        return $return;
    }
    public function commentAddAction(Request $request){
        if (!$request->isXmlHttpRequest())
        {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        $db_handler = $this->get('db_handler');
        $user = $this->getUser();
        $comment = $request->request->get('text');
        $return = null;
//        $item_id = $request->request->get('id');
//        $comments = $db_handler->getCommentsById($item_id, $item);
//        if($comments != 0) {
//            $return = new JsonResponse($comments, 200);
//        }else{
//            return 0;
//        }
        return $return;
    }
}
