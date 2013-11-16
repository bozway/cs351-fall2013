<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HotTags
 *
 * @ORM\Table(name="hottags")
 * @ORM\Entity
 */
class HotTags
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
     * @ORM\Column(name="popularity", type="integer")
     */
    private $popularity;

    /**
     * @var integer
     *
     * @ORM\Column(name="searchTimes", type="integer")
     */
    private $searchTimes;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", unique=true)
     */
    private $name;


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
     * Set popularity
     *
     * @param integer $popularity
     * @return HotTags
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
     * @return HotTags
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
     * Set type
     *
     * @param integer $type
     * @return HotTags
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
     * Set name
     *
     * @param string $name
     * @return HotTags
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
}
