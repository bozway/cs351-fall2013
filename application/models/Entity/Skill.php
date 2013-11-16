<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Skill
 *
 * @ORM\Table(name="skill")
 * @ORM\Entity
 */
class Skill
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
     * @ORM\Column(name="iconPath", type="string")
     */
    private $iconPath;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="popularity", type="integer", nullable=true)
     */
    private $popularity;

    /**
     * @var integer
     *
     * @ORM\Column(name="searchTimes", type="integer", nullable=true)
     */
    private $searchTimes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\Skill", mappedBy="category")
     */
    private $skills;

    /**
     * @var \Entity\Skill
     *
     * @ORM\ManyToOne(targetEntity="Entity\Skill", inversedBy="skills")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->skills = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Skill
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
     * Set iconPath
     *
     * @param string $iconPath
     * @return Skill
     */
    public function setIconPath($iconPath)
    {
        $this->iconPath = $iconPath;
    
        return $this;
    }

    /**
     * Get iconPath
     *
     * @return string 
     */
    public function getIconPath()
    {
        return $this->iconPath;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Skill
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
     * Set popularity
     *
     * @param integer $popularity
     * @return Skill
     */
    public function setPopularity($popularity)
    {
        $this->popularity = $popularity;
    
        return $this;
    }

    /**
     * Get popularity
     *
     * @return integer 
     */
    public function getPopularity()
    {
        return $this->popularity;
    }

    /**
     * Set searchTimes
     *
     * @param integer $searchTimes
     * @return Skill
     */
    public function setSearchTimes($searchTimes)
    {
        $this->searchTimes = $searchTimes;
    
        return $this;
    }

    /**
     * Get searchTimes
     *
     * @return integer 
     */
    public function getSearchTimes()
    {
        return $this->searchTimes;
    }

    /**
     * Add skills
     *
     * @param \Entity\Skill $skills
     * @return Skill
     */
    public function addSkill(\Entity\Skill $skills)
    {
        $this->skills[] = $skills;
    
        return $this;
    }

    /**
     * Remove skills
     *
     * @param \Entity\Skill $skills
     */
    public function removeSkill(\Entity\Skill $skills)
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
     * Set category
     *
     * @param \Entity\Skill $category
     * @return Skill
     */
    public function setCategory(\Entity\Skill $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Entity\Skill 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
