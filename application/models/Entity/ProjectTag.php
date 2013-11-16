<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectTag
 *
 * @ORM\Table(name="projecttag")
 * @ORM\Entity
 */
class ProjectTag
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
     * @return ProjectTag
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
     * Set popularity
     *
     * @param integer $popularity
     * @return ProjectTag
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
     * @return ProjectTag
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
}
