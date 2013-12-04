<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms extends Authenticated_service {
	public function __construct() {
		parent::__construct ();
		
		$this->load->helper ( 'html' );
		$this->load->helper ( 'url' );
		$this->load->helper ( 'form' );		
		$this->load->library( 'form_validation' );		
		$this->load->model	( 'fms_email_list_model' );
		$this->load->model	('docModels/fms_user_model');
		$this->load->model	('docModels/fms_project_model');
	}
	public function index() {
		//echo "DEBUG: reached the fms_index controller<br />";
		$this->home();
	}
	
	/**
	 * This will display the homepage.
	 */
	public function home() {
		$data ['title'] = "Home";
		/* $data['font_ref'] = array(
				"Open+Sans:700",	
		); */
		$data ['css_ref'] = array (
				"css/home.css",
				"css/bootstrap-responsive.min.css",
				"css/mobile_home.css",
		);
		$data ['extrascripts'] = array (
				"js/jquery.validate.min.js",
				"js/fms_home.js",
				//"js/fms_home.min.js",
				"js/fms_analytics_home.js",
				//"js/fms_analytics_home.min.js",
		);
		
		// $data['loggedinUser'] = $this->userId;
		
		// SEO
		$meta_data = array (
				array (
						'name' => 'description',
						'content' => "At Find My Song you can create superfantasticwonderful music together!" 
				),
				array (
						'name' => 'keywords',
						'content' => 'Music,Songs,Editing,Collaboration,Learn, Discovery, Song Writer, Guitar, Singer, Producer, Music Producer, Piano, Classical Music', 
				) 
		);
		/** Development data **
		$data ['metadata'] = $meta_data;
		$data['featured_musicians'] =$this->getFeaturedMusician(
        	array(5,6,7,8,9,10)
		);
		$data['featured_projects'] =$this->getFeaturedProjects(
        	array(1,2,3,9)
		);**/
		/** Production data**/
		$data['featured_musicians'] =$this->getFeaturedMusician(
        	//array(50, 18, 17, 16, 24, 28)// array(36, 39, 30, 32, 34, 33)
            array(78,79,80,81,5,18)
		);
		
		$data['featured_projects'] =$this->getFeaturedProjects(
        	array(30, 140, 9, 131)//array(109, 110, 118, 121)
		);
		
		
		$this->load->view ( 'view_header', $data );
		$this->load->view ( 'view_fms/view_home', $data );		
		$this->load->view ( 'view_footer', $data );
	}
	
	
	/**
	 * Have people enter their emails before they Get Started on projects.
	 */
	public function add_email() {		
		
		$this->form_validation->set_message('valid_email', 'You entered an invalid email!');
		$this->form_validation->set_rules('fms_email', 'Email', 
				'required|valid_email|max_length[100]|mysql_real_escape_string|xss_clean|htmlspecialchars|htmlspecialchars');
		if ($this->form_validation->run() === TRUE) {
			$validatedEmail = $this->input->post('fms_email');
			$this->fms_email_list_model->add_email_to_list($validatedEmail, $this->input->ip_address());
			redirect(404);
		} else {
			// reload home and show error messages
			$this->home();
		}
		
		
		
	}
	
	/**
	 * Debug method to check what is available in the email database
	 */
	public function show_email_DB($password) {
		if ($password === 'vince2012') { 
			$this->load->model('fms_email_list_model');
			$everything = $this->fms_email_list_model->getAllRecords();
			print_r($everything);
		} else {
			redirect(); // goes straight to home page
		}
		
	}
	
	
	/****************************
	 * 		Static Pages		*
	 ***************************/
	
	public function about() {
		$data['title'] = "About Us";
		$data['css_ref'] = array(
				"css/about.css",
                "css/bootstrap-responsive.min.css",
                "css/mobile_about.css",
		);
		/*$data ['extrascripts'] = array (
		 "js/user_profile/fms_user_profile.js"
		);*/
	
		$data['current_nav'] = 'about_fms';
	
		$this->load->view( 'view_header', $data );
		$this->load->view( 'view_about/view_about.php');
		$this->load->view( 'view_footer', $data );
	}
	
	public function contact() {
		$data['title'] = "Contact Us";
		$data['css_ref'] = array(
				"css/contact.css",
		);
		$data['userid']=$this->userId;
		
		$data['extrascripts'] = array(	
				'js/jquery.validate.min.js',			
				'js/fms_contact_validator.js'
		);
	
	
		$this->load->view( 'view_header', $data );
		$this->load->view( 'view_contact/view_contact.php',$data);
		$this->load->view( 'view_footer', $data );
	}
	

	public function help() {
		$data['title'] = "Help Center";
		$data['css_ref'] = array(
				"css/help_center/help_center.css",
		);
		/*$data ['extrascripts'] = array (
		 "js/user_profile/fms_user_profile.js"
		);*/
	
		$this->load->view( 'view_header', $data );
		$this->load->view( 'view_help_center/view_help_center.php');
		$this->load->view( 'view_footer', $data );
	}
	public function faq() {
		$data['title'] = "Frequently Asked Questions";
		$data['css_ref'] = array(
				"css/help_center/help_center.css",
		);
		$data ['extrascripts'] = array (
				"js/jquery.easing.1.3.js",
				"js/jquery.scrollTo-1.4.3.1-min.js",				
				"js/help_center/help_center.js"
		);
	
		$this->load->view( 'view_header', $data );
		$this->load->view( 'view_help_center/view_FAQ.php');
		$this->load->view( 'view_footer', $data );
	}
	public function terms() {
		$data['title'] = "Terms and Conditions";
		$data['css_ref'] = array(
				"css/legal.css",
		);
	
		$this->load->view( 'view_header', $data );
		$this->load->view( 'view_legal/view_legal.php',$data);
		$this->load->view( 'view_footer', $data );
	}
	public function privacy() {
		$data['title'] = "Privacy Policy";
		$data['css_ref'] = array(
				"css/legal.css",
		);
	
		$this->load->view( 'view_header', $data );
		$this->load->view( 'view_legal/view_privacy.php',$data);
		$this->load->view( 'view_footer', $data );
	}
	/**
	 *  forgot_password($user_id = 0, $token = NULL)
	 *  1. When there is not parameter passed, this function simply direct to forgot password page
	 *  2. When $user_id and $token are passed as parameter, this function may go to reset password page
	 *     if everything is valid, otherwise it goes to forgot password page with an error message
	 */
	public function forgot_password($user_id = 0, $token = NULL) {
		$data['title'] = "Forgot Password";
		$data['css_ref'] = array(
				"css/password/password.css",
		);
		$data['extrascripts'] = array(
			'js/jquery.validate.min.js',
			'js/password/forgot_password.js'
		);
		
		$is_reset = FALSE;
		if($user_id !== 0 && $token !== NULL){
			$is_reset = TRUE;
			$user = $this->fms_user_model->getEntityById($user_id);
			if(isset($user)){
				if($user->getPwToken() === $token){
					$token_creationTime = $user->getPwTokenCreationTime();
					$date = new DateTime();
					$date->sub(new DateInterval('P1D'));
					if($token_creationTime >= $date){
						$data['user_id'] = $user_id;
						$data['token'] = $token;
						$data['user_name'] = $user->getFirstName().' '.$user->getLastName();
						$data['email'] = $user->getEmail();
					}
					else{
						$is_reset = FALSE;
						$data['error_message'] = "Sorry, your token is expired, please retrieve your password again";
					}
				}
				else{
					$is_reset = FALSE;
					$data['error_message'] = "Sorry, your token is invalid, please retrieve your password again";	
				}
			}
			else{
				$is_reset = FALSE;
				$data['error_message'] = "Sorry, your id is invalid, please retrieve your password again";
			}
		}
		
		$this->load->view( 'view_header', $data );
		if($is_reset){
			$this->load->view( 'view_password/view_reset_password.php', $data);
		}
		else{
			$this->load->view( 'view_password/view_forgot_password.php', $data);
		}
		$this->load->view( 'view_footer', $data );
	}

	/** ww
	 * Build the email headers using CodeIgniter's email library,
	 * then build message body using a html template, and then send it
	 * through PHP's mail function.
	 **/
	public function send_email() {
		$this -> load -> library('email');
		$this->load->model('docModels/fms_user_model');
		
		$service_email = 'info@findmysong.com';
			
		// build email header info
		if ($this->authenticated()) {
			$userEn = $this->fms_user_model->getEntityById($this->userId);
			$data['customer_name'] = $userEn->getFirstName() . " " . $userEn->getLastName();
			$data['customer_email'] = $userEn->getEmail();	
		} else { 
			$data['customer_name'] = $this -> input -> post('customer_name');
			$data['customer_email'] = $this -> input -> post('customer_email');
		}
		
		$data['customer_ip'] = $this -> input -> ip_address();						
		$data['customer_message'] = $this -> input -> post('customer_message');
		$data['customer_subject'] = $this -> input -> post('customer_subject');		
	
		// send data to view and build an HTML formatted email.
		$email_body = $this -> load -> view('email_templates/plain_text_contact_us', $data, TRUE);
	
		//echo $email_body; // debug - render the email and check how it looks like.
	
		$config['mailtype'] = 'html';
		$this -> email -> initialize($config);
	
		$this -> email -> from($data['customer_email'], $data['customer_name']);
		$this -> email -> to($service_email); // uncomment this when site goes live
		// $this -> email -> to($debugemail); // comment this out when site goes live
		$this -> email -> subject('Contact details regarding: #'.$data['customer_subject'].'#');
		$this -> email -> message($email_body);
	
		//echo $this -> email -> print_debugger();
		$this -> email -> send();
	
	}
	/**
	 * @author Original author unknown
	 * @author Waylan Wong <waylan.wong@willrainit.com>
	 * 
	 * This function will get all the data necessary to display the featured musicians
	 * 
	 * @param array $musicianIdArray An array of featured musicians ID's
	 * @return array Associative array of musician data, including profile picture.
	 */
	private function getFeaturedMusician($musicianIdArray){
		$musicianinformation = array();
		$iter=0;
		if (count($musicianIdArray) > 0 ) {
	        foreach($musicianIdArray as $index => $id) {
	        	$musicianEn = $this->fms_user_model->getEntityById($id);
				$ProfilePicture = $musicianEn -> getProfilePicture();
				if($ProfilePicture) {
					$musicianinformation[$iter]['profile_img_path'] = base_url($ProfilePicture -> getPath() . $ProfilePicture -> getName());
				} else {
					$musicianinformation[$iter]['profile_img_path'] = base_url('img/default_avatar_photo.jpg');
				}
				$musicianinformation[$iter]['id']=$id;
				$iter++;
			}
		}
        return $musicianinformation;
	}	
	
	/**
	 * @author Original author unknown
	 * @author Waylan Wong <waylan.wong@willrainit.com>
	 * 
	 * This function will get all the data necessary to display the featured projects
	 * 
	 * @param array $ProjectIdArray An array of featured project ID's
	 * @return array Associative array of project data, including cover picture, project title, etc
	 */
	private function getFeaturedProjects($ProjectIdArray){
		$projectinformation = array();
		if (count($ProjectIdArray) > 0) {
			foreach ($ProjectIdArray as $index => $id) {
				$projectinformation[] = $this -> fms_project_model -> getProjectInfoSummary($id);
			}
		}
        return $projectinformation;
	}
	
	// this function is used to provide a user confirmation willing to unsubscribe
	// fms email notifications
	// Pankaj K., Sept 25 2013
	public function unsubscribe(){
		$data['title'] = "Unsubsribe Confirmation";
		
		$this->load->view( 'view_header', $data );
		$this->load->view( 'unsubscribe_fms_confirm.php',$data);
		$this->load->view( 'view_footer', $data );
	}
}
?>