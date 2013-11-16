<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSkill
 *
 * @ORM\Table(name="userskill")
 * @ORM\Entity
 */
class UserSkill
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
     * @ORM\Column(name="ranking", type="string", length=32)
     */
    private $ranking;

    /**
     * @var string
     *
     * @ORM\Column(name="videoPreview", type="string", nullable=true)
     */
    private $videoPreview;

    /**
     * @var \Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Entity\User", inversedBy="skills")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Entity\Skill
     *
     * @ORM\ManyToOne(targetEntity="Entity\Skill")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="skill_id", referencedColumnName="id")
     * })
     */
    private $skill;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Genre")
     * @ORM\JoinTable(name="userskill_genre",
     *   joinColumns={
     *     @ORM\JoinColumn(name="userskill_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="genre_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $genres;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Influence")
     * @ORM\JoinTable(name="userskill_influence",
     *   joinColumns={
     *     @ORM\JoinColumn(name="userskill_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="influence_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $influences;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->influences = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set ranking
     *
     * @param string $ranking
     * @return UserSkill
     */
    public function setRanking($ranking)
    {
        $this->ranking = $ranking;
    
        return $this;
    }

    /**
     * Get ranking
     *
     * @return string 
     */
    public function getRanking()
    {
        return $this->ranking;
    }

    /**
     * Set videoPreview
     *
     * @param string $videoPreview
     * @return UserSkill
     */
    public function setVideoPreview($videoPreview)
    {
        $this->videoPreview = $videoPreview;
    
        return $this;
    }

    /**
     * Get videoPreview
     *
     * @return string 
     */
    public function getVideoPreview()
    {
        return $this->videoPreview;
    }

    /**
     * Set user
     *
     * @param \Entity\User $user
     * @return UserSkill
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
     * Set skill
     *
     * @param \Entity\Skill $skill
     * @return UserSkill
     */
    public function setSkill(\Entity\Skill $skill = null)
    {
        $this->skill = $skill;
    
        return $this;
    }

    /**
     * Get skill
     *
     * @return \Entity\Skill 
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * Add genres
     *
     * @param \Entity\Genre $genres
     * @return UserSkill
     */
    public function addGenre(\Entity\Genre $genres)
    {
        $this->genres[] = $genres;
    
        return $this;
    }

    /**
     * Remove genres
     *
     * @param \Entity\Genre $genres
     */
    public function removeGenre(\Entity\Genre $genres)
    {
        $this->genres->removeElement($genres);
    }

    /**
     * Get genres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * Add influences
     *
     * @param \Entity\Influence $influences
     * @return UserSkill
     */
    public function addInfluence(\Entity\Influence $influences)
    {
        $this->influences[] = $influences;
    
        return $this;
    }

    /**
     * Remove influences
     *
     * @param \Entity\Influence $influences
     */
    public function removeInfluence(\Entity\Influence $influences)
    {
        $this->influences->removeElement($influences);
    }

    /**
     * Get influences
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInfluences()
    {
        return $this->influences;
    }
}
