<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contract
 *
 * @ORM\Table(name="contract")
 * @ORM\Entity
 */
class Contract
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationTime", type="datetime")
     */
    private $creationTime;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\ContractItem", mappedBy="mainContract")
     */
    private $subcontracts;

    /**
     * @var \Entity\Project
     *
     * @ORM\ManyToOne(targetEntity="Entity\Project", inversedBy="contracts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subcontracts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set id
     *
     * @param integer $id
     * @return Contract
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
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
     * Set type
     *
     * @param integer $type
     * @return Contract
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
     * Set status
     *
     * @param integer $status
     * @return Contract
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
     * @return Contract
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
     * Add subcontracts
     *
     * @param \Entity\ContractItem $subcontracts
     * @return Contract
     */
    public function addSubcontract(\Entity\ContractItem $subcontracts)
    {
        $this->subcontracts[] = $subcontracts;
    
        return $this;
    }

    /**
     * Remove subcontracts
     *
     * @param \Entity\ContractItem $subcontracts
     */
    public function removeSubcontract(\Entity\ContractItem $subcontracts)
    {
        $this->subcontracts->removeElement($subcontracts);
    }

    /**
     * Get subcontracts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubcontracts()
    {
        return $this->subcontracts;
    }

    /**
     * Set project
     *
     * @param \Entity\Project $project
     * @return Contract
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
