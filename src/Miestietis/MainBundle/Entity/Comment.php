<?php
namespace Miestietis\MainBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
     * @ORM\Entity
     * @ORM\Table(name="comments")
     */
class Comment
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user_id;
    /**
     * @ORM\ManyToOne(targetEntity="Problema", inversedBy="comments")
     * @ORM\JoinColumn(name="problem_id", referencedColumnName="id")
     */
    protected $problem_id;
    /**
     * @ORM\ManyToOne(targetEntity="Initiative", inversedBy="comments")
     * @ORM\JoinColumn(name="initiative_id", referencedColumnName="id")
     */
    protected $initiative_id;
    /**
     * @ORM\Column(type="string")
     */
    protected $date;
    /**
     * @ORM\Column(type="text")
     */
    protected $text;


    //----------------------------------------------------------------------------

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return Comment
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set userId
     *
     * @param \Miestietis\MainBundle\Entity\User $userId
     *
     * @return Comment
     */
    public function setUserId(\Miestietis\MainBundle\Entity\User $userId = null)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return \Miestietis\MainBundle\Entity\User
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set problemId
     *
     * @param \Miestietis\MainBundle\Entity\Problema $problemId
     *
     * @return Comment
     */
    public function setProblemId(\Miestietis\MainBundle\Entity\Problema $problemId = null)
    {
        $this->problem_id = $problemId;

        return $this;
    }

    /**
     * Get problemId
     *
     * @return \Miestietis\MainBundle\Entity\Problema
     */
    public function getProblemId()
    {
        return $this->problem_id;
    }

    /**
     * Set initiativeId
     *
     * @param \Miestietis\MainBundle\Entity\Initiative $initiativeId
     *
     * @return Comment
     */
    public function setInitiativeId(\Miestietis\MainBundle\Entity\Initiative $initiativeId = null)
    {
        $this->initiative_id = $initiativeId;

        return $this;
    }

    /**
     * Get initiativeId
     *
     * @return \Miestietis\MainBundle\Entity\Initiative
     */
    public function getInitiativeId()
    {
        return $this->initiative_id;
    }
}
