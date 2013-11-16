<?php
if (! defined( 'BASEPATH' ))
	exit( 'No direct script access allowed' );
class Account extends Authenticated_service {
	const ERROR_SUCCESS = 0;
	const ERROR_MISSING_PARAM = 200;
	const ERROR_USER_NO_FOUND = 300;
	const ERROR_TOKEN_INVALID = 301;
	const ERROR_TOKEN_EXPIRED = 302;
	const ERROR_PASSWORD_INVALID = 303; 
	public function __construct() {
		parent::__construct();
		$this->load->helper( 'url' );
		$this->load->library('email');
		$this->load->model( 'docModels/fms_user_model' );
		$this->load->model( 'docModels/fms_general_model' );
	}
	
	public function retrievePassword(){
		$email = $this->input->post('email');
		
		//validation
		$errorcode = $this->validate_retrievePassword($email);
		if($errorcode != Account::ERROR_SUCCESS){
			$response = array(
				'errorcode' => $errorcode,
				'message' => "Your registered email is not found!"
			);
			$this->encodeJSON( $response );
			return;
		}
		
		//action
		$user = $this->fms_user_model->getEntityByEmail($email);
		//generate token
		$token = $this->generateToken();
		//store token and creation time
		$user->setPwToken($token);
		$user->setPwTokenCreationTime(new DateTime());
		$this->fms_general_model->flush();
		//generate password reset link
		$link = base_url('forgot_password/'.$user->getId().'/'.$token);
		//send email
		$config = array(
			'mailtype' => 'html'
		);
		$service_email = 'noreply@findmysong.com';
		$subject = 'Password Reset Link from FindMySong';
		
		$data = array();
		$data['userName'] = $user->getFirstName() . " " . $user->getLastName();
		$data['link'] = $link;
		$message = $this -> load -> view('email_templates/plain_text_forgot_password', $data, TRUE);
		
		$this->send_email($config, $service_email, $email, $subject, $message);
		
		//response
		$response = array(
			'errorcode' => $errorcode,
			'message' => "Password reset email is sent to your registered email"
		);
		$this->encodeJSON( $response );	
	}

	private function validate_retrievePassword($email){
		$errorcode = Account::ERROR_SUCCESS;
		
		if($email === FALSE){
			$errorcode = Account::ERROR_MISSING_PARAM;
			return $errorcode;
		}
		
		$user = $this->fms_user_model->getEntityByEmail($email);
		if(!isset($user)){
			$errorcode = Account::ERROR_USER_NO_FOUND;
			return $errorcode;
		}
		return $errorcode;
	}
	
	public function resetPassword(){
		$user_id = $this->input->post('id');
		$token = $this->input->post('token');
		$password = $this->input->post('password');
		//validation
		$errorcode = $this->validate_resetPassword($user_id, $token, $password);
		if($errorcode != Account::ERROR_SUCCESS){
			$response = array(
				'errorcode' => $errorcode,
				'message' => 'Your token is expired, please try to retrieve your password again'
			);
			if($errorcode == Account::ERROR_PASSWORD_INVALID){
				$response['message'] = 'Your password is invalid';
			}
			$this->encodeJSON( $response );
			return;
		}
		
		//action
		$user = $this->fms_user_model->getEntityById($user_id);
		$user->setPassword($password);
		$user->setPwToken("");
		$this->fms_general_model->flush();
		
		//response
		$response = array(
			'errorcode' => Account::ERROR_SUCCESS,
			'message' => 'password changed'
		);
		$this->encodeJSON( $response );	
	}
	
	private function validate_resetPassword($user_id, $token, $password){
		$errorcode = Account::ERROR_SUCCESS;
		if($user_id === FALSE || $token === FALSE || $password === FALSE){
			$errorcode = Account::ERROR_MISSING_PARAM;
			return $errorcode;
		}
		
		$user = $this->fms_user_model->getEntityById($user_id);
		if(!isset($user)){
			$errorcode = Account::ERROR_USER_NO_FOUND;
			return $errorcode;
		}
		
		if($user->getPwToken() !== $token){
			$errorcode = Account::ERROR_TOKEN_INVALID;
			return $errorcode;
		}
		
		$token_creationTime = $user->getPwTokenCreationTime();
		$date = new DateTime();
		$date->sub(new DateInterval('P1D'));
		if($token_creationTime < $date){
			$errorcode = Account::ERROR_TOKEN_EXPIRED;
			return $errorcode;
		}
		
		if(strlen($password) < 8 ||
		   strlen($password) > 20 ||
		   !preg_match("/[A-Z]/i", $password) ||
		   !preg_match("/[a-z]/i", $password) ||
		   !preg_match("/[0-9]/i", $password) ||
		   preg_match("/[^A-Za-z0-9]/i", $password)
		   ){
		   	$errorcode = Account::ERROR_PASSWORD_INVALID;
			return $errorcode;
		}
		
		return $errorcode;
	}
	
	private function generateToken(){
		list($usec, $sec) = explode(' ', microtime());
	  	$seed = (float) $sec + ((float) $usec * 100000);
		mt_srand($seed);
		$first_part_token = strval(mt_rand());
		$second_part_token = strval(mt_rand());
		return $first_part_token.$second_part_token;
	}
	
	private function send_email($config, $from, $to, $subject, $message){
		$this->email->initialize($config);
		$this->email->from($from);
		$this->email->to($to); 
		$this->email->subject($subject);
		$this->email->message($message);	

		$this->email->send();
	}
}
