<?php
namespace Miestietis\MainBundle\Services;

use Miestietis\MainBundle\Entity\Initiative;
use Doctrine\ORM\EntityManager;

class Formation{

    private $em;
    public function __construct(EntityManager $entityManager){
        $this->em = $entityManager;
    }
    public function getAllProblems(){
        $query = $this->em->createQuery(
            'SELECT p
            FROM MiestietisMainBundle:Problema p
            WHERE p.is_active = 1
            ORDER BY p.votes DESC'
        );
        $problems = $query->getResult();
        return $problems;
    }
    public function getProblems( $n, $offset){
        $query = $this->em->createQuery(
            'SELECT p
            FROM MiestietisMainBundle:Problema p
            WHERE p.is_active = 1
            ORDER BY p.votes DESC'
        )->setMaxResults($n)->setFirstResult($offset);
        $problems = $query->getResult();
        return $problems;
    }
    public function getInitiatives( $n, $offset){
        $query = $this->em->createQuery(
            'SELECT i
            FROM MiestietisMainBundle:Initiative i
            ORDER BY i.votes DESC'
        )->setMaxResults($n)->setFirstResult($offset);
        $problems = $query->getResult();
        return $problems;
    }
    public function formInitiative(){
        $initiative = new Initiative();
        $initiative->setVotes(0);
        $initiative->setIsActive(true);
        $initiative->setRegistrationDate(date("Y-m-d"));
        return $initiative;
    }

}