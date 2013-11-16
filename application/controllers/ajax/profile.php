<?php

/**
 * "Profile" controller contains bunch of AJAX handlers
 * that are related to update user profile.
 * 
 * <b>Notice:<b><br/> 
 * <ul>
 *      <li>
 *          Most of the functions comes from setup.php, we have removed functions
 *          that we no longer use, and added new functions according to the new wireframes.
 *      <li>
 *      <li>
 *          Most of the function require checking login, and checking permission.
 *      </li>
 *      <li>
 *          Do remember to update the description and author when you work on the
 *          functions.
 *      </li>
 * </ul>
 * 
 */
session_start();
if (! defined( 'BASEPATH' ))
	exit( 'No direct script access allowed' );
class Profile extends Authenticated_service {
	const SUCCESS = 0;
	const ERROR_PARAM_MISSING = 100;
	const ERROR_SKILL_NUM = 200;
	const ERROR_GENRE_NUM = 201;
	const ERROR_INFLUENCE_NUM = 202;
	const ERROR_SKILL_RANKING = 203;
	const ERROR_FNAME = 1;
	const ERROR_LNAME = 2;
	const ERROR_EMAIL = 3;
	const ERROR_CITY = 4;
	const ERROR_LANGUAGE = 5;
	const ERROR_WEBADDR = 6;
	const ERROR_COUNTRY = 7;
	const ERROR_STATE = 8;
	public function __construct() {
		parent::__construct();
		
		$this->load->helper( 'url' );
		$this->load->model( 'docModels/fms_user_model' );
		$this->load->model( 'docModels/fms_general_model' );
		$this->load->model( 'docModels/fms_skill_model' );
		$this->load->model( 'docModels/fms_user_skill_model' );
		$this->load->model( 'docModels/fms_country_model' );
		$this->load->model( 'docModels/fms_project_model' );
		$this->load->model( 'docModels/fms_genre_model' );
		$this->load->model( 'docModels/fms_influence_model' );
		$this->load->model( 'docModels/fms_snw_model' );
		$this->load->model( 'docModels/fms_file_model' );
		$this->load->model( 'docModels/fms_project_member_model' );
		$this->load->model( 'docModels/fms_language_model');
		$this->load->model( 'docModels/fms_us_state_model' );
	}
	
	/**
	 * This function handles the AJAX call from front-end to display dashboard
	 * profile skill editing page. The page is returned as JSON or HTML. Use session
	 * variable to identify the user.
	 *
	 */
	public function getUserSkills() {

		// Retrive user data from DB
		$user = $this -> fms_user_model -> getEntityById($this -> userId);
		$userSkills = $user -> getSkills();
		$data = array();
		foreach ($userSkills as $key => $userSkill) {
			$data[$key]['name'] = $userSkill -> getSkill() -> getName();
			$data[$key]['ranking'] = $userSkill -> getRanking();
			$data[$key]['videoPreview'] = $userSkill -> getVideoPreview();
			$data[$key]['genres'] = array();
			$data[$key]['influnces'] = array();
			foreach ($userSkill->getGenres() as $genre) {
				$data[$key]['genres'][] = $genre -> getName();
			}
			foreach ($userSkill->getInfluences() as $influence) {
				$data[$key]['influences'][] = $influence -> getName();
			}
		}

		// encodes the response object into JSON
		$this -> encodeJSON($data);
	}
	
