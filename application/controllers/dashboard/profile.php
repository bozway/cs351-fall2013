<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Profile extends Authenticated_service {
	const BASIC_SETTINGS = 'basicsettings';
	const PORTFOLIO = 'portfolio';
	const BIOGRAPHY = 'biography';
	const SKILLS = 'skills';
	const CONNECT = 'connect';
	public function __construct() {
		parent::__construct(array('flag_restricted_page' => true));
		$this -> load -> helper('html');
		$this -> load -> helper('url');
		$this -> load -> helper('form');
		$this -> load -> model('docModels/fms_user_model');
		$this -> load -> model('docModels/fms_project_model');
		$this -> load -> model('docModels/fms_skill_model');
		$this -> load -> model('docModels/fms_user_skill_model');
		$this -> load -> model('docModels/fms_project_member_model');
		$this -> load -> model('docModels/fms_language_model');
		$this -> load -> model('docModels/fms_us_state_model');
	}

	/**
	 * This function display the dashboard profile default page. It loads the
	 * extra CSS & JS files, which have not been included in headers and footers
	 * respectively.
	 *
	 * It passes the currently logged in user id as well as other user profile
	 * data to the view.
	 */
	public function index($active_panel = FALSE) {
		$data['title'] = "My Profile";
		$data['css_ref'] = array(
			'css/FMS/dropdown.css',
			'css/textext.css',
			'css/dashboard/profile.css',
			'css/dashboard/dashboard_default.css',
			'css/dashboard/navigation.css',
			"css/jquery-ui.css",
			'css/utility.css',
			'css/test/jquery.Jcrop.css',
            'css/bootstrap-responsive.min.css',
		);
		$data['extrascripts'] = array(
			'js/jquery.slimscroll.min.js',
			'js/textext.min.js',
			"js/vendor/jquery.ui.widget.js",
			"js/jquery.validate.min.js",
			"js/tmpl.min.js",
			"js/load-image.min.js",
			"js/canvas-to-blob.min.js",
			"js/jquery.iframe-transport.js",
			"js/jquery.fileupload.js",
			"js/jquery.fileupload-process.js",
			"js/jquery.fileupload-resize.js",
			"js/jquery.fileupload-validate.js",
			"js/jquery.fileupload-ui.js",
			"js/test/jquery.Jcrop.min.js",
			"js/dashboard/nicEdit-latest.js",
			"js/utility.js",
			"js/jquery.tinysort.min.js",
			'js/dashboard/profile.js',
			'js/dashboard/navigation.js',
			'js/dashboard/profile_skills.js',
		);
		
		if($active_panel !== FALSE){
			if($active_panel != Profile::BASIC_SETTINGS && 
			   $active_panel != Profile::PORTFOLIO &&
			   $active_panel != Profile::BIOGRAPHY &&
			   $active_panel != Profile::SKILLS &&
			   $active_panel != Profile::CONNECT){
			   		redirect(base_url('dashboard/profile'));
			   }
		}
		
		$nav_data = $this->getNavData($active_panel);

		$data['loggedin_user'] = $this->userId;
		$data['active_panel'] = $active_panel;
		$data = $this -> get_profile_data($data);
		
		$data['freeze_header'] = 'dashboard_profile';
		$data['show_navigation'] = 'true';

		// Loads the view
		$this -> load -> view('view_header', $data);
		$this -> load -> view('view_dashboard/navigation/view_navbar', $nav_data);
		$this -> load -> view('view_dashboard/profile/view_dashboard_profile_default', $data);
		$this -> load -> view('view_xtmpl');
		$this -> load -> view('view_footer', $data);
	}

	private function getNavData($active_panel){
		$nav_data['arrow_class_name'] = 'profile-arrow';
		$nav_data['links'] = array(
			Profile::BASIC_SETTINGS =>
				array(
					'value' => 'Basic Settings',
					'id' => 'profile_basic_settings'
				),
			Profile::PORTFOLIO =>
				array(
					'value' => 'Project Portfolio',
					'id' => 'project_portfolio'
				),
			Profile::BIOGRAPHY =>
				array(
					'value' => 'Biography',
					'id' => 'profile_biography'
				),
			Profile::SKILLS =>
				array(
					'value' => 'Skills',
					'id' => 'profile_skills'
				),
			Profile::CONNECT =>
				array(
					'value' => 'Connect',
					'id' => 'profile_connect'
				)
		);
		if($active_panel !== FALSE){
			$nav_data['links'][$active_panel]['is_active'] = TRUE;
		}
		return $nav_data;
	}

	/**
	 * This function handles the AJAX call from front-end to display dashboard
	 * profile basic setting page. The page is returned as JSON or HTML. Use session
	 * variable to identify the user.
	 *
	 */
	public function getUserBasicSetting() {

		// Retrive user data from DB
		$user = $this -> fms_user_model -> getEntityById($this -> userId);
		$data['user'] = $user;

		// Get Profile Picture uploading and cropping module
		$profilePictureUploader = array();
		$profilePicture = $user -> getProfilePicture();
		if ($profilePicture) {
			$profilePictureUploader['picUrl'] = $profilePicture -> getPath() . $profilePicture -> getName();
			$profilePictureUploader['picName'] = $profilePicture -> getName();
		} else {
			$profilePictureUploader['picUrl'] = false;
			$profilePictureUploader['picName'] = false;
		}
		$profilePictureUploader['uploader_id'] = 'profile_picture_module';
		$data['profile_picture_module'] = $this -> load -> view('view_img_uploader', $profilePictureUploader, true);

		// Get Cover Photo uploading and cropping module
		$coverPhotoUploader = array();
		$coverPhoto = $user -> getCoverPhoto();
		if ($coverPhoto) {
			$coverPhotoUploader['picUrl'] = $coverPhoto -> getPath() . $coverPhoto -> getName();
			$coverPhotoUploader['picName'] = $coverPhoto -> getName();
		} else {
			$coverPhotoUploader['picUrl'] = false;
			$coverPhotoUploader['picName'] = false;
		}
		$coverPhotoUploader['uploader_id'] = 'cover_photo_module';
		$data['cover_photo_module'] = $this -> load -> view('view_img_uploader', $coverPhotoUploader, true);

		$data['spotlight'] = array();
		
		// Load the user spotlight module
		$spotlight_data['uploader_id'] = 'user_spotlight_uploader';
		$spotlight_data['files'] = $this->fms_file_model->getAudioFiles($user);
		if(!$spotlight_data['files']) $spotlight_data['files'] = array();
		$data['spotlight_module'] = $this->load->view('view_audio_uploader', $spotlight_data, true);
		
		// Language Dropdown
		$data['languageDropdown'] = $this->fms_language_model->getAllLanguages();
		
		// State Dropdown
		$data['stateList'] = $this->fms_us_state_model->getAllStates();
		
		// Loads the view
		$data['profile_basic_settings'] = $this -> load -> view('view_dashboard/profile/view_dashboard_profile_basic_settings', $data, true);

		// Return the html
		// $responseObj = array(
			// 'errorcode' => 0,
			// 'page' => $data['profile_basic_settings']
		// );
		// encodes the response object into JSON
		//$this -> encodeJSON($responseObj);
		
		return $data['profile_basic_settings'];
	}

	/**
	 * YET TO BE IMPLEMENTED
	 *
	 * This function handles the AJAX call from front-end to display dashboard
	 * profile project portfolio page. The page is returned as JSON or HTML. Use
	 * session
	 * variable to identify the user.
	 *
	 */
	public function getUserProjects() {

	}

	/**
	 * YET TO BE IMPLEMENTED
	 *
	 * This function handles the AJAX call from front-end to display dashboard
	 * profile connection editing page. The page is returned as JSON or HTML. Use
	 * session variable to identify the user.
	 *
	 */
	public function getUserConnect() {

	}

	private function get_profile_data($data) {
		$user = $this -> fms_user_model -> getEntityById($this -> userId);

		$userProjects = $user -> getProjects();
		//$data['projectMembers'] = $userProjects; // This seems to be useless :: Waylan
		$data['user'] = $user;
		$user_project_ranking = array();
		foreach ($userProjects as $userProject) {
			// Waylan :: We don't want any unpublished, unsaved, or inactive projects shown
			if ($userProject->getProject()->getStatus() != Fms_project_model::UNPUBLISHED 
				&& $userProject->getProject()->getStatus() != Fms_project_model::UNSAVED
				&& $userProject->getProject()->getStatus() != Fms_project_model::INACTIVE) {
				$ranking = $userProject -> getRanking();
				array_push($user_project_ranking, array(
					$ranking,
					$userProject
				));	
			}		
		}

		usort($user_project_ranking, function($a, $b) {
			return strcmp($a[0], $b[0]);
		});

		$connectData = array();
		$defaultValue = "";

		// Use an = agent name, ae = agent email, ap = agent phone , mn =
		// manager name , if you are not sure what does it mean cheack get
		// method follows it.
		$connectData['an'] = $user -> getAgentName() ? $user -> getAgentName() : $defaultValue;
		$connectData['ae'] = $user -> getAgentEmail() ? $user -> getAgentEmail() : $defaultValue;
		$connectData['ap'] = $user -> getAgentPhone() ? $user -> getAgentPhone() : $defaultValue;

		$connectData['mn'] = $user -> getManagerName() ? $user -> getManagerName() : $defaultValue;
		$connectData['me'] = $user -> getManagerEmail() ? $user -> getManagerEmail() : $defaultValue;
		$connectData['mp'] = $user -> getManagerPhone() ? $user -> getManagerPhone() : $defaultValue;

		$connectData['bn'] = $user -> getBookingName() ? $user -> getBookingName() : $defaultValue;
		$connectData['be'] = $user -> getBookingEmail() ? $user -> getBookingEmail() : $defaultValue;
		$connectData['bp'] = $user -> getBookingPhone() ? $user -> getBookingPhone() : $defaultValue;

		$connectData['pn'] = $user -> getPublisherName() ? $user -> getPublisherName() : $defaultValue;
		$connectData['pe'] = $user -> getPublisherEmail() ? $user -> getPublisherEmail() : $defaultValue;
		$connectData['pp'] = $user -> getPublisherPhone() ? $user -> getPublisherPhone() : $defaultValue;

		$connectData['rn'] = $user -> getRecordName() ? $user -> getRecordName() : $defaultValue;
		$connectData['rw'] = $user -> getRecordWebsite() ? $user -> getRecordWebsite() : $defaultValue;

		$connectData['pwl'] = $user -> getPWL() ? $user -> getPWL() : $defaultValue;
		$connectData['fbl'] = $user -> getFBL() ? $user -> getFBL() : $defaultValue;
		$connectData['twl'] = $user -> getTWL() ? $user -> getTWL() : $defaultValue;
		$connectData['scl'] = $user -> getSCL() ? $user -> getSCL() : $defaultValue;

		$connectData['linkFB'] = $user -> getFB() ? 1 : 0;
		$connectData['linkTW'] = $user -> getTW() ? 1 : 0;
		$connectData['active_panel'] = $data['active_panel'];

		$data['user_project_ranking'] = $user_project_ranking;
		$data['profile_connect'] = $this -> load -> view('view_dashboard/profile/view_dashboard_profile_connect', $connectData, true);
		$data['profile_basic_settings'] = $this -> getUserBasicSetting();
		$data['profile_portfolio'] = $this -> load -> view("view_dashboard/profile/view_dashboard_profile_project_portfolio", $data, true);
		$data['profile_skill'] = $this -> load -> view('view_dashboard/profile/view_dashboard_profile_skills', $data, true);

		return $data;
	}
}
?>