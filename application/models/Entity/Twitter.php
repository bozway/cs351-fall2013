<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Twitter
 *
 * @ORM\Table(name="twitter")
 * @ORM\Entity
 */
class Twitter
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
     * @ORM\Column(name="twitterUserId", type="string")
     */
    private $twitterUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="accessToken", type="string")
     */
    private $accessToken;

    /**
     * @var string
     *
     * @ORM\Column(name="tokenSecret", type="string", nullable=true)
     */
    private $tokenSecret;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire", type="datetime", nullable=true)
     */
    private $expire;

    /**
     * @var \Entity\User
     *
     * @ORM\OneToOne(targetEntity="Entity\User", mappedBy="TW")
     */
    private $user;


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
     * Set twitterUserId
     *
     * @param string $twitterUserId
     * @return Twitter
     */
    public function setTwitterUserId($twitterUserId)
    {
        $this->twitterUserId = $twitterUserId;
    
        return $this;
    }

    /**
     * Get twitterUserId
     *
     * @return string 
     */
    public function getTwitterUserId()
    {
        return $this->twitterUserId;
    }

    /**
     * Set accessToken
     *
     * @param string $accessToken
     * @return Twitter
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    
        return $this;
    }

    /**
     * Get accessToken
     *
     * @return string 
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set tokenSecret
     *
     * @param string $tokenSecret
     * @return Twitter
     */
    public function setTokenSecret($tokenSecret)
    {
        $this->tokenSecret = $tokenSecret;
    
        return $this;
    }

    /**
     * Get tokenSecret
     *
     * @return string 
     */
    public function getTokenSecret()
    {
        return $this->tokenSecret;
    }

    /**
     * Set expire
     *
     * @param \DateTime $expire
     * @return Twitter
     */
    public function setExpire($expire)
    {
        $this->expire = $expire;
    
        return $this;
    }

    /**
     * Get expire
     *
     * @return \DateTime 
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * Set user
     *
     * @param \Entity\User $user
     * @return Twitter
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
}
