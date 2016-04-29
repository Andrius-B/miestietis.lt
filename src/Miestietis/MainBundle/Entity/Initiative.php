<?php

namespace Miestietis\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="initiatives")
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="problems")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $userId;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="problem_id")
     */
    protected $comments;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $votes;

    /**
     * @ORM\Column(type="string")
     */
    protected $picture;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isActive;

    /**
     * @ORM\Column(name="registration_date", type="datetime")
     */
    protected $registration_date;

    /**
     * @ORM\Column(name="initiative_date", type="datetime")
     */
    protected $initiativeDate;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="participations")
     * @ORM\JoinTable(name="initiatives_join",
     * joinColumns={@ORM\JoinColumn(name="initiative_id", referencedColumnName="id", onDelete="CASCADE")},
     * inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")})
     */
    protected $participants;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="upvoted_problems")
     * @ORM\JoinTable(name="initiative_upvotes",
     *      joinColumns={@ORM\JoinColumn(name="initiative_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $upvoted_by;

    public function __construct()
    {
        $this->upvoted_by = new ArrayCollection();
    }
    public function upvoteBy(User $user)
    {
        $this->upvoted_by->add($user);
        return $this;
    }
    /** @return ArrayCollection */
    public function getUpvotedBy()
    {
        return $this->upvoted_by;
    }

    public function  incrementVote()
    {
        $this->votes++;
        return $this->votes;
    }

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
     * Set id
     * @param integer $id
     * @return Initiative
     */
    public function setId($id)
    {
        $this->id =$id;
        return $this;
    }

    /**
     * Set userId
     *
     * @param User $userId
     *
     * @return Initiative
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return User
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Initiative
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Set picture
     *
     * @param string $picture
     *
     * @return Initiative
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
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
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add comment
     *
     * @param \Miestietis\MainBundle\Entity\Comment $comment
     *
     * @return Initiative
     */
    public function addComment(\Miestietis\MainBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \Miestietis\MainBundle\Entity\Comment $comment
     */
    public function removeComment(\Miestietis\MainBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add upvotedBy
     *
     * @param \Miestietis\MainBundle\Entity\User $upvotedBy
     *
     * @return Initiative
     */
    public function addUpvotedBy(\Miestietis\MainBundle\Entity\User $upvotedBy)
    {
        $this->upvoted_by[] = $upvotedBy;

        return $this;
    }

    /**
     * Remove upvotedBy
     *
     * @param \Miestietis\MainBundle\Entity\User $upvotedBy
     *
     * @return Initiative
     */
    public function removeUpvotedBy(\Miestietis\MainBundle\Entity\User $upvotedBy)
    {
        $this->upvoted_by->removeElement($upvotedBy);

        return $this;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Initiative
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set registrationDate
     *
     * @param \DateTime $registrationDate
     *
     * @return Initiative
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return \DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Set initiativeDate
     *
     * @param \DateTime $initiativeDate
     *
     * @return Initiative
     */
    public function setInitiativeDate($initiativeDate)
    {
        $this->initiativeDate = $initiativeDate;

        return $this;
    }

    /**
     * Get initiativeDate
     *
     * @return \DateTime
     */
    public function getInitiativeDate()
    {
        return $this->initiativeDate;
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
}
