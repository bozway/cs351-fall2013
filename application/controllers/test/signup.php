<?php

session_start();
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Signup extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this -> load -> helper('html');
		$this -> load -> helper('url');
		$this -> load -> helper('form');
		$this -> load -> library('Fms_user_profile');
		$this -> load -> model('fms_user_profile_model');
		$this -> load -> library('encrypt');
	}

	/*
	 * Check the size of $_POST if it equal to 0 load signup page
	 * otherwise check $_POST input. If $_POST passed safety check.
	 * Call isEmailTaken() to verify that email has not been taken then call addUser()
	 *
	 */

	public function index() {

		if (is_array($_POST) && count($_POST) != 4) {
			//echo "load signup!";
			print_r($_POST);

		} else {
			echo "else";
			if (isset($_POST["firstNameKey"]) && isset($_POST["lastNameKey"]) && isset($_POST["passwordKey"]) && isset($_POST["emailKey"])) {
				//echo "error checking";
				$cleanArray = $this -> fms_user_profile -> cleanInput($_POST);
				//print_r($cleanArray);
				if ($this -> singupErrorCheck($cleanArray)) {
					//echo "error checking successed";
					if ($this -> isEmailTaken($cleanArray["emailKey"])) {
						//echo "email is already taken please use another one";
					} else {
						if ($this -> addUser($cleanArray)) {
							//echo "add user succed";
							// add session 
							// redirect
						}
					}
				} else {
					echo "error checking failed";
				}
			} else {
				echo "required filed not set";
			}
		}

	}

	/*
	 * Handle Facebook signup. Frontend should send in the user information
	 * plus facebook userID returned from facebook.
	 */

	public function test() {

		$data['title'] = "Create";

		$data['css_ref'] = array("css/create.css", "css/login_modal.css");

		$data['extrascripts'] = array("js/jquery.validate.min.js", "js/signup/fms_signup.js");

		// SEO
		$meta_data = array( array('name' => 'description', 'content' => "At Find My Song you can create superfantasticwonderful music together!"), array('name' => 'keywords', 'content' => 'Music,Songs,Editing,Collaboration,Learn, Discovery, Song Writer, Guitar, Singer, Producer, Music Producer, Piano, Classical Music', ));
		$data['metadata'] = $meta_data;

		$this -> load -> view('view_header', $data);
		$this -> load -> view('leo_testing');
	}

	/*
	 * Handle Twitter signup. (Need more research)
	 */

	public function signupTwitter() {

	}

	/*
	 * adduser() is taking in array of user info then checks if this user has existed.
	 * If it is, then I return false.
	 * Else I call the model function and insert it to the database.
	 *
	 * used to insert the user table, when a user signs up. An array is passed
	 * as a parameter, where following are the keys in the array:
	 *
	 * @array-key first_name        first name of the new user
	 * @array-key last_name         last name of the new user
	 * @array-key email             email of the new user
	 * @array-key password          hashed password of the new user
	 * @array-key ip_registration   IP address of the client machine used to register
	 * @array-key timestamp         timestamp at the time of registration
	 * @array-key sign_stage        current signup stage
	 *
	 *
	 * @return  If success return True, If error happened return error message
	 *
	 *
	 */

	public function addUser($array) {
			   $array["passwordKey"] = $this -> encrypt -> encode($array["passwordKey"]);
			   $array["ipRegistrationKey"] = $_SERVER['REMOTE_ADDR'];
			   $array["timestampKey"] = date("Y-m-d H:i:s");
			   $array["signStageKey"] = 1;
			   print_r($cleanArray);
		       if($this->fms_user_profile_model->addUser($array)){
		           return true;
		       }
		       else{
		           return false;
		       }
		//return true;

	}

	/*
	 * Check if email is already been used before
	 *
	 * @param $email                 Email address user inputed in signup page
	 *
	 * @return true/false            If email address is taken return true otherwise return false
	 */

	public function isEmailTaken($email) {
		       if($this->fms_user_profile_model->getUserIdByEmail($email)){
		           return true;
		       }
		       else{
		           return false;
		       }
		//return true;

	}

	/*
	 * Check all the input from signup page
	 *
	 * @param array a array of input
	 * @array-key firstName
	 * @array-key lastName
	 * @array-key email
	 * @array-key password
	 *
	 */

	public function singupErrorCheck($array) {

		foreach ($array as $key => $value) {
			if (strlen($value) < 4) {
				return false;
			}
		}

		return true;

	}

}
?>
