<?php
namespace Miestietis\MainBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Miestietis\MainBundle\Entity\Problema;
use Miestietis\MainBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class Database
{
    private $em;
    public function __construct(EntityManager $entityManager){
        $this->em = $entityManager;
    }
    public function insertProblem($name, $description, $picture, User $user){
        $problem = new Problema();
        $time = getdate();

        $problem->setName($name);
        $problem->setPicture($picture);
        $problem->setDescription($description);
        $problem->setUserId($user);
        $problem->setVotes(0);
        $problem->setIsActive(true);
        $problem->setDate($time['year'].' '.$time['mon'].' '.$time['yday']);

        if($this->em == null){
            return 0;
        }
        $this->em->persist($problem);
        $this->em->flush();

        return $problem;
    }
    public function forTest($a){
        return ++$a;
    }
}