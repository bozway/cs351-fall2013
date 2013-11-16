<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectFile
 *
 * @ORM\Table(name="projectfile")
 * @ORM\Entity
 */
class ProjectFile
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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string")
     */
    private $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationTime", type="datetime")
     */
    private $creationTime;

    /**
     * @var string
     *
     * @ORM\Column(name="uploadIp", type="string")
     */
    private $uploadIp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modifiedTime", type="datetime", nullable=true)
     */
    private $modifiedTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=true)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="subtype", type="integer", nullable=true)
     */
    private $subtype;

    /**
     * @var \Entity\Project
     *
     * @ORM\ManyToOne(targetEntity="Entity\Project", inversedBy="files")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     * })
     */
    private $owner;


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
     * @return ProjectFile
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
     * Set path
     *
     * @param string $path
     * @return ProjectFile
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set creationTime
     *
     * @param \DateTime $creationTime
     * @return ProjectFile
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
     * Set uploadIp
     *
     * @param string $uploadIp
     * @return ProjectFile
     */
    public function setUploadIp($uploadIp)
    {
        $this->uploadIp = $uploadIp;
    
        return $this;
    }

    /**
     * Get uploadIp
     *
     * @return string 
     */
    public function getUploadIp()
    {
        return $this->uploadIp;
    }

    /**
     * Set modifiedTime
     *
     * @param \DateTime $modifiedTime
     * @return ProjectFile
     */
    public function setModifiedTime($modifiedTime)
    {
        $this->modifiedTime = $modifiedTime;
    
        return $this;
    }

    /**
     * Get modifiedTime
     *
     * @return \DateTime 
     */
    public function getModifiedTime()
    {
        return $this->modifiedTime;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return ProjectFile
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set subtype
     *
     * @param integer $subtype
     * @return ProjectFile
     */
    public function setSubtype($subtype)
    {
        $this->subtype = $subtype;
    
        return $this;
    }

    /**
     * Get subtype
     *
     * @return integer 
     */
    public function getSubtype()
    {
        return $this->subtype;
    }

    /**
     * Set owner
     *
     * @param \Entity\Project $owner
     * @return ProjectFile
     */
    public function setOwner(\Entity\Project $owner = null)
    {
        $this->owner = $owner;
    
        return $this;
    }

    /**
     * Get owner
     *
     * @return \Entity\Project 
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
