<?php

namespace Proxy\__CG__\Entity;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Skill extends \Entity\Skill implements \Doctrine\ORM\Proxy\Proxy
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

    public function setName($name)
    {
        $this->__load();
        return parent::setName($name);
    }

    public function getName()
    {
        $this->__load();
        return parent::getName();
    }

    public function setIconPath($iconPath)
    {
        $this->__load();
        return parent::setIconPath($iconPath);
    }

    public function getIconPath()
    {
        $this->__load();
        return parent::getIconPath();
    }

    public function setType($type)
    {
        $this->__load();
        return parent::setType($type);
    }

    public function getType()
    {
        $this->__load();
        return parent::getType();
    }

    public function setPopularity($popularity)
    {
        $this->__load();
        return parent::setPopularity($popularity);
    }

    public function getPopularity()
    {
        $this->__load();
        return parent::getPopularity();
    }

    public function setSearchTimes($searchTimes)
    {
        $this->__load();
        return parent::setSearchTimes($searchTimes);
    }

    public function getSearchTimes()
    {
        $this->__load();
        return parent::getSearchTimes();
    }

    public function addSkill(\Entity\Skill $skills)
    {
        $this->__load();
        return parent::addSkill($skills);
    }

    public function removeSkill(\Entity\Skill $skills)
    {
        $this->__load();
        return parent::removeSkill($skills);
    }

    public function getSkills()
    {
        $this->__load();
        return parent::getSkills();
    }

    public function setCategory(\Entity\Skill $category = NULL)
    {
        $this->__load();
        return parent::setCategory($category);
    }

    public function getCategory()
    {
        $this->__load();
        return parent::getCategory();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'name', 'iconPath', 'type', 'popularity', 'searchTimes', 'skills', 'category');
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