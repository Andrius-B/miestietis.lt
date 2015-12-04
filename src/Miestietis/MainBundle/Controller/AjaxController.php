<?php

namespace Miestietis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class AjaxController extends Controller
{

    public function problemAction(Request $request)
    {
        if (!$request->isXmlHttpRequest())
        {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        // Getting everything from the request
        $db_handler = $this->get('db_handler');
        $image_handler = $this->get('image_handler');
        $user = $this->getUser();

        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $file = $request->files->get('file');

        //Get file extension
        $ext = $file->guessExtension();
        //Setting a unique file name
        $fileName = md5(uniqid()).'.'.$ext;
        //Setting the file directory
        $fileDir = $this->container->getParameter('kernel.root_dir').'/../web/public/images/problems/';

        //Crop, resize and save a file
        $image_handler->handleImage($file, $ext, 750, 500, $fileDir, $fileName);

        //Persisting problem to a database
        $db_handler->insertProblem($name, $description, $fileName, $user);

        //Forming a response
        $data = array('name' => $name, 'description' => $description, 'picture' => $fileName);
        $response = new JsonResponse($data, 200);
        return $response;//$data;
    }

    public function getUserStatsAction(Request $request){
        $user = $this->getUser();
        $db_handler = $this->get('db_handler');
        $data = $db_handler->getUserStats($user);
        $response = new JsonResponse($data, 200);
        return $response;//$data;
    }

    public function problemEditAction(Request $request)
    {
        if (!$request->isXmlHttpRequest())
        {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        // Getting everything from the request
        $db_handler = $this->get('db_handler');
        $probId = $request->request->get('probId');
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $address = $request->request->get('address');

        $problem = $this->getDoctrine()->getRepository('MiestietisMainBundle:Problema')->find($probId);

        //Forming a response
        $data = array('name' => $name, 'description' => $description);
        //Persisting problem to a database

        $db_handler->editProblem($name, $description, $address, $problem);

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
        $response = [];
        foreach($comments as $comment){
            $response[] = array('user_picture'=> $comment->getUserId()->getProfilePicture(),
                'user_name'=> $comment->getUserId()->getFirstName().' '. $comment->getUserId()->getLastName() ,
                'date'=> $comment->getDate(),
                'comment'=> $comment->getText());
        }
        if($comments != 0) {
            $return = new JsonResponse($response, 200);
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
        $comment = $request->request->get('comment');
        $item_id = $request->request->get('item_id');
        $item = $request->request->get('item');
        //$c = array('comment' => $comment, 'item id' => $item_id, 'item' => $item);
        $db_handler->insertComment($item, $item_id, $comment, $user);
        $c = array('text' => $comment, 'user_name' => $user->getFirstName().' '.$user->getLastName(),
            'date' => date('Y m d'), 'picture' => $user->getProfilePicture());

        $return = new JsonResponse($c, 200);
        return $return;
    }

//    public function editAction(Request $request)
//    {
//        if (!$request->isXmlHttpRequest())
//        {
//            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
//        }
//    }
}
