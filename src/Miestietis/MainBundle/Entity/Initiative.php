<?php
// src/Miestietis/MainBundle/Entity/Product.php
namespace Miestietis\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="initiative")
 */
class Initiative
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="initiatives")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user_id;
    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="participations")
     * @ORM\JoinTable(name="initiatives_join")
     */
    protected $participants;
    /**
     * @ORM\OneToOne(targetEntity="Problema")
     * @ORM\JoinColumn(name="problem_id", referencedColumnName="id")
     */
    protected $problem_id;
    /**
     * @ORM\Column(type="string")
     */
    protected $registration_date;
    /**
     * @ORM\Column(type="string")
     */
    protected $initiative_date;
    /**
     * @ORM\Column(type="text")
     */
    protected $description;
    /**
     * @ORM\Column(type="smallint")
     */
    protected $votes;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_active;

    public function __construct() {
        //$this->participants = new ArrayCollection();
    }

    //------------------------------------------------------------------
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
     * Set registrationDate
     *
     * @param string $registrationDate
     *
     * @return Initiative
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registration_date = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return string
     */
    public function getRegistrationDate()
    {
        return $this->registration_date;
    }

    /**
     * Set initiativeDate
     *
     * @param string $initiativeDate
     *
     * @return Initiative
     */
    public function setInitiativeDate($initiativeDate)
    {
        $this->initiative_date = $initiativeDate;

        return $this;
    }

    /**
     * Get initiativeDate
     *
     * @return string
     */
    public function getInitiativeDate()
    {
        return $this->initiative_date;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Initiative
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set votes
     *
     * @param integer $votes
     *
     * @return Initiative
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * Get votes
     *
     * @return integer
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Initiative
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set userId
     *
     * @param \Miestietis\MainBundle\Entity\User $userId
     *
     * @return Initiative
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
     * Add participant
     *
     * @param \Miestietis\MainBundle\Entity\User $participant
     *
     * @return Initiative
     */
    public function addParticipant(\Miestietis\MainBundle\Entity\User $participant)
    {
        $this->participants[] = $participant;

        return $this;
    }

    /**
     * Remove participant
     *
     * @param \Miestietis\MainBundle\Entity\User $participant
     */
    public function removeParticipant(\Miestietis\MainBundle\Entity\User $participant)
    {
        $this->participants->removeElement($participant);
    }

    /**
     * Get participants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * Set problemId
     *
     * @param \Miestietis\MainBundle\Entity\Problema $problemId
     *
     * @return Initiative
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
}
