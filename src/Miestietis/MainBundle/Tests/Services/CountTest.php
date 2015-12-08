<?php
namespace Miestietis\MainBundle\Tests\Services;

use Miestietis\MainBundle\Services\Count;
use Miestietis\MainBundle\Entity\Problema;
use Miestietis\MainBundle\Entity\Initiative;
use Miestietis\MainBundle\Entity\Comment;
use Miestietis\MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CountTest extends WebTestCase{
    public function testProblemCommentCount()
    {
        $problema1 = new Problema();
        $problema1->setId(1);
        $problema2 = new Problema();
        $problema2->setId(2);
        $problema1->addComment(new Comment());
        $problema2->addComment(new Comment());
        $problema2->addComment(new Comment());
        $p_array = [];
        $p_array[] = $problema1;
        $p_array[] = $problema2;

        $counter = new Count();
        $comment_count = $counter->problemCommentCount($p_array);

        $this->assertCount(2, $comment_count);
        $this->assertEquals(1, $comment_count[1]);
        $this->assertEquals(2, $comment_count[2]);
    }
    public function testInitiativeCommentCount()
    {
        $problema1 = new Problema();
        $problema1->setId(1);
        $problema2 = new Problema();
        $problema2->setId(2);
        $problema1->addComment(new Comment());

        $problema2->addComment(new Comment());
        $problema2->addComment(new Comment());
        $problema2->addComment(new Comment());

        $initiative1 = new Initiative();
        $initiative1->setId(1);
        $initiative1->setProblemId($problema1);
        $initiative2 = new Initiative();
        $initiative2->setId(2);
        $initiative2->setProblemId($problema2);

        $i_array = array($initiative1, $initiative2);
        $counter = new Count();
        $comment_count = $counter->initiativeCommentCount($i_array);

        $this->assertCount(2, $comment_count);
        $this->assertEquals(1, $comment_count[1]);
        $this->assertEquals(3, $comment_count[2]);
    }
    public function testJoinCount()
    {

        $initiative1 = new Initiative();
        $initiative1->setId(1);
        $initiative1->addParticipant(new User());
        $initiative1->addParticipant(new User());
        $initiative1->addParticipant(new User());
        $initiative2 = new Initiative();
        $initiative2->setId(2);
        $initiative2->addParticipant(new User());
        $initiative2->addParticipant(new User());
        $initiative2->addParticipant(new User());
        $initiative2->addParticipant(new User());

        $i_array = array($initiative1, $initiative2);
        $counter = new Count();
        $join_count = $counter->joinCount($i_array);

        $this->assertCount(2, $join_count);
        $this->assertEquals(3, $join_count[1]);
        $this->assertEquals(4, $join_count[2]);
    }
}