	/**
	 *
	 *
	 * This function delete the current user's existing skills, genres,
	 * influences,
	 * and add new records based on the POST data. This function can be AJAXed
	 * by
	 * signup stage3 and dashboard user skill editing page.<br/>
	 * <b>Notice:</b> We have addUserSkill() in setup.php, but since we added
	 * genres
	 * and influences and categories, this function should be rewritten.
	 *
	 * @access public
	 * @author Hao Cai
	 *        
	 */
	public function updateUserSkill() {
		$user_skill_array = $this->input->post( 'userSkills' );
		
		// validate the data
		$errorcode = $this->validateUserSkills( $user_skill_array );
		if ($errorcode != Profile::SUCCESS) {
			$responseObj = array(
					'errorcode' => $errorcode,
					'message' => 'Data is invalid' 
			);
			$this->encodeJSON( $responseObj );
			return;
		}
		
		// FIRST: Delete all the userskills
		$user = $this->fms_user_model->getEntityById( $this->userId );
		$this->fms_user_skill_model->deleteAllUserSkills( $user );
		
		// SECOND: Add updated userskills
		foreach ( $user_skill_array as $user_skill_item ) {
			$skillId = $user_skill_item['skillid'];
			$ranking = $user_skill_item['ranking'];
			$previw_src = $user_skill_item['skillPrev'];
			if (strrpos( $previw_src, "//www.youtube.com/embed/" ) !== 0 && $previw_src != "") {
				continue;
			}
			$skill = $this->fms_skill_model->getEntityById( $skillId );
			$userSkill = $this->fms_user_skill_model->createEntity( $user, $skill, $ranking, $previw_src );
			if (isset( $user_skill_item['influences'] )) {
				foreach ( $user_skill_item['influences'] as $influenceName ) {
					if (strlen( $influenceName ) > 255) {
						continue;
					}
					$influence = $this->fms_influence_model->getEntityByName( $influenceName );
					$userSkill->addInfluence( $influence );
				}
			}
			if (isset( $user_skill_item['genres'] )) {
				foreach ( $user_skill_item['genres'] as $genreId ) {
					$genre = $this->fms_genre_model->getEntityById( $genreId );
					if (! isset( $genre )) {
						continue;
					}
					$userSkill->addGenre( $genre );
				}
			}
			$this->fms_general_model->flush();
		}		
		$responseObj = array(
				'errorcode' => 0,
				'message' => 'Your changes has been saved' 
		);
		$this->encodeJSON( $responseObj );
	}
	private function validateUserSkills($user_skill_array) {
		$errorcode = Profile::SUCCESS;
		if (! $user_skill_array) {
			$errorcode = Profile::ERROR_PARAM_MISSING;
			return $errorcode;
		}
		if (count( $user_skill_array ) > 10) {
			$errorcode = Profile::ERROR_SKILL_NUM;
			return $errorcode;
		}
		foreach ( $user_skill_array as $user_skill ) {
			if ($user_skill['ranking'] < 0 || $user_skill['ranking'] > count( $user_skill_array ) - 1) {
				$errorcode = Profile::ERROR_SKILL_RANKING;
				return $errorcode;
			}
			if (isset( $user_skill['genres'] ) && count( $user_skill['genres'] ) > 5) {
				$errorcode = Profile::ERROR_GENRE_NUM;
				return $errorcode;
			}
			if (isset( $user_skill['influences'] ) && count( $user_skill['influences'] ) > 5) {
				$errorcode = Profile::ERROR_INFLUENCE_NUM;
				return $errorcode;
			}
		}
		return $errorcode;
	}
	
