<?php
namespace Miestietis\MainBundle\Services;

use Miestietis\MainBundle\Entity\Problema;
use Miestietis\MainBundle\Entity\Initiative;
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
        $problem->setDate($time['year'].' '.$time['mon'].' '.$time['mday']);

        if($this->em == null){
            return 0;
        }
        $this->em->persist($problem);
        $this->em->flush();

        return $problem;
    }

    public function insertInitiative($description, $date,Problema $probId, User $user){
        $initiative = new Initiative();

        $initiative->setVotes(0);
        $initiative->setIsActive(true);
        $initiative->setRegistrationDate(date("Y m d"));
        $initiative->setProblemId($probId);
        $initiative->setUserId($user);
        $initiative->setInitiativeDate($date);
        $initiative->setDescription($description);
        $probId->setInitiative($initiative); //inserts initiative ref to problem
        $this->em->persist($initiative);
        $this->em->flush();

        return $initiative;
    }
    public function deleteInitiative(Initiative $initiative, User $user){
        $this->em->remove($initiative);
        //still need to remove the initiative from problema and maybe check for privelages
    }

    public function upvoteProblem(Problema $problem,User $user)
    {
        $votes = $problem->incrementVote();
        $user->upvoteProblem($problem);
        $this->em->persist($problem);
        $this->em->persist($user);
        $this->em->flush();
        return $votes;
    }
    public function forTest($a){
        return ++$a;

    }
}