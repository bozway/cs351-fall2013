<?php

namespace Proxy\__CG__\Entity;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Twitter extends \Entity\Twitter implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setTwitterUserId($twitterUserId)
    {
        $this->__load();
        return parent::setTwitterUserId($twitterUserId);
    }

    public function getTwitterUserId()
    {
        $this->__load();
        return parent::getTwitterUserId();
    }

    public function setAccessToken($accessToken)
    {
        $this->__load();
        return parent::setAccessToken($accessToken);
    }

    public function getAccessToken()
    {
        $this->__load();
        return parent::getAccessToken();
    }

    public function setTokenSecret($tokenSecret)
    {
        $this->__load();
        return parent::setTokenSecret($tokenSecret);
    }

    public function getTokenSecret()
    {
        $this->__load();
        return parent::getTokenSecret();
    }

    public function setExpire($expire)
    {
        $this->__load();
        return parent::setExpire($expire);
    }

    public function getExpire()
    {
        $this->__load();
        return parent::getExpire();
    }

    public function setUser(\Entity\User $user = NULL)
    {
        $this->__load();
        return parent::setUser($user);
    }

    public function getUser()
    {
        $this->__load();
        return parent::getUser();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'twitterUserId', 'accessToken', 'tokenSecret', 'expire', 'user');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}