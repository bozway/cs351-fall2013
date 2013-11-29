<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Profile extends Authenticated_service {
	private $targetProjectId;
	public function __construct() {
		parent::__construct();
		$this -> load -> helper('html');
		$this -> load -> helper('url');
		$this -> load -> model('docModels/fms_user_model');
		$this -> load -> model('docModels/fms_project_model');
		$this -> load -> model('docModels/fms_project_file_model');
	}

	/**
	 * @author Hao Cai
	 * This function creates the general page structure
	 */

	public function index($id = 0) {
		
		$data['css_ref'] = array(
			//"dnn506yrbagrg.cloudfront.net/pages/scripts/0016/9661.js?382376",
			"css/projects/profile.css",
            "css/boostrap-responsive.min.css",
		);
		$data['extrascripts'] = array("js/projects/profile.js");
		if ($id == 0) {
			redirect('fms_404');
			$this->output->set_status_header('404');
			return;
		}
		$this -> targetProjectId = $id;

		$data['project'] = $this -> fms_project_model -> getEntityById($this -> targetProjectId);
		//$datau=$this->fms_user_model->getUserById($this->targetProjectId);
		$project = $data['project'];
		if (!$project || !($project->getStatus() == Fms_project_model::RECRUITING || $project->getStatus() == Fms_project_model::ACTIVE || $project->getStatus() == Fms_project_model::COMPLETED)) {
			redirect('fms_404');
			$this->output->set_status_header('404');
			return;
		}
		$owner = $project -> getOwner();
		//获取项目拥有者实体
		$photoPath = $project -> getPhoto();
		//得项目照片实体
		
		$city = $project -> getCity();
		$country = $project -> getCountry();
		
		// Wei: Change location displaying
		$location = array();
		if($project->getCity())
			$location[] = $project->getCity();
		if($project->getState())
			$location[] = $project->getState()->getAbbreviatedName();
		if($project->getCountry())
			$location[] = $project->getCountry()->getIsoCode();
		if(count($location)) {
			$data['project_location'] = implode(', ', $location);
		} else {
			$data['project_location'] = "";
		}

		//project tags
		$tags = $project -> getTags();
		$data['project_creator'] = $owner;
		$data['project_cover'] = $photoPath;
		$data['title'] = $project -> getName();
		$data['loggedInUser'] = $this->userId;

		//project preview video
		$VideoPreview = "";
		if($project->getVideoPreview() != null && $project->getVideoPreview() != ''){
			$VideoPreview = $project->getVideoPreview();
		}
		$data['VideoPreview'] = $VideoPreview;
		
		
		// Spotlight audio with ranking = 1
		$data['project_spotlight_audio'] = false;
		$spotlights = $this->fms_project_file_model->getAudioFiles($project);
		foreach($spotlights as $row) {
			if($row->getSubType() == 1) {
				$data['project_spotlight_audio'] = base_url($row->getPath() . $row->getName() );
			}
		}
		

		if ($tags) {
			$data['project_tags'] = $tags;
		} else {
			$data['project_tags'] = null;
		}

		$duration= (int)($project->getDuration());		
		$startdate=$project->getStartDate();
		if($startdate){
		$data['project_start'] = $startdate->format('d F Y');
		$enddate = date_add($startdate, new DateInterval("P".$duration."M"));//加了持续时间等于结束时间
		$data['project_end'] =$enddate->format('Y-m-d');
		}else{
			$data['project_start'] ="";
		    $data['project_end'] ="";

		}

		$project_kills = $project -> getSkills();
		//取得当前项目的所有技能记录
		$project_needs = array();

		foreach ($project_kills as $project_kill) {
			$kill_isOpen = $project_kill -> getIsOpen();
			if (!$kill_isOpen) {
				continue;
			}

			$kill_name=$project_kill->getSkill()->getName();
			$kill_categoryid="";
				if($project_kill->getSkill()->getCategory()){
					$kill_categoryid=$project_kill->getSkill()->getCategory()->getId();
					}
			$skill_genres=$project_kill->getGenres();
			$skill_influences=$project_kill->getInfluences();
			$skill_cover=$project_kill->getSkill()->getIconPath();
			$skill_description=$project_kill->getDescription();
			$genre_str="";
			$influence_str="";			
			foreach($skill_genres as $skill_genre){
				$genre_str .=$skill_genre->getName().", ";				
			}
			foreach($skill_influences as $skill_influence){
				$influence_str .=$skill_influence->getName().", ";				
			}
			
			$trimmed_genres = rtrim($genre_str, ", ");
			$trimmed_influences = rtrim($influence_str, ", "); 
			
			 $project_need = array(
			    'skill_cover'=> base_url($skill_cover),
				'skill' => $kill_name,
				'genres' => (strlen($trimmed_genres) > 0) ? $trimmed_genres : "None specified.",
				'influence' => (strlen($trimmed_influences) > 0) ? $trimmed_influences : "None specified.",
				'skill_description'=> (strlen($skill_description) > 0) ? $skill_description : "No special requirements.",
				'kill_categoryid'=>$kill_categoryid
				);
			array_push($project_needs,$project_need);
		}
		
		$data['project_needs'] =$project_needs;				
		
		
		$memberInfo=array();
		$members=$project->getMembers();
		$is_projectMember = false; //check if curent user is one of the project members 
		foreach($members as $member){
			$Cur_member=$member->getUser();
			$user_id=$Cur_member->getId();
			if($user_id == $this->userId){	//this member is current user, set the flag true 
				$is_projectMember = true;
			}
			$user_name=	$Cur_member->getFirstName().' '.$Cur_member->getLastName();
			$skillforproject=$member->getSkillForProject();
			$user_skill='';
			if($skillforproject){
				foreach($skillforproject as $row)
					$user_skill .= ($row->getSkill()->getName().',');
			}

			if ($Cur_member -> getProfilePicture()) {
				$userPhoto = $Cur_member -> getProfilePicture() -> getPath() . $Cur_member -> getProfilePicture() -> getName();
			} else {
				$userPhoto = '/img/default_avatar_photo.jpg';
			}
			$user_Joined_date = $member -> getCreationTime() -> format('Y-m-d');

			$single_mmember = array(
				'name' => $user_name,
				'userid' => $user_id,
				'photo' => site_url($userPhoto),
				'page' => site_url('/users/profile?id=' . $user_id),
				'joined_date' => $user_Joined_date,
				'skills' => rtrim($user_skill,",")
			);
			array_push($memberInfo, $single_mmember);
		}

		$data['project_members'] = $memberInfo;
		$data['is_projectMember'] = $is_projectMember;
		$data['fb_share_link'] = $this->getFacebookShareLink_project($id, "", 'linkFB', '', FALSE);
		
		$auditions = $project->getAuditions();
		$is_applicant = false;
		foreach($auditions as $audition){
			if($audition->getApplicant()->getId() == $this->userId){
				$is_applicant = true;
			}
		}
		$data['is_applicant'] = $is_applicant;

		$this -> load -> view('view_header', $data);
		$this -> load -> view('view_project_profile/view_project', $data);
		$this -> load -> view('view_footer', $data);

	}

}
?>