	/**
	 * This function updates the following user profile:
	 * DOB, country, city, privacy, gender, signup stage, language, name, web
	 * address.
	 * This function can be AJAXed by signup stage3 and dashboard user skill
	 * editing page.
	 * This function will also update spotlight rankings, which is an ordered
	 * array of file_id.
	 *
	 * @access public
	 * @author Wei Zong
	 *        
	 */
	public function updateUserProfile() {
		$user = $this->fms_user_model->getEntityById( $this->userId );
		// Retrive the posted data
		$firstname = $this->input->post( 'namefirst' );
		$lastname = $this->input->post( 'namelast' );
		$language = $this->input->post( 'language' );
		$country = $this->input->post( 'country' );
		$state = $this->input->post( 'state' );
		$city = $this->input->post( 'city' );
		$webaddr = $this->input->post( 'webaddr' );
		$dob = $this->input->post( 'dob' );
		$gender = $this->input->post( 'gender' );
		$ranking = $this->input->post( 'audioranking' );
		
		// Validate the ALPHA only values
		// $firstname = iconv( "UTF-8", "ISO-8859-1", $firstname) ;
		// $firstname = iconv( "UTF-8", "ISO-8859-1", $lastname) ;
		if ($firstname) {
			if (! ctype_alpha( $firstname ) || strlen( $firstname ) > 20)
				$errorcode = Profile::ERROR_FNAME;
		}
		if ($lastname) {
			if (! ctype_alpha( $lastname ) || strlen( $lastname ) > 20)
				$errorcode = Profile::ERROR_LNAME;
		}
		
		// Validate Webaddr
		if ($webaddr) {
			if (! preg_match( '/^[a-zA-Z0-9_-]+$/', $webaddr, $matches ) || strlen( $webaddr ) > 20) {
				$errorcode = Profile::ERROR_WEBADDR;
			}
		}
		
		// Validate City
		if ($city) {
			if (! preg_match( '/^[a-zA-Z ]+$/', $city, $matches ) || strlen( $city ) > 20) {
				$errorcode = Profile::ERROR_CITY;
			}
		}
		
		// Validate language
		$languageEntity = false;
		if($language){
			$languageEntity = $this->fms_language_model->getEntityByName($language);
			if (!$languageEntity && $language != "Language") {
				$errorcode = Profile::ERROR_LANGUAGE;
			}
		}
		
		
		// Validate States
		$stateEntity = false;
		if($state) {
			$stateEntity = $this->fms_us_state_model->getEntityByName($state);
			if(!$stateEntity && $state != 'State') {
				$errorcode = Profile::ERROR_STATE;
			}
		}
		
		// Validate Country
		$countryArray = array(
				false,
				'Country',
				'CHINA',
				'UNITED STATES',
				'INDIA' 
		); // The array can be put to DB
		if (array_search( $country, $countryArray ) === false) {
			$errorcode = Profile::ERROR_COUNTRY;
		}
		
		if (isset( $errorcode ) && $errorcode != 0) {
			$responseObj = array(
					'errorcode' => $errorcode,
					'message' => 'there is an error' 
			);
			$this->encodeJSON( $responseObj );
			return;
		}
		switch($gender)
		{
		case "Male":
			$gender = fms_user_model::GENDER_MALE;
			break;
		case "Female":
			$gender = fms_user_model::GENDER_FEMALE;
			break;
		case "Unspecified":
			$gender= fms_user_model::GENDER_UNSPECIFIED;
			break;
		}
		// Validate DOB and gender
		// NOT IMPLEMENTED YET
		
		// No need to check language: cannot find country entity if not valid
		// Don't know how to validate array of ranking
		
		// Update User basic information
		if ($firstname)
			$user->setFirstName( $firstname );
		if ($lastname)
			$user->setLastName( $lastname );
		if ($languageEntity)
			$user->setLanguage( $languageEntity );
		if ($language == 'Language')
			$user->setLanguage( null );
		if ($stateEntity)
			$user->setState( $stateEntity );
		if ($state == 'State')
			$user->setState( null );
		if ($country) {
			$countryObj = $this->fms_country_model->getEntityByName( $country );
			$user->setCountry( $countryObj );
		}
		if ($city)
			$user->setCity( $city );
		if ($dob)
			$user->setDob( $dob );
		if ($gender)
			$user->setGender( $gender );
		$user->setStatus( 3 );
		
		// Check web address availability and update
		if ($webaddr) {
			$checkUser = $this->fms_user_model->getEntityByWebAddress( $webaddr );
			if (! $checkUser) {
				$user->setWebAddress( $webaddr );
			} else {
				if ($checkUser->getId() === $user->getId()) {
					$user->setWebAddress( $webaddr );
				}
			}
		}
		
		// Update audio ranking
		if ($ranking) {
			$iter = 1;
			foreach ( $ranking as $row ) {
				$audio = $this->fms_file_model->getEntityById( $row );
				$audio->setSubtype( $iter );
				$iter ++;
			}
		}
		
		// flush
		$this->fms_general_model->flush();
		
		$responseObj = array(
				'errorcode' => 0,
				'message' => 'success' 
		);
		$this->encodeJSON( $responseObj );
	}
	
	/**
	 * skipProfileStep()
	 *
	 * This function is called by AJAX to skip stage 3 of the signup process.
	 * skipProfileStep() and returnToPreviousStep() are similar, they could be
	 * combined to use a parameter to switch between different actions, but to
	 * remove data validation/security concerns out of the equation, we make
	 * them
	 * separate functions that take no input.
	 * Only the user's signup stage will be set, rerouting will be done by the
	 * frontend.
	 *
	 * @author Waylan Wong <waylan.wong@willrainit.com>
	 */
	public function skipProfileStep() {
		$signup_stage_completed = $this->fms_user_profile_model->getSignedStageByUserId( $this->userId );
		if ($this->userId > 0 && $signup_stage_completed >= 2) {
			$this->fms_user_profile_model->updateSignStageByUserId( $this->userId, 3 );
		} else {
			$this->encodeJSON( (array(
					"userid" => $this->userId,
					"signup_stage" => $signup_stage_completed 
			)) );
		}
	}
	
