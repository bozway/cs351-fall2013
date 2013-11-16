<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facebook
 *
 * @ORM\Table(name="facebook")
 * @ORM\Entity
 */
class Facebook
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
     * @ORM\Column(name="facebookUserId", type="string")
     */
    private $facebookUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="accessToken", type="string")
     */
    private $accessToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire", type="datetime")
     */
    private $expire;

    /**
     * @var \Entity\User
     *
     * @ORM\OneToOne(targetEntity="Entity\User", mappedBy="FB")
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
     * Set facebookUserId
     *
     * @param string $facebookUserId
     * @return Facebook
     */
    public function setFacebookUserId($facebookUserId)
    {
        $this->facebookUserId = $facebookUserId;
    
        return $this;
    }

    /**
     * Get facebookUserId
     *
     * @return string 
     */
    public function getFacebookUserId()
    {
        return $this->facebookUserId;
    }

    /**
     * Set accessToken
     *
     * @param string $accessToken
     * @return Facebook
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
     * Set expire
     *
     * @param \DateTime $expire
     * @return Facebook
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
     * @return Facebook
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
