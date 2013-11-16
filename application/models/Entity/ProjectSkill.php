<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectSkill
 *
 * @ORM\Table(name="projectskill")
 * @ORM\Entity
 */
class ProjectSkill
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isOpen", type="boolean")
     */
    private $isOpen;

    /**
     * @var string
     *
     * @ORM\Column(name="requiredFileList", type="string", nullable=true)
     */
    private $requiredFileList;

    /**
     * @var float
     *
     * @ORM\Column(name="preSplit", type="float", nullable=true)
     */
    private $preSplit;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\Audition", mappedBy="skill")
     */
    private $auditions;

    /**
     * @var \Entity\Project
     *
     * @ORM\ManyToOne(targetEntity="Entity\Project")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;

    /**
     * @var \Entity\Skill
     *
     * @ORM\ManyToOne(targetEntity="Entity\Skill")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="skill_id", referencedColumnName="id")
     * })
     */
    private $skill;

    /**
     * @var \Entity\ProjectMember
     *
     * @ORM\ManyToOne(targetEntity="Entity\ProjectMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="performer_id", referencedColumnName="id")
     * })
     */
    private $performer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Genre")
     * @ORM\JoinTable(name="projectskill_genre",
     *   joinColumns={
     *     @ORM\JoinColumn(name="projectskill_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="genre_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $genres;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Influence")
     * @ORM\JoinTable(name="projectskill_influence",
     *   joinColumns={
     *     @ORM\JoinColumn(name="projectskill_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="influence_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $influences;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->auditions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->influences = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set description
     *
     * @param string $description
     * @return ProjectSkill
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
     * Set isOpen
     *
     * @param boolean $isOpen
     * @return ProjectSkill
     */
    public function setIsOpen($isOpen)
    {
        $this->isOpen = $isOpen;
    
        return $this;
    }

    /**
     * Get isOpen
     *
     * @return boolean 
     */
    public function getIsOpen()
    {
        return $this->isOpen;
    }

    /**
     * Set requiredFileList
     *
     * @param string $requiredFileList
     * @return ProjectSkill
     */
    public function setRequiredFileList($requiredFileList)
    {
        $this->requiredFileList = $requiredFileList;
    
        return $this;
    }

    /**
     * Get requiredFileList
     *
     * @return string 
     */
    public function getRequiredFileList()
    {
        return $this->requiredFileList;
    }

    /**
     * Set preSplit
     *
     * @param float $preSplit
     * @return ProjectSkill
     */
    public function setPreSplit($preSplit)
    {
        $this->preSplit = $preSplit;
    
        return $this;
    }

    /**
     * Get preSplit
     *
     * @return float 
     */
    public function getPreSplit()
    {
        return $this->preSplit;
    }

    /**
     * Add auditions
     *
     * @param \Entity\Audition $auditions
     * @return ProjectSkill
     */
    public function addAudition(\Entity\Audition $auditions)
    {
        $this->auditions[] = $auditions;
    
        return $this;
    }

    /**
     * Remove auditions
     *
     * @param \Entity\Audition $auditions
     */
    public function removeAudition(\Entity\Audition $auditions)
    {
        $this->auditions->removeElement($auditions);
    }

    /**
     * Get auditions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAuditions()
    {
        return $this->auditions;
    }

    /**
     * Set project
     *
     * @param \Entity\Project $project
     * @return ProjectSkill
     */
    public function setProject(\Entity\Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set skill
     *
     * @param \Entity\Skill $skill
     * @return ProjectSkill
     */
    public function setSkill(\Entity\Skill $skill = null)
    {
        $this->skill = $skill;
    
        return $this;
    }

    /**
     * Get skill
     *
     * @return \Entity\Skill 
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * Set performer
     *
     * @param \Entity\ProjectMember $performer
     * @return ProjectSkill
     */
    public function setPerformer(\Entity\ProjectMember $performer = null)
    {
        $this->performer = $performer;
    
        return $this;
    }

    /**
     * Get performer
     *
     * @return \Entity\ProjectMember 
     */
    public function getPerformer()
    {
        return $this->performer;
    }

    /**
     * Add genres
     *
     * @param \Entity\Genre $genres
     * @return ProjectSkill
     */
    public function addGenre(\Entity\Genre $genres)
    {
        $this->genres[] = $genres;
    
        return $this;
    }

    /**
     * Remove genres
     *
     * @param \Entity\Genre $genres
     */
    public function removeGenre(\Entity\Genre $genres)
    {
        $this->genres->removeElement($genres);
    }

    /**
     * Get genres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * Add influences
     *
     * @param \Entity\Influence $influences
     * @return ProjectSkill
     */
    public function addInfluence(\Entity\Influence $influences)
    {
        $this->influences[] = $influences;
    
        return $this;
    }

    /**
     * Remove influences
     *
     * @param \Entity\Influence $influences
     */
    public function removeInfluence(\Entity\Influence $influences)
    {
        $this->influences->removeElement($influences);
    }

    /**
     * Get influences
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInfluences()
    {
        return $this->influences;
    }
}
