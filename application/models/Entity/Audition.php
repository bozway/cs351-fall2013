<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Audition
 *
 * @ORM\Table(name="audition")
 * @ORM\Entity
 */
class Audition
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
     * @ORM\Column(name="cl", type="string", nullable=true)
     */
    private $cl;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationTime", type="datetime")
     */
    private $creationTime;

    /**
     * @var \Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Entity\User", inversedBy="auditions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="applicant_id", referencedColumnName="id")
     * })
     */
    private $applicant;

    /**
     * @var \Entity\Project
     *
     * @ORM\ManyToOne(targetEntity="Entity\Project", inversedBy="auditions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;

    /**
     * @var \Entity\ProjectSkill
     *
     * @ORM\ManyToOne(targetEntity="Entity\ProjectSkill", inversedBy="auditions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="skill_id", referencedColumnName="id")
     * })
     */
    private $skill;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\File")
     * @ORM\JoinTable(name="Audition_File",
     *   joinColumns={
     *     @ORM\JoinColumn(name="auditionID", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="fileID", referencedColumnName="id", unique=true)
     *   }
     * )
     */
    private $files;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set cl
     *
     * @param string $cl
     * @return Audition
     */
    public function setCl($cl)
    {
        $this->cl = $cl;
    
        return $this;
    }

    /**
     * Get cl
     *
     * @return string 
     */
    public function getCl()
    {
        return $this->cl;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Audition
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set creationTime
     *
     * @param \DateTime $creationTime
     * @return Audition
     */
    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;
    
        return $this;
    }

    /**
     * Get creationTime
     *
     * @return \DateTime 
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * Set applicant
     *
     * @param \Entity\User $applicant
     * @return Audition
     */
    public function setApplicant(\Entity\User $applicant = null)
    {
        $this->applicant = $applicant;
    
        return $this;
    }

    /**
     * Get applicant
     *
     * @return \Entity\User 
     */
    public function getApplicant()
    {
        return $this->applicant;
    }

    /**
     * Set project
     *
     * @param \Entity\Project $project
     * @return Audition
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
     * @param \Entity\ProjectSkill $skill
     * @return Audition
     */
    public function setSkill(\Entity\ProjectSkill $skill = null)
    {
        $this->skill = $skill;
    
        return $this;
    }

    /**
     * Get skill
     *
     * @return \Entity\ProjectSkill 
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * Add files
     *
     * @param \Entity\File $files
     * @return Audition
     */
    public function addFile(\Entity\File $files)
    {
        $this->files[] = $files;
    
        return $this;
    }

    /**
     * Remove files
     *
     * @param \Entity\File $files
     */
    public function removeFile(\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }
}
