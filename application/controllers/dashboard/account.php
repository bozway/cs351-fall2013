<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Account extends Authenticated_service {
	public function __construct() {
		parent::__construct(array("flag_restricted_page" => true));

		$this -> load -> helper('url');
		$this -> load -> helper('html');
		$this -> load -> helper('form');
		$this -> load -> model('docModels/fms_user_model');
		$this -> load -> helper('html');
		$this -> load -> helper('url');
		$this -> load -> helper('form');
	}

	/**
	 * index()
	 *
	 * This function is used to display the dashboard account setting page
	 *
	 * @author XXX & Pankaj K.
	 */
	public function index() {
		$data['title'] = "My Settings";
		$data['css_ref'] = array(
			'css/dashboard/account.css',
			'css/dashboard/navigation.css'
		);
		$data['extrascripts'] = array(
			"js/jquery.validate.min.js",
			'js/dashboard/account.js'
		);

		//var_dump($this->userId);
		$user = $this -> fms_user_model -> getEntityById($this->userId);
		$data['user_email'] = $user -> getEmail();
		
		$data['freeze_header'] = 'account';
		$data['show_navigation'] = 'true';
		$data['current_page'] = 'manage_account';

		$mainNavData = $this->getMainNavData();
		$this -> load -> view('view_header', $data);
		$this->load->view('view_dashboard/navigation/view_navbar', $mainNavData);
		$this -> load -> view('view_dashboard/account/view_dashboard_account_general_settings', $data);
		$this -> load -> view('view_footer', $data);
	}
	
	
	/**
     * This function get all the data of main navigation needs, the horizontal
     * nagivation
     */
    private function getMainNavData() {
        $mainNavData = array();

        $mainNavData['links'] = array(
            array(
            	'id' => 'manage_account',
                'value' => 'Manage Your Account',
                'url' => site_url('/dashboard/account')
            )
        );
        return $mainNavData;
    }
	
	/**
	 * 
	 * The following controller function is used for the test purpose 
	 * for the welcome email template.
	 * 
	 * Pankaj K., Sept 23, 2013
	 */
	 public function welcomeEmail(){
	 	$data['title'] = "Welcome User";
		
	 	$user_id = $this->userId;
		$user = $this->fms_user_model->getEntityById($user_id);
		$data['userName'] = $user->getFirstName() . " " . $user->getLastName();
		$data['userEmail'] = $user->getEmail(); 
		$data['userId'] = $user_id;
		$this -> load -> view('email_templates/plain_text_welcome_to_fms', $data);
	 }
}
?>