	/**
	 * returnToPreviousStep()
	 *
	 * This function will reset the user's signup stage to stage 2.
	 * skipProfileStep() and returnToPreviousStep() are similar, they could be
	 * combined to use a parameter to switch between different actions, but to
	 * remove data validation/security concerns out of the equation, we make
	 * them
	 * separate functions that take no input.
	 * Only the user's signup stage will be set, rerouting will be done by the
	 * frontend.
	 *
	 * @author Waylan Wong <waylan.wong@willrainit.com>
	 */
	public function returnToPreviousStep() {
		$signup_stage_completed = $this->fms_user_profile_model->getSignedStageByUserId( $this->userId );
		if ($this->userId > 0 && $signup_stage_completed >= 2) {
			$this->fms_user_profile_model->updateSignStageByUserId( $this->userId, 1 );
		} else {
			$this->encodeJSON( (array(
					"userid" => $userid,
					"signup_stage" => $signup_stage_completed 
			)) );
		}
	}
	
	/**
	 * getCountryCodeName()
	 *
	 * This function retrun country name and code for the country dropdown menu
	 * or textext.
	 * It will be AJAXed by front-end on page load. Data returned as json
	 * string. We are not getting
	 * the data in the controller because we are using JS to intialize textext
	 * with the array right
	 * now. It would be very inconvenient to be done by controller.
	 *
	 * @author Wei
	 */
	public function getCountryCodeName() {
		$result = $this->fms_user_profile_model->getCountry();
		$this->encodeJSON( ($result) );
	}
	
	/**
	 * validateUserProfile()
	 *
	 * This function validate submitted user profile form.
	 * Notice: this function should be rewritten!!!
	 *
	 * @access private
	 * @author
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
	 * addContact()
	 *
	 * This function handles AJAX call from front-end when user click to save a
	 * user
	 * as his/ her new contact. It adds the user to his/her contact list.
	 * $_POST should contain target user_id.
	 *
	 * @author Pankaj K.
	 */
	public function addContact() {
		$contactId = $_POST['contactId'];
		$user = $this->fms_user_model->getEntityById( $this->userId );
		
		$user->addContact( $this->fms_user_model->getEntityById( $contactId ) );
		$this->fms_general_model->flush();
		
		$this->encodeJSON( 'true' );
	}
	
	/**
	 *
	 *
	 * This function handles AJAX call from front-end to check whether posted
	 * web address
	 * has been taken. $_POST should contain the web address. Return status
	 * code.
	 *
	 * @author
	 *
	 * @access public
	 */
	public function checkWebAddrAvailability() {
		$user = $this->fms_user_model->getEntityById( $this->userId );
		$webAddr = $this->input->post( 'webAddr' );
		$checkUser = $this->fms_user_model->getEntityByWebAddress( $webAddr );
		if (! $checkUser) {
			$error = 0;
		} else {
			if ($checkUser->getId() === $user->getId()) {
				$error = 0;
			} else {
				$error = 1;
			}
		}
		
		$responseObj = array(
				'errorcode' => $error,
				'message' => '' 
		);
		$this->encodeJSON( $responseObj );
	}
	
	/**
	 *
	 *
	 * This function updates ranking of spotlights that user uploaded. This
	 * function
	 * can be AJAXed by signup stage3 and dashboard user profile edit page. When
	 * the
	 * actual audio file was uplaoded we still dont know ranking for that file
	 * yet.
	 * We only can get this information after all file is uploaded, so at the
	 * end of
	 * setup/edit user profile, front end should make an Ajax call to this
	 * function.
	 * $_POST data is an array of (audioID => ranking). Notice: this function
	 * can be merged with updateUserProfile(), since we've done this function,
	 * keep it.
	 *
	 * @access public
	 * @author Leo
	 */
	public function updateSpotlightRanking() {
		$spotlightRanking = $this->cleanPost['spotlightRanking'];
		// This function already exists in the user profile library, why rewrite
		// it?
		if ($this->fms_user_profile_model->updateSpotLight( $this->userId, $spotlightRanking )) {
			return TRUE;
		} else {
			return false;
		}
	}
	
