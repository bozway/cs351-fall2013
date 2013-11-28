<?php
/**
 * This class is responsible for doing all the authentication work.
 * 
 * All the authentication will go thru this class. Every controller that needs to 
 * perform authentication should extend this class. Any class that needs access to 
 * the authenticated user's ID should assume that $this->userId; is valid and !=NULL. 
 * 
 * @author Waylan, Leo
 * 
 * 
 */
require_once ('twitteroauth.php');
class Authenticated_service extends MY_Controller {
	public $cleanPost = array();
	private $flag_ip_gatedonly = FALSE;
	private $flag_timezone = FALSE;
	private $bool_ip_whitelisted = FALSE;
	private $whitelistedIP = array(
			'dummy-value-avoid-index-0',
			'127.0.0.1',
			// development
			'76.94.87.156',
			// LA office
			'67.197.234.53',
			// Thomas Honeyman
			'68.123.236.140',
			// Waylan Wong
			'218.250.142.40',
			// Vince Fong, Hong Kong
			'207.151.239.148' 
			// Thomas Honeyman
		);
	protected $userId = NULL;
	
	/**
	 * Constructor for Authenticated_service.
	 * Accepts flags for advanced
	 * authentication options. All flags are optional, flags are all boolean
	 * values, please see below for valid flags and their uses.
	 *
	 * <ul>
	 * <li>flag_ip_whitelist		: If true, will filter visitors based on
	 * REMOTE_ADDR</li>
	 * <li>flag_ip_gatedonly		: If flag_ip_whitelist == true, and this is true,
	 * test only at gated points of the site</li>
	 * <li>flag_timezone			: If true, will apply timezone data from the db for
	 * each user.</li>
	 * <li>flag_restricted_page		: If true, we will check if the user is logged
	 * in before displaying the page</li>
	 * <li>default					: By default, we will force users to log in.</li>
	 * </ul>
	 *
	 * @param array $flags
	 *        	array of flags and their values
	 */
	function __construct($flags = array()) {
		parent::__construct();
		
		$this->load->helper( 'url' );
		$this->load->model( "docModels/fms_user_model" );
		
		/*
		// DEBUG
		mysql_connect("localhost", 'fms', 'vince2012') or die(mysql_error());
		echo "Connected to MySQL<br />";
		mysql_select_db('fms_main') or die(mysql_error());
		echo "Connected to Database<br />";
		
		$this->debug( "auth_service constructor", "Dump all users from fms_user_model" );
		var_dump($this->fms_user_model->getAllEntities());
		*/
		//var_dump($_SERVER);
		if (!ini_get('display_errors')) {
			ini_set('display_errors', '1');
		}		
		//phpinfo();
		//echo ini_get('display_errors');
		//=aflkjdf;
		//throw Exception("TEST ERROR");
		
		/**
		 * Load the current authentication state into a global variable
		 * for access in the view, and essentially everywhere in the app
		 * as $authenticated, but mainly used in the view.
		 */
		$authdata = new stdClass;
		if ( $this->authenticated() ) {
			$authdata->userId = $this->session->userdata('userid');
			$this->userId = $this->session->userdata('userid');
			//$this->logout();
			//var_dump($this->userId);			
			$user = $this->fms_user_model->getEntityById($this->userId);
			$userprofilepictureEntity = $user->getProfilePicture();
			$profile_image_path = '';
			//var_dump($userprofilepictureEntity);
			if ($userprofilepictureEntity) {
				//var_dump($userprofilepictureEntity);
				$profile_image_path = $userprofilepictureEntity -> getPath() . $userprofilepictureEntity -> getName();
				$authdata->profile_img_path = $profile_image_path;
			}
			$authdata->authenticated = 1;
		} else {
			$authdata->authenticated = 0;
		}		
		$this->load->vars( $authdata );		
		
		if (count( $flags ) > 0) {
			// var_dump($flags);
			foreach ( $flags as $flag_key => $flag_value ) {
				switch ($flag_key) {
					case "flag_ip_gatedonly" :
						{
							$this->flag_ip_gatedonly = $flag_value;
							$this->bool_ip_whitelisted = (array_search( $_SERVER['REMOTE_ADDR'], $this->whitelistedIP ) > 0) ? TRUE : FALSE;
							break;
						}
					case "flag_timezone" :
						{
							$this->flag_timezone = $flag_value;
							break;
						}
					case "flag_restricted_page" :
						{
							// $this->debug("Authenticated::construct", "Only
							// authenticated users allowed!");
							if (! $this->authenticated()) {
								// store where they wanted to go, so that we may
								// redirect later.
								$this->session->set_userdata( 'redirecturl', $_SERVER['REQUEST_URI'] );
								redirect( 'login' );
							}
							$authdata->protectedPage = 1;
							$this->load->vars( $authdata );
						}
				}
			}
			/*
			 * Whitelist of IP address was enabled, checking for the entire
			 * site, not just for the gated portions of the site. "Gated" means
			 * any link that invokes the js::auth_processor() =>
			 * php::auth_gateway() pathway
			 */
			if (isset( $flags['flag_ip_whitelist'] ) && isset( $flags['flag_ip_gatedonly'] )) {
				if ($flags['flag_ip_whitelist'] === TRUE && ! $flags['flag_ip_gatedonly']) {
					if (array_search( $_SERVER['REMOTE_ADDR'], $this->whitelistedIP ) > 0) {
						// not on whitelist, kicking to homepage.
						redirect( '/' );
					}
				}
			}
		}
		
		$this->load->model("fms_user_profile_model");
		$this->load->model('docModels/fms_snw_model');
		$this->load->model('docModels/fms_file_model');	
		$this->load->model('docModels/fms_general_model');	
		$this->load->helper( 'security' );
		$this->load->library( 'encrypt' );
		
		
		$this->cleanPost = array();
		// DEBUG :: WAYLAN		
		//$this->debug( "auth_service constructor", "Dump of POST" );
		//var_dump( $_POST );
		// $this->debug("auth_service constructor", "Dump of SERVER");
		// print_r($_SERVER);		
		
		foreach ( $_POST as $key => $value ) {
			if (is_array( $value )) {
				// ww :: Leo, you need to process arrays of data differently.
				$this->cleanPost[$key] = $value;
			} else {
				$this->cleanPost[$key] = mysql_real_escape_string( htmlspecialchars( xss_clean( ($value) ) ) );
				// need to add urlencode here ww :: htmlspecialchars() does the
			// minimum amount of encoding to ensure that your string is not
			// parsed as HTML.
			}
		}
		//$this->debug( "auth_service constructor", "Dump of cleanPost" );
		//var_dump( $this->cleanPost );
	}
	
