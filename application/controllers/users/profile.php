<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Profile extends Authenticated_service {

	private $targetUserId;

	public function __construct() {
		parent::__construct();

		$this -> load -> helper('html');
		$this -> load -> helper('url');
		$this -> load -> helper('form');
		$this -> load -> helper('mp3file');

		$this -> load -> model('docModels/fms_user_model');
		$this -> load -> model('docModels/fms_project_model');
		$this -> load -> model('docModels/fms_skill_model');
		$this -> load -> model('docModels/fms_user_skill_model');
		$this -> load -> model('docModels/fms_file_model');
		$this -> load -> model('docModels/fms_project_member_model');
	}

	public function index($id = 0) {
		$data['title'] = "Musician Profile";
		$data['css_ref'] = array(
			"css/user_profile/fms_user_profile.css",
			"css/fms_signup_elements.css"
		);
		$data['extrascripts'] = array("js/user_profile/fms_user_profile.js");
		
		$user  =$this->fms_user_model->getEntityById($id);
		if (!$user) {
			redirect('fms_404');
			return;
		}
		$this -> targetUserId = $id;

		// Get data
		$data['userid'] = $this -> targetUserId; // We are going to view this user's profile.
		$data['loggedinUser'] = $this -> userId; // Used to prevent the current user from messaging or inviting themselves.
		$user_info = $this -> getUserInfo();
		$proj_data = $this -> getProjectListing();
		$tour_data = $this -> getTourDates();
		
	 	$data['userAudioFiles'] = $this->fms_file_model->getAudioFiles($user);
		//print_r($data['userAudioFiles']);
		// $userAudioFiles= $this->fms_file_model->getAudioFiles($user);
		// foreach($userAudioFiles as $audioFile){
			// echo $audioFile->getName() . "<br/>";
		// }

		/**
		 * The godamn query variable is a STRING, but checkUserContact() requires
		 * and checks against an INT so you MUST CAST to int before passing!
		 */
		if ($this -> authenticated()) {
			$data['ifInContact'] = $this -> fms_user_model -> checkUserContact($this -> userId, (int)$this -> targetUserId);
		} else {
			$data['ifInContact'] = 0;
		}

		$proj_data['userid'] = $this -> targetUserId; // We are going to view this user's profile.
		$proj_data['loggedinUser'] = $this -> userId; // Used to prevent the current user from messaging or inviting themselves.
		// Load views for each tab
		$data['view_project_listing'] = $this -> load -> view("view_user_profile/view_user_project_listing", $proj_data, true);

		$this -> load -> view('view_header', $data);
		$this -> load -> view('view_user_profile/view_user_profile_frontpage.php', $user_info);
		$this -> load -> view('view_footer', $data);
	}

	/**
	 * This function returns list of projects of specified user.
	 * Since this is the public profile, we do not want people to see
	 * the Fms_project_model::inactive, unpublished, or unsaved projects.
	 *
	 * @access private
	 * @author
	 */
	private function getProjectListing() {

		$userid = $this -> targetUserId;
		$user_info = $this -> fms_user_model -> getEntityById($userid);
		
		$myprojects = $user_info -> getProjects();

		$iter = 0;

		$project = array();

		foreach ($myprojects as $row) {
			$userProject = $row -> getProject();
			
			$projectMember = $this -> fms_project_member_model -> getMemberInProject( $user_info, $userProject );

			if ($userProject -> getStatus() != Fms_project_model::UNPUBLISHED 
					&& $userProject -> getStatus() != Fms_project_model::UNSAVED 
					&& $userProject -> getStatus() != Fms_project_model::INACTIVE
					&& $projectMember -> getVisibility() == 1
					&& $projectMember -> getRanking() != 0) {
				// We have filtered out the undesired projects
				//echo "This project passed status check: " . $project_id = $row -> getId();
				$project_id = $userProject -> getId();
				$photo = $userProject -> getPhoto();
				$file = $userProject -> getFiles();
				$rank = $projectMember -> getRanking();
				if ($project_id) {
					$project[$iter]['project_id'] = $project_id;
				}
				foreach ($file as $projectfile) {
					$type = $projectfile -> getType();
					$subtype = $projectfile -> getSubtype();
					if ($type == 0 && $subtype == 1) {
						$project[$iter]['audio_link'] = base_url() . $projectfile -> getPath() . $projectfile -> getName();
					}
				}
				if (!isset($project[$iter]['audio_link'])) {
					$project[$iter]['audio_link'] = '';
				}
				$title = $userProject -> getName();
				if ($title) {
					$project[$iter]['title'] = $title;
				} else {
					$project[$iter]['title'] = '';
				}
				if ($photo) {
					$project[$iter]['img'] = base_url() . $photo -> getPath() . $photo -> getName();
				} else {
					$project[$iter]['img'] = '';
				}
				if ($rank) {
					$project[$iter]['rank'] = $rank;
				}
				$project[$iter]['link'] = '';
				$project[$iter]['length'] = '';
				$iter++;
			}
		}
		$data['projects'] = $project;
		return $data;
	}

	/**
	 * FOR DEMO
	 * This function returns user's basic information
	 */
	private function getUserInfo() {
		$userid = $this -> targetUserId;
		$user_info = $this -> fms_user_model -> getEntityById($userid);

		// Fake data for the front page
		$coverphoto = $user_info -> getCoverPhoto();
		if ($coverphoto) {
			$data['cover_photo_path'] = base_url() . $coverphoto -> getPath() . $coverphoto -> getName();
		} else {
			$data['cover_photo_path'] = base_url().'img/default_cover_photo.jpg';
		}

		$ProfilePicture = $user_info -> getProfilePicture();
		if ($ProfilePicture) {
			$data['profile_img_path'] = base_url() . $ProfilePicture -> getPath() . $ProfilePicture -> getName();
		} else {
			$data['profile_img_path'] = base_url().'img/default_avatar_photo.jpg';
		}

		$data['city'] = $user_info -> getCity();
		$country = $user_info -> getCountry();
		if ($country) {
			$data['country'] = $country -> getCountryName();
			$data['usercountrycode'] = strtolower($country -> getIsoCode());
		} else {
			$data['country'] = '';
		}
		
		$data['agent'] = array(
			"contentLength" => strlen($user_info -> getAgentName()) + strlen($user_info -> getAgentEmail()) + strlen($user_info -> getAgentPhone()),
			"name" => $user_info -> getAgentName(),
			"email" =>  $user_info -> getAgentEmail(),
			"phone" => $user_info -> getAgentPhone()
		);
		$data['manager'] = array(
			"contentLength" => strlen($user_info -> getManagerName()) + strlen($user_info -> getManagerEmail()) + strlen($user_info -> getManagerPhone()), 
			"name" =>  $user_info -> getManagerName(),
			"email" => $user_info -> getManagerEmail(),
			"phone" => $user_info -> getManagerPhone()
		);
		$data['booking'] = array(
			"contentLength" => strlen($user_info -> getBookingName()) + strlen($user_info -> getBookingEmail()) + strlen($user_info -> getBookingPhone()), 
			"name" => $user_info -> getBookingName(),
			"email" =>$user_info -> getBookingEmail(),
			"phone" =>$user_info -> getBookingPhone()
		);
		$data['publisher'] = array(
			"contentLength" => strlen($user_info -> getPublisherName()) + strlen($user_info -> getPublisherEmail()) + strlen($user_info -> getPublisherPhone()),
			"name" =>$user_info -> getPublisherName(),
			"email" => $user_info -> getPublisherEmail(),
			"phone" =>$user_info -> getPublisherPhone()
			);
		$data['recordlabel'] = array(
			"contentLength" => strlen($user_info -> getRecordName()) + strlen($user_info -> getRecordWebsite()), 
			"name"=> $user_info -> getRecordName(),
			"website"=>$user_info -> getRecordWebsite()
		);
		$data['management_title'] = "";
		if ($data['agent']['contentLength'] > 0
			|| $data['manager']['contentLength'] > 0
			|| $data['booking']['contentLength'] > 0
			|| $data['publisher']['contentLength'] > 0
			|| $data['recordlabel']['contentLength'] > 0 
			) {
			$data['management_title'] = "Management";	
		}	
		
		$facebook_link = $user_info -> getFBL();
		$facebook_pattern = '/([\w-\.]+)/';
		if (preg_match($facebook_pattern,$facebook_link,$facebook_matches)) {
			$data['facebook'] = array(
				"link" => 'http://www.facebook.com/'.$facebook_matches[0],
				"name" => $facebook_matches[1]
			);
		}else{
			$data['facebook'] = array(
				"link" => '',
				"name" => ''
			);
		}
		$twitter_link=$user_info -> getTWL();
		$twitter_pattern='/([\w-\.]+)/';
		if(preg_match($twitter_pattern,$twitter_link,$twitter_matches)){
			$data['twitter'] = array(
				"link" => 'http://twitter.com/'.$twitter_matches[0],
				"name" => $twitter_matches[1]
			);
		}else{
			$data['twitter'] = array(
				"link" => '',
				"name" => ''
			);
		}
		$soundcloud_link = $user_info -> getSCL();
		$soundcloud_pattern = '/([\w-\.]+)/';
		if(preg_match($soundcloud_pattern,$soundcloud_link,$soundcloud_matches)){
			$data['soundcloud'] = array(
				"link" => 'http://www.soundcloud.com/'.$soundcloud_matches[0],
				"name" => $soundcloud_matches[1]
			);
		}else{
			$data['soundcloud'] = array(
				"link" => '',
				"name" => ''
			);
		}
		$data['namefirst'] = $user_info -> getFirstName();
		$data['namelast'] = $user_info -> getLastName();
		$data['userid'] = $user_info -> getId();
		$fmsTwitterId = ""; $fmsFacebookId = "";
		
		if($user_info -> getTW() !== null){
			$fmsTwitterId = $user_info -> getTW() -> getTwitterUserId();
		}
		if($user_info -> getFB() !== null){
			$fmsFacebookId = $user_info -> getFB() -> getFacebookUserId();
		}		
		
		// # of Twitter Followers 
		// Pankaj K., Sept 12, 2013
		if($fmsTwitterId !== ""){
			$twitterUrl = "http://api.twittercounter.com/?apikey=" . TWITTER_COUNTER_API_KEY . "&twitter_id=" . $fmsTwitterId;
			$twitterCurl = curl_init();
			curl_setopt($twitterCurl, CURLOPT_URL, $twitterUrl);
			curl_setopt($twitterCurl, CURLOPT_HEADER, 0);
			curl_setopt($twitterCurl, CURLOPT_RETURNTRANSFER, true);
			$resultTW = curl_exec($twitterCurl);
			$objTW = json_decode($resultTW);
			curl_close($twitterCurl);
			
			if(isset($objTW -> followers_current)){
				if(intval($objTW->followers_current)>1000){
					$followers = round(intval($objTW->followers_current)/1000, 1) ;
					$data['followers'] = $followers . "K";
				}else{
					$data['followers'] = intval($objTW->followers_current);
				}
			}else{
				$data['followers'] = 0;
			}
		}else{
			$data['followers'] = 0;
		}
		
		
		// # of Facebook Likes
		// Pankaj K., Sept 12, 2013
		if($fmsFacebookId !== ""){
			$facebookUrl = "http://graph.facebook.com/" . $fmsFacebookId;
			$facebookCurl = curl_init();
			curl_setopt($facebookCurl, CURLOPT_URL, $facebookUrl);
			curl_setopt($facebookCurl, CURLOPT_HEADER, 0);
			curl_setopt($facebookCurl, CURLOPT_RETURNTRANSFER, true);
			$resultFB = curl_exec($facebookCurl);
			$objFB = json_decode($resultFB);
			curl_close($facebookCurl);
	 		
			if(isset($objFB -> likes)){
				if(intval($objFB -> likes)>1000){
					$followers = round(intval($objFB -> likes)/1000, 1);
					$data['likes'] = $followers . "K";
				}else{
					$data['likes'] = intval($objFB -> likes);
				}
			}else{
				$data['likes'] = 0;
			}
		}else{
			$data['likes'] = 0;
		}
		
		$music_url = $user_info -> getFiles();
		$iter = 0;
		foreach ($music_url as $row) {
			$music_link[$iter] = base_url() . $row -> getPath() . $row -> getName();
		}

		$user_file = $user_info -> getFiles();
		$iter = 0;

		//What the heck is this for.
		
		// Actually subtype represents the spotlight number, 
		// I came to learn about it from the database design.
		// Pankaj K.
		foreach ($user_file as $row) {
			$type = $row -> getType();
			if ($type == 0) {
				$subtype = $row -> getSubtype();
				$spotlightPath = base_url() . $row -> getPath() . $row -> getName();
			    if(file_exists($row -> getPath() . $row -> getName())){ 
					$m = new mp3file($row -> getPath() . $row -> getName());
					$a = $m->get_metadata();
					 if(isset($a['Length mm:ss'])){ 	
				$spotlight[$subtype]['length'] = $a['Length mm:ss']; // an additional column is needed into the db table
					 }else{
							$spotlight[$subtype]['length'] = "--:--";
						}
				}else{
					$spotlight[$subtype]['length'] = "--:--"; // an additional column is needed into the db table
				}
				
				$spotlight[$subtype]['title'] = $row -> getName();				
				$spotlight[$subtype]['link'] = $spotlightPath;
			}
		}
		
		if (isset($spotlight) && $spotlight) {
			$data['spotlight'] = $spotlight;
		} else {
			$data['spotlight'] = array();
		}
		
		return $data;
	}

	/**
	 * This function returns list of user's tour plans
	 *
	 * @deprecated removed from MVP
	 */
	private function getTourDates() {
		$data['tours'] = array(
			array(
				"day" => "25",
				"month" => "June",
				"week_day" => "Tuesday",
				"location" => "02 Arena",
				"city" => "London",
				"info" => "Glen Hansard and Scottish Symphony Orchestra Nico Muhly and bedroom community"
			),
			array(
				"day" => "25",
				"month" => "June",
				"week_day" => "Tuesday",
				"location" => "02 Arena",
				"city" => "London",
				"info" => "Glen Hansard and Scottish Symphony Orchestra Nico Muhly and bedroom community"
			),
			array(
				"day" => "25",
				"month" => "June",
				"week_day" => "Tuesday",
				"location" => "02 Arena",
				"city" => "London",
				"info" => "Glen Hansard and Scottish Symphony Orchestra Nico Muhly and bedroom community"
			)
		);
		return $data;
	}
}