	/**
	 *
	 *
	 * This function updates ranking and visibility of user projects portfolio.
	 * $_POST is an array of "project_id" => "visibility_flag" pairs. The
	 * front-end
	 * will post the entire list of user's projects in order of ranking, and the
	 * backend
	 * will update the ranking of ranking of each project, trying to think about
	 * better
	 * way to update project ranking.
	 *
	 * @access public
	 * @author Pankaj K.
	 */
	public function updateUserProjectRankingVisibility() {
		
		// Wei: Re-write this AJAX handler due to front-end change and for
		// better efficiency
		$projectVisibility = $this->input->post( 'visibility' );
		
		$user = $this->fms_user_model->getEntityById( $this->userId );
		
		// Check login
		if (! $user) {
			$responseObj = array(
					'errorcode' => 1,
					'message' => 'You should login first.' 
			);
			$this->encodeJSON( $responseObj );
			return;
		}
		
		// Sanity Check
		$userProjects = $user->getProjects();
		if (! $userProjects) {
			$responseObj = array(
					'errorcode' => 2,
					'message' => 'You do not have any projects yet.' 
			);
			$this->encodeJSON( $responseObj );
			return;
		}
		
		// Reset all projects
		// foreach ( $userProjects as $row ) {
			// $row->setVisibility( 0 );
			// $row->setRanking( 0 );
		// }
		
		// Set posted project visibility ranking
		if ($projectVisibility) {
			$iter = 1;
			foreach ( $projectVisibility as $row ) {
				$project = $this->fms_project_model->getEntityById( $row['id'] );
				
				// Sanity Check
				if (! $project) {
					$responseObj = array(
							'errorcode' => 2,
							'message' => 'You do not have any projects yet.' 
					);
					$this->encodeJSON( $responseObj );
					return;
				}
				if($iter > 12) {
					$responseObj = array(
							'errorcode' => 3,
							'message' => 'You can only choose 12 projects.' 
					);
					$this->encodeJSON( $responseObj );
					return;
				}
				if($project->getStatus() == Fms_project_model::UNPUBLISHED || $project->getStatus() == Fms_project_model::INACTIVE || $project->getStatus() == Fms_project_model::UNSAVED){
					$responseObj = array(
							'errorcode' => 4,
							'message' => 'Fatal error. Please refresh the page and try again.' 
					);
					$this->encodeJSON( $responseObj );
					return;
				}
				
				// Action
				$member = $this->fms_project_member_model->getMemberInProject( $user, $project );
				if ($member) {
					//echo $row['visibility'];
					if($row['visibility'] == '1'){
						$member->setVisibility(1);
						$member->setRanking( $iter );
						$iter ++;
					}else{
						$member->setVisibility(0);
						$member->setRanking( 0 );
					}
					
				}
			}
			$this->fms_general_model->flush();
		}
		
		// Response
		$responseObj = array(
				'errorcode' => 0,
				'message' => 'Success' 
		);
		$this->encodeJSON( $responseObj );
	}

	/**
	 * This function updates user biography. $_POST should be HTML string.
	 *
	 * @access public
	 * @author Wei Zong
	 */
	public function updateUserBiography() {
		$biography = $this->input->post( 'biography' );
		
		$biography = str_replace("\\x11", "\"", $biography);
		$biography = str_replace("\\x22", "=", $biography);
		$biography = str_replace("\\x33", ":", $biography);
		// $biography = str_replace("\\x44", ";", $biography);		
		
		$user = $this->fms_user_model->getEntityById( $this->userId );
		$biography = rtrim(ltrim($biography));
		$biography_num = mb_strlen( $biography, 'utf-8' );
		if ($biography_num <= 2000 && $biography_num >= 0) {
			if($biography !== "Write your biography here..."
				&& $biography !== "<br>"){
				$user->setBiography( $biography );
			}
			
		} else {
			// Waylan :: currently if it doesn't meet the min/max length requirements
			// we will just ignore it. Later, we should make it output an error code
			// and have the front end notify the user of the error. 
		}
		$this->fms_general_model->flush();
		$responseObj = array(
				'errorcode' => 0,
				'message' => 'Your changes has been saved' 
		);
		$this->encodeJSON( $responseObj );
		return;
	}
	
