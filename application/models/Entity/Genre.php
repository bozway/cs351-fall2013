<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Genre
 *
 * @ORM\Table(name="genre")
 * @ORM\Entity
 */
class Genre
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
     * @ORM\Column(name="name", type="string", nullable=false)
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
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\Genre", mappedBy="category")
     */
    private $genres;

    /**
     * @var \Entity\Genre
     *
     * @ORM\ManyToOne(targetEntity="Entity\Genre", inversedBy="genres")
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
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Genre
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
     * @return Genre
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
     * @return Genre
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
     * @return Genre
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
     * Add genres
     *
     * @param \Entity\Genre $genres
     * @return Genre
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
     * Set category
     *
     * @param \Entity\Genre $category
     * @return Genre
     */
    public function setCategory(\Entity\Genre $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Entity\Genre 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