	// /////////////////////////////////////////////////////////////////////////
	// AJAX functions //
	// /////////////////////////////////////////////////////////////////////////
	
	/**
	 * This function is the gateway for authentication process.
	 *
	 * If the user is logged in, true is set for 'loggedin'.
	 * We will need to check which stage of the signup process they have
	 * completed. If they are still in stage 2 or 3, we must display the
	 * appropriate modal by setting the modal variables in the returned
	 * JSON object. If user is not logged in, then we will return an
	 * array containing the following information for the frontend JS
	 * to display a login screen.
	 * Structure of the JSON object, JS syntax
	 * [{
	 * 'loggedin' : boolean, // flag reflecting the session variable
	 * 'modalcontent' : string, // HTML string of the appropriate modal
	 * 'modaljs' : string, // relative path to JS file for the modal
	 * 'modalid' : string // CSS id selector of the modal view
	 * 'completedsignup': boolean // flag showing if user has completed signup
	 * }]
	 *
	 * @return JSON object		JSON object containing all the information.
	 */
	public function auth_gateway() {
		// var_dump($this->flag_ip_gatedonly);
		// var_dump($this->bool_ip_whitelisted);
		if ($this->flag_ip_gatedonly && ! $this->bool_ip_whitelisted) {
			/*
			 * We are restricting access based on IP address to specific
			 * portions of the site, specifically, the portions that call the
			 * js::auth_processor => php::auth_gateway() pathway. If we enter
			 * this block, that means the user is not authorized.
			 */
			$this->encodeJSON( array(
					"auth_error" => 1 
			) );
			return;
		}
		
		$responseObj = array();
		
		if (! $this->session->userdata( 'loggedin' )) {
			$responseObj['loggedin'] = false;
			$responseObj['modalcontent'] = $this->getSignupView();
			$responseObj['modaljs'] = array(
					"js/jquery.validate.min.js",
					"js/fms_user_portal/fms_user_signup_portal.js" 
			);
			$responseObj['modalid'] = "#signupModal";
			$responseObj['modalcss'] = array(
					'css/signup_modal.css' 
			);
		} else {
			$responseObj['loggedin'] = true;
			// $uid = $this->session->userdata( 'userid' );
			$signStage = $this->getSignupStageByUserid( $this->userId );
			$responseObj["uid"] = $this->userId;
			
			// $signStage = 1; // DEBUG, working on stage 2 of the signup
			// process.
			$responseObj['signup_stage'] = $signStage;
			if ($signStage == 1) {
				$responseObj['modalcontent'] = $this->getSkillsEditView();
				$responseObj['modaljs'] = array(
						"js/jquery.validate.min.js",
						"js/textext.min.js",
						"js/vendor/jquery.ui.widget.js",
						"js/test/jquery.Jcrop.min.js",
						"js/fms_user_portal/fms_user_signup_skills.js" 
				);
				$responseObj['modalid'] = "#signupSkillsModal";
				$responseObj['modalcss'] = array(
						"css/jquery-ui.css",
						'css/textext.css',
						'css/utlity.css',
						'css/signup_skill_modal.css' 
				);
			} else if ($signStage == 2) {
				$responseObj['modalcontent'] = $this->getUserAbbrProfileView();
				$responseObj['modaljs'] = array(
						'js/jquery.slimscroll.min.js',
						"js/jquery.validate.min.js",
						"js/test/jquery.Jcrop.min.js",
						"js/textext.min.js",
						//"js/tmpl.js",
						"js/load-image.min.js",
						"js/canvas-to-blob.min.js",
						"js/jquery.iframe-transport.js",
						"js/jquery.fileupload.js",
						"js/jquery.fileupload-process.js",
						"js/jquery.fileupload-resize.js",
						"js/jquery.fileupload-validate.js",
						"js/jquery.fileupload-ui.js",
						"js/utility.js",
						"js/fms_user_portal/fms_user_signup_profile.js" 
				);
				$responseObj['modalid'] = "#profileModal";
				$responseObj['modalcss'] = array(
						'css/FMS/dropdown.css',
						"css/jquery-ui.css",
						"css/test/jquery.Jcrop.css",
						'css/textext.css',
						'css/signup_profile_modal.css',
						'css/utility.css' 
				);
				$responseObj['redirecturl'] = $this->session->userdata( 'redirecturl' );
			} else if ($signStage == 3) {
				$responseObj['completedsignup'] = true;
			}
		}
		// echo json_encode( $responseObj );
		$this->encodeJSON( $responseObj );
	}
	
