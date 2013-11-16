<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	
	use Doctrine\Common\Collections\Criteria;

class Fms_user_model extends CI_Model
{
	private $em;
        
    /*USER STATUS CODES*/
    const ACTIVE    = 888; //this need to be deleted, keep it here just so code won't crash
    const INACTIVE  = -1;
    const STAGE1    = 1;
    const STAGE2    = 2;
    const STAGE3    = 3;
	
	/* User gender codes */
	const GENDER_UNSPECIFIED		= 0;
	const GENDER_MALE				= 1;
	const GENDER_FEMALE				= 2;
	
	
    public function __construct()
    {
      $this -> em = $this -> doctrine -> em;
    }
    
    public function createEntity($arr){        
	    $user = new Entity\User;
	    $user->setFirstName($arr['name_first']);
	    $user->setLastName($arr['name_last']);
	    $user->setPassword($arr['password']);
	    $user->setEmail($arr['email']);
	    $user->setCreationTime(new DateTime());
	    $user->setLastLoginTime(new DateTime());
		$user->setRegistrationIP($arr['ip_registration']);
		$user->setStatus($arr['sign_stage']);
        $user->setLastLoginIP($arr['ip_registration']);
	    $this ->em-> persist($user);
		$this ->em-> flush();
		return $user;
	}
	public function updateLastLoginTime($email){
		$user = $this->getEntityByEmail($email);
		$user->setLastLoginTime(new DateTime());
		$this ->em-> flush();
	}
	public function getAllEntities(){
		return $this->em->getRepository('Entity\User')->findAll();
	}
	
	public function getEntityByEmail($email){
		$user = $this->em->getRepository('Entity\User')->findOneBy(array('email' => $email));
		return $user;		
	}
    public function getEntityById($userId) {
    	$user = $this -> em ->find('Entity\User',$userId);
		return $user;		
    }	
	public function setProfilePhoto($userId,$imgFile){
		$user = $this->getEntityById($userId);
		$user->setProfilePicture($imgFile);
		$imgFile->setType(0);
		$this -> em->flush();		
	}
	public function getEntityByWebAddress($webAddress) {
		$user = $this->em->getRepository('Entity\User')->findOneBy(array('webAddress' => $webAddress));
		return $user;
	}
	
    /**
     * this method returns the encrypted password stored in the database for the
     * user for his/ her email
     *
     * @param string $email
     *        	email of the user; must be unique
     *        	
     * @return string encrypted password stored in the DB for the user
     */	
	public function getPassword($email){
		$user = $this->em->getRepository('Entity\User')->findOneBy(array('email' => $email));
		if($user){
			return $user->getPassword();
		}else{
			return false;
		}		
	}
    /**
     * returns the user id of a user by his/ her email
     *
     * @param string $email
     *        	email of the user; must be unique
     *        	
     * @return integer user id of the user
     */
    public function getUserIdByEmail($email) {
    	//echo "fms_user_model" . "This is the email we are going to check: " . $email;
    	$user = $this -> em -> getRepository('Entity\User')->findOneBy(array('email' => $email));
    	//echo "fms_user_model" . "This is the user entity object that we are getting from the Entity Manager: " . $user;;
		if($user){
			//echo "fms_user_model" . "User exists, this is the user: ". $user->getFirstName();
			return $user -> getId();
		}else{
			//echo "fms_user_model" . "User does not exist for this email: " . $email;
			return false;
		}		
    }
    /**
     * Returns the user's full name with one space in between.
     * 
     * @param integer $user_id unique user_id of the user
     * 
     * @return string user's full name
     */
    public function getUserName($userID){
    	$user = $this -> em -> getRepository('Entity\User')->findOneBy(array('id' => $userID));
		if($user){
			return $user -> getFirstName()." ".$user -> getLastName();
		}else{
			return false;
		}
    }		
   /**
     * this function returns the current sign-stage of the user, given his/ her email
     *
     * @param string $user_id
     *        	email of the user
     *        	
     * @return signed-up stage of the user, if found, else false
     */
    public function getSignupStage($userID) {
    	$user = $this -> em ->find('Entity\User',$userID);
		if($user){
			return $user -> getStatus();
		}else{
			return false;
		}		
    }
	/**
	 * This function will update the signup stage for the given user.
	 *
	 * @author Waylan Wong <waylan.wong@willrainit.com>
	 * @param int $user_id
	 *        	The unique user ID of the user.
	 * @param int $newStage
	 *        	The stage ID we will store in the user's record.
	 * @return bool True if the user's signup stage was updated successfully, otherwise false.
	 */
	public function updateSignupStage($userID, $stage) {
    	$user = $this -> em -> getRepository('Entity\User')->findOneBy(array('id' => $userID));
		if($user){
			$user->setStatus($stage);
			$this->em->flush();
		}
	}		
    /**
	 * This function return an array of skills for a given userid.
	 * 
	 * @param int userid.
	 * 
	 * @return array  an array of user's skills and skill id, orderd by their ranking.
	 */
	 public function getSkills($userID){
    	$user = $this -> em -> getRepository('Entity\User')->findOneBy(array('id' => $userID));
		if($user){
			return $user -> getSkills();
		}else{
			return false;
		}		
	 }
    /**
     * Add association between user and skills.
     * It passes two parameters, i.e. 'user id'
     * and array for the skill sets.
     *
     * @param integer $user_id
     *        	unique id for the user
     *        	
     *        	The key of the array represent:
     *        	
     *        	<ul>
     *        	<li>skill_id - id of the skill of the user</li>
     *        	<li>ranking - ranking of the skill for that user</li>
     *        	</ul>
     *        	
     * @return true false success - true, else false
     */
    public function addUserSkill($userId, $skills) {
    	$skillRepo = $this->em->getRepository('Entity\Skill');
		$user = $this->getEntityById($userId);
		foreach($user->getSkills() as $skill){
			$this->em->remove($skill);
		}
		$this->em->flush(); 		
    	foreach ($skills as $sid => $ranking ){
    		$skill = $skillRepo ->findOneBy(array('id' => $sid));
    		$userSkill = new Entity\UserSkill;
			$this->em->persist($userSkill);
			$userSkill->setRanking($ranking);
			$userSkill->setUser($user);
			$userSkill->setSkill($skill);
			$user->addSkill($userSkill);
    	} 
		
		$this->em->flush();   
		return true;	  
    }
    /**
     * used to delete all skills for a specific user .
     *
     * The user_id and an array of
     * skills are passed as parameters:
     *
     * @param integer $user_id
     *        	user_id of the logged in user
     *        	
     * @return true false success - true, else false
     */
    public function deleteAllSkills($userID) {
    	$user = $user = $this -> em -> getRepository('Entity\User')->findOneBy(array('id' => $userID));
		$user -> removeAllSkills();
		$this->em->flush();
    }	 

    
    /**
     * The following function is used to check if a user with user_id = contactId
     * is present in the contact list of currently logged in user with user_id = userId
     * 
     * @param integer $userId        The user_id of the currently logged in user
     * @param integer $contactId     The user_id of any user
     * 
     * @return boolean               if contactId is in contact list of userId, then true
     *                                else false
     */
    public function checkUserContact($userId, $contactId){
        
    	$user = $this->getEntityById($userId);
    	if($user) {
        	$userContacts = $user->getContacts();
    	} else {
    		return 0;
    	}
        $ifInContact = 0;
        
        foreach($userContacts as $userContact){
            if($userContact->getId() === $contactId){
                $ifInContact = 1;
                break;
            }
        }
        return $ifInContact;        
    }
}

?>