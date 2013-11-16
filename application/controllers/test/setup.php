<?php

if (! defined( 'BASEPATH' ))
	exit( 'No direct script access allowed' );
/**
 * The controller for set up user's account.
 *
 * This controller is responsible for set up user's account when they first signup.
 * Including add their skills and profile information such as date of birst, country, gender etc.
 * But the profile picture and spot light musics at stage 3 is handled in another controler which is specialized in handler file upload.
 *
 *
 * @author Leo
 */
class Setup extends Authenticated_service {
	public $cleanPost = array();
	public function __construct() {
		parent::__construct();
		
		$this->load->library( 'fms_user_profile' );
		$this->load->library( 'encrypt' );
		$this->load->model( "fms_user_profile_model" );
		$this->load->model( "docModels/fms_user_model" );
		$this->load->model( "docModels/fms_general_model" );
		$params = array('em' => $this->doctrine->em);
		$this->load->library('entityserializer',$params);
	}
	
	/**
	 * Add a array of new skill to user.
	 *
	 * This function will be called by an Ajax call from frontend. The skillsarray will be passed thru $_POST[].
	 * It is a associate array with skillID as key, ranking for each skill as value. This function will call skillsArrayValidation()
	 * to validate $skillsArray. If it pass validation it will call model function to insert data into database.
	 *
	 * @access public
	 *        
	 * @param array $skillsArray        	
	 *
	 * @return true/false based on whether database operation return error or not.
	 */
	public function addUserSkill() {
		
		$skillsArray = $this->cleanPost["skillsArray"];
		if ($this->fms_user_model->addUserSkill( $this->userId, $skillsArray )) {
			// return TRUE;
			$this->fms_user_model->updateSignupStage($this->userId,2);
			$this->encodeJSON( [
					"signup_success" => 1,
			] );
		} else {
			// return FALSE;
			$this->encodeJSON( [
					"signup_success" => 0,
			] );
		}
	}
	
	public function updateSignupStage($userid,$stage){
		$this->fms_user_model->updateSignStageByUserId( $userid, $stage );
		
	}
	
