<?php
namespace Miestietis\MainBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Miestietis\MainBundle\Entity\Problema;
use Miestietis\MainBundle\Entity\User;

class Database
{
    public function insertProblem($name, $description, $picture, User $user){
        $problem = new Problema();
        $time = getdate();
        $em = $this->getDoctrine()->getManager();

        $problem->setName($name);
        $problem->setPicture($picture);
        $problem->setDescription($description);
        $problem->setUserId($user);
        $problem->setVotes(0);
        $problem->setIsActive(true);
        $problem->setDate($time['year'].' '.$time['mon'].' '.$time['yday']);

        $em->persist($problem);
        $em->flush;

        return $problem;
    }

}