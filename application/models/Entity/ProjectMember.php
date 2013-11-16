<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectMember
 *
 * @ORM\Table(name="projectmember")
 * @ORM\Entity
 */
class ProjectMember
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
     * @var integer
     *
     * @ORM\Column(name="role", type="integer")
     */
    private $role;

    /**
     * @var integer
     *
     * @ORM\Column(name="ranking", type="integer", nullable=true)
     */
    private $ranking;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visibility", type="boolean")
     */
    private $visibility;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationTime", type="datetime")
     */
    private $creationTime;

    /**
     * @var string
     *
     * @ORM\Column(name="past_participant_skills", type="string", length=100, nullable=true)
     */
    private $past_participant_skills;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\ProjectSkill", mappedBy="performer")
     */
    private $skillForProject;

    /**
     * @var \Entity\Project
     *
     * @ORM\ManyToOne(targetEntity="Entity\Project", inversedBy="members")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;

    /**
     * @var \Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Entity\User", inversedBy="projects")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->skillForProject = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set role
     *
     * @param integer $role
     * @return ProjectMember
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return integer 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set ranking
     *
     * @param integer $ranking
     * @return ProjectMember
     */
    public function setRanking($ranking)
    {
        $this->ranking = $ranking;
    
        return $this;
    }

    /**
     * Get ranking
     *
     * @return integer 
     */
    public function getRanking()
    {
        return $this->ranking;
    }

    /**
     * Set visibility
     *
     * @param boolean $visibility
     * @return ProjectMember
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    
        return $this;
    }

    /**
     * Get visibility
     *
     * @return boolean 
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set creationTime
     *
     * @param \DateTime $creationTime
     * @return ProjectMember
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
     * Set past_participant_skills
     *
     * @param string $pastParticipantSkills
     * @return ProjectMember
     */
    public function setPastParticipantSkills($pastParticipantSkills)
    {
        $this->past_participant_skills = $pastParticipantSkills;
    
        return $this;
    }

    /**
     * Get past_participant_skills
     *
     * @return string 
     */
    public function getPastParticipantSkills()
    {
        return $this->past_participant_skills;
    }

    /**
     * Add skillForProject
     *
     * @param \Entity\ProjectSkill $skillForProject
     * @return ProjectMember
     */
    public function addSkillForProject(\Entity\ProjectSkill $skillForProject)
    {
        $this->skillForProject[] = $skillForProject;
    
        return $this;
    }

    /**
     * Remove skillForProject
     *
     * @param \Entity\ProjectSkill $skillForProject
     */
    public function removeSkillForProject(\Entity\ProjectSkill $skillForProject)
    {
        $this->skillForProject->removeElement($skillForProject);
    }

    /**
     * Get skillForProject
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSkillForProject()
    {
        return $this->skillForProject;
    }

    /**
     * Set project
     *
     * @param \Entity\Project $project
     * @return ProjectMember
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
     * Set user
     *
     * @param \Entity\User $user
     * @return ProjectMember
     */
    public function setUser(\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
