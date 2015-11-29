<?php
namespace Miestietis\MainBundle\Services;

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
            ORDER BY p.votes DESC'
        );
        $problems = $query->getResult();
        return $problems;
    }
    public function getProblems(int $n){
        $query = $this->em->createQuery(
            'SELECT p
            FROM MiestietisMainBundle:Problema p
            ORDER BY p.votes DESC'
        )->setMaxResults($n);
        $problems = $query->getResult();
        return $problems;
    }


}