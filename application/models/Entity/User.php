<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
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
     * @ORM\Column(name="password", type="string", length=32)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationTime", type="datetime")
     */
    private $creationTime;

    /**
     * @var string
     *
     * @ORM\Column(name="registrationIP", type="string")
     */
    private $registrationIP;

    /**
     * @var string
     *
     * @ORM\Column(name="lastLoginIP", type="string", nullable=true)
     */
    private $lastLoginIP;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastLoginTime", type="datetime")
     */
    private $lastLoginTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dob", type="date", nullable=true)
     */
    private $dob;

    /**
     * @var integer
     *
     * @ORM\Column(name="gender", type="integer", nullable=true)
     */
    private $gender;

    /**
     * @var integer
     *
     * @ORM\Column(name="privacy", type="integer", nullable=true)
     */
    private $privacy;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="zipCode", type="string", nullable=true)
     */
    private $zipCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="unreadThread", type="integer", nullable=true)
     */
    private $unreadThread;

    /**
     * @var string
     *
     * @ORM\Column(name="webAddress", type="string", nullable=true)
     */
    private $webAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="biography", type="string", length=2000, nullable=true)
     */
    private $biography;

    /**
     * @var string
     *
     * @ORM\Column(name="agentName", type="string", nullable=true)
     */
    private $agentName;

    /**
     * @var string
     *
     * @ORM\Column(name="agentEmail", type="string", nullable=true)
     */
    private $agentEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="agentPhone", type="string", nullable=true)
     */
    private $agentPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="managerName", type="string", nullable=true)
     */
    private $managerName;

    /**
     * @var string
     *
     * @ORM\Column(name="managerEmail", type="string", nullable=true)
     */
    private $managerEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="managerPhone", type="string", nullable=true)
     */
    private $managerPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="bookingName", type="string", nullable=true)
     */
    private $bookingName;

    /**
     * @var string
     *
     * @ORM\Column(name="bookingEmail", type="string", nullable=true)
     */
    private $bookingEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="bookingPhone", type="string", nullable=true)
     */
    private $bookingPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="publisherName", type="string", nullable=true)
     */
    private $publisherName;

    /**
     * @var string
     *
     * @ORM\Column(name="publisherEmail", type="string", nullable=true)
     */
    private $publisherEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="publisherPhone", type="string", nullable=true)
     */
    private $publisherPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="recordName", type="string", nullable=true)
     */
    private $recordName;

    /**
     * @var string
     *
     * @ORM\Column(name="recordWebsite", type="string", nullable=true)
     */
    private $recordWebsite;

    /**
     * @var string
     *
     * @ORM\Column(name="PWL", type="string", nullable=true)
     */
    private $PWL;

    /**
     * @var string
     *
     * @ORM\Column(name="FBL", type="string", nullable=true)
     */
    private $FBL;

    /**
     * @var string
     *
     * @ORM\Column(name="TWL", type="string", nullable=true)
     */
    private $TWL;

    /**
     * @var string
     *
     * @ORM\Column(name="SCL", type="string", nullable=true)
     */
    private $SCL;

    /**
     * @var string
     *
     * @ORM\Column(name="pwToken", type="string", length=32, nullable=true)
     */
    private $pwToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pwTokenCreationTime", type="datetime", nullable=true)
     */
    private $pwTokenCreationTime;

    /**
     * @var \Entity\File
     *
     * @ORM\OneToOne(targetEntity="Entity\File")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="profilePicture_id", referencedColumnName="id", unique=true)
     * })
     */
    private $profilePicture;

    /**
     * @var \Entity\File
     *
     * @ORM\OneToOne(targetEntity="Entity\File")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="coverPhoto_id", referencedColumnName="id", unique=true)
     * })
     */
    private $coverPhoto;

    /**
     * @var \Entity\Facebook
     *
     * @ORM\OneToOne(targetEntity="Entity\Facebook", inversedBy="user")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FB_id", referencedColumnName="id", unique=true)
     * })
     */
    private $FB;

    /**
     * @var \Entity\Twitter
     *
     * @ORM\OneToOne(targetEntity="Entity\Twitter", inversedBy="user")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TW_id", referencedColumnName="id", unique=true)
     * })
     */
    private $TW;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\ProjectMember", mappedBy="user")
     */
    private $projects;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\File", mappedBy="owner")
     */
    private $files;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\Audition", mappedBy="applicant")
     */
    private $auditions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\Message", mappedBy="sender")
     */
    private $messageSent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\UserSkill", mappedBy="user")
     * @ORM\OrderBy({
     *     "ranking"="ASC"
     * })
     */
    private $skills;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\ThreadUser", mappedBy="user")
     */
    private $threads;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entity\Project", mappedBy="owner")
     */
    private $myProjects;

    /**
     * @var \Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="countryCode", referencedColumnName="isoCode")
     * })
     */
    private $country;

    /**
     * @var \Entity\USState
     *
     * @ORM\ManyToOne(targetEntity="Entity\USState")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="state", referencedColumnName="abbreviatedName")
     * })
     */
    private $state;

    /**
     * @var \Entity\Language
     *
     * @ORM\ManyToOne(targetEntity="Entity\Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="languageCode", referencedColumnName="isoCode")
     * })
     */
    private $language;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\User")
     * @ORM\JoinTable(name="user_contacts",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="contacts_id", referencedColumnName="id")
     *   }
     * )
     */
    private $contacts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->auditions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messageSent = new \Doctrine\Common\Collections\ArrayCollection();
        $this->skills = new \Doctrine\Common\Collections\ArrayCollection();
        $this->threads = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myProjects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set creationTime
     *
     * @param \DateTime $creationTime
     * @return User
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
     * Set registrationIP
     *
     * @param string $registrationIP
     * @return User
     */
    public function setRegistrationIP($registrationIP)
    {
        $this->registrationIP = $registrationIP;
    
        return $this;
    }

    /**
     * Get registrationIP
     *
     * @return string 
     */
    public function getRegistrationIP()
    {
        return $this->registrationIP;
    }

    /**
     * Set lastLoginIP
     *
     * @param string $lastLoginIP
     * @return User
     */
    public function setLastLoginIP($lastLoginIP)
    {
        $this->lastLoginIP = $lastLoginIP;
    
        return $this;
    }

    /**
     * Get lastLoginIP
     *
     * @return string 
     */
    public function getLastLoginIP()
    {
        return $this->lastLoginIP;
    }

    /**
     * Set lastLoginTime
     *
     * @param \DateTime $lastLoginTime
     * @return User
     */
    public function setLastLoginTime($lastLoginTime)
    {
        $this->lastLoginTime = $lastLoginTime;
    
        return $this;
    }

    /**
     * Get lastLoginTime
     *
     * @return \DateTime 
     */
    public function getLastLoginTime()
    {
        return $this->lastLoginTime;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dob
     *
     * @param \DateTime $dob
     * @return User
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    
        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime 
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender
     *
     * @return integer 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set privacy
     *
     * @param integer $privacy
     * @return User
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;
    
        return $this;
    }

    /**
     * Get privacy
     *
     * @return integer 
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     * @return User
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    
        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string 
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set unreadThread
     *
     * @param integer $unreadThread
     * @return User
     */
    public function setUnreadThread($unreadThread)
    {
        $this->unreadThread = $unreadThread;
    
        return $this;
    }

    /**
     * Get unreadThread
     *
     * @return integer 
     */
    public function getUnreadThread()
    {
        return $this->unreadThread;
    }

    /**
     * Set webAddress
     *
     * @param string $webAddress
     * @return User
     */
    public function setWebAddress($webAddress)
    {
        $this->webAddress = $webAddress;
    
        return $this;
    }

    /**
     * Get webAddress
     *
     * @return string 
     */
    public function getWebAddress()
    {
        return $this->webAddress;
    }

    /**
     * Set biography
     *
     * @param string $biography
     * @return User
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;
    
        return $this;
    }

    /**
     * Get biography
     *
     * @return string 
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set agentName
     *
     * @param string $agentName
     * @return User
     */
    public function setAgentName($agentName)
    {
        $this->agentName = $agentName;
    
        return $this;
    }

    /**
     * Get agentName
     *
     * @return string 
     */
    public function getAgentName()
    {
        return $this->agentName;
    }

    /**
     * Set agentEmail
     *
     * @param string $agentEmail
     * @return User
     */
    public function setAgentEmail($agentEmail)
    {
        $this->agentEmail = $agentEmail;
    
        return $this;
    }

    /**
     * Get agentEmail
     *
     * @return string 
     */
    public function getAgentEmail()
    {
        return $this->agentEmail;
    }

    /**
     * Set agentPhone
     *
     * @param string $agentPhone
     * @return User
     */
    public function setAgentPhone($agentPhone)
    {
        $this->agentPhone = $agentPhone;
    
        return $this;
    }

    /**
     * Get agentPhone
     *
     * @return string 
     */
    public function getAgentPhone()
    {
        return $this->agentPhone;
    }

    /**
     * Set managerName
     *
     * @param string $managerName
     * @return User
     */
    public function setManagerName($managerName)
    {
        $this->managerName = $managerName;
    
        return $this;
    }

    /**
     * Get managerName
     *
     * @return string 
     */
    public function getManagerName()
    {
        return $this->managerName;
    }

    /**
     * Set managerEmail
     *
     * @param string $managerEmail
     * @return User
     */
    public function setManagerEmail($managerEmail)
    {
        $this->managerEmail = $managerEmail;
    
        return $this;
    }

    /**
     * Get managerEmail
     *
     * @return string 
     */
    public function getManagerEmail()
    {
        return $this->managerEmail;
    }

    /**
     * Set managerPhone
     *
     * @param string $managerPhone
     * @return User
     */
    public function setManagerPhone($managerPhone)
    {
        $this->managerPhone = $managerPhone;
    
        return $this;
    }

    /**
     * Get managerPhone
     *
     * @return string 
     */
    public function getManagerPhone()
    {
        return $this->managerPhone;
    }

    /**
     * Set bookingName
     *
     * @param string $bookingName
     * @return User
     */
    public function setBookingName($bookingName)
    {
        $this->bookingName = $bookingName;
    
        return $this;
    }

    /**
     * Get bookingName
     *
     * @return string 
     */
    public function getBookingName()
    {
        return $this->bookingName;
    }

    /**
     * Set bookingEmail
     *
     * @param string $bookingEmail
     * @return User
     */
    public function setBookingEmail($bookingEmail)
    {
        $this->bookingEmail = $bookingEmail;
    
        return $this;
    }

    /**
     * Get bookingEmail
     *
     * @return string 
     */
    public function getBookingEmail()
    {
        return $this->bookingEmail;
    }

    /**
     * Set bookingPhone
     *
     * @param string $bookingPhone
     * @return User
     */
    public function setBookingPhone($bookingPhone)
    {
        $this->bookingPhone = $bookingPhone;
    
        return $this;
    }

    /**
     * Get bookingPhone
     *
     * @return string 
     */
    public function getBookingPhone()
    {
        return $this->bookingPhone;
    }

    /**
     * Set publisherName
     *
     * @param string $publisherName
     * @return User
     */
    public function setPublisherName($publisherName)
    {
        $this->publisherName = $publisherName;
    
        return $this;
    }

    /**
     * Get publisherName
     *
     * @return string 
     */
    public function getPublisherName()
    {
        return $this->publisherName;
    }

    /**
     * Set publisherEmail
     *
     * @param string $publisherEmail
     * @return User
     */
    public function setPublisherEmail($publisherEmail)
    {
        $this->publisherEmail = $publisherEmail;
    
        return $this;
    }

    /**
     * Get publisherEmail
     *
     * @return string 
     */
    public function getPublisherEmail()
    {
        return $this->publisherEmail;
    }

    /**
     * Set publisherPhone
     *
     * @param string $publisherPhone
     * @return User
     */
    public function setPublisherPhone($publisherPhone)
    {
        $this->publisherPhone = $publisherPhone;
    
        return $this;
    }

    /**
     * Get publisherPhone
     *
     * @return string 
     */
    public function getPublisherPhone()
    {
        return $this->publisherPhone;
    }

    /**
     * Set recordName
     *
     * @param string $recordName
     * @return User
     */
    public function setRecordName($recordName)
    {
        $this->recordName = $recordName;
    
        return $this;
    }

    /**
     * Get recordName
     *
     * @return string 
     */
    public function getRecordName()
    {
        return $this->recordName;
    }

    /**
     * Set recordWebsite
     *
     * @param string $recordWebsite
     * @return User
     */
    public function setRecordWebsite($recordWebsite)
    {
        $this->recordWebsite = $recordWebsite;
    
        return $this;
    }

    /**
     * Get recordWebsite
     *
     * @return string 
     */
    public function getRecordWebsite()
    {
        return $this->recordWebsite;
    }

    /**
     * Set PWL
     *
     * @param string $pWL
     * @return User
     */
    public function setPWL($pWL)
    {
        $this->PWL = $pWL;
    
        return $this;
    }

    /**
     * Get PWL
     *
     * @return string 
     */
    public function getPWL()
    {
        return $this->PWL;
    }

    /**
     * Set FBL
     *
     * @param string $fBL
     * @return User
     */
    public function setFBL($fBL)
    {
        $this->FBL = $fBL;
    
        return $this;
    }

    /**
     * Get FBL
     *
     * @return string 
     */
    public function getFBL()
    {
        return $this->FBL;
    }

    /**
     * Set TWL
     *
     * @param string $tWL
     * @return User
     */
    public function setTWL($tWL)
    {
        $this->TWL = $tWL;
    
        return $this;
    }

    /**
     * Get TWL
     *
     * @return string 
     */
    public function getTWL()
    {
        return $this->TWL;
    }

    /**
     * Set SCL
     *
     * @param string $sCL
     * @return User
     */
    public function setSCL($sCL)
    {
        $this->SCL = $sCL;
    
        return $this;
    }

    /**
     * Get SCL
     *
     * @return string 
     */
    public function getSCL()
    {
        return $this->SCL;
    }

    /**
     * Set pwToken
     *
     * @param string $pwToken
     * @return User
     */
    public function setPwToken($pwToken)
    {
        $this->pwToken = $pwToken;
    
        return $this;
    }

    /**
     * Get pwToken
     *
     * @return string 
     */
    public function getPwToken()
    {
        return $this->pwToken;
    }

    /**
     * Set pwTokenCreationTime
     *
     * @param \DateTime $pwTokenCreationTime
     * @return User
     */
    public function setPwTokenCreationTime($pwTokenCreationTime)
    {
        $this->pwTokenCreationTime = $pwTokenCreationTime;
    
        return $this;
    }

    /**
     * Get pwTokenCreationTime
     *
     * @return \DateTime 
     */
    public function getPwTokenCreationTime()
    {
        return $this->pwTokenCreationTime;
    }

    /**
     * Set profilePicture
     *
     * @param \Entity\File $profilePicture
     * @return User
     */
    public function setProfilePicture(\Entity\File $profilePicture = null)
    {
        $this->profilePicture = $profilePicture;
    
        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return \Entity\File 
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * Set coverPhoto
     *
     * @param \Entity\File $coverPhoto
     * @return User
     */
    public function setCoverPhoto(\Entity\File $coverPhoto = null)
    {
        $this->coverPhoto = $coverPhoto;
    
        return $this;
    }

    /**
     * Get coverPhoto
     *
     * @return \Entity\File 
     */
    public function getCoverPhoto()
    {
        return $this->coverPhoto;
    }

    /**
     * Set FB
     *
     * @param \Entity\Facebook $fB
     * @return User
     */
    public function setFB(\Entity\Facebook $fB = null)
    {
        $this->FB = $fB;
    
        return $this;
    }

    /**
     * Get FB
     *
     * @return \Entity\Facebook 
     */
    public function getFB()
    {
        return $this->FB;
    }

    /**
     * Set TW
     *
     * @param \Entity\Twitter $tW
     * @return User
     */
    public function setTW(\Entity\Twitter $tW = null)
    {
        $this->TW = $tW;
    
        return $this;
    }

    /**
     * Get TW
     *
     * @return \Entity\Twitter 
     */
    public function getTW()
    {
        return $this->TW;
    }

    /**
     * Add projects
     *
     * @param \Entity\ProjectMember $projects
     * @return User
     */
    public function addProject(\Entity\ProjectMember $projects)
    {
        $this->projects[] = $projects;
    
        return $this;
    }

    /**
     * Remove projects
     *
     * @param \Entity\ProjectMember $projects
     */
    public function removeProject(\Entity\ProjectMember $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Add files
     *
     * @param \Entity\File $files
     * @return User
     */
    public function addFile(\Entity\File $files)
    {
        $this->files[] = $files;
    
        return $this;
    }

    /**
     * Remove files
     *
     * @param \Entity\File $files
     */
    public function removeFile(\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add auditions
     *
     * @param \Entity\Audition $auditions
     * @return User
     */
    public function addAudition(\Entity\Audition $auditions)
    {
        $this->auditions[] = $auditions;
    
        return $this;
    }

    /**
     * Remove auditions
     *
     * @param \Entity\Audition $auditions
     */
    public function removeAudition(\Entity\Audition $auditions)
    {
        $this->auditions->removeElement($auditions);
    }

    /**
     * Get auditions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAuditions()
    {
        return $this->auditions;
    }

    /**
     * Add messageSent
     *
     * @param \Entity\Message $messageSent
     * @return User
     */
    public function addMessageSent(\Entity\Message $messageSent)
    {
        $this->messageSent[] = $messageSent;
    
        return $this;
    }

    /**
     * Remove messageSent
     *
     * @param \Entity\Message $messageSent
     */
    public function removeMessageSent(\Entity\Message $messageSent)
    {
        $this->messageSent->removeElement($messageSent);
    }

    /**
     * Get messageSent
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessageSent()
    {
        return $this->messageSent;
    }

    /**
     * Add skills
     *
     * @param \Entity\UserSkill $skills
     * @return User
     */
    public function addSkill(\Entity\UserSkill $skills)
    {
        $this->skills[] = $skills;
    
        return $this;
    }

    /**
     * Remove skills
     *
     * @param \Entity\UserSkill $skills
     */
    public function removeSkill(\Entity\UserSkill $skills)
    {
        $this->skills->removeElement($skills);
    }

    /**
     * Get skills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Add threads
     *
     * @param \Entity\ThreadUser $threads
     * @return User
     */
    public function addThread(\Entity\ThreadUser $threads)
    {
        $this->threads[] = $threads;
    
        return $this;
    }

    /**
     * Remove threads
     *
     * @param \Entity\ThreadUser $threads
     */
    public function removeThread(\Entity\ThreadUser $threads)
    {
        $this->threads->removeElement($threads);
    }

    /**
     * Get threads
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getThreads()
    {
        return $this->threads;
    }

    /**
     * Add myProjects
     *
     * @param \Entity\Project $myProjects
     * @return User
     */
    public function addMyProject(\Entity\Project $myProjects)
    {
        $this->myProjects[] = $myProjects;
    
        return $this;
    }

    /**
     * Remove myProjects
     *
     * @param \Entity\Project $myProjects
     */
    public function removeMyProject(\Entity\Project $myProjects)
    {
        $this->myProjects->removeElement($myProjects);
    }

    /**
     * Get myProjects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMyProjects()
    {
        return $this->myProjects;
    }

    /**
     * Set country
     *
     * @param \Entity\Country $country
     * @return User
     */
    public function setCountry(\Entity\Country $country = null)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return \Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set state
     *
     * @param \Entity\USState $state
     * @return User
     */
    public function setState(\Entity\USState $state = null)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return \Entity\USState 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set language
     *
     * @param \Entity\Language $language
     * @return User
     */
    public function setLanguage(\Entity\Language $language = null)
    {
        $this->language = $language;
    
        return $this;
    }

    /**
     * Get language
     *
     * @return \Entity\Language 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Add contacts
     *
     * @param \Entity\User $contacts
     * @return User
     */
    public function addContact(\Entity\User $contacts)
    {
        $this->contacts[] = $contacts;
    
        return $this;
    }

    /**
     * Remove contacts
     *
     * @param \Entity\User $contacts
     */
    public function removeContact(\Entity\User $contacts)
    {
        $this->contacts->removeElement($contacts);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContacts()
    {
        return $this->contacts;
    }
}
