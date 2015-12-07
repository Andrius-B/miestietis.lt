<?php
namespace Miestietis\MainBundle\Services;

use Miestietis\MainBundle\Entity\Problema;
use Miestietis\MainBundle\Entity\Initiative;
use Miestietis\MainBundle\Entity\User;
use Miestietis\MainBundle\Entity\Comment;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

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
        $problem->setDate(new \DateTime(date('Y-m-d H:i:s')));

        if($this->em == null){
            return 0;
        }
        $this->em->persist($problem);
        $this->em->flush();

        return $problem;
    }

    public function editProblem($name, $description, $address, $problem){
        if($name!=''){ //should this validation be front-end?
            $problem->setName($name);
        }
        if($description!=''){
            $problem->setDescription($description);
        }

        if($this->em == null){
            return 0;
        }
        $this->em->persist($problem);
        $this->em->flush();

        return $problem;
    }

    public function editInitiative($description,DateTime $date, $initiative){
        $initiative->setIsActive(true);
        $initiative->setRegistrationDate(new \DateTime(date('Y-m-d H:i:s')));
        $initiative->setInitiativeDate($date);
        $initiative->setDescription($description);
        $this->em->persist($initiative);
        $this->em->flush();

        return $initiative;
    }

    public function insertInitiative($description, $date,Problema $probId, User $user){
        $initiative = new Initiative();

        $initiative->setVotes(0);
        $initiative->setIsActive(true);
        $initiative->setRegistrationDate(new \DateTime(date('Y-m-d H:i:s')));
        $initiative->setProblemId($probId);
        $initiative->setUserId($user);
        $initiative->setInitiativeDate($date);
        $initiative->setDescription($description);
        $probId->setInitiative($initiative); //inserts initiative ref to problem
        $probId->setIsActive(false);
        $this->em->persist($initiative);
        $this->em->persist($probId);
        $this->em->flush();

        return $initiative;
    }

    public function getUserStats($user){
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(problem.user_id)');
        $qb->where('problem.user_id = :user');
        $qb->from('MiestietisMainBundle:Problema','problem');
        $qb->setParameter('user', $user);
        $problemCount = $qb->getQuery()->getSingleScalarResult();

        $qb = $this->em->createQuery("
        SELECT COUNT(problems) FROM MiestietisMainBundle:Problema problems
        JOIN problems.upvoted_by voter
        WHERE voter.id = :user
        ")->setParameter('user', $user);
        $upvoteCount = $qb->getSingleScalarResult();

        return array('created'=>$problemCount, 'upvoted'=>$upvoteCount);
    }

    public function deleteInitiative(Initiative $initiative, User $user){
        //delete the initiative comments
        $user->removeInitiative($initiative);

        $commentQuery = $this->em->createQuery("
        DELETE MiestietisMainBundle:Comment c
        WHERE c.initiative_id = :init
        ")->setParameter('init',$initiative);
        $commentQuery->execute();

        $problem = $initiative->getProblemId();
        $problem->removeInitiative();
        $problem->setIsActive(true);//revive the problem
        $this->em->persist($problem);

        $this->em->remove($initiative);
        $this->em->flush();
        //still need to remove the initiative from problema
    }

    public function deleteProblem(Problema $problem, User $user){
        //need to authenticate the user
        //delete the problems comments
        $commentQuery = $this->em->createQuery("
        DELETE MiestietisMainBundle:Comment c
        WHERE c.problem_id = :problem
        ")->setParameter('problem',$problem);
        $commentQuery->execute();

        $user->removeProblem($problem);

        //find users who upvoted
        $usersUpvoted = $problem->getUpvotedBy();
        foreach($usersUpvoted as $voter){
            $problem = $problem->removeUpvotedBy($voter);
            $voter = $voter->removeUpvotedProblem($problem); //remove their upvote
            $this->em->persist($voter);
            $this->em->persist($problem);
            $this->em->flush();
        }

        //delete the problem
        $this->em->remove($problem);
        $this->em->flush();
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
    public function getCommentsById($id, $item){
        if($item == 'problem'){
            $query = $this->em->createQuery(
                "SELECT c
                FROM MiestietisMainBundle:Comment c
                WHERE c.problem_id = :problem
                ORDER BY c.date ASC"
            )->setParameter('problem', $id);
            $comments = $query->getResult();
            return $comments;

        }elseif($item == 'initiative'){
            $query = $this->em->createQuery(
                "SELECT c
                FROM MiestietisMainBundle:Comment c
                WHERE c.initiative_id = :initiative
                ORDER BY c.date ASC"
            )->setParameter('initiative', $id);
            $comments = $query->getResult();
            return $comments;
        }else{
            return 0;
        }
    }
    public function insertComment($item, $item_id, $text, $user){
        $comment = new Comment();
        $comment->setText($text);
        $comment->setUserId($user);
        $comment->setDate(date('Y m d'));
        if($item == 'problem'){
            // Finding a corresponding problem
            $query = $this->em->createQuery(
                "SELECT p
                FROM MiestietisMainBundle:Problema p
                WHERE p.id = :initiative"
            )->setParameter('initiative', $item_id);
            $problems = $query->getResult();
            foreach($problems as $problem) {
                $comment->setProblemId($problem);
            }
            $this->em->persist($comment);
            $this->em->flush();
            return $comment;

        }elseif($item == 'initiative'){
            $comment->setInitiativeId($item_id);
            $this->em->persist($comment);
            $this->em->flush();
            return $comment;
        }else{
            return 0;
        }
    }
    public function joinInitiative($id, $user){

        $initiative = $this->em->getRepository('MiestietisMainBundle:Initiative')->find($id);
        $initiative->addParticipant($user);
        $this->em->flush();
        return true;
    }
}