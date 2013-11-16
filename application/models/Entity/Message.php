<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity
 */
class Message
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
     * @ORM\Column(name="content", type="string", length=1000)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationTime", type="datetime")
     */
    private $creationTime;

    /**
     * @var \Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Entity\User", inversedBy="messageSent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     * })
     */
    private $sender;

    /**
     * @var \Entity\Thread
     *
     * @ORM\ManyToOne(targetEntity="Entity\Thread", inversedBy="messages")
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
     * Set content
     *
     * @param string $content
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set creationTime
     *
     * @param \DateTime $creationTime
     * @return Message
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
     * Set sender
     *
     * @param \Entity\User $sender
     * @return Message
     */
    public function setSender(\Entity\User $sender = null)
    {
        $this->sender = $sender;
    
        return $this;
    }

    /**
     * Get sender
     *
     * @return \Entity\User 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set thread
     *
     * @param \Entity\Thread $thread
     * @return Message
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
