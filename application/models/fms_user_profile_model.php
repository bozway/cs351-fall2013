<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Fms_user_profile_model extends CI_Model {

	// Project tables
	private $AUDIO_FILES = 'audio_files';
	private $COUNTRY = 'country';
	private $PERMISSIONS = 'permissions';
	private $PHOTO_FILES = 'photo_files';
	private $SKILLS = 'skills';
	private $USER = 'user';
	private $USER_JOIN_PROFILE_PHOTO = 'user_jn_profile_photo';
	private $USER_JOIN_SKILLS = 'user_jn_skills';
	private $USER_JOIN_SPOTLIGHT_AUDIO = 'user_jn_spotlight_audio';
	private $USER_SOCIAL_NETWORKS_LINKS = 'user_social_networks_links';
	private $USER_SOCIAL_NETWORK_CREDENTIALS = 'user_social_network_credentials';
	public function __construct() {
		$this -> load -> database();
		$this -> load -> helper('security');
	}

	
    /**
     * used to insert in the user table, when a user signs up.
     * An array is passed as
     * a parameter, which is as follows:
     *
     * <ul>
     * <li>email - email of the new user</li>
     * <li>name_first - first name of the new user</li>
     * <li>name_last - last name of the new user</li>
     * <li>password - encrypted password of the new user</li>
     * <li>ip_registration - IP address of the client machine used to register</li>
     * <li>ip_last_login - IP address of the client machine used for logging in last time</li>
     * <li>timestamp_regis - timestamp at the time of registration</li>
     * <li>timestamp_last_login - timestamp at the time of last login of the user</li>
     * <li>sign_stage - current signup stage</li>
     * </ul>
     *
     * @return user_id|false    if success - true, else falses
     */
    public function addUser($arr) {
        $c1 = $this->USER;

        $insertThese = array(
            "email" => $arr['email'],
            "name_first" => $arr['name_first'],
            "name_last" => $arr['name_last'],
            "password_hashed" => $arr['password'],
            "ip_registration" => $arr['ip_registration'],
            "ip_last_login" => $arr['ip_registration'],
            "timestamp_registered" => $arr['timestamp_regis'],
            "timestamp_last_login" => $arr['timestamp_regis'],
            "current_signup_stage" => $arr['sign_stage']
        );
        $insertResult = $this->db->insert( $c1, $insertThese );   
        if($insertResult)
            return mysql_insert_id();
        else
            return false;
    }

   /**
     * this function returns the current sign-stage of the user, given his/ her email
     *
     * @param string $user_id
     *        	email of the user
     *        	
     * @return signed-up stage of the user, if found, else false
     */
    public function getSignedStageByUserId($user_id) {
            $c1 = $this->USER;

            $this->db->select("$c1.current_signup_stage");
            $this->db->from($c1);
            $this->db->where("$c1.id = $user_id");
            $query = $this->db->get();

            $result = $query->result_array();
            if (! empty( $result ))
                    return $result[0]['current_signup_stage'];
            else
                    return false;
    }


    /**
     * this function returns the current sign-stage of the user, given his/ her email
     *
     * @param string $email
     *        	email of the user
     *        	
     * @return signed-up stage of the user, if found, else false
     */
    public function getSignedStageByEmail($email) {
            $c1 = $this->USER;

            $this->db->select("$c1.current_signup_stage");
            $this->db->from($c1);
            $this->db->where("$c1.email = '$email'");
            $query = $this->db->get();

            $result = $query->result_array();
            if (! empty( $result ))
                    return $result[0]['current_signup_stage'];
            else
                    return false;
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
	public function updateSignStageByUserId($user_id, $newStage) {
		$updateThese = array("current_signup_stage" => $newStage);
		$this -> db -> where('id', $user_id);
		$updateResult = $this -> db -> update($this -> USER, $updateThese);
		return $updateResult;
	}

        
    /**
     * returns the user's info for the given user_id
     * 
     * @param integer $user_id unique user_id of the user
     * 
     * @return array user information for the given user_id
     */
    public function getUserInfo($user_id){
        $c1 = $this->USER;

        $this->db->select("*");
        $this->db->from($c1);
        $this->db->where("$c1.id = $user_id");
        $query = $this->db->get();

        $result = $query->result_array();
        if(!empty($result))
            return $result[0];
        else
            return false;
    }
	
    /**
     * Returns the user's full name with one space in between.
     * 
     * @param integer $user_id unique user_id of the user
     * 
     * @return string user's full name
     */
    public function getUserName($user_id){
        $c1 = $this->USER;

        $this->db->select("$c1.name_first, $c1.name_last");
        $this->db->from($c1);
        $this->db->where("$c1.id = $user_id");
        $query = $this->db->get();

        $result = $query->result_array();
        if(!empty($result))
            return $result[0]['name_first']." ".$result[0]['name_last'];
        else
            return false;
    }
	

	
    /**
     * used to update the user table, whenever a user loggs in
     *
     * @param integer $user_id
     *        	user_id of the logged in user
     * @param string $ip_last_login
     *        	ip address of the machine used to login
     * @param timestamp $timestamp_last_login
     *        	timestamp of the last login of the user
     *        	
     * @return true false success - true, else false
     */
    public function userUpdateLastLogin($user_id, $ip_last_login, $timestamp_last_login) {
            $c1 = $this->USER;

            $updateThese = array(
                "ip_last_login" => $ip_last_login,
                "timestamp_last_login" => $timestamp_last_login
            );
            $this->db->where( "id = $user_id" );
            $updateResult = $this->db->update( $c1, $updateThese );

            if($updateResult)
                return true;
            else
                return false;
    }

    /**
     * The user information is updated according to the user's request and submitted
     * information (in the form of an array).
     *
     * @param integer $user_id
     *        	user_id of the user
     *        	
     *        	The keys in the array is as folows:
     *        	
     *        	<ul>
     *        	<li>privacy_flag - 0-private, 1-public</li>
     *        	<li>dob - date of birth of the new use</li>
     *        	<li>gender - gender of the new user</li>
     *        	<li>city - city, to which the new user belongs to</li>
     *        	<li>location_lattitude - lattitude of the geographical location of the user</li>
     *        	<li>location_longitude - longitude of the geographical location of the user</li>
     *        	<li>fk_iso_country_code - iso_country_code, to which the new user belongs to</li>
     *        	</ul>
     *        	
     * @return true false success - true, else false
     */
    public function userUpdateProfile($user_id, $arr) {
            $c1 = $this->USER;

            foreach ( $arr as $key => $value ) {
                $updateThis = array($key => $value);
                $this->db->where( "id = $user_id" );
                $updateResult = $this->db->update( $c1, $updateThis );

                if(!$updateResult)
                    return false;
            }
            return true;
    }

    /**
     * used to add new audio files to the main audio file table.
     * An array is passed
     * as the parameter, in which the keys are as follows:
     *
     * <ul>
     * <li>audio_file_path - relative path of the file</li>
     * <li>audio_file_name_hashed - hash name of the file</li>
     * <li>timestamp_uploaded - timestamp, when the file had been uploaded</li>
     * <li>ip_uploaded - ip address of the machine, used for uploading the file</li>
     * </ul>
     *
     * @return true false success - true, else false
     */
    public function audioFilesInsert($arr) {
        $c1 = $this->AUDIO_FILES;

        $insertThese = array(
            "audio_file_path" => $arr['audio_file_path'],
            "audio_file_name_hashed" => $arr['audio_file_name_hashed'],
            "timestamp_uploaded" => $arr['timestamp_uploaded'],
            "ip_uploaded" =>  $arr['ip_uploaded']
        );
        $insertResult = $this->db->insert( $c1, $insertThese );   
        if($insertResult)
            return mysql_insert_id();
        else
            return false;
    }

    /**
     * used to delete audio file(s)
     *          An array as a parameter is passed 
     *
     * @param array        	
     *        	The key in the array represents:
     *        	
     *        	<ul>
     *        	<li>audio_id - id of the audio file to be deleted for that user</li>
     *        	</ul>
     *        	
     * @return true false success - true, else false
     */
    public function audioFileDelete($arr) {
        $c1 = $this->AUDIO_FILES;
        
        foreach($arr as $row){
            $this->db->where("$c1.id = $row");
            $deleteResult = $this->db->delete($c1); 
            if(!$deleteResult)
                return false;
        }
        return true;
    }
    
    
    /**
     * used to delete the spotlights for the given user for a given array of 
     * audio id
     * 
     * @param integer $user_id  unique user_id of the given user
     * @param array             an array of audio files, which are spotlight
     *                          for the user
     * 
     * @return true|false       if success, then true; else false      
     */
    public function deleteSpotlightsByAudioId($user_id, $arr){
        $c1 = $this->USER_JOIN_SPOTLIGHT_AUDIO;
        
        foreach($arr as $row){
            $this->db->where("$c1.fk_user_id = $user_id");
            $this->db->where("$c1.fk_audio_id = $row");
            $deleteResult = $this->db->delete($c1); 
            if(!$deleteResult)
                return false;
        }
        return true;
    }
    

    /**
     * used to add new audio files to the main audio file table.
     * An array is passed
     * as the parameter, in which the keys are as follows:
     *
     * <ul>
     * <li>photo_file_path - relative path of the file</li>
     * <li>photo_file_name_hashed - hash name of the file </li>
     * <li>timestamp_uploaded - timestamp, when the file had been uploaded</li>
     * <li>ip_uploaded - ip address of the machine, used for uploading the file</li>
     * </ul>
     *
     * @return true false success - true, else false
     */
    public function photoFilesInsert($arr) {
        $c1 = $this->PHOTO_FILES;

        $insertThese = array(
            "photo_file_path" => $arr['photo_file_path'],
            "photo_file_name_hashed" => $arr['photo_file_name_hashed'],
            "timestamp_uploaded" => $arr['timestamp_uploaded'],
            "ip_uploaded" =>  $arr['ip_uploaded']
        );
        $insertResult = $this->db->insert( $c1, $insertThese );   
        if($insertResult)
            return mysql_insert_id();
        else
            return false;
    }

    /**
     * used to delete photo file(s).
     * An array is passed as another parameter.
     *
     * @param array
     *      The keys of the array represent:
     *        	
     *        	<ul>
     *        	<li>photo_id - id of the photo file to be deleted </li>
     *        	</ul>
     *        	
     * @return true false success - true, else false
     */
    public function photoFileDelete($arr) {
        $c1 = $this->PHOTO_FILES;
        
        foreach($arr as $row){
            $this->db->where("$c1.id = $row");
            $deleteResult = $this->db->delete($c1); 
            if(!$deleteResult)
                return false;
        }
        return true;
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
    public function addUserSkill($user_id, $arr) {   // left to modify
        $c1 = $this->USER_JOIN_SKILLS;

        foreach ( $arr as $key => $value ) {
            $insertThis = array(
                "fk_user_id" => $user_id,
                "fk_skill_id" => $key,
                "ranking" => $value
            );
            $insertResult = $this->db->insert( $c1, $insertThis );   
            if(!$insertResult)
                return false;
        }
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
    public function deleteAllSkills($user_id) {
        $c1 = $this->USER_JOIN_SKILLS;
        
        $this->db->where("$c1.fk_user_id = $user_id");
        $deleteResult = $this->db->delete($c1);
        if(!$deleteResult)
            return false;
        else
            return true;
    }

    /**
     * Adds the user's profile photo.
     *
     * @param integer $user_id
     *        	unique user id
     * @param integer $photo_id
     *        	unique id for a photo file
     * @param timestamp $timestamp
     *        	current timestamp, i.e. at which profile photo was added
     *        	
     * @return true false success - true, else false
     */
    public function addProfilePhoto($user_id, $photo_id, $timestamp) {
        $c1 = $this->USER_JOIN_PROFILE_PHOTO;
        
        $insertThese = array(
            "fk_user_id" => $user_id,
            "fk_photo_id" => $photo_id,
            "timestamp_setphoto" => $timestamp
        );
        $insertResult = $this->db->insert( $c1, $insertThese );   
        if($insertResult)
            return mysql_insert_id();
        else
            return false;
    }

    /**
     * update the user's profile photo:
     *
     * @param integer $user_id
     *        	unique user id
     * @param integer $photo_id
     *        	unique id for a photo file
     * @param timestamp $timestamp
     *        	current timestamp, i.e. at which profile photo was changed
     *        	
     * @return true false success - true, else false
     */
    public function changeProfilePhoto($user_id, $photo_id, $timestamp) {
        $c1 = $this->USER_JOIN_PROFILE_PHOTO;

        $updateThese = array("fk_photo_id" => $photo_id, "timestamp_setphoto" => $timestamp);
        $this->db->where( "fk_user_id", $user_id );
        $updateResult = $this->db->update( $c1, $updateThese );
        if($updateResult)
            return true;
        else
            return false;
    }
    
    
        
    /**
     * used to delete the profile image for the given user 
     * 
     * @param integer $user_id  unique user_id of the given user
     * @param integer $photo_id unique photo_id of an image                         
     * 
     * @return true|false       if success, then true; else false      
     */
    public function deleteProfilePhoto($user_id, $photo_id){
        $c1 = $this->USER_JOIN_PROFILE_PHOTO;
        
        $this->db->where("$c1.fk_user_id = $user_id");
        $this->db->where("$c1.fk_photo_id = $photo_id");
        $deleteResult = $this->db->delete($c1); 
        if(!$deleteResult)
            return false;
        else
            return true;
    }

    /**
     * used to add an audio file as spotlight w.r.t the specific user.
     * This is used to create
     * a new spotlight, which unique audio_id and ranking for the user_id;
     *
     * @param integer $user_id
     *        	user id of the user for which spotlight is being updated
     *        	
     *        	An array is also passed as the second parameter, which represents the following
     *        	key - value pair:
     *        	
     *        	<ul>
     *        	<li>ranking (key) - ranking of the audio as a spotlight; 1, 2 or 3</li>
     *        	<li>audio_id (value) - id of the audio file, being set as a spotlight</li>
     *        	</ul>
     *        	
     * @return true false successful, true, else false
     */
    public function addSpotLight($user_id, $arr) {
        $c1 = $this->USER_JOIN_SPOTLIGHT_AUDIO;

        foreach ( $arr as $key => $value ) {
            $insertThese = array(
                "fk_user_id" => $user_id,
                "fk_audio_id" => $key,
                "ranking" => $value
            );
            $insertResult = $this->db->insert( $c1, $insertThese );   
            if(!$insertResult)
                return false;
        }
        return true;
    }

    /**
     * used to update the spotlights for a specific user for a given ranking.
     * There must exist an audio for the user_id with the specific ranking.
     *
     * @param integer $user_id
     *        	unique user id of the user
     *        	
     *        	An array is also passed as the second parameter, which represents the following
     *        	key - value pair:
     *        	
     *        	<ul>
     *        	<li>ranking (key) - ranking of the audio as a spotlight; 1, 2 or 3</li>
     *        	<li>audio_id (value) - id of the audio file, being set as a spotlight</li>
     *        	</ul>
     *        	
     * @return true|false  if successful - true, else false
     */
    public function updateSpotLight($user_id, $arr) {
        $c1 = $this->USER_JOIN_SPOTLIGHT_AUDIO;

        foreach ( $arr as $key => $value ) {
            $updateThis = array("ranking" => $value);
            $this->db->where( "fk_user_id", $user_id );
            $this->db->where( "fk_audio_id", $key );
            $updateResult = $this->db->update( $c1, $updateThis );
            
            if(!$updateResult)
                return false;
        }
        return true;
    }

    /**
     * used to update the ranking of an existing spotlight.
     * Here, the spotlight must exist, if the ranking is needed to be modified.
     *
     * @param integer $user_id
     *        	unique user id of the user
     * @param integer $audio_id
     *        	unique id for an audio file
     *        	
     * @return true false success - true, else false
     */
    public function updateSpotLightRanking($user_id, $audio_id, $ranking) {
        $c1 = $this->USER_JOIN_SPOTLIGHT_AUDIO;

        $updateThis = array("ranking" => $ranking);
        $this->db->where( "fk_user_id", $user_id );
        $this->db->where( "fk_audio_id", $audio_id );
        $updateResult = $this->db->update( $c1, $updateThis );

        if($updateResult)
            return true;
        else
            return false;
    }

    /**
     * used to add a permission for different field(s) associated with a specific user.
     * Apart from the user_id, an array with key-value pair is passed as parameter, for
     * which the permission to be modified.
     *
     * The values of the array_key should be either 1, 2 or 3.
     *
     * @param integer $user_id
     *        	user id of the user
     *        	
     *        	As second parameter, an array is passed which represents the following as keys:
     *        	
     *        	<ul>
     *        	<li>dob - date of birth of the user</li>
     *        	<li>city - city to which the user belongs to</li>
     *        	<li>country - iso-country code </li>
     *        	<li>spotlight - spotlights associated with the user</li>
     *        	<li>skills - skills associated with the user</li>
     *        	</ul>
     *        	
     * @return true false successful - true, else return false
     */
    public function addPermission($user_id, $arr) {
        $c1 = $this->PERMISSIONS;

        $insertThese = array(
            "fk_user_id" => $user_id,
            "dob" => $arr['dob'], 
            "city" => $arr['city'], 
            "country" => $arr['country'], 
            "spotlight" => $arr['spotlight'], 
            "skills" => $arr['skills'], 
        );
        $insertResult = $this->db->insert( $c1, $insertThese );   
        if($insertResult)
            return mysql_insert_id();
        else
            return false;
    }

    /**
     * used to update a permission for some or all field(s) associated with a specific
     * user.
     * Apart from the user_id, an array with key-value pair is passed as parameter,
     * for which the permission to be modified.
     *
     * The values of the array_key should be either 1, 2 or 3.
     * Note: the array-key must be same as the column name of the 'permission' table.
     *
     * @param integer $user_id
     *        	user_id of the user
     *        	
     *        	The keys of the array represents the following:
     *        	
     *        	<ul>
     *        	<li>dob - date of birth of the user</li>
     *        	<li>city - city to which the user belongs to</li>
     *        	<li>country - iso-country code </li>
     *        	<li>spotlight - spotlights associated with the user</li>
     *        	<li>skills - skills associated with the user</li>
     *        	</ul>
     *        	
     * @return true false successful, return true; else return false;
     */
    public function updatePermission($user_id, $arr) {
        $c1 = $this->PERMISSIONS;

        foreach ( $arr as $key => $value ) {
            $updateThis = array($key => $value);
            $this->db->where( "fk_user_id", $user_id );
            $updateResult = $this->db->update( $c1, $updateThis );   
            if(!$updateResult)
                return false;
        }
        return true;
    }

    /**
     * adds the user's social network credentials information
     *
     * @param integer $user_id
     *        	unique user_id of the user
     * @param integer $user_net_id
     *        	unique network id of the user provided by the social network, like facebook or twitter
     * @param varchar $user_net_token
     *        	user's social network lookup id
     * @param varchar $user_net_secret 
     *              user's authentication secret provided by the social network provider
     * @param integer $user_net_acc_type
     *        	user's social network lookup id, i.e. facebook or twitter etc.
     *        	
     * @return true|false success - true, else false
     */
    public function addUserNetworkInfo($user_id, $user_net_id, $user_net_token, $user_net_secret, $user_net_acc_type) {
        $c1 = $this->USER_SOCIAL_NETWORK_CREDENTIALS;

        $insertThis = array(
            "fk_user_id" => $user_id,
            "user_net_id" => $user_net_id,
            "user_net_token" => $user_net_token,
            "user_net_secret" => $user_net_secret,
            "user_net_acc_type" => $user_net_acc_type
        );
        $insertResult = $this->db->insert( $c1, $insertThis );   
        if($insertResult)
            return mysql_insert_id();
        else
            return false;
    }


    /**
     * this function updates the network token of the user, if the user's session
     * expires for the specific social network session
     * 
     * @param integer $user_id          
     *              unique user_id for the user
     * @param string $user_net_token    
     *              the new user_net_token (for e.g. - oauth_token for twitter) for the user 
     * @param integer $user_net_id 
     *              user's social network id  
     * @param integer $user_net_acc_type
     *              the account type for the various social networks                              
     * 
     * @return true|false if success - true, else false
     */
    public function updateUserNetworkInfo($user_id, $user_net_id, $user_net_token, $user_net_acc_type){
        $c1 = $this->USER_SOCIAL_NETWORK_CREDENTIALS;

        $updateThis = array("user_net_token" => $user_net_token);
        $this->db->where( "fk_user_id", $user_id );
        $this->db->where( "user_net_id", $user_net_id );
        $this->db->where( "user_net_acc_type", $user_net_acc_type );
        $updateResult = $this->db->update( $c1, $updateThis );
        if($updateResult)
            return true;
        else
            return false;
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
        $c1 = $this->USER;

        $this->db->select("$c1.id");
        $this->db->from($c1);
        $this->db->where("$c1.email = '$email'");
        $query = $this->db->get();

        $result = $query->result_array();
        if (! empty( $result ))
                return $result[0]['id'];
        else
                return false;
    }

    /**
     * returns user-id of a user by his/her Facebook User Id
     *
     * @param string $user_net_Id
     *        	Facebook user id of the user
     *        	
     * @return integer user_id of the user
     */
    public function getUserIdByFacebookID($user_net_Id) {
        $c1 = $this->USER_SOCIAL_NETWORK_CREDENTIALS;
        $user_net_acc_type = 1;

        $this->db->select("$c1.fk_user_id");
        $this->db->from($c1);
        $this->db->where("$c1.user_net_id = '$user_net_Id'");
        $this->db->where("$c1.user_net_acc_type = '$user_net_acc_type'");
        $query = $this->db->get();

        $result = $query->result_array();
        if (! empty( $result ))
                return $result[0]['fk_user_id'];
        else
                return false;
    }

    /**
     * returns user-id of a user by his/ her Twitted id
     *
     * @param string $user_net_Id
     *        	unique token for the user provided by Twitter
     *        	
     * @return integer user_id of the user
     */
    public function getUserIdByTwitterID($user_net_Id) {
        $c1 = $this->USER_SOCIAL_NETWORK_CREDENTIALS;
        $user_net_acc_type = 2;

        $this->db->select("$c1.fk_user_id");
        $this->db->from($c1);
        $this->db->where("$c1.user_net_id = '$user_net_Id'");
        $this->db->where("$c1.user_net_acc_type = '$user_net_acc_type'");
        $query = $this->db->get();

        $result = $query->result_array();
        if (! empty( $result ))
                return $result[0]['fk_user_id'];
        else
                return false;
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
    public function getHashPasswordByEmail($email) {
        $c1 = $this->USER;
        
        $this->db->select("$c1.password_hashed");
        $this->db->from($c1);
        $this->db->where("$c1.email = '$email'");
        $query = $this->db->get();
        
        $result = $query->result_array();
        if (! empty( $result ))
                return $result[0]['password_hashed'];
        else
                return false;
    }

    /**
     * @deprecated since version 1.0
     * this function checks if the logging user is valid user or not.
     *
     * @param integer $user_id
     *        	user_id of the user
     * @param string $password
     *        	password of the user
     *        	
     * @return true false if a match found; otherwise false
     */
    public function ifValidUser($user_id, $password) {
        $c1 = $this->USER;

        $this->db->select("*");
        $this->db->from($c1);
        $this->db->where("$c1.id = $user_id");
        $this->db->where("$c1.password_hashed = '$password'");
        $query = $this->db->get();
        
        $result = $query->result_array();
        if (! empty( $result ))
                return true;
        else
                return false; 
    }

    /**
     * this function returns the array of all the countries
     *
     * @return array list of all countries
     */
    public function getCountry() {
        $c1 = $this->COUNTRY;

        $this->db->select("*");
        $this->db->from($c1);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * this function returns the profile picture id of the user with given 
     * user_id
     * 
     * @param intgeger $user_id unique user_id of the user
     * 
     * @return integer photo_id of the profile picture for the user
     */
    public function getProfilePictureByUserId($user_id){
        $c1 = $this->USER_JOIN_PROFILE_PHOTO;
        $c2 = $this -> PHOTO_FILES;
        $this->db->select("$c2.photo_file_path as path, $c2.photo_file_name_hashed as name");
        $this->db->from($c1.",$c2");
        $this->db->where("$c1.fk_user_id = '$user_id' and $c1.fk_photo_id = $c2.id");
        $query = $this->db->get();
        $result = $query->result_array();
		//print_r($result);
        if (!empty( $result))
            return $result[0];
        else
            return false;
    }
    
    /**
     * this function returns the user's spotlights, for his/ her user_id. 
     * 
     * @param integer $user_id unique user_id of the user
     * 
     * @return array array of the spotlights of the user, ordered by the ranking
     */
    
    public function getSpotlights($user_id){
        $c1 = $this->USER_JOIN_SPOTLIGHT_AUDIO;
        $c2 = $this->AUDIO_FILES;
        
        $this->db->select("$c1.fk_audio_id as id, $c2.audio_file_path as path, $c2.audio_file_name_hashed as name");
        $this->db->from($c1);
        $this->db->join($c2,"$c1.fk_audio_id = $c2.id");
        $this->db->where("$c1.fk_user_id = '$user_id'");
        $this->db->order_by("$c1.ranking", "asc");
        $query = $this->db->get();
        $result = $query->result_array();
        if (!empty( $result))
            return $result;
        else
            return false;
    }

    
    
    /**
     * this function returns the profile picture path for given photo id
     * 
     * @param integer $photo_id unique photo id of the image
     * 
     * @return string the path of the image file
     */
    public function getImagePathByPhotoId($photo_id){
        $c1 = $this->PHOTO_FILES;
        
        $this->db->select("$c1.photo_file_path");
        $this->db->from($c1);
        $this->db->where("$c1.id = '$photo_id'");
        $query = $this->db->get();
        $result = $query->result_array();
        if (!empty( $result))
            return $result[0]['photo_file_path'];
        else
            return false;
    }
    
    /**
	 * This function return an array of skills for a given userid.
	 * 
	 * @param int userid.
	 * 
	 * @return array  an array of user's skills and skill id, orderd by their ranking.
	 */
	 public function getSkillsByUserid($uid){
	 	$c1 = $this -> USER_JOIN_SKILLS;
		$c2 = $this -> SKILLS;
		
		$this -> db -> select("$c1.fk_skill_id as id, $c2.name");
		$this -> db -> from ($c1.",$c2");
		//$this -> db -> from ();
		$this -> db -> where ("$c1.fk_user_id ='$uid' and $c1.fk_skill_id = $c2.id");
		$this -> db -> order_by("$c1.ranking", "asc");
        $query = $this->db->get();
		//echo $this -> db ->last_query();
        $result = $query->result_array();
        //print_r($a);
        if (!empty( $result))
            return $result;
        else
            return false;		
		
	 }

}

?>
