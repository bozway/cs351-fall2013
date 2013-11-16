<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity
 */
class Project
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
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isSave", type="boolean")
     */
    private $isSave;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", nullable=true)
     */
    private $city;

    /**
     * @var integer
     *
     * @ORM\Column(name="ranking", type="integer", nullable=true)
     */
    private $ranking;

    /**
     * @var string
     *
     * @ORM\Column(name="zipCode", type="string", nullable=true)
     */
    private $zipCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationTime", type="date")
     */
    private $creationTime;

    /**
     * @var array
     *
     * @ORM\Column(name="tags", type="array", nullable=true)
     */
    private $tags;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="listLength", type="string", nullable=true)
     */
    private $listLength;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=2000, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="completeTime", type="date", nullable=true)
     */
    private $completeTime;

    /**
     * @var string
     *
     * @ORM\Column(name="videoPreview", type="string", nullable=true)
     */
    private $videoPreview;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visibility", type="boolean", nullable=true)
     */
    private $visibility;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showCountry", type="boolean", nullable=true)
     */
    private $showCountry;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showCity", type="boolean", nullable=true)
     */
    private $showCity;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showAudioPreview", type="boolean", nullable=true)
     */
    private $showAudioPreview;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showTags", type="boolean", nullable=true)
     */
    private $showTags;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showDescription", type="boolean", nullable=true)
     */
    private $showDescription;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showLanguage", type="boolean", nullable=true)
     */
    private $showLanguage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showStartDate", type="boolean", nullable=true)
     */
    private $showStartDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showDuration", type="boolean", nullable=true)
     */
    private $showDuration;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showListLength", type="boolean", nullable=true)
     */
    private $showListLength;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showVideoPreview", type="boolean", nullable=true)
     */
    private $showVideoPreview;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastEditTime", type="datetime", nullable=true)
     */
    private $lastEditTime;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\Audition", mappedBy="project")
     */
    private $auditions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\ProjectSkill", mappedBy="project")
     */
    private $skills;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\ProjectMember", mappedBy="project")
     */
    private $members;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\ProjectFile", mappedBy="owner")
     */
    private $files;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\Contract", mappedBy="projects")
     */
    private $contracts;

    /**
     * @var \Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="countryCode", referencedColumnName="isoCode")
     * })
     */
    private $country;

    /**
     * @var \Entity\USState
     *
     * @ORM\ManyToOne(targetEntity="Entity\USState")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="state", referencedColumnName="abbreviatedName")
     * })
     */
    private $state;

    /**
     * @var \Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     * })
     */
    private $owner;

    /**
     * @var \Entity\ProjectFile
     *
     * @ORM\ManyToOne(targetEntity="Entity\ProjectFile")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="photo_id", referencedColumnName="id")
     * })
     */
    private $photo;

    /**
     * @var \Entity\Language
     *
     * @ORM\ManyToOne(targetEntity="Entity\Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="languageCode", referencedColumnName="isoCode")
     * })
     */
    private $language;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->auditions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->skills = new \Doctrine\Common\Collections\ArrayCollection();
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contracts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Project
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
     * Set isSave
     *
     * @param boolean $isSave
     * @return Project
     */
    public function setIsSave($isSave)
    {
        $this->isSave = $isSave;
    
        return $this;
    }

    /**
     * Get isSave
     *
     * @return boolean 
     */
    public function getIsSave()
    {
        return $this->isSave;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Project
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set ranking
     *
     * @param integer $ranking
     * @return Project
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
     * Set zipCode
     *
     * @param string $zipCode
     * @return Project
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    
        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string 
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set creationTime
     *
     * @param \DateTime $creationTime
     * @return Project
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
     * Set tags
     *
     * @param array $tags
     * @return Project
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    
        return $this;
    }

    /**
     * Get tags
     *
     * @return array 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Project
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    
        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Project
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    
        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set listLength
     *
     * @param string $listLength
     * @return Project
     */
    public function setListLength($listLength)
    {
        $this->listLength = $listLength;
    
        return $this;
    }

    /**
     * Get listLength
     *
     * @return string 
     */
    public function getListLength()
    {
        return $this->listLength;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
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
     * Set completeTime
     *
     * @param \DateTime $completeTime
     * @return Project
     */
    public function setCompleteTime($completeTime)
    {
        $this->completeTime = $completeTime;
    
        return $this;
    }

    /**
     * Get completeTime
     *
     * @return \DateTime 
     */
    public function getCompleteTime()
    {
        return $this->completeTime;
    }

    /**
     * Set videoPreview
     *
     * @param string $videoPreview
     * @return Project
     */
    public function setVideoPreview($videoPreview)
    {
        $this->videoPreview = $videoPreview;
    
        return $this;
    }

    /**
     * Get videoPreview
     *
     * @return string 
     */
    public function getVideoPreview()
    {
        return $this->videoPreview;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Project
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
     * Set visibility
     *
     * @param boolean $visibility
     * @return Project
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
     * Set showCountry
     *
     * @param boolean $showCountry
     * @return Project
     */
    public function setShowCountry($showCountry)
    {
        $this->showCountry = $showCountry;
    
        return $this;
    }

    /**
     * Get showCountry
     *
     * @return boolean 
     */
    public function getShowCountry()
    {
        return $this->showCountry;
    }

    /**
     * Set showCity
     *
     * @param boolean $showCity
     * @return Project
     */
    public function setShowCity($showCity)
    {
        $this->showCity = $showCity;
    
        return $this;
    }

    /**
     * Get showCity
     *
     * @return boolean 
     */
    public function getShowCity()
    {
        return $this->showCity;
    }

    /**
     * Set showAudioPreview
     *
     * @param boolean $showAudioPreview
     * @return Project
     */
    public function setShowAudioPreview($showAudioPreview)
    {
        $this->showAudioPreview = $showAudioPreview;
    
        return $this;
    }

    /**
     * Get showAudioPreview
     *
     * @return boolean 
     */
    public function getShowAudioPreview()
    {
        return $this->showAudioPreview;
    }

    /**
     * Set showTags
     *
     * @param boolean $showTags
     * @return Project
     */
    public function setShowTags($showTags)
    {
        $this->showTags = $showTags;
    
        return $this;
    }

    /**
     * Get showTags
     *
     * @return boolean 
     */
    public function getShowTags()
    {
        return $this->showTags;
    }

    /**
     * Set showDescription
     *
     * @param boolean $showDescription
     * @return Project
     */
    public function setShowDescription($showDescription)
    {
        $this->showDescription = $showDescription;
    
        return $this;
    }

    /**
     * Get showDescription
     *
     * @return boolean 
     */
    public function getShowDescription()
    {
        return $this->showDescription;
    }

    /**
     * Set showLanguage
     *
     * @param boolean $showLanguage
     * @return Project
     */
    public function setShowLanguage($showLanguage)
    {
        $this->showLanguage = $showLanguage;
    
        return $this;
    }

    /**
     * Get showLanguage
     *
     * @return boolean 
     */
    public function getShowLanguage()
    {
        return $this->showLanguage;
    }

    /**
     * Set showStartDate
     *
     * @param boolean $showStartDate
     * @return Project
     */
    public function setShowStartDate($showStartDate)
    {
        $this->showStartDate = $showStartDate;
    
        return $this;
    }

    /**
     * Get showStartDate
     *
     * @return boolean 
     */
    public function getShowStartDate()
    {
        return $this->showStartDate;
    }

    /**
     * Set showDuration
     *
     * @param boolean $showDuration
     * @return Project
     */
    public function setShowDuration($showDuration)
    {
        $this->showDuration = $showDuration;
    
        return $this;
    }

    /**
     * Get showDuration
     *
     * @return boolean 
     */
    public function getShowDuration()
    {
        return $this->showDuration;
    }

    /**
     * Set showListLength
     *
     * @param boolean $showListLength
     * @return Project
     */
    public function setShowListLength($showListLength)
    {
        $this->showListLength = $showListLength;
    
        return $this;
    }

    /**
     * Get showListLength
     *
     * @return boolean 
     */
    public function getShowListLength()
    {
        return $this->showListLength;
    }

    /**
     * Set showVideoPreview
     *
     * @param boolean $showVideoPreview
     * @return Project
     */
    public function setShowVideoPreview($showVideoPreview)
    {
        $this->showVideoPreview = $showVideoPreview;
    
        return $this;
    }

    /**
     * Get showVideoPreview
     *
     * @return boolean 
     */
    public function getShowVideoPreview()
    {
        return $this->showVideoPreview;
    }

    /**
     * Set lastEditTime
     *
     * @param \DateTime $lastEditTime
     * @return Project
     */
    public function setLastEditTime($lastEditTime)
    {
        $this->lastEditTime = $lastEditTime;
    
        return $this;
    }

    /**
     * Get lastEditTime
     *
     * @return \DateTime 
     */
    public function getLastEditTime()
    {
        return $this->lastEditTime;
    }

    /**
     * Add auditions
     *
     * @param \Entity\Audition $auditions
     * @return Project
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
     * Add skills
     *
     * @param \Entity\ProjectSkill $skills
     * @return Project
     */
    public function addSkill(\Entity\ProjectSkill $skills)
    {
        $this->skills[] = $skills;
    
        return $this;
    }

    /**
     * Remove skills
     *
     * @param \Entity\ProjectSkill $skills
     */
    public function removeSkill(\Entity\ProjectSkill $skills)
    {
        $this->skills->removeElement($skills);
    }

    /**
     * Get skills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Add members
     *
     * @param \Entity\ProjectMember $members
     * @return Project
     */
    public function addMember(\Entity\ProjectMember $members)
    {
        $this->members[] = $members;
    
        return $this;
    }

    /**
     * Remove members
     *
     * @param \Entity\ProjectMember $members
     */
    public function removeMember(\Entity\ProjectMember $members)
    {
        $this->members->removeElement($members);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Add files
     *
     * @param \Entity\ProjectFile $files
     * @return Project
     */
    public function addFile(\Entity\ProjectFile $files)
    {
        $this->files[] = $files;
    
        return $this;
    }

    /**
     * Remove files
     *
     * @param \Entity\ProjectFile $files
     */
    public function removeFile(\Entity\ProjectFile $files)
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

    /**
     * Add contracts
     *
     * @param \Entity\Contract $contracts
     * @return Project
     */
    public function addContract(\Entity\Contract $contracts)
    {
        $this->contracts[] = $contracts;
    
        return $this;
    }

    /**
     * Remove contracts
     *
     * @param \Entity\Contract $contracts
     */
    public function removeContract(\Entity\Contract $contracts)
    {
        $this->contracts->removeElement($contracts);
    }

    /**
     * Get contracts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * Set country
     *
     * @param \Entity\Country $country
     * @return Project
     */
    public function setCountry(\Entity\Country $country = null)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return \Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set state
     *
     * @param \Entity\USState $state
     * @return Project
     */
    public function setState(\Entity\USState $state = null)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return \Entity\USState 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set owner
     *
     * @param \Entity\User $owner
     * @return Project
     */
    public function setOwner(\Entity\User $owner = null)
    {
        $this->owner = $owner;
    
        return $this;
    }

    /**
     * Get owner
     *
     * @return \Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set photo
     *
     * @param \Entity\ProjectFile $photo
     * @return Project
     */
    public function setPhoto(\Entity\ProjectFile $photo = null)
    {
        $this->photo = $photo;
    
        return $this;
    }

    /**
     * Get photo
     *
     * @return \Entity\ProjectFile 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set language
     *
     * @param \Entity\Language $language
     * @return Project
     */
    public function setLanguage(\Entity\Language $language = null)
    {
        $this->language = $language;
    
        return $this;
    }

    /**
     * Get language
     *
     * @return \Entity\Language 
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