	/**
	 * processCredentials()
	 *
	 * This function handles credential processing.
	 *
	 * Frontend will call this function using Ajax with email and password. This
	 * fucntion will perform authentication
	 * then return true or false based on the result of authentication.
	 * Parameter will be passed in thru $_POST.
	 * All the array-key will be listed below as param. After input check it
	 * will call checkLogin() to check whether user's
	 * credential is correct.
	 *
	 * @access public
	 *        
	 * @param string $email        	
	 * @param string $password        	
	 *
	 * @return boolean If login was successful, it will return TRUE otherwise
	 *         FALSE
	 */
	public function processCredentials() {
		$responseObj = array();
		$responseObj['status'] = 0;
		if (isset( $_POST["email"] ) && isset( $_POST["password"] )) {
			$signStage = $this->checkLogin( $this->cleanPost["email"], $this->cleanPost["password"] );
			if ($signStage) {
				$responseObj['status'] = 1;
				$uid = $this->getUserIdByEmail( $this->cleanPost["email"] );
				$this->session->set_userdata( 'loggedin', 1 );
				$this->session->set_userdata( 'userid', $uid );
			} else {
				$responseObj['error'] = "failed to login";
			}
		} else {
			$responseObj['error'] = "required field not set";
		}
		
		$this->encodeJSON( $responseObj );
	}
	
	/**
	 * This function handles the process of signup.
	 *
	 * This function will be called by frontend AJAX to add new user to
	 * database.
	 * After input validation it will first check whether email is already taken
	 * by call isEmailTaken().
	 * Then call addUser() to add user to database.
	 * Parameter is expected in $_POST all the array-key will be listed below as
	 * param.
	 *
	 * @access public
	 *        
	 * @param string $email        	
	 * @param string $password        	
	 * @param string $name_last        	
	 * @param string $name_first        	
	 *
	 * @return boolean If signup was successful,it will return TRUE otherwise
	 *         return FALSE
	 */
	public function signup() {
		$responseObj = array();
		$responseObj["signup_success"] = 0;
		
		$this -> load -> library('email');
		$invite_code = $this->input->post('invitation_code');
		$invitation_code_isvalid = json_decode($this->checkInvitationCode($invite_code));
			
		if(!$invitation_code_isvalid) {
			$responseObj["signup_success"] = -1;
			$responseObj['error'] = "Please enter valid invitation code";
			$this->encodeJSON( $responseObj );
			return;
		}
		//$this->debug("auth_service signup", "Passed the invitation code gate.");
		//var_dump( $this->cleanPost );
		//var_dump( $this->input->post( 'invitation_code' ) );
		
		
		if (isset( $this->cleanPost["name_first"] ) && isset( $this->cleanPost["name_last"] ) && isset( $this->cleanPost["password"] ) && isset( $this->cleanPost["email"] )) {
			$error = $this->signupErrorCheck( $this->cleanPost );
			$error = false; // added by wei zong; June 19th, 2013
			//$this->debug("auth_service signup", "Passed signup error check, value coerced as no-errors.");
			if (! $error) {
				if ($this->isEmailTaken( $this->cleanPost["email"] )) {
					//$this->debug("auth_service signup", "Failed to pass unique email test.");
					$responseObj['error'] = "Email taken";
				} else {
					//$this->debug("auth_service signup", "User email is unique, not taken.");
					$user = $this->addUser( $this->cleanPost );
					if ($user) {
						$responseObj["signup_success"] = 1;
						$this->session->set_userdata( 'loggedin', 1 );
						$this->session->set_userdata( 'userid', $user->getId() );
						
						// Send email to user, once he/ she has successfully signed up to FMS
						// Pankaj K., Sept 13 2013
						$welcomeEmail = 'welcome@findmysong.com';
						$userEmail =  $user -> getEmail();
						$data['userName'] = $user->getFirstName() . " " . $user->getLastName();
						$data['userEmail'] = $user->getEmail();
						$data['userId'] = $this->userId;
						$emailBody = $this -> load -> view('email_templates/plain_text_welcome_to_fms', $data, TRUE);
						$config['mailtype'] = 'html';
						$this -> email -> initialize($config);
						
						$this -> email -> from($welcomeEmail, "Find My Song");
						$this -> email -> to($userEmail);

						$this -> email -> subject("Welcome to Find My Song! Let's get started.");
						$this -> email -> message($emailBody);
						$this -> email -> send();
						
					} else {
						$responseObj['error'] = "database error";
					}
				}
			} else {
				//$this->debug("auth_service signup", "Failed to pass validation.");
				$responseObj['error'] = "failed to pass validation";
			}
		}
		//$this->debug("auth_service signup", "Dump of the JSON responseObj");
		//var_dump($responseObj);
		$this->encodeJSON( $responseObj );
	}
	
