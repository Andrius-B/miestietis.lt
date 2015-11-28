<?php

namespace Miestietis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

class AjaxController extends Controller
{
    public function problemAction(Request $request){
        if (!$request->isXmlHttpRequest()) {
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

}