	/**
	 * This function updates user connection.
	 * $_POST should be content of the form.
	 *
	 * @access public
	 * @author Leo
	 */
	public function updateUserConnect() {
		print_r( $this->cleanPost );
		
		$user = $this->fms_user_model->getEntityById( $this->userId );
		
		if (isset( $this->cleanPost['agent_name'] )) {
			$user->setAgentName( $this->cleanPost['agent_name'] );
		}
		if (isset( $this->cleanPost['agent_email'] )) {
			$user->setAgentEmail( $this->cleanPost['agent_email'] );
		}
		if (isset( $this->cleanPost['agent_phone'] )) {
			$user->setAgentPhone( $this->cleanPost['agent_phone'] );
		}
		
		if (isset( $this->cleanPost['manager_name'] )) {
			$user->setManagerName( $this->cleanPost['manager_name'] );
		}
		if (isset( $this->cleanPost['manager_email'] )) {
			$user->setManagerEmail( $this->cleanPost['manager_email'] );
		}
		if (isset( $this->cleanPost['manager_phone'] )) {
			$user->setManagerPhone( $this->cleanPost['manager_phone'] );
		}
		
		if (isset( $this->cleanPost['booking_name'] )) {
			$user->setBookingName( $this->cleanPost['booking_name'] );
		}
		if (isset( $this->cleanPost['booking_email'] )) {
			$user->setBookingEmail( $this->cleanPost['booking_email'] );
		}
		if (isset( $this->cleanPost['booking_phone'] )) {
			$user->setBookingPhone( $this->cleanPost['booking_phone'] );
		}
		
		if (isset( $this->cleanPost['publisher_name'] )) {
			$user->setPublisherName( $this->cleanPost['publisher_name'] );
		}
		if (isset( $this->cleanPost['publisher_email'] )) {
			$user->setPublisherEmail( $this->cleanPost['publisher_email'] );
		}
		if (isset( $this->cleanPost['publisher_phone'] )) {
			$user->setPublisherPhone( $this->cleanPost['publisher_phone'] );
		}
		
		if (isset( $this->cleanPost['record_name'] )) {
			$user->setRecordName( $this->cleanPost['record_name'] );
		}
		if (isset( $this->cleanPost['record_website'] )) {
			$user->setRecordWebsite( $this->cleanPost['record_website'] );
		}
		
		if (isset( $this->cleanPost['website'] )) {
			$user->setPWL( $this->cleanPost['website'] );
		}
		if (isset( $this->cleanPost['facebook'] )) {
			$user->setFBL( $this->cleanPost['facebook'] );
		}
		if (isset( $this->cleanPost['twitter'] )) {
			$user->setTWL( $this->cleanPost['twitter'] );
		}
		if (isset( $this->cleanPost['soundcloud'] )) {
			$user->setSCL( $this->cleanPost['soundcloud'] );
		}
		
		$this->fms_general_model->flush();
	}
	
	/**
	 * Link user's facebook account with findmy song account
	 *
	 * @access public
	 * @author Leo
	 */
	public function linkFB() {
		$reponse = array();
		$FB = $this->fms_snw_model->getFbById( $this->cleanPost['facebookUserid'] );
		if ($FB) {
			$response['status'] = 0;
			$this->encodeJSON( $response );
			return;
		}
		$user = $this->fms_user_model->getEntityById( $this->userId );
		$expireDate = new DateTime();
		$intvalStr = 'PT' . $this->cleanPost['expire'] . 'S';
		$expireDate->add( new DateInterval( $intvalStr ) );
		$FB = $this->fms_snw_model->createFB( $user, $this->cleanPost['token'], $this->cleanPost['facebookUserid'], $expireDate );
		$reponse['status'] = 1;
		$this->encodeJSON( $reponse );
	}
	/**
	 * Unlink user's facebook account with findmy song account
	 *
	 *
	 * @access public
	 * @author Leo
	 */
	public function unlinkFB() {
		$user = $this->fms_user_model->getEntityById( $this->userId );
		$FB = $user->getFB();
		$user->setFB();
		$this->fms_general_model->remove( $FB );
		$this->fms_general_model->flush();
	}
	
	/**
	 * Link user's twitter account with findmy song account
	 *
	 * @access public
	 * @author Leo
	 */
	public function linkTW() {
		print_r( $this->cleanPost );
		$user = $this->fms_user_model->getEntityById( $this->userId );
	}
	