	/**
	 * This function handle the process of signup for facebook user.
	 *
	 *
	 * This function will be called by frontend AJAX add new facebook user to
	 * database. The difference with regular user is that after addUser() being
	 * success excuted it still need to call addUserNetworkInfo to add
	 * facebookUserid and userid to a joint table.
	 *
	 * @access public
	 *        
	 * @param string $email        	
	 * @param string $name_last        	
	 * @param string $name_first        	
	 * @param string $token
	 *        	access token of facebook needed when we request user's
	 *        	information from facebook server.
	 * @param string $facebookUserid        	
	 *
	 * @return boolean If signup was successful,it will return TRUE otherwise it
	 *         will return FALSE.
	 */
	public function facebookSignup() {
		$responseObj = array();
		$responseObj['status'] = "0";
		if (isset( $this->cleanPost['facebookUserid'] ) && isset( $this->cleanPost['name_last'] ) && isset( $this->cleanPost['name_first'] ) && isset( $this->cleanPost['token'] ) && isset( $this->cleanPost['email'] )) {
			$FB = $this->fms_snw_model->getFbById( $this->cleanPost['facebookUserid'] );
			if ($FB) {
				$this->session->set_userdata( 'loggedin', 1 );
				$this->session->set_userdata( 'userid', $FB->getUser()->getId() );
				$responseObj['status'] = 1;
			} else {
				$user = $this->addUser( $this->cleanPost );
				$expireDate = new DateTime();
				$intvalStr = 'PT' . $this->cleanPost['expire'] . 'S';
				$expireDate->add( new DateInterval( $intvalStr ) );
				$FB = $this->fms_snw_model->createFB( $user, $this->cleanPost['token'], $this->cleanPost['facebookUserid'], $expireDate );
				if ($user && $FB) {
					$responseObj["FacebookID"] = $this->cleanPost['facebookUserid'];
					$responseObj["uid"] = $user->getId();
					$responseObj['status'] = 1;
					$baseurl = "http://graph.facebook.com/";
					$sufix = "/picture?type=large";
					$facebookid = $this->cleanPost['facebookUserid'];
					$imgurl = $baseurl . $facebookid . $sufix;
					$imgDir = "user_files/" . $uid . "/image/";
					if (! is_dir( $imgDir )) {
						mkdir( $imgDir, true );
					}
					$imgName = $facebookid . "profile.jpg";
					$imgPath = $imgDir . $imgName;
					file_put_contents( $imgPath, file_get_contents( $imgurl ) );
					$dataArray = array();
					// $dataArray["photo_file_name_hashed"] = $this -> encrypt
					// -> encode($imgName); we decide to just store the plain
					// file name.
					$dataArray["fileName"] = $imgName;
					$dataArray["uploadIp"] = $_SERVER['REMOTE_ADDR'];
					$dataArray["filePath"] = $imgDir;
					$dataArray["type"] = Fms_file_model::PROFILETYPE;
					$dataArray["subtype"] = 100;
					$photo = $this->fms_file_model->addFile( $dataArray, $user->getId() );
					$user->setProfilePicture( $photo );
					$this->session->set_userdata( 'loggedin', 1 );
					$this->session->set_userdata( 'userid', $user->getId() );
				} else {
					$responseObj['error'] = "database Error";
				}
			}
		}
		$this->encodeJSON( $responseObj );
	}
	
	/**
	 * This function handle facebook login process.
	 *
	 * Since facebook login api provide a very nice way to check user's login
	 * information at frontend using javescript.
	 * So frontend can check whether user login itself. Backend just need the
	 * facebookUserid and set the session data for this user.
	 * Parameter is passed in $_POST all array-key will be list below as
	 * param.For the debug purpose it is still return something. But eventually
	 * it will not return anything
	 * but onll call auth_gateway() at the end of the function.
	 *
	 * @param string $facebookUserid        	
	 *
	 * @return void
	 */
	public function facebookSignin() {
		$responseObj = array();
		$responseObj['status'] = "0";
		if (isset( $this->cleanPost['facebookUserid'] )) {
			$responseObj['status'] = "1";
			$FB = $this->fms_snw_model->getFbById($this->cleanPost['facebookUserid']);
			$user = $FN->getUser();
			$user->setLastLoginTime(new DateTime());
			$uid = $user->getId();
			$this->session->set_userdata( 'loggedin', 1 );
			$this->session->set_userdata( 'userid', $uid );
			$responseObj["uid"] = $uid;
			$this->fms_general_model->flush();
		}
		$this->encodeJSON( $responseObj );
	}
	
	/**
	 * This function handle's twitter signup.
	 *
	 * It will first prompt user with twitter's login page.
	 * After user successfully login it will call the call back function which
	 * is twitterCallback() below. No paramter no return for this function
	 *
	 * @access public
	 *        
	 * @param $flag flag
	 *        	= 0 means TWITTER SIGNIN , flag = 1 means twitter connect.
	 *        	
	 * @return void
	 */
	public function twitterSignup($flag) {
		$connection = new TwitterOAuth( CONSUMER_KEY, CONSUMER_SECRET );
		$request_token = $connection->getRequestToken( OAUTH_CALLBACK );
		
		/* Save temporary credentials to session. */
		$this->session->set_userdata( 'oauth_token', $request_token['oauth_token'] );
		$this->session->set_userdata( 'oauth_token_secret', $request_token['oauth_token_secret'] );
		$this->session->set_userdata( 'twitterflag', $flag );
		
		switch ($connection->http_code) {
			case 200 :
				$redirect_url = $connection->getAuthorizeURL( $request_token );
				header( 'Location: ' . $redirect_url );
				break;
			default :
				echo 'Could not connect to Twitter. Refresh the page or try again later.';
		}
	}
	// hard coded for test purpose. always logout first when you use twitter
	// sign up.
	public function twitterCallback() {
		$status = 0;
		$oauth_token = $this->session->userdata( 'oauth_token' );
		$oauth_secret = $this->session->userdata( 'oauth_token_secret' );
		$flag = $this->session->userdata( 'twitterflag' );
		
		$connection = new TwitterOAuth( CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_secret );
		
		$token_credentials = $connection->getAccessToken( $_REQUEST['oauth_verifier'] );
		
		$connection = new TwitterOAuth( CONSUMER_KEY, CONSUMER_SECRET, $token_credentials['oauth_token'], $token_credentials['oauth_token_secret'] );
		$account = $connection->get( 'account/verify_credentials' );
		
		//print_r($account); // PANKAJ DEBUG :: waylan 
		
		$twitterId = $account->id_str;
		$TW = $this->fms_snw_model->getTwById($twitterId);
		$uid = $this->session->userdata( 'userid' );		
		
		// "denied" parameter is set when the user cancels the login to twitter for authentication.
		// in such case, we simply need to close the window.
		// Waylan and Pankaj K., Sept 10, 2013
		
		if(isset($_GET['denied'])) {
			if ($_GET['denied']) {					
				$script = '<script>window.opener.h(%d);window.close();</script>';
				echo sprintf($script, $status);	
			}
		}
		
		if(!$uid){
			return;
		}
		if ($flag == 1) {
			if (! $TW) {
				$user = $this->fms_user_model->getEntityById( $uid );
				$TW = $this->fms_snw_model->createTW( $user, $twitterId, $token_credentials['oauth_token'], $token_credentials['oauth_token_secret'] );
				$status = 1;
			} elseif ($TW->getUser()->getId() != $uid) {
				$status = 2;
			}			
		} else if ($flag == 0){
			if($TW){
				$user = $TW->getUser();
				$user->setLastLoginTime(new DateTime());
				$uid = $user->getId();
				$this->session->set_userdata( 'loggedin', 1 );
				$this->session->set_userdata( 'userid', $uid );		
				$this->fms_general_model->flush();
				$status = 3;		
			} else {
				$status = 4;
			}			
		}	
		$script = '<script>   
			   window.opener.h(%d);
			   window.close();
 			  </script>';
		echo sprintf( $script, $status );
	}
	// /////////////////////////////////////////////////////////////////////////
	// Non AJAX functions //
	// /////////////////////////////////////////////////////////////////////////
	
	/**
	 * Check if user's credential is correct
	 *
	 * It will get hashed password from database then unhash it. Then compare
	 * the plain password with input password.
	 *
	 * @access protected
	 *        
	 * @param string $email
	 *        	email address
	 * @param string $password
	 *        	password
	 *        	
	 * @return boolean if signin success return true otherwise false
	 *        
	 */
	protected function checkLogin($email, $password) {
		$hashedPassword = $this->fms_user_model->getPassword( $email );
		// echo $hashedPassword."\r\n";
		if (! $hashedPassword) {
			return FALSE;
		}
		// echo $this -> config -> config ["encryption_key"];
		$decrypted_password = $this->encrypt->decode( $hashedPassword, $this->config->config["encryption_key"] );
		$decrypted_password = $hashedPassword;
		// echo "decrypted ".$decrypted_password."\r\n";
		if ($password != $decrypted_password) {
			// echo "password are not same!!";
			return FALSE;
		} else {
			$this->fms_user_model->updateLastLoginTime($email);
			return TRUE;
		}
		// $signStage = $this -> fms_user_profile_model
	// ->getSignupStagebyEmail($email);
		/*
		 * $signStage = 1; if ($signStage) { //echo "password are same!!";
		 * //return $signStage; } //echo " error happened !"; return FALSE;
		 */
	}
	
