<?php

session_start();
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Profile extends CI_Controller {
	
        public function __construct() {
			parent::__construct ();
	        
	        $this -> load -> library('Fms_user_profile');
			$this -> load -> library( 'session' ); 
			$this -> load -> library('encrypt');
			$this -> load -> model("fms_user_profile_model");
			
			// $cleanArray = array();
	        // foreach($_POST as $key => $value){
	            // $cleanPost[$key]=mysql_real_escape_string ( xss_clean (  ( $value ) ) ); //need to add urlencode here 
	        // }						
				//$this -> load -> model ("fms_user_profile_model");
	}
        
       /*
        * First check the signup_stage varible. If it is 1 load singup page 2
        * If it is 2 load singup page 3.
        * After decide which stage is user in check the size of $_POST if it equal to 0
        * Just loadview if it is greater than 0 it is a post request then it will call addSkill() 
        * or updateUser() depends on 
        * 
        */
        
        public function index(){
        	
			echo "profile index";
            
			
		}
		
		
	/**
	 * Get user's Spotlight music.
	 * 
	 * Spotlight music is what user want to show to other people to prove his capability in music.
	 * Each user can at most have 3 Spotlight music and they can ranking them in any order they prefer.
	 * Whenever front end need to display user's Spotlight music they need to make a Ajax call to this function.
	 * But this function can only be called if user is already login.This function does not take any parameter. It will
	 * get userid from session data. And call corresponding model function to get a list of user's spotlight music.
	 * 
	 * @access public
	 * 
	 * @return array  SpotlightLists  A list of URL pointing to user's Spotlight music.
	 */	
	 
	 public function getSpotlight(){
		$userid = $this->session->userdata( 'userid' );
		//h$userid = 1;
		//$data = array();
       	$spotLight = $this->fms_user_profile_model->getSpotlights($userid);
		
		if ($spotLight){
			$this -> encodeJSON($spotLight);
		} 	

	 }
	
	/**
	 * Get user's current profile picture.
	 * 
	 * Whenever frontEnd need current login user's profile picture. Make an Ajax call to this fucntion without
	 * any parameter. It will get usreid from session varible. And it will return url of current user's profile picture.
	 * 
	 * @access public
	 * 
	 * @return string profileUrl  URL point to current user's profile picture.
	 */
	 
	 public function getProfilePicture(){
	 	// TO DO waiting for model function
	 	$userid = $this->session->userdata('userid');
	 	$profileUrl = $this->fms_user_profile_model-> getProfilePictureByUserid($userid);
	 	if ($profileUrl){
	 		return $profileUrl;
	 	}
	 }
	
	/**
	 * Add a new skill to user 
	 * 
	 * @param userID 
	 * @param skillID 
	 * @ranking user can rank his skills. 
	 * @return true/false based on whether database operation return error or not.
	 **/
	public function addUserSkill() {
		$userid = $this->session->userdata( 'userid' );
		$skillsArray = $_POST["skillsArray"];
		// This function already exists in the user profile library, why rewrite it?
		if ($this->fms_user_profile_model->addUserSkill( $userid, $skillsArray )) {
			//return TRUE;
			$this->fms_user_profile_model->updateSignStageByUserId($userid, 2);
			$this->output->set_content_type('application/json')->set_output(json_encode(["signup_success" => 1, "userid" => $userid]));
		} else {
			//return FALSE;
			$this->output->set_content_type('application/json')->set_output(json_encode(["signup_success" => 0, "userid" => $userid]));			
		}
	}
	
	/**
	 * This function is called by AJAX to skip stage 3 of the signup process.
	 * 
	 * skipProfileStep() and returnToPreviousStep() are similar, they could be
	 * combined to use a parameter to switch between different actions, but to 
	 * remove data validation/security concerns out of the equation, we make them 
	 * separate functions that take no input. 
	 * Only the user's signup stage will be set, rerouting will be done by the frontend.
	 * 
	 * @author Waylan Wong <waylan.wong@willrainit.com> 
	 */
	public function skipProfileStep() {
		$userid = $this->session->userdata('userid');
		$signup_stage_completed = $this->fms_user_profile_model->getSignedStageByUserId($userid); 
		if ($userid > 0 && $signup_stage_completed >= 2) {
			$this->fms_user_profile_model->updateSignStageByUserId($userid, 3);
		} else {
			$this->output->set_content_type('application/json')->set_output(json_encode(["userid" => $userid, "signup_stage" => $signup_stage_completed]));
		}
	}
	
	/**
	 * This function will reset the user's signup stage to stage 2.
	 *
	 * skipProfileStep() and returnToPreviousStep() are similar, they could be
	 * combined to use a parameter to switch between different actions, but to 
	 * remove data validation/security concerns out of the equation, we make them 
	 * separate functions that take no input. 
	 * Only the user's signup stage will be set, rerouting will be done by the frontend.
	 * 
	 * @author Waylan Wong <waylan.wong@willrainit.com>
	 */
	public function returnToPreviousStep() {
		$userid = $this->session->userdata('userid');
		$signup_stage_completed = $this->fms_user_profile_model->getSignedStageByUserId($userid); 
		if ($userid > 0 && $signup_stage_completed >= 2) {
			$this->fms_user_profile_model->updateSignStageByUserId($userid, 1);
		} else {
			$this->output->set_content_type('application/json')->set_output(json_encode(["userid" => $userid, "signup_stage" => $signup_stage_completed]));
		}	
	}
	
	/**
	 * Get a list of country's name.
	 * 
	 * Whenever front end need to populater a dropdown menu for countries. They should make an Ajax call to this function.
	 * it does not need any parameter and it will return an arrray of countries name.
	 * 
	 * @access public 
	 * 
	 * @return array  an array of countries name in decending order.
	 */
	 public function getCountryNames(){
	 	//TO DO
	 	$countries = $this -> fms_user_profile_model -> getCountry();
		$this -> encodeJSON($countries);
	 }
	 
	/**
	 * Add picture by ULR.
	 * 
	 * This function will be called when user want to add their profile picture by URL. Front end will make a Ajax call with URL of the picture
	 * as the only parameter. Key should be imgurl.
	 * 
	 * @access public
	 * 
	 * @return void
	 */
	public function uploadPictureByUrl(){
		if(isset($_POST['imgurl'])){
			$imgurl = $_POST['imgurl'];
		}
		$userid = $this->session->userdata('userid');
		$imgDir = "user_files/" . $userid . "/image/"; 
		$imgname = basename($imgurl);
		file_put_contents( $imgPath, file_get_contents( $imgurl ) );
		$dataArray = array();
		// $dataArray["photo_file_name_hashed"] = $this -> encrypt -> encode($imgName); we decide to just store the plain file name.
		$dataArray["photo_file_name_hashed"] = $imgName;
		$dataArray["ip_uploaded"] = $_SERVER['REMOTE_ADDR'];
		$dataArray["timestamp_uploaded"] = date( "Y-m-d H:i:s" );
		$dataArray["photo_file_path"] = $imgDir;
		$pid = $this->fms_user_profile_model->photoFilesInsert( $dataArray );
		$this->fms_user_profile_model->addProfilePhoto( $uid, $pid, date( "Y-m-d H:i:s" ) );		
		
	}	
		
		public function uploadPicture(){
			
			$userid = $this->session->userdata('userid');	
	        $user_id = 1;
	        //$data['upload_audio_dir'] = 'user_files/' . $user_id . '/audio/';       // directory for uploading the audio for user_id
	        //$data['upload_image_dir'] = 'user_files/' . $user_id . '/images/';      // directory for uploading the images for user_id
	        $uploadDir = 'user_files/' . $user_id . '/images/';
			print_r($_FILES);
			//echo "Sdas".$_FILES['userfile']['type'];
			// if($_FILES['userfile']['type'] == 'image/png' || $_FILES['userfile']['type'] == 'image/gif' || $_FILES['userfile']['type'] == 'image/jpg' || $_FILES['userfile']['type'] == 'image/jpeg'){
				// $filename = basename($_FILES['userfile']['name']);
                // $imagePath = $uploadDir . $filename; 
				// $dataArray=array();
				// //echo basename($_FILES['userfile']['name']);
                // move_uploaded_file($_FILES['userfile']['tmp_name'], $imagePath);
				// $dataArray["photo_file_name_hashed"] = $this -> encrypt -> encode($filename);
			   	// $dataArray["ip_uploaded"] = $_SERVER['REMOTE_ADDR'];
			   	// $dataArray["timestamp_uploaded"] = date("Y-m-d H:i:s");
			   	// $dataArray["photo_file_path"] = $uploadDir;
				// $this -> fms_user_profile_model -> photoFilesInsert($dataArray);
                // //$data['images'] = 1;
	        // }else{                                       // mime-type NOT supported
	            // echo "MIME type not supported."; 
	            // //$data['invalid_mime_type'] = 1;
	        // }
				
				
		}
	/**
	 * This function will return the response as a JSON object.
	 * It is important to set the header before echo the JSON object
	 * because for some reason, sometimes it makes it into a string
	 * intead of a JSON object.
	 * 
	 * @param 		Anything you want encoded as a JSON object
	 */
	protected function encodeJSON($encodeThis) {
		$this->output->set_content_type('application/json')->set_output(json_encode($encodeThis));
	}	

    protected function get_full_url() {
        $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

        return
            ($https ? 'https://' : 'http://').
            (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
            ($https && $_SERVER['SERVER_PORT'] === 443 ||
            $_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
            substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }				

}
?>
