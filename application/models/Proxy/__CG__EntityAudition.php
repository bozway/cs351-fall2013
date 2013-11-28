<?php

namespace Proxy\__CG__\Entity;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Audition extends \Entity\Audition implements \Doctrine\ORM\Proxy\Proxy
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

    public function setCl($cl)
    {
        $this->__load();
        return parent::setCl($cl);
    }

    public function getCl()
    {
        $this->__load();
        return parent::getCl();
    }

    public function setStatus($status)
    {
        $this->__load();
        return parent::setStatus($status);
    }

    public function getStatus()
    {
        $this->__load();
        return parent::getStatus();
    }

    public function setCreationTime($creationTime)
    {
        $this->__load();
        return parent::setCreationTime($creationTime);
    }

    public function getCreationTime()
    {
        $this->__load();
        return parent::getCreationTime();
    }

    public function setApplicant(\Entity\User $applicant = NULL)
    {
        $this->__load();
        return parent::setApplicant($applicant);
    }

    public function getApplicant()
    {
        $this->__load();
        return parent::getApplicant();
    }

    public function setProject(\Entity\Project $project = NULL)
    {
        $this->__load();
        return parent::setProject($project);
    }

    public function getProject()
    {
        $this->__load();
        return parent::getProject();
    }

    public function setSkill(\Entity\ProjectSkill $skill = NULL)
    {
        $this->__load();
        return parent::setSkill($skill);
    }

    public function getSkill()
    {
        $this->__load();
        return parent::getSkill();
    }

    public function addFile(\Entity\File $files)
    {
        $this->__load();
        return parent::addFile($files);
    }

    public function removeFile(\Entity\File $files)
    {
        $this->__load();
        return parent::removeFile($files);
    }

    public function getFiles()
    {
        $this->__load();
        return parent::getFiles();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'cl', 'status', 'creationTime', 'applicant', 'project', 'skill', 'files');
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