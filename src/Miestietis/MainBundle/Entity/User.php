<?php
//  puslapis:    https://gist.github.com/danvbe/4476697


namespace Miestietis\MainBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Miestietis\MainBundle\Repository\UserRepository")
 * @ORM\Table(name="lcl_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebook_id;
    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;
    /** @ORM\Column(name="google_id", type="string", length=255, nullable=true) */
    protected $google_id;
    /** @ORM\Column(name="google_access_token", type="string", length=255, nullable=true) */
    protected $google_access_token;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_picture", type="string", length=250, nullable=true)
     *
     */
    protected $profilePicture;
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=250, nullable=true)
     *
     */
    protected $firstName;
    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=250, nullable=true)
     *
     */
    protected $lastName;
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user_id")
     */
    protected $comments;
    /**
     * @ORM\OneToMany(targetEntity="Initiative", mappedBy="user_id")
     */
    protected $initiatives;
    /**
     * @ORM\ManyToMany(targetEntity="Initiative", mappedBy="participants")
     */
    protected $participations;

    /**
     * @ORM\ManyToMany(targetEntity="Initiative", mappedBy="upvoted_by")
     */
    protected $upvotedInitiatives;

    public function __construct()
    {
        parent::__construct();
        $this->initiatives = new ArrayCollection();
        $this->initiatives = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->upvotedInitiatives = new ArrayCollection();
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
     *
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    /**
     * Set facebook_id
     *
     * @param string $facebook_id
     *
     * @return User
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;

        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }
    /**
     * Set facebook_access_token
     *
     * @param string $facebook_access_token
     *
     * @return User
     */
    public function setFacebookAccessToken($facebook_access_token)
    {
        $this->facebook_access_token = $facebook_access_token;

        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Set googleId
     *
     * @param string $googleId
     *
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->google_id = $googleId;

        return $this;
    }

    /**
     * Get googleId
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Set googleAccessToken
     *
     * @param string $googleAccessToken
     *
     * @return User
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->google_access_token = $googleAccessToken;

        return $this;
    }

    /**
     * Get googleAccessToken
     *
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->google_access_token;
    }
    /**
     * Set profilePicture
     *
     * @param string $profilePicture
     *
     * @return User
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }
    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLAstName()
    {
        return $this->lastName;
    }

    /**
     * Add upvoted problem
     *
     * @param \Miestietis\MainBundle\Entity\Initiative $problem
     *
     * @return User
     */
    public function upvoteProblem(\Miestietis\MainBundle\Entity\Initiative $problem){
        $this->upvotedInitiatives->add($problem);
        $problem->upvoteBy($this);
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUpvotedInitiatives(){
        return $this->upvotedInitiatives;
    }

    public function isUpvotedProblem(Initiative $problema){
        if($this->upvotedInitiatives->contains($problema))return true;
        else return false;
    }

    public function removeUpvotedProblem(Initiative $problema){
        $this->upvotedInitiatives->removeElement($problema);
        return $this;
    }

    /**
     * Add problem
     *
     * @param \Miestietis\MainBundle\Entity\Initiative $problem
     *
     * @return User
     */
    public function addProblem(\Miestietis\MainBundle\Entity\Initiative $problem)
    {
        $this->initiatives->add($problem);

        return $this;
    }

    /**
     * Remove problem
     *
     * @param \Miestietis\MainBundle\Entity\Initiative $problem
     */
    public function removeProblem(\Miestietis\MainBundle\Entity\Initiative $problem)
    {
        $this->initiatives->removeElement($problem);
    }

    /**
     * Get Initiatives
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInitiatives()
    {
        return $this->initiatives;
    }

    /**
     * Add initiative
     *
     * @param \Miestietis\MainBundle\Entity\Initiative $initiative
     *
     * @return User
     */
    public function addInitiative(\Miestietis\MainBundle\Entity\Initiative $initiative)
    {
        $this->initiatives[] = $initiative;

        return $this;
    }

    /**
     * Remove initiative
     *
     * @param \Miestietis\MainBundle\Entity\Initiative $initiative
     */
    public function removeInitiative(\Miestietis\MainBundle\Entity\Initiative $initiative)
    {
        $this->initiatives->removeElement($initiative);
    }

    /**
     * Add participation
     *
     * @param \Miestietis\MainBundle\Entity\Initiative $participation
     *
     * @return User
     */
    public function addParticipation(\Miestietis\MainBundle\Entity\Initiative $participation)
    {
        $this->participations[] = $participation;

        return $this;
    }

    /**
     * Remove participation
     *
     * @param \Miestietis\MainBundle\Entity\Initiative $participation
     */
    public function removeParticipation(\Miestietis\MainBundle\Entity\Initiative $participation)
    {
        $this->participations->removeElement($participation);
    }

    /**
     * Get participations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipations()
    {
        return $this->participations;
    }
}