	/**
	 * Unlink user's twitter account with findmy song account
	 *
	 * @access public
	 * @author Leo
	 */
	public function unLinkTW() {
		$user = $this->fms_user_model->getEntityById( $this->userId );
		$TW = $user->getTW();
		$user->setTW();
		$this->fms_general_model->remove( $TW );
		$this->fms_general_model->flush();
	}
	
	/**
	 * This function is used to get the biography of a user for a given id.
	 *
	 * @access public
	 */
	public function getuserbiography() {
		$userid = $this->input->get( 'id' );

	
		$bio_data['cur_userId']=$userid;
		$bio_data['loggedinUser']=$this->userId;		

		$response = '';
		if($userid){
			$user = $this->fms_user_model->getEntityById( $userid );
			$bio_data['userbiography'] = $user->getBiography();
			$response = $this->load->view( 'view_user_profile/view_user_biography', $bio_data, true );
			echo $response;
		}
		else{
			$user = $this->fms_user_model->getEntityById( $this->userId );
			$bio_data['user'] = $user;
			$bio_data['biography'] = $user->getBiography();
			$response = array(
				'errorcode' => 0,
				'data' => $this->load->view( 'view_dashboard/profile/view_dashboard_profile_biography', $bio_data, true )
			);
			$this->encodeJSON($response);
		}

	}
	
	/**
	 * This function is used to get the information about the users, with whom
	 * the user for a given id, has worked.
	 *
	 * @access public
	 */
	public function getworked_with() {
		$userid = $this->input->get( 'id' );
		$user = $this->fms_user_model->getEntityById( $userid );
		$projectmenbers = $user->getProjects();
		$worked_with = array();
		foreach ( $projectmenbers as $projectmenber ) {
			$project = $projectmenber->getProject();
			// only show the colloboration for valid projects
			$projectStatus = $project -> getStatus();
			if ($projectStatus != Fms_project_model::UNPUBLISHED 
				&& $projectStatus != Fms_project_model::UNSAVED
				&& $projectStatus != Fms_project_model::INACTIVE) {
					// passed project status validity check
				$members = $project->getMembers();
				foreach ( $members as $member ) {
					$project_user = $member->getUser();
					// if this member is user himself. skip it
					if ($project_user->getId() == $userid) {
						continue;
					}
					$profile_image = base_url('img/default_avatar_photo.jpg');
					if ($project_user->getProfilePicture()) {
						$profile_image = base_url() . $project_user->getProfilePicture()->getPath() . $project_user->getProfilePicture()->getName();
					}
					// if this member is already in the work with list, replace it
					// for the reason of lastest collaborate project
					foreach ( $worked_with as $key => $collaborator ) {
						if ($project_user->getId() == $collaborator['user_id']) {
							$worked_with[$key]['project_id'] = $project->getId();
							continue 2;
						}
					}
					$worked_with[] = array(
							'user_id' => $project_user->getId(),
							'project_id' => $project->getId(),
							'user_fullname' => $project_user->getFirstName() . ' ' . $project_user->getLastName(),
							'profile_image' => $profile_image 
					);
				}
			}
		}
		$data['worked_with'] = $worked_with;
		$data['cur_userId']=$userid;
		$data['loggedinUser']=$this->userId;
		$response = $this->load->view( 'view_user_profile/view_user_workedwith', $data, true );
		echo $response;
	}
	
