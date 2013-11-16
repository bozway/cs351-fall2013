<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table(name="Language")
 * @ORM\Entity
 */
class Language
{
    /**
     * @var string
     *
     * @ORM\Column(name="isoCode", type="string")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $isoCode;

    /**
     * @var string
     *
     * @ORM\Column(name="languageName", type="string", nullable=false)
     */
    private $languageName;


    /**
     * Set isoCode
     *
     * @param string $isoCode
     * @return Language
     */
    public function setIsoCode($isoCode)
    {
        $this->isoCode = $isoCode;
    
        return $this;
    }

    /**
     * Get isoCode
     *
     * @return string 
     */
    public function getIsoCode()
    {
        return $this->isoCode;
    }

    /**
     * Set languageName
     *
     * @param string $languageName
     * @return Language
     */
    public function setLanguageName($languageName)
    {
        $this->languageName = $languageName;
    
        return $this;
    }

    /**
     * Get languageName
     *
     * @return string 
     */
    public function getLanguageName()
    {
        return $this->languageName;
    }
}
