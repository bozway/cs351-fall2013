<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadUser
 *
 * @ORM\Table(name="threaduser")
 * @ORM\Entity
 */
class ThreadUser
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
     * @ORM\Column(name="readFlag", type="integer")
     */
    private $readFlag;

    /**
     * @var \Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Entity\Thread
     *
     * @ORM\ManyToOne(targetEntity="Entity\Thread")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="thread_id", referencedColumnName="id")
     * })
     */
    private $thread;


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
     * Set readFlag
     *
     * @param integer $readFlag
     * @return ThreadUser
     */
    public function setReadFlag($readFlag)
    {
        $this->readFlag = $readFlag;
    
        return $this;
    }

    /**
     * Get readFlag
     *
     * @return integer 
     */
    public function getReadFlag()
    {
        return $this->readFlag;
    }

    /**
     * Set user
     *
     * @param \Entity\User $user
     * @return ThreadUser
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

    /**
     * Set thread
     *
     * @param \Entity\Thread $thread
     * @return ThreadUser
     */
    public function setThread(\Entity\Thread $thread = null)
    {
        $this->thread = $thread;
    
        return $this;
    }

    /**
     * Get thread
     *
     * @return \Entity\Thread 
     */
    public function getThread()
    {
        return $this->thread;
    }
}