	/**
	 * This function updates the login credentials (username and/or password)
	 * of the currently logged in user
	 *
	 * @access public
	 * @author Pankaj K.
	 */
	public function updateUserLoginCredentials() {
		
		// $newEmail = $_POST['newEmail'];
		// $newPassword = $_POST['newPassword'];
		// $password = $_POST['password'];
		$user = $this->fms_user_model->getEntityById( $this->userId );
		
		// if (strcmp($user->getPassword(), $password) === 0) {
		// if (strlen($newEmail) > 0) {
		// $user->setEmail($newEmail);
		// $this->fms_general_model->flush();
		// }
		// if (strlen($newPassword) > 0) {
		// $user->setPassword($newPassword);
		// $this->fms_general_model->flush();
		// }
		// echo 'true';
		// } else {
		// echo 'false';
		// }
		
		// Check Login

        if(!$user) {
        	$responseObj = array(
        			'errorcode'	=> 1,
        			'message'	=> 'Please Login First!'
        	);
        	$this->encodeJSON($responseObj);
        	return;
        }
        
        // Check Credential
        if($this->input->post('password') != $user->getPassword()) {
        	$responseObj = array(
        			'errorcode'	=> 2,
        			'message'	=> 'Your password is incorrect'
        	);
        	$this->encodeJSON($responseObj);
        	return;
        }
        
        // Update Email
        $newEmail = $this->input->post('newEmail');
        if($newEmail) {
			$expression = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/";
			if (! preg_match( $expression, $newEmail )) {
		    	$responseObj = array(
		    			'errorcode'	=> 3,
		    			'message'	=> 'Your new email is invalid'
		    	);
		    	$this->encodeJSON($responseObj);
		    	return;
			}
        	$user->setEmail($newEmail);
        }
        
        // Update Password
        $newPassword = $this->input->post('newPassword');
        if($newPassword) {
			if(strlen($newPassword) < 8 ||
			   strlen($newPassword) > 20 ||
			   !preg_match("/[A-Z]/i", $newPassword) ||
			   !preg_match("/[a-z]/i", $newPassword) ||
			   !preg_match("/[0-9]/i", $newPassword) ||
			   preg_match("/[^A-Za-z0-9]/i", $newPassword)
			   ){
		        	$responseObj = array(
		        			'errorcode'	=> 4,
		        			'message'	=> 'Your new password is invalid'
		        	);
		        	$this->encodeJSON($responseObj);
		        	return;
			}
        	$user->setPassword($newPassword);
        }
        
        // flush
        $this->fms_general_model->flush();
        
        // Response
        $responseObj = array(
        		'errorcode'	=> 0,
        		'message'	=> $user->getEmail()
        );
        $this->encodeJSON($responseObj);
    }

    /**
     * 
     * This function deactivates the currently logged in user's
     * account
     * 
     * @access public
     * @author Pankaj K.
     */
    public function deactivateUserAccount() {

        $user = $this->fms_user_model->getEntityById($this->userId);
        $user->setStatus(Fms_user_model::INACTIVE);    // the setter method needs to be changed to setStatus() in future 
        $this->fms_general_model->flush();
        echo 'true';
    }

    /**
     * This function is used to get the skill(s) of the user with an 'id'
     * 
     * @access public
     */
    public function getskill() {
        $userid = $this->input->get('id');
        $user = $this->fms_user_model->getEntityById($userid);
		
		$projects=$user->getProjects();
		$iter=0;
		foreach($projects as $project){
			$SkillForProjects=$project->getSkillForProject();
			foreach($SkillForProjects as $SkillForProject){
				$projectskill[$iter][]=$SkillForProject->getSkill()->getId();
			}
			$iter++;
		}
		
        $userSkills = $user->getSkills();

        // fake data for skills
        $data['skills'] = array();
        foreach ($userSkills as $userSkill) {
			$experience=0;
            $skill = $userSkill->getSkill();
            if ($skill) {
                $name = $skill->getName();
                $video_cover = $skill->getIconPath();
            } else {
                $name = '';
                $video_cover = '';
            }

            $video_src = $userSkill->getVideoPreview();
            if (!$video_src) {
                $video_src = '';
            }
            $genres = $userSkill->getGenres();
            $genresArray = array();
            foreach ($genres as $genre) {
                $genresArray[] = $genre->getName();
            }

            $influences = $userSkill->getInfluences();
            $influencesArray = array();
            foreach ($influences as $row) {
                $influencesArray[] = $row->getName();
            }
			if(isset($projectskill)){
				foreach($projectskill as $row){
					foreach($row as $row2){
						if($skill->getId() == $row2){
							$experience++;
						}
					}
				}
			}
            $data['skills'][] = array(
                'name' => $name,
                'userid' => $userid,
                'video_cover' => $video_cover,
                'video_src' => $video_src,
                'genres' => $genresArray,
                'influences' => $influencesArray,
				'experience' =>$experience
            );
        }
        $skill_data = $data;
        $data['view_skill'] = $this->load->view('view_user_profile/view_user_skills', $skill_data, true);
        echo $data['view_skill'];
    }
}
