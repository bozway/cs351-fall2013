<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * USState
 *
 * @ORM\Table(name="usstate")
 * @ORM\Entity
 */
class USState
{
    /**
     * @var string
     *
     * @ORM\Column(name="abbreviatedName", type="string")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $abbreviatedName;

    /**
     * @var string
     *
     * @ORM\Column(name="fullName", type="string")
     */
    private $fullName;


    /**
     * Set abbreviatedName
     *
     * @param string $abbreviatedName
     * @return USState
     */
    public function setAbbreviatedName($abbreviatedName)
    {
        $this->abbreviatedName = $abbreviatedName;
    
        return $this;
    }

    /**
     * Get abbreviatedName
     *
     * @return string 
     */
    public function getAbbreviatedName()
    {
        return $this->abbreviatedName;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     * @return USState
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    
        return $this;
    }

    /**
     * Get fullName
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->fullName;
    }
}
