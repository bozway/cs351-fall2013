<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HotProject
 *
 * @ORM\Table(name="hotproject")
 * @ORM\Entity
 */
class HotProject
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
     * @var \Entity\Project
     *
     * @ORM\OneToOne(targetEntity="Entity\Project")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id", unique=true)
     * })
     */
    private $project;


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
     * @return HotProject
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
     * Set project
     *
     * @param \Entity\Project $project
     * @return HotProject
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
}