	/**
	 * Add new user to database.
	 *
	 * adduser() is taking in array of user info then checks if this user has
	 * existed.
	 * If it is, then it return false. Else it will first create information :
	 * ip of registration, timestamp of registration, signStage, and hash
	 * password.
	 * Then it call the model function and insert it to the database. used to
	 * insert the user table, when a user signs up. An array is passed as a
	 * parameter,
	 * array-key will be list below as param.
	 *
	 * @access protected
	 *        
	 * @param string $name_first        	
	 * @param string $name_last        	
	 * @param string $email        	
	 * @param string $password        	
	 *
	 * @return boolean If success return True, If error happened return error
	 *         message
	 */
	protected function addUser($array) {
		// print_r($array);
		// $array["password"] = $this->encrypt->encode( $array["password"] );
		$array["ip_registration"] = $_SERVER['REMOTE_ADDR'];
		$array["timestamp_regis"] = date( "Y-m-d H:i:s" );
		$array["sign_stage"] = 1;
		$user = $this->fms_user_model->createEntity( $array );
		return $user;
	}
	protected function addFbUser($array) {
		// print_r($array);
		// $array["password"] = $this->encrypt->encode( $array["password"] );
		$array["ip_registration"] = $_SERVER['REMOTE_ADDR'];
		$array["timestamp_regis"] = date( "Y-m-d H:i:s" );
		$array["sign_stage"] = 1;
		$user = $this->fms_user_model->createEntity( $array );
		return $user;
	}
	/**
	 * Check if email is already been used before.
	 *
	 * @access protected
	 *        
	 * @param $email Email address that user inputed in signup page
	 *        	
	 * @return boolean email address is taken return true otherwise return false
	 *        
	 */
	protected function isEmailTaken($email) {
		//$this->debug("auth_service", "Checking if this email: [". $email ."] is taken.");
		if ($this->fms_user_model->getUserIdByEmail( $email )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * validate the input for signup.
	 *
	 * It will perform detailed data validation on the input for signup include
	 * password, email,_name_first,name_last.
	 * It will take a array of those input as array-key which will be listed
	 * below as param.
	 * This function need to wait for the detailed restriction on each input
	 * field.
	 *
	 * @access protected
	 *        
	 * @param string $name_first        	
	 * @param string $name_last        	
	 * @param string $email        	
	 * @param string $password        	
	 *
	 * @return boolen Return TRUE if input data passed validation otherwise
	 *         return FALSE.
	 */
	public function signupErrorCheck() {
		return false;
		$array = $_POST;
		$errorArray = array();
		foreach ( $array as $key => $value ) {
			if (strlen( $value ) < 1) {
				$errorArray[$key] = "too short";
			}
		}
		if (strlen( $value ) > 50) {
			$errorArray[$key] = "too long";
		}
		if (! ctype_alpha( $array['name_first'] )) {
			$errorArray['name_first'] = "contains illegal character";
		}
		if (! ctype_alpha( $array['name_last'] )) {
			$errorArray['name_last'] = "contains illegal character";
		}
		if (! ctype_alnum( $array['password'] )) {
			$errorArray['password'] = "contains illegal character";
		}
		if (strlen( $array['password'] ) < 6) {
			$errorArray['password'] = "too short";
		}
		$expression = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/";
		if (! preg_match( $expression, $array['email'] )) {
			$errorArray['email'] = "invalid";
		}
		$this->responseObj["validation"] = $errorArray;
		// print_r($errorArray);
		if (count( $errorArray ) == 0) {
			// echo "sss";
			return true;
		} else {
			// echo "fail";
			return false;
		}
	}
	private function getUserIdByEmail($email) {
		$userId = $this->fms_user_model->getUserIdByEmail( $email );
		if ($userId) {
			return $userId;
		} else {
			// echo "ERROR EMAIL DOES NOT EXIST";
			return FALSE;
		}
	}
	private function getSignupStageByUserid($uid) {
		$uid = $this->fms_user_model->getSignupStage( $uid );
		// return 1; // hard coded uid wait for model to be done
		return $uid;
	}
	/**
	 * This function will return the signup modal as a string.
	 * This is stage 1 of the signup process.
	 *
	 * @return string signup modal as a HTML string
	 */
	private function getSignupView() {
		// passing in TRUE as a third parameter - second paramter
		// $data is missing here, substituting with an empty array to the third
		// parameter
		return $this->load->view( 'view_fms_user_portal/view_signup', [], TRUE );
	}
	
	/**
	 * This function will return the signin modal as a string.
	 *
	 * @return string signin modal as a HTML string
	 */
	private function getSigninView() {
		// passing in TRUE as a third parameter - second paramter
		// $data is missing here, gives us the view as a string.
		return $this->load->view( 'view_fms_user_portal/view_signin', [], TRUE );
	}
	
	/**
	 * This function will return the skills edit modal as a string.
	 * This is stage 2 of the signup process.
	 *
	 * @return string skills editing modal as a HTML string
	 */
	private function getSkillsEditView() {
		// passing in TRUE as a third parameter - second paramter
		// $data is missing here, gives us the view as a string.
		// $userId = $this->session->userdata( 'userid' );
		// $userId = 5;
		$data = array();
		$skills = $this->fms_user_model->getSkills( $this->userId );
		
		if ($skills) {
			$data['skills'] = $skills;
		}
		$user = $this->fms_user_model->getEntityById( $this->userId );
		$name = $user->getFirstName();
		if ($name) {
			$data['name'] = $name;
		}
		return $this->load->view( 'view_fms_user_profile/view_user_signup_skills_addition', $data, TRUE );
	}
	
	/**
	 * This function will return the abbreviated user profile editting modal as
	 * a string.
	 * This is stage 3 of the signup process.
	 *
	 * @return string abbreviated user profile editing modal as a HTML string
	 */
	private function getUserAbbrProfileView() {
		// passing in TRUE as a third parameter - second paramter
		// $data is missing here, gives us the view as a string.
		$userId = $this->session->userdata( 'userid' );
		// $userId = 36;
		$data = array();
		// $profilePic =
		// $this->fms_user_profile_model->getProfilePictureByUserId( $userId );
		//
		// if ($profilePic) {
		// $baseUrl = $this->get_full_url() . '/';
		// $data['picUrl'] = $baseUrl . $profilePic['path']
		// .$profilePic['name'];
		// }
		$name = $this->fms_user_model->getUserName( $userId );
		if ($name) {
			$data['name'] = $name;
		}
		
		// Load audio uploader module
		$user = $this->fms_user_model->getEntityById( $userId );
		$audioModule['uploader_id'] = 'signup_audio_uploader';
		$files = $this->fms_file_model->getAudioFiles( $user );
		if ($files) {
			$audioModule['files'] = $files;
		} else {
			$audioModule['files'] = array();
		}
		$data['audioModule'] = $this->load->view( 'view_audio_uploader', $audioModule, true );
		
		// Load image uploader module
		$imageModule['uploader_id'] = 'signup_image_uploader';
		$profilePicture = $user->getProfilePicture();
		if($profilePicture) {
			$imageModule['picUrl'] = base_url($profilePicture->getPath() . $profilePicture->getName());
			$imageModule['picName'] = $profilePicture->getName();
		} else {
			$imageModule['picUrl'] = false;
			$imageModule['picName'] = false;
		}
		$data['imageModule'] = $this->load->view( 'view_img_uploader', $imageModule, true );
		
		// State Dropdown
		$this->load->model('docModels/fms_us_state_model');
		$data['stateList'] = $this->fms_us_state_model->getAllStates();
		
		return $this->load->view( 'view_fms_user_profile/view_user_signup_profile_edit', $data, TRUE );
	}
	
	/**
	 * This function will tell the client browser not to cache the page.
	 */
	private function clear_cache() {
		$this->output->set_header( "Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0" );
		$this->output->set_header( "Pragma: no-cache" );
	}
	
	/**
	 * login()
	 *
	 * This is the page users will be redirected to, in order to login, if they
	 * are
	 * trying to reach a page that is only accessible to authenticated users.
	 *
	 * @access public
	 */
	public function login() {
		$data['title'] = "Login";
		$data['css_ref'] = array(
				'css/fms_login.css',
                'css/bootstrap-responsive.min.css',
                'css/mobile_login.css',

		);
		$data['extrascripts'] = array(
                'js/jquery.validate.min.js',
                'js/fms_user_portal/fms_user_signup_portal.js',
        );
		
		// SEO
		$meta_data = array(
				array(
						'name' => 'description',
						'content' => "At Find My Song you can create superfantasticwonderful music together!" 
				),
				array(
						'name' => 'keywords',
						'content' => 'Music,Songs,Editing,Collaboration,Learn, Discovery, Song Writer, Guitar, Singer, Producer, Music Producer, Piano, Classical Music' 
				) 
		);
		$data['metadata'] = $meta_data;
		$this->load->view( 'view_header', $data );
		$this->load->view( 'view_login/view_login_page', $data );
		$this->load->view( 'view_footer', $data );
	}

    /**
     *  This is a signup page for mobile
     */
    public function mobile_signup() {
        $data['title'] = "Signup";
        $data['css_ref'] = array(
            'css/bootstrap-responsive.min.css',
            'css/fms_signup_elements.css',
            'css/signup_modal.css',
            'css/mobile_signup.css',
        );
        $data['extrascripts'] = array(
            'js/jquery.validate.min.js',
            'js/fms_user_portal/fms_user_signup_portal.js',
        );

        // SEO
        $meta_data = array(
            array(
                'name' => 'description',
                'content' => "At Find My Song you can create superfantasticwonderful music together!"
            ),
            array(
                'name' => 'keywords',
                'content' => 'Music,Songs,Editing,Collaboration,Learn, Discovery, Song Writer, Guitar, Singer, Producer, Music Producer, Piano, Classical Music'
            )
        );
        $data['metadata'] = $meta_data;
        $this->load->view( 'view_header', $data );
        $this->load->view( 'view_fms_user_portal/view_signup', $data );
        $this->load->view( 'view_footer', $data );

    }


    /**
	 * This is the function that should be called by the client to log the user
	 * out.
	 */
	public function logout() {
		$this->session->sess_destroy(); // this actually just severs link between
		                                // server and user cookie.
		$this->session->userdata = array(); // force clearing of the CI session
		                                    // data.
		$this->clear_cache();
		// $this->debug( "logout", "The user id is " . $this->session->userdata(
		// 'user_id' ) );
		// $this->debug( "logout", "Login stage: " . $this->session->userdata(
		// 'loginstage' ) );
		// $this->debug( "logout", "destinationURL: " .
		// $this->session->userdata( 'destinationURL' ) );
		redirect( '/' ); // force user to the home page.
	}
	protected function get_full_url() {
		$https = ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off';
		
		return ($https ? 'https://' : 'http://') . (! empty( $_SERVER['REMOTE_USER'] ) ? $_SERVER['REMOTE_USER'] . '@' : '') . (isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'] . ($https && $_SERVER['SERVER_PORT'] === 443 || $_SERVER['SERVER_PORT'] === 80 ? '' : ':' . $_SERVER['SERVER_PORT']))) . substr( $_SERVER['SCRIPT_NAME'], 0, strrpos( $_SERVER['SCRIPT_NAME'], '/' ) );
	}
	
	/**
	 * checkUserCredentialsExist()
	 *
	 * This function returns true if user is logged in, and we have a user ID.
	 *
	 * @access protected
	 *        
	 * @return boolean True if user is logged in and user ID exists
	 */
	protected function authenticated() {
		// var_dump( $this->session->userdata( 'loggedin' ) );
		// var_dump( $this->session->userdata( 'userid' ) );
		return ($this->session->userdata( 'loggedin' )) && ($this->session->userdata( 'userid' ));
	}
	
	/**
	 * getFacebookShareLink_project()
	 * 
	 * @access protected
	 * 
	 * This function will generate a Facebook sharer.php link for projects.
	 * The link includes the project title, link, summary, and cover photo.
	 * 
	 * @param int $int_id The project ID
	 * @param string $str_displaytext The text to display to the user
	 * @param string $str_id The link HTML id attribute
	 * @param string $str_classes The link HTML class attributes
	 * @param bool $bool_includeClosingTag If you want the <a> element to wrap 
	 * 		something, set this to true, you will be responsible for closing the <a> element. 
	 * @return string The completed link as a string. Echo it into the view to use.
	 */
	protected function getFacebookShareLink_project($int_id, $str_displaytext, $str_id, $str_classes, $bool_includeClosingTag = true) {
		$str_sharelink = array();
		$my_proj_id = (isset($int_id)) ? $int_id : -1;
		if ($my_proj_id === -1) {
			return false;
		}
		$my_displaytext = (isset($str_displaytext)) ? $str_displaytext : 'Share on Facebook';
		$my_id = (isset($str_id)) ? $str_id : '';
		$my_classes = (isset($str_classes)) ? $str_classes : '';
		$my_closingTag = (isset($bool_includeClosingTag)) ? $bool_includeClosingTag : true;
				
		$this->load->model('docModels/fms_project_model');
		$project_entity = $this->fms_project_model->getEntityById($int_id);
		if (!$project_entity) {
			return false;
		}  
		$profilePicture = $project_entity->getPhoto();
		$proj_photo_path = "http://www.findmysong.com/img/logo/fms_HeaderLogo.png"; // placeholder pic (development)
		$proj_photo_path = base_url("/img/logo/fms_HeaderLogo.png"); // placeholder pic (production)
		if ($profilePicture) {
			$proj_photo_path = base_url($profilePicture->getPath() . $profilePicture->getName());
		}			
		$proj_desc_raw = $project_entity -> getDescription();
		$proj_desc_processed = 'Come checkout my project on FindMySong and find projects from artists like me!';
		if (strlen($proj_desc_raw) > 0) {		
			$truncateLength = 255;
			$proj_desc_processed = htmlspecialchars(substr(strip_tags($proj_desc_raw), 0, $truncateLength));
			if (strlen($proj_desc_processed) >= $truncateLength ) { 
				$proj_desc_processed .= "...";
			}
		} 
		
		// Add the id and class attributes
		$str_sharelink[] = '<a id="' . $my_id . '" class="'. $my_classes .'" ';
		$str_sharelink[] = 'href="http://www.facebook.com/sharer/sharer.php?s=100';
		$str_sharelink[] = '&amp;p%5Btitle%5D=';
		// Add the project name/title.
		$str_sharelink[] = $project_entity -> getName();  
		$str_sharelink[] = '&amp;p%5Burl%5D=';
		// Add a link to the project
		$str_sharelink[] = base_url("/projects/profile/". $int_id);
		$str_sharelink[] = '&amp;p%5Bsummary%5D=';
		// Add a project summary
		$str_sharelink[] = $proj_desc_processed;
		$str_sharelink[] = '&amp;p%5Bimages%5D%5B0%5D=';
		// Add the project photo
		$str_sharelink[] = $proj_photo_path . '"';
		$str_sharelink[] = 'onclick="javascript:window.open(this.href,';
		$str_sharelink[] = "'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;";
		$str_sharelink[] = '" title="'. $my_displaytext .'">';
        $str_sharelink[] = $my_displaytext;
        if ($my_closingTag) {
        	$str_sharelink[] = '</a>';
        }             
        
		
		return implode('', $str_sharelink);
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
	/**
	 * AJAX connector method to check if the provided invitation code is correct.
	 * However, we can also call this function internally, and check the bool returned.
	 * 
	 * @access public
	 * 
	 * @param int Extracted from $_GET 
	 * @param int $inputCode If we are calling this function internally, 
	 * 	we will use this instead of the GET value
	 * 
	 * @return TRUE/FALSE Returns the test result as JSON object.
	 */
	public function checkInvitationCode($inputCode = -1){
		// development value
		$inputCode = 'USC2013';
		$correct_codes = array(
			'USC2013',
			'CREATENOW',
			'HAOSFRIEND'
		);/**
		$code = ($inputCode === -1) ? $this->input->get('code') : $inputCode;
		foreach ($correct_codes as $correct_code) {
			if (strtolower($correct_code) === strtolower($code)) {
				$this->encodeJSON( TRUE );
				return TRUE;		
			}
		}
		$this->encodeJSON( FALSE );
		return FALSE;
		 
		 */
		$this->encodeJSON( TRUE );
		return TRUE;
	}
}

