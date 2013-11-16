<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContractItem
 *
 * @ORM\Table(name="contractitem")
 * @ORM\Entity
 */
class ContractItem
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
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var float
     *
     * @ORM\Column(name="pay", type="float", nullable=true)
     */
    private $pay;

    /**
     * @var float
     *
     * @ORM\Column(name="equity", type="float", nullable=true)
     */
    private $equity;

    /**
     * @var \Entity\Contract
     *
     * @ORM\ManyToOne(targetEntity="Entity\Contract", inversedBy="contractItems")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mainContract_id", referencedColumnName="id")
     * })
     */
    private $mainContract;

    /**
     * @var \Entity\ProjectMember
     *
     * @ORM\ManyToOne(targetEntity="Entity\ProjectMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="signee_id", referencedColumnName="id")
     * })
     */
    private $signee;


    /**
     * Set id
     *
     * @param integer $id
     * @return ContractItem
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
     * Set status
     *
     * @param integer $status
     * @return ContractItem
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
     * Set pay
     *
     * @param float $pay
     * @return ContractItem
     */
    public function setPay($pay)
    {
        $this->pay = $pay;
    
        return $this;
    }

    /**
     * Get pay
     *
     * @return float 
     */
    public function getPay()
    {
        return $this->pay;
    }

    /**
     * Set equity
     *
     * @param float $equity
     * @return ContractItem
     */
    public function setEquity($equity)
    {
        $this->equity = $equity;
    
        return $this;
    }

    /**
     * Get equity
     *
     * @return float 
     */
    public function getEquity()
    {
        return $this->equity;
    }

    /**
     * Set mainContract
     *
     * @param \Entity\Contract $mainContract
     * @return ContractItem
     */
    public function setMainContract(\Entity\Contract $mainContract = null)
    {
        $this->mainContract = $mainContract;
    
        return $this;
    }

    /**
     * Get mainContract
     *
     * @return \Entity\Contract 
     */
    public function getMainContract()
    {
        return $this->mainContract;
    }

    /**
     * Set signee
     *
     * @param \Entity\ProjectMember $signee
     * @return ContractItem
     */
    public function setSignee(\Entity\ProjectMember $signee = null)
    {
        $this->signee = $signee;
    
        return $this;
    }

    /**
     * Get signee
     *
     * @return \Entity\ProjectMember 
     */
    public function getSignee()
    {
        return $this->signee;
    }
}