	/**
	 * Validate whether skillsArray is legal.
	 *
	 * Go thru all the element inside array and check whether its pure interger.
	 * $skillArray is a array with skillID as key and ranking as value. Each pair represent
	 * user's ranking for a particular skill. This function will go thru element inside array and
	 * check whether all the key,value pair is integer.
	 *
	 * @access private
	 * @param array $skillArray        	
	 * @return bool true|false True if it passes validation, false otherwise.
	 */
	private function skillsArrayValidation($skillArray) {
		foreach ( $skillArray as $key => $value ) {
			if ((! ctype_digit( $key )) || (! ctype_digit( $value ))) {
				return false;
			}
		}
		return true;
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
		$user = $this->fms_user_model->getEntityById( $this->userId );
		$signup_stage_completed = $user->getStatus();
		if ($this->userId > 0 && $signup_stage_completed >= 2) {
			$user->setStatus(3);
			$this->doctrine->em->flush();
		} else {
			$this->output->set_content_type( 'application/json' )->set_output( json_encode( [
					"userid" => $this->userId,
					"signup_stage" => $signup_stage_completed 
			] ) );
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
		
		$user = $this->fms_user_model->getEntityById($this->userId);
		$signup_stage_completed = $user->getStatus();
		if ($this->userId > 0 && $signup_stage_completed >= 2) {
			$user->setStatus(1);
			$this -> doctrine -> em ->flush();
		} else {
			$this->output->set_content_type( 'application/json' )->set_output( json_encode( [
					"userid" => $this->userId,
					"signup_stage" => $signup_stage_completed 
			] ) );
		}
	}
	
	/**
	 * This function is responsible for set up user's profile.
	 *
	 * This function will expect a Ajax call from front end. And will take his paramter from $_POST
	 * All the array-key will be listed below as param. It will also set user's signup_stage to 3.
	 *
	 * @access public
	 *        
	 * @param date $dob
	 *        	date of birth
	 * @param int $gender
	 *        	3 possible value: 0 means no information on gender, 1 means male, 2 means female.
	 * @param string $city        	
	 * @param string $country
	 *        	country code
	 * @param int $privacy
	 *        	use's preference for privacy 1 for public profile, 2 for private profile.
	 *        	
	 * @return void
	 */
	public function updateUserProfile() {
		
		$data = array();
		$data['dob'] = $this->cleanPost['dob'];
		$data['gender'] = $this->cleanPost['gender'];
		$data['city'] = $this->cleanPost['city'];
		$data['fk_iso_country_code'] = $this->cleanPost['country'];
		$data['privacy_flag'] = $this->cleanPost['privacy'];
		$data['current_signup_stage'] = 3;
		if ($this->validateUserProfile( $data )) {
			$this->fms_user_profile_model->userUpdateProfile( $this->userId, $data );
		}
	}
	
	/**
	 * Validate input of user's profile information such as dob,city,conuntry and gender etc.
	 *
	 * Date of birth has to be yyyy-mm-dd, city has to be string, country has to be 2 character.
	 * gender must be 0 or 1 or 2. privacry_flag must be 1 or 2.
	 *
	 * @access private
	 *        
	 * @param array $data        	
	 *
	 * @return Boolen True if $data array pass validation,false otherwise.
	 *        
	 */
	private function validateUserProfile($data) {
		if ($data['gender'] != 0 && $data['gender'] != 1 && $data['gender'] != 1) {
			return false;
		}
		if (! ctype_alpha( $data['city'] )) {
			return false;
		}
		if ($data['privacy_flag'] != 1 && $data['privacy_flag'] != 1) {
			return false;
		}
		if (! ctype_alpha( $data['fk_iso_country_code'] ) && strlen( $data['fk_iso_country_code'] ) != 2) {
			return false;
		}
		$expression = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/";
		if (! preg_match( $expression, $data['dob'] )) {
			return false;
		}
		return true;
	}

    /**
	 * This function add ranking to uploaded spotlight music.
	 * 
	 * When the actual audio file was uplaoded we still dont know ranking for that file yet.
	 * We only can get this information after all file is uploaded, so at the end of setup user profile,
	 * front end should make an Ajax call to this function with an array as parameter. Where audio file id is key
	 * and ranking is value.
	 * 
	 * @access public
	 * 
	 * @param array    An array of (audioID => ranking).
	 * 
	 * @return void
	 */
	 public function updateSpotlightRanking(){
	 	
		$spotlightRanking = $this -> cleanPost['spotlightRanking'];		
		// This function already exists in the user profile library, why rewrite it?
		if ($this->fms_user_profile_model->updateSpotLight( $this->userId, $spotlightRanking)) {
			return TRUE;		
		}else{
			return false;
		}
	 }
	
	

	
	/**
	 * This function is under construction.
	 * (Implentation not sure yet)
	 */
	public function uploadPicture() {
		
		// $data['upload_audio_dir'] = 'user_files/' . $user_id . '/audio/'; // directory for uploading the audio for user_id
		// $data['upload_image_dir'] = 'user_files/' . $user_id . '/images/'; // directory for uploading the images for user_id
		$uploadDir = 'user_files/' . $this->userId . '/images/';
		print_r( $_FILES );
		echo "Sdas" . $_FILES['userfile']['type'];
		if ($_FILES['userfile']['type'] == 'image/png' || $_FILES['userfile']['type'] == 'image/gif' || $_FILES['userfile']['type'] == 'image/jpg' || $_FILES['userfile']['type'] == 'image/jpeg') {
			$filename = basename( $_FILES['userfile']['name'] );
			$imagePath = $uploadDir . $filename;
			$dataArray = array();
			// echo basename($_FILES['userfile']['name']);
			move_uploaded_file( $_FILES['userfile']['tmp_name'], $imagePath );
			$dataArray["photo_file_name_hashed"] = $this->encrypt->encode( $filename );
			$dataArray["ip_uploaded"] = $_SERVER['REMOTE_ADDR'];
			$dataArray["timestamp_uploaded"] = date( "Y-m-d H:i:s" );
			$dataArray["photo_file_path"] = $uploadDir;
			$this->fms_user_profile_model->photoFilesInsert( $dataArray );
			// $data['images'] = 1;
		} else { // mime-type NOT supported
			echo "MIME type not supported.";
			// $data['invalid_mime_type'] = 1;
		}
	}
	
	/**
	 * Retrun country name and code to front-end
	 * This function will retrun country name and code as a json string to the front-end
	 * 
	 * @author Wei
	 */
	public function getCountryCodeName() {
		$results = $this->fms_general_model->getAllCountries();
		$dataArray=array();
		if(!$results){
			return false;
		}
		foreach($results as $country){
			$dataArray[] = $this->entityserializer->toArray($country);							
		}
				
		$this->output->set_content_type('application/json')->set_output(json_encode($dataArray));
	}
}
?>
