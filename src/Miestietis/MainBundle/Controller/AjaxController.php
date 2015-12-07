<?php

namespace Miestietis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints\DateTime;


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

    public function deleteItemAction(Request $request){
        if (!$request->isXmlHttpRequest())
        {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        $type = $request->request->get('type');
        $itemId = $request->request->get('itemId');
        $db_handler = $this->get('db_handler');
        $user = $this->getUser();
        $status = '';
        if($type == 'problem'){
            $problema = $this->getDoctrine()->getRepository("MiestietisMainBundle:Problema")->find($itemId);
                if ($problema->getUserId() == $user) {
                    $db_handler->deleteProblem($problema, $user);
                } else {
                    $status = 'Galite trinti tik problemas, kurias sukurėte';
                }
        } else if($type == 'initiative'){
            $initiative = $this->getDoctrine()->getRepository("MiestietisMainBundle:Initiative")->find($itemId);
            if($initiative->getUserId() == $user){
                $db_handler->deleteInitiative($initiative, $user);
            }else{
                $status = 'Galite trinti tik iniciatyvas, kurias sukurėte';
            }
        }else{
            $status = 'Netinkamas tipas';
        }

        $data = array('type'=>$type, 'itemId'=>$itemId, 'status'=>$status);
        $response = new JsonResponse($data, 200);
        return $response;//$data;
    }

    public function initiativeEditAction(Request $request){
        if (!$request->isXmlHttpRequest())
        {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        $description = $request->request->get('description');
        $datestr = $request->request->get('date');
        $date = new \DateTime($datestr); //MUST BE FORMATTED Y-m-d h:i;s
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
        $datestr = $request->request->get('date');
        $date = new \DateTime($datestr);
        $probId = intval($request->request->get('probId'));

        $problem = $this->getDoctrine()
            ->getRepository('MiestietisMainBundle:Problema')
            ->find($probId);

        if($this->getDoctrine()->getRepository('MiestietisMainBundle:Initiative')->findBy(array('problem_id'=>$problem)) != null)
        {
            $status = 'Ši problema jau turi iniciatyvą!';
            $data = array('description' => $description, 'date'=>$date->format("Y m d"), 'probId'=>$problem, 'status'=>$status);
            $response = new JsonResponse($data, 200);
            return $response;
        }

        $data = array('description' => $description, 'date'=>$date->format('Y-m-d H:i'), 'probId'=>$problem, 'status'=>$status);
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
        $problems = $this->getDoctrine()
            ->getRepository('MiestietisMainBundle:Problema')
            ->findBy(array('user_id'=>$user, 'is_active' => 1));
        $initiatives = $this->getDoctrine()
            ->getRepository('MiestietisMainBundle:Initiative')
            ->findBy(array('user_id'=>$user));
        $init = $this->getDoctrine()
            ->getRepository('MiestietisMainBundle:Initiative')
            ->findAll();
        $participations = [];
        foreach ($init as $i) {
            if($i->getParticipants()->contains($user) && $i->getUserId() != $user ) {
                $participations[] = $i;
            }
        }
        $items =  array_merge($problems, $initiatives, $participations);

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
    public function initiativeJoinAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        $db_handler = $this->get('db_handler');
        $user = $this->getUser();
        $id = $request->request->get('initiative');
        $data = array('response' => 'ok');
        if ($db_handler->joinInitiative($id, $user)) {
            $response = new JsonResponse($data, 200);
            return $response;
        } else {
            $response = new JsonResponse(array('response' => 'error'));
            return $response;
        }
    }

    // load more items to the system

    public function loadMoreAction(){
        $user = $this->getUser();
        $counter = $this->get('counter');
        $session =  $this->container->get('session');
        $ob_former = $this->get('ob_formation');
        $problems = [];
        $initiatives = [];
        $poffset = $session->get('problems');
        $ioffset = $session->get('initiatives');
        if($poffset>0){
            $problems = $ob_former->getProblems( 10, $poffset);
        }
        if($poffset>0){
            $initiatives = $ob_former->getInitiatives( 10, $ioffset);
        }
        $p_count = count($problems);
        $i_count = count($initiatives);
        if($p_count<10){
            $session->set('problems', -1);
        }else{
            $session->set('problems', $session->get('problems')+10);
        }
        if($i_count<10){
            $session->set('initiatives', -1);
        }else{
            $session->set('initiatives', $session->get('initiatives')+10);
        }
        $more = true;
        // Process problem status and tooltip values
        $this->get('item_type')->itemType($problems, $initiatives, null, $user, $this->get('security.authorization_checker'));

        $problemsTemp = [];
        $initiativesTemp = [];
        $problemComments = $counter->problemCommentCount($problems);
        $initiativeComments = $counter->initiativeCommentCount($initiatives);
        $participants = $counter->joinCount($initiatives);

        foreach($problems as $p) {
            $problemsTemp[] = $this->renderView('items.html.twig', array(
                'problem' => $p,
                'user' => $user,
                'problemCommentCount' => $problemComments,
                // ...
            ));
        }
        foreach($initiatives as $i) {
            $initiativesTemp[] = $this->renderView('initiatives.html.twig', array(
                'problem' => $i,
                'user' => $user,
                'problemCommentCount' => $initiativeComments,
                'participantCount' => $participants
                // ...
            ));
        }

        if($i_count<10&&$p_count<10) $more =false;

        $a = array('p_count' => $p_count,
            'i_count' => $i_count,
            'problems'=>$problemsTemp,
            'initiatives'=>$initiatives,
            'more' => $more);
        $result = new JsonResponse($a, 200);

        return $result;
    }
}
