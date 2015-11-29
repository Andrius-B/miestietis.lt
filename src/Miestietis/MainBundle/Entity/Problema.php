<?php
// src/Miestietis/MainBundle/Entity/Product.php
namespace Miestietis\MainBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 * @ORM\Table(name="problema")
 */
class Problema
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
    protected $user_id;
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;
    /**
     * @ORM\Column(type="string")
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
     *
     * @Vich\UploadableField(mapping="problem_image", fileNameProperty="picture")
     *
     * @var File
     */
    private $imageFile;
    /**
     * @ORM\Column(type="string")
     */
    protected $picture;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_active;
    /**
     * @ORM\OneToOne(targetEntity="Initiative", mappedBy="problem_id")
     */
    private $initiative;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="upvoted_problems")
     * @ORM\JoinTable(name="problema_upvotes",
     *      joinColumns={@ORM\JoinColumn(name="problema_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    private $upvoted_by;


    // -------------------------------------------------

    public function __construct(){
        $this->upvoted_by = new ArrayCollection();
    }
    public function upvoteBy(User $user){
        $this->upvoted_by->add($user);
        return $this;
    }
    /** @return ArrayCollection */
    public function getUpvotedBy(){
        return $this->upvoted_by;
    }

    public function  incrementVote(){
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
     * Set userId
     *
     * @param User $userId
     *
     * @return Problema
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return User
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Problema
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Problema
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
     * Set description
     *
     * @param string $description
     *
     * @return Problema
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
     * @return Problema
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
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {

            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }
    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Problema
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
     * @return Problema
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
     * Set initiative
     *
     * @param \Miestietis\MainBundle\Entity\Initiative $initiative
     *
     * @return Problema
     */
    public function setInitiative(\Miestietis\MainBundle\Entity\Initiative $initiative = null)
    {
        $this->initiative = $initiative;

        return $this;
    }

    /**
     * Get initiative
     *
     * @return \Miestietis\MainBundle\Entity\Initiative
     */
    public function getInitiative()
    {
        return $this->initiative;
    }
}
