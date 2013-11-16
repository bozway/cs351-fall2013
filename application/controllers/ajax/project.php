<?php

/**
 * 
 * Project controller contains bunch of AJAX handlers that are
 * related to update user projects editing.
 * 
 * <b>Notice:</b><br/> 
 * <ul>
 *      <li>Most of the function require checking login, and checking permission.</li>
 *      <li>Do remember to update the description and author when you work on the functions.</li>
 * </ul>
 * 
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Project extends Authenticated_service {

    public $response = array();
    public $userId;
    
    const ERR_CODE_NO_ERROR 			=	0;
    
    const ERR_CODE_FAILED_VALIDATION	=	3; 
    const ERROR_PERMISSION 				= 	200;
    
    // Data limits
    const LIMIT_MAX_PROJECT_SKILLS		=	15;
    const LIMIT_MAX_GENRE_TAGS			=	5;
    const LIMIT_MAX_INFLUENCE_TAGS		=	5;
    const LIMIT_MAX_DESCRIPTION_CHARS	=	255; // This limit corresponds to db varchar limit
    const LIMIT_MAX_INFLUENCE_CHARS		=	255; // This limit corresponds to db varchar limit
    const LIMIT_MIN_OWNER_SKILLS		=	1;
    const LIMIT_MIN_TEAM_SKILLS			=	1;

    public function __construct() {
        parent::__construct();

        $this->load->model("docModels/fms_project_model");
        $this->load->model("docModels/fms_general_model");
        $this->load->model("docModels/fms_genre_model");
        $this->load->model("docModels/fms_influence_model");
        $this->load->model("docModels/fms_project_member_model");
        $this->load->model("docModels/fms_country_model");
        $this->load->model("docModels/fms_project_skill_model");
        $this->load->model("docModels/fms_skill_model");
        $this->load->model("docModels/fms_user_model");
        $this->load->model("docModels/fms_audition_model");
        $this->load->model("docModels/fms_project_file_model");
		$this->load->model("docModels/fms_language_model");
		$this->load->model("docModels/fms_us_state_model");

        $params = array(
            'em' => $this->doctrine->em
        );
        $this->load->library('entityserializer', $params);
    }

    /**
     * This function handles AJAX call from front-end and deletes a member from
     * a project. $POST data should be member_id and project_id. The owner of
     * the project
     * and the user him/herself are eligible to conduct this.
     *
     * @author
     * @access public
     */
    public function deleteProjectMember() {
        $member_id = $this->input->post('member_id');
        $project_id = $this->input->post('project_id');

        // Check Permission
        $project = $this->fms_project_model->getEntityById($project_id);
        $project_member = $this->fms_project_member_model->getEntityById($member_id);
        if (!$project) {
            $responseObj = array(
                'errorcode' => 2,
                'message' => 'Project Not Found'
            );
            $this->encodeJSON($responseObj);
            return;
        }
        if (!$project_member) {
            $responseObj = array(
                'errorcode' => 2,
                'message' => 'Project Member Not Found'
            );
            $this->encodeJSON($responseObj);
            return;
        }		
        if ($this->userId != $project->getOwner()->getId() && $this->userId != $project_member->getUser()->getId()) {
            $responseObj = array(
                'errorcode' => 3,
                'message' => 'You do not have permission to remove this member.'
            );
            $this->encodeJSON($responseObj);
            return;
        }

        // Check if project is completed
        if ($project->getStatus() != Fms_project_model::ACTIVE && $project->getStatus()!= Fms_project_model::RECRUITING) {
            $responseObj = array(
                'errorcode' => 4,
                'message' => 'Project is not currently active, no further modification is not allowed.'
            );
            $this->encodeJSON($responseObj);
            return;
        }



        $project_member->setRole(Fms_project_member_model::PAST_MEMBER);
        $project -> setLastEditTime( new DateTime() );
        /* if a project member has multiple skills on this project, then this
         * will break, because we are currently only grabbing the first 
         */ 
        $proj_skills = $project_member->getSkillForProject();
        //var_dump($past_member_proj_skill[0]);


		// making the skill(s) open, once the member is deleted
		// Pankaj K., Sept 04, 2013
		$memberSkills = $project_member->getUser()->getSkills();

	    $skillNames = "";
		foreach($proj_skills as $memberSkill){
			$skillNames = $skillNames.$memberSkill->getSkill()->getName()."|";
			$memberSkill->setIsOpen(1);
			$memberSkill->setPerformer();
		}
		$skillNames = substr($skillNames,0,-1);
		$project_member->setPastParticipantSkills($skillNames);
		
		$this->fms_general_model->flush();
        $responseObj = array(
            'errorcode' => 0,
            'message' => 'member has been deleted'
        );
        $this->encodeJSON($responseObj);
        return;
            
    }

    /**
     * NOT COMPLETED
     * 
     * This function handles AJAX call from front-end to leave the project.
     *
     * @author Hao Cai
     * @access public
     */
    public function leaveProject() {
        $user = $this->fms_user_model->getEntityById($this->userId);
        $projectId = $this->input->post('projectid');
        $project = $this->fms_project_model->getEntityById($projectId);
        foreach ($project->getMembers() as $member) {
            
        }
    }

    /**
     * This function handles AJAX call from front-end to change project status.
     * Status includes unpublished/active/complete. (Need confirm: Recruiting
     * status is not set by the user but determined by whether there is any 
     * opening in this project.) Only the owner of the project is eligible to 
     * do this. $_POST data should be project_id with status_code.
     *
     * @author Pankaj K.
     * @access public
     */
    public function updateProjectStatus() {

        // NEED TO SANITIZE AND VALIDATE THE DATA
        $project_id = $this->input->post('project_id');
        $project = $this->fms_project_model->getEntityById($project_id);
        
        // Wei: Donnot trust front-end, check current status from Backend
        //$projectCurrentStatus = $this->input->post('project_status');
        $projectCurrentStatus = $project->getStatus();
        $postedStatus = $this->input->post('project_status');
        
        // Sanity check: Status should match
        //		This is to prevent user click submit button twice
        if($postedStatus === 'UNSAVED' && $projectCurrentStatus != Fms_project_model::UNSAVED) {
        	$responseObj = array('errorcode'=>1);
        	$this->encodeJSON($responseObj);
        	return;
        } 
        if($postedStatus === 'UNPUBLISHED' && $projectCurrentStatus != Fms_project_model::UNSAVED) {
        	$responseObj = array('errorcode'=>1);
        	$this->encodeJSON($responseObj);
        	return;
        }
        if($postedStatus === 'RECRUITING' && $projectCurrentStatus != Fms_project_model::RECRUITING) {
        	$responseObj = array('errorcode'=>1);
        	$this->encodeJSON($responseObj);
        	return;
        }
        if($postedStatus === 'COMPLETE' && $projectCurrentStatus != Fms_project_model::COMPLETE) {
        	$responseObj = array('errorcode'=>1);
        	$this->encodeJSON($responseObj);
        	return;
        }

        // Check Permission
        if (!$project) {
            $responseObj = array(
                'errorcode' => 2,
                'message' => 'Project Not Found',
                'project_id' => $project_id
            );
            $this->encodeJSON($responseObj);
            return;
        }
        if ($this->userId != $project->getOwner()->getId()) {
            $responseObj = array(
                'errorcode' => 3,
                'message' => 'You do not have permission to remove this member.'
            );
            $this->encodeJSON($responseObj);
            return;
        }

        // Action
        $skillsOpenFlag = 0;
        $projectSkills = $project->getSkills();

        if ($projectCurrentStatus == Fms_project_model::UNPUBLISHED) {
            foreach ($projectSkills as $projectSkill) {
                if ($projectSkill->getIsOpen()) {
                    $skillsOpenFlag++;
                    break;
                }
            }

            if ($skillsOpenFlag === 1) {
                $project->setStatus(Fms_project_model::RECRUITING);
                $responseObj = array('errorcode'=>0);
	        	$this->encodeJSON($responseObj);
            } else {
                $project->setStatus(Fms_project_model::ACTIVE);
                $responseObj = array('errorcode'=>0);
	        	$this->encodeJSON($responseObj);
            }
        } else {
            if ($projectCurrentStatus == Fms_project_model::RECRUITING || $projectCurrentStatus == Fms_project_model::ACTIVE) {
                $project->setStatus(Fms_project_model::COMPLETED);
                $responseObj = array('errorcode'=>0);
	        	$this->encodeJSON($responseObj);
            }
        }
		
		$project->setLastEditTime( new DateTime() );
		$this->fms_general_model->flush();
    }

    /**
     *
     * This function handles AJAX call from front-end to delete a project. Only
     * the owner of the project is eligible of doing this. $_POST should be project_id.
	 * If the flag is passed as true, the project will be really deleted, otherwise it
	 * will be set status as "inactive"
     *
     * @author Hao Cai
     * @access public
     */
    public function deleteProject($flag = false) {
        if($projectId = $this->input->post('project_id')){
	        $project = $this->fms_project_model->getEntityById($projectId);
			//check permission
			if($project->getOwner()->getId() != $this->userId){
        		$response = array(
					'errorcode' => 1,
					'message' => 'You do not have permission to delete this project.'
				);
	            $this->encodeJSON($response);
	            return;
			}
			//really delete
	        if($flag){
	        	//check if the project is unpublished or unsaved
	        	if($project->getStatus() != Fms_project_model::UNPUBLISHED && $project->getStatus() != Fms_project_model::UNSAVED){
	        		$response = array(
						'errorcode' => 2,
						'message' => 'This project can not be deleted since it is published.'
					);
		            $this->encodeJSON($response);
		            return;
	        	}
				
				/**
				 * if there is/ are any member in the project apart from the owner, 
				 * project can't be deleted
				 * 
				 * Pankaj K.
				 */ 
				$projectMembers = $project->getMembers();
				$countCurrentMembers = 0;
				foreach($projectMembers as $projectMember){
					if($projectMember->getRole() != Fms_project_member_model::PAST_MEMBER){
						$countCurrentMembers++;	
					}
				}
				
				if($countCurrentMembers > 1){
					$response = array(
						'errorcode' => 3,
						'message' => 'This project can\'t be deleted, as there are still ' . $countCurrentMembers . ' members associated with it.'
					);
					$this->encodeJSON($response);
					return;
				}
				
	        	//delete all projectSkills
				$this->fms_project_skill_model->deleteAllProjectSkills($project);
				//delete all projectFiles
				$this->fms_project_file_model->deleteAllFilesByProject($project);
				//delete owner
				$user = $this->fms_user_model->getEntityById($this->userId);
				$this->fms_project_member_model->deleteEntity($user, $project);
				//delete project
				$this->fms_project_model->deleteProject($project);
	        }
			//set it as INACTIVE
			else{
				$project->setStatus(Fms_project_model::INACTIVE);
				$this->fms_general_model->flush();
			}
    		$response = array(
				'errorcode' => Project::ERR_CODE_NO_ERROR,
				'message' => 'Project has been deleted.'
			);
            $this->encodeJSON($response);
        }
    }
	
    /**
     * This function handles AJAX call from front-end to update the following
     * info of a project:
     * <ul>
     *      <li>Name</li>
     *      <li>Country</li>
     *      <li>City</li>
     *      <li>Start Date</li>
     *      <li>Duration</li>
     *      <li>Listing Length</li>
     *      <li>Language</li>
     *      <li>Video URL</li>
     *      <li>Image URL</li>
     *      <li>Tags</li>
     *      <li>Description</li>
     * </ul>
     * 
     * The project_id should be pasted together with the data listed above.
     *
     * @author Leo
     * @access public
     */
    public function updateProjectDetail() {
        // Leo : Date Country and Tags need wait for other works;
        $project = $this->fms_project_model->getEntityById($this->cleanPost['projectId']);
		
		$response = array(
			'errorcode' => 0,
			'message' => 'Success'
		);
		
		if($project->getOwner()->getId() != $this->userId){
			$response = array(
				'errorcode' => 2,
				'message' => 'You do not have permission to edit this project.'
			);
            $this->encodeJSON($response);
            return;
		}
		
		//Changes made by Pankaj K., Oct 14 2013
		$projectName = $this->input->post('name');
			
		$projectName = str_replace("'", "&apos;", $projectName);
		$projectName = str_replace("\"", "&quot;", $projectName);
		
        $project->setName($projectName);
        $country = $this->fms_country_model->getEntityByName($this->cleanPost['country']);

        if ($country) {
            $project->setCountry($country);
        }
		
		// Validate and update city
		$city = $this->input->post('city');
		if ($city) {
			if (preg_match( '/^[a-zA-Z ]+$/', $city, $matches ) && strlen( $city ) <= 20) {
				$project->setCity($city);
			}
		}
        
		
		// Validate and update Language
		//	Notice that if the posted language name is incorrect, it will just suppress and bypass
		$languageEntity = $this->fms_language_model->getEntityByName($this->input->post('language'));
		if($languageEntity)
        	$project->setLanguage($languageEntity);
		if($this->input->post('language') == "Language") {
			$project->setLanguage(null);
		}
		
		// Validate and update State
		//	Notice that if the posted State name is incorrect, it will just suppress and bypass
		$stateEntity = $this->fms_us_state_model->getEntityByName($this->input->post('state'));
		if($stateEntity)
			$project->setState($stateEntity);
		if($this->input->post('state') == "State")
			$project->setState(null);
		
        //$project->setListLength($this->cleanPost['listLength']);
        $project->setDuration($this->input->post('duration'));
		
		$project->setTags(array());	
        if($this->input->post('tags')){
    		$tags = $this->input->post('tags');
			if(count($tags) > 10){
				$response = array(
					'errorcode' => 3,
					'message' => "You can't add more than 10 tags."
				);
				$this->encodeJSON($response);
				return;
			}
			foreach($tags as $tag){
				if((strlen($tag)-4) > 30){
					$response = array(
						'errorcode' => 4,
						'message' => "You can't add a tag with more than 30 characters."
					);
					$this->encodeJSON($response);
					return;
				}
			}
			$project->setTags($tags);	
        }
        
        if($this->input->post('description')){        	
			$projectDescription = $this->input->post('description');
			
			$projectDescription = str_replace("\\x11", "\"", $projectDescription);
			$projectDescription = str_replace("\\x22", "=", $projectDescription);
			$projectDescription = str_replace("\\x33", ":", $projectDescription);
			// $projectDescription = str_replace("\\x44", ";", $projectDescription);
			
			// Wei: WHO THE HELL HAS ECHOED THIS ONE?!!!!!!
			// echo $projectDescription;
			$projectDescription = rtrim(ltrim($projectDescription));
			
			$description_num = mb_strlen($projectDescription, 'utf-8');
			// we need less than the max, but greater than 0, && $description_num is the 
			// fastest way to test if it is nonnegative :: Waylan
			if($description_num <= 2000 && $description_num){
				if($projectDescription !== "Enter something ..."
					&& $projectDescription !== "<br>"){
					//var_dump($projectDescription);
					$project->setDescription($projectDescription);	
				}/** 
				else {
					$response['errorcode'] = self::ERR_CODE_FAILED_VALIDATION;
					$response['message'] = "There is no description to save.";					
				}**/
			} /**else {
				// Waylan :: currently if it doesn't meet the min/max length requirements
				// we will just ignore it. Later, we should make it output an error code
				// and have the front end notify the user of the error. 
			}**/
        }
        if($this->input->post('videoPreview') !== FALSE) {
        	$project->setVideoPreview($this->input->post('videoPreview'));
        }
        $project->setShowAudioPreview($this->cleanPost['showAudioPreview']);
        //$project->setShowTags($this->cleanPost['showTags']);
        $project->setShowCountry($this->cleanPost['showCountry']);
        $project->setShowCity($this->cleanPost['showCity']);
        //$project->setShowStartDate($this->cleanPost['showStartDate']);
        $project->setShowDuration($this->cleanPost['showDuration']);
        //$project->setShowListLength($this->cleanPost['showListLength']);
        //$project->setShowLanguage($this->cleanPost['showLanguage']);
        $project->setShowVideoPreview($this->cleanPost['showVideoPreview']);
        $project->setShowDescription($this->cleanPost['showDescription']);
        $project->setIsSave(true);
		
		// update start date (without validation)
		$startDate = $this->input->post('startDate');
		if($startDate) {
			// Save as UTC
			$startDateTime = new DateTime(date('Y-m-d', time($startDate)));
			//$startDateTime->setTimezone(new DateTimeZone('UTC'));
			$project->setStartDate($startDateTime);
		}

        // update audio ranking		
        if (isset($this->cleanPost['audioranking'])) {
            $ranking = $this->cleanPost['audioranking'];
            $iter = 1;
            foreach ($ranking as $row) {
                $audio = $this->fms_project_file_model->getEntityById($row);
                $audio->setSubtype($iter);
                $iter++;
            }
        }
		
        // update project status (If project is unsaved before)
        if($project->getStatus() == Fms_project_model::UNSAVED) {
        	$project->setStatus(Fms_project_model::UNPUBLISHED);
        }
        
		$project->setLastEditTime( new DateTime() );
		
        // Flush and respond
        $this->fms_general_model->flush();		
        
		$this->encodeJSON($response);
        
    }

    /**
     * This function handles AJAX call from front-end to update project skills.
     * The behavior is similar as updateUserSkills(). My Idea is remove all
     * existing skills and add the new data posted.
     * 
     * Project status validation written by Wei Zong.
     * Data validation written by Waylan Wong. NOTE: due to current order of 
     * operation, if there data validation fails at any time, there is no 
     * way recover the open skills that were deleted. We need to do validation 
     * before any db operation, and for that to happen, there is no way around
     * looping through the data more than once.
     *
     * @author Leo Zhu
     * @author Waylan Wong
     * @author Wei Zong
     * @access public
     */
    public function createProjectSkills() {
    	//$this->debug("createProjectSkills", "This is the project ID: " . $this->cleanPost['projectId']);    	
        $project = $this->fms_project_model->getEntityById($this->cleanPost['projectId']);
        $error_message = '';
        
        // Wei: Sanity Check - Project status should be UNPUBLISHED
        if($project->getStatus() == Fms_project_model::UNSAVED) {
        	$responseObj = array(
        			'errorcode'=>1, 
        			'message'=>'Hey, you cannot access this function if your project status is UNSAVED'
        	);
        	$this->encodeJSON($responseObj);
        	return;
        }
        if($project->getStatus() != Fms_project_model::UNPUBLISHED) {
        	$responseObj = array(
        			'errorcode'=>2,
        			'message'=>'Your project is already published.'
        	);
        	$this->encodeJSON($responseObj);
        	return;
        }
        
        // Wei - Check permission
        $user = $this->fms_user_model->getEntityById($this->userId);
        if(!$user) {
        	$responseObj = array(
        			'errorcode'=>2,
        			'message'=>'You should login first'
        	);
        	$this->encodeJSON($responseObj);
        	return;
        }
        if($user->getId() != $project->getOwner()->getId()) {
        	$responseObj = array(
        			'errorcode'=>2,
        			'message'=>'Permission Denied'
        	);
        	$this->encodeJSON($responseObj);
        	return;
        }
		
		// DATA VALIDATION TRACKERS        
        $count_team_skills = 0;
        $count_owner_skills = 0;
        $bool_failed_validation = false;
        
        // Action
        // Modified: owner member should be created when you create project
        //$ownerMember = $this->fms_project_member_model->createEntity($user, $project, 0);
        //$project->addMember($ownerMember);
        $ownerMember = $this->fms_project_member_model->getOwnerMember($project);
        $projectSkills = array();
        if (isset($this->cleanPost['projectSkills'])) {
            $projectSkills = $this->cleanPost['projectSkills'];
            
            // DATA VALIDATION - check if we are within conditions for project skills            
            if (count($projectSkills) > self::LIMIT_MAX_PROJECT_SKILLS 
            	|| count($projectSkills) < (self::LIMIT_MIN_TEAM_SKILLS + self::LIMIT_MIN_OWNER_SKILLS)) {
            	$error_message = "You have an unusual number of skills.";
            	$bool_failed_validation = true;
				$responseObj = array(
            			'errorcode'=>self::ERR_CODE_FAILED_VALIDATION,
            			'message'=> $error_message
            	);
            	$this->encodeJSON($responseObj);
				return;            	
            }            
            
            $index = 0;
            $count_genre = 0;
            $count_influence = 0;
            //$this->debug("createProjectSkills", "Failed validation before while loop? " . intVal($bool_failed_validation));
            while (!$bool_failed_validation) {
            	$projectSkill = $projectSkills[$index][0];
            	if ($projectSkill['ownerskill'] == "true") {
            		$count_owner_skills++;
            		//$this->debug("createProjectSkills", "owner skills count: " . $count_owner_skills);
            	} else {
            		$count_team_skills++;
            		//$this->debug("createProjectSkills", "team skills count: " . $count_team_skills);            		
            	}
            	if (isset($projectSkill['skilldesc'])) {
            		if (strlen($projectSkill['skilldesc']) > self::LIMIT_MAX_DESCRIPTION_CHARS) {
            			$error_message = "Please keep your skill descriptions short!";
            			$bool_failed_validation = true;
            		}            		
            	}
            	if (isset($projectSkill['genre'])) {
            		if (count($projectSkill['genre']) > self::LIMIT_MAX_GENRE_TAGS) {
            			$error_message = "You have too many genre tags.";
            			$bool_failed_validation = true;
            		}	
            	}          	
            	if (isset($projectSkill['influences'])) {
            		if (count($projectSkill['influences']) > self::LIMIT_MAX_INFLUENCE_TAGS) {
            			$error_message = "You have too many influence tags.";
            			$bool_failed_validation = true;
            		}            		
            	}            	             	
            	++$index;
            	if ($index >= count($projectSkills)) {            		
            		break;
            	}
            }            
            
            // Now that we have checked some general limits on the skills, check the aggregate counts
            if ($count_team_skills < self::LIMIT_MIN_TEAM_SKILLS 
            	|| $count_owner_skills < self::LIMIT_MIN_OWNER_SKILLS) {
            	$error_message = "You do not have enough skills for this project!";
            	//echo $count_team_skills . " TEAM::OWNER " . $count_owner_skills;
            	$bool_failed_validation = true;            	
            }
            
            // If we failed any condition, exit and notify the front end.
            if ($bool_failed_validation) {
            	$responseObj = array(
            			'errorcode'=>self::ERR_CODE_FAILED_VALIDATION,
            			'message'=> $error_message
            	);
				
            	$this->encodeJSON($responseObj);
            	return;
            }            
        }
		
        
        /*
         * Now process each skill and inspect it in detail to see if they 
         * are valid ID's and save it to the DB.
         */
        foreach ($projectSkills as $item) {

            $projectSkill = $item[0];
            $skill = $this->fms_skill_model->getEntityById($projectSkill['skillid']);           
            
            if ($skill === NULL) {
            	if ($projectSkill['ownerskill'] == 'true') {            		
            		$count_owner_skills--;
            		//$this->debug("createProjectSkills", "Got a bad skill ID, now owner skill qty is: " . $count_owner_skills);
            	} else {
            		$count_team_skills--;
            		//$this->debug("createProjectSkills", "Got a bad skill ID, now owner skill qty is: " . $count_team_skills);
            	}       
            	// Now that we have checked some general limits on the skills, check the aggregate counts
            	if ($count_team_skills < self::LIMIT_MIN_TEAM_SKILLS
            		|| $count_owner_skills < self::LIMIT_MIN_OWNER_SKILLS) {
            		$error_message = "You have some invalid skill ID's, please remove and re-add your skills.";
            		$bool_failed_validation = true;            		
            		break;
            	}            	
            	// Skip further processing of this entry because this is an invalid skill ID.     	
            	continue;
            }
            $projectSkillEn = $this->fms_project_skill_model->createEntity($project, $skill, $projectSkill['skilldesc']);

            if (isset($projectSkill['genre'])) {
                foreach ($projectSkill['genre'] as $genreId) {
                    $genre = $this->fms_genre_model->getEntityById($genreId);
                    if (!isset($genre)) {
                    	//$this->debug("createProjectSkills", "Got a bad genre ID, ignoring it.");
                    	continue;
                    } else {
                    	$projectSkillEn->addGenre($genre);
                    }
                }
            }
            if (isset($projectSkill['influences'])) {
                foreach ($projectSkill['influences'] as $influenceName) {
                	if (strlen($influenceName) <= self::LIMIT_MAX_INFLUENCE_CHARS){
                		$influence = $this->fms_influence_model->getEntityByName($influenceName);
                		$projectSkillEn->addInfluence($influence);
                	} else {
                		//$this->debug("createProjectSkills", "Influence has too many characters: [". $influenceName ."]");
                		continue;
                	}                    
                }
            }
            if ($projectSkill['ownerskill'] == 'true') {
                $projectSkillEn->setPerformer($ownerMember);
                $projectSkillEn->setIsOpen(false);
            }
            $project->addSkill($projectSkillEn);
        }
		
		// Update last edit time
		$project->setLastEditTime( new DateTime() );
		
		// Update project status
        $project->setStatus(Fms_project_model::ACTIVE);
        if ($count_team_skills > 0) {
            $project->setStatus(Fms_project_model::RECRUITING);
        }
        $this->fms_general_model->flush();        
        
        // If we failed any condition, exit and notify the front end.
        if ($bool_failed_validation) {
        	$responseObj = array(
        			'errorcode'=>self::ERR_CODE_FAILED_VALIDATION,
        			'message'=>$error_message
        	);
        	$this->encodeJSON($responseObj);
        	return;
        }
        
        $responseObj = array('errorcode'=>0, 'message'=>'success');
        $this->encodeJSON($responseObj);
    }

    /**
     * This function is used to get the following information of the skills 
     * (in the form of an array) related to a project for a given project id:
     * 
     * <ul>
     *      <li>Owner Skill</li>
     *      <li>Skill Id</li>
     *      <li>Performer Name</li>
     *      <li>Project Skill Id</li>
     *      <li>If the position for that skill in the project is open</li>
     *      <li>Skill Description</li>
     *      <li>Skill Influence</li>
     *      <li>Skill Genre</li>
     * </ul>
     * 
     * @access public
     */
    public function getProjectSkills() {
        $response = array();
        $projectId = $this->cleanPost['projectId'];
        $project = $this->fms_project_model->getEntityById($projectId);
        $skills = $project->getSkills();

        foreach ($skills as $skill) {
            $tempArray = array();
            $tempArray['ownerskill'] = 0;
            $performer = $skill->getPerformer();
            if ($performer) {
                if ($performer->getRole() == Fms_project_member_model::OWNER) {
                    $tempArray['ownerskill'] = 1;
                }
                $tempArray['performerName'] = $performer->getUser()->getFirstName();
            }
            $tempArray['skillid'] = $skill->getSkill()->getId();
            $tempArray['projectSkillId'] = $skill->getId();
            $tempArray['isOpen'] = $skill->getIsOpen();
            $tempArray['skilldesc'] = $skill->getDescription();
            $genreArray = array();
            $influArray = array();
            foreach ($skill->getGenres() as $genre) {
                $genreArray[] = $genre->getId();
            }
            foreach ($skill->getInfluences() as $influence) {
                $influArray[] = $influence->getName();
            }
            $tempArray['influences'] = $influArray;
            $tempArray['genre'] = $genreArray;
            $response[] = $tempArray;
        }
        $this->encodeJSON($response);
    }

    /**
     *
     * This function handles AJAX call from front-end to update project skills.
     * The behavior is similar as updateUserSkills(). My Idea is remove all
     * existing skills as long as they don't have an active performer, 
     * and add the new data posted.
     * 
     * Data validation by Waylan Wong 
     *
     * @author Leo Zhu
     * @author Waylan Wong
     * @access public
     */
    public function updateProjectSkills() {

        $project = $this->fms_project_model->getEntityById($this->cleanPost['projectId']);

        $ownerMember = $this->fms_project_member_model->getOwnerMember($project);
        
        $bool_failed_validation = false;
        $error_message = '';
        $error_code = 0;
        
        
        $newSkills = array();
        if (isset($this->cleanPost['newSkills'])) {
            $newSkills = $this->cleanPost['newSkills'];
        }
        $deletedSkills = array();
        if (isset($this->cleanPost['deletedSkills'])) {
            $deletedSkills = $this->cleanPost['deletedSkills'];
        }
        $editedSkills = array();
        if (isset($this->cleanPost['editedSkills'])) {
        	$editedSkills = $this->cleanPost['editedSkills'];
        }

        
        /* Waylan :: Eventually we will need to check if the user has enough skills
         * to meet the minimum requirements even after deleting the specified skills.
         * For now, we will assume that the front end has kept track of that,
         * and proceed. Otherwise we would have to juggle multiple counts of skills
         * and execute duplicate loops.
         */
        while(!$bool_failed_validation) {

	        foreach ($deletedSkills as $deleteSkillId) {
	            $projectSkill = $this->fms_project_skill_model->getEntityById($deleteSkillId);
	            if ($projectSkill === NULL) {
	            	$error_message = "One of the deleted skills is invalid. Please refresh and try again.";
	            	$error_code = self::ERR_CODE_FAILED_VALIDATION;
	            	$bool_failed_validation = true;
	            	break;
	            } else {
	            	// TODO WAYLAN LINWEI :: THIS IS OBSOLETE CODE, MUST TAKE INTO ACCOUNT AUDITIONERS - 
	            	// MUST DELETE THE AUDITIONS FIRST BECAUSE THE AUDITIONS HAVE A FK REFERENCE TO THIS SKILL
	            	$auditions = $projectSkill->getAuditions();
					if ($auditions!=null){
						foreach ($auditions as $audition){
							$this->fms_general_model->remove($audition);
						}
					}
	            	$this->fms_general_model->remove($projectSkill);
	            }            
	        }
	       
	        // TODO WAYLAN LINWEI :: THIS IS OBSOLETE CODE, ONLY DELETE SKILLS THAT ARE MARKED FOR DELETION
	        // Delete all skills that don't have anyone assigned to it, 
	        // assuming that front end will give us the data back.
	        // Must take into account open project skills that have auditioners.
	        // Those skills cannot be deleted because of foreign key constraints.
	        /**
	        foreach ($project->getSkills() as $projectSkill) {
	            if ($projectSkill->getIsOpen()) {
	                $this->fms_general_model->remove($projectSkill);
	            }
	        }**/
	        

	        foreach ($editedSkills as $projectSkill) {	
	            //$projectSkill = $item[0];	
	            $projectSkillEn = $this->fms_project_skill_model->getEntityById($projectSkill['projectskillid']);
	            if ($projectSkillEn === NULL) {
	            	$error_message = "One of the new skills is invalid. Please refresh and try again.";
	            	$error_code = self::ERR_CODE_FAILED_VALIDATION;
	            	//$this->debug("updateProjectSkills", "There is no skill with skill ID [". $projectSkill['skillid'] ."]");	            	
	            	$bool_failed_validation = true;	     
	            	break;       	
	            }	
	            //$this->debug("updateProjectSkills", "Going to create a new project skill with skill ID: [". $projectSkill['skillid'] ."]");
	            //$projectSkillEn = $this->fms_project_skill_model->createEntity($project, $skill, $projectSkill['skilldesc']);
	            if (isset($projectSkill['genre'])) {
	            	$projectSkillEn->getGenres()->clear();
	                foreach ($projectSkill['genre'] as $genreId) {
	                    $genre = $this->fms_genre_model->getEntityById($genreId);
	                    if ($genre === NULL) {
	                    	$error_message = "One of the genre ID's is invalid. Please refresh and try again.";
	                    	$error_code = self::ERR_CODE_FAILED_VALIDATION;
	                    	$bool_failed_validation = true;
	                    	break 2;
	                    } else {
	                    	$projectSkillEn->addGenre($genre);
	                    }	                    
	                }
	            }
	            if (isset($projectSkill['influences'])) {
	            	$projectSkillEn->getInfluences()->clear();
	                foreach ($projectSkill['influences'] as $influenceName) {
	                	if (strlen($influenceName) > self::LIMIT_MAX_INFLUENCE_CHARS) {
	                		$error_message = "One of the influences is invalid. Please refresh and try again.";
	                		$error_code = self::ERR_CODE_FAILED_VALIDATION;
	                		$bool_failed_validation = true;
	                		break 2;
	                	} else {
	                		$influence = $this->fms_influence_model->getEntityByName($influenceName);
	                		$projectSkillEn->addInfluence($influence);
	                	}	                    
	                }
	            }
	            // if ($projectSkill['ownerskill'] == 'true') {
	                // $projectSkillEn->setPerformer($ownerMember);
	                // $projectSkillEn->setIsOpen(false);
	            // }
	            // $project->addSkill($projectSkillEn);
	        }	
	        foreach ($newSkills as $item) {	
	            $projectSkill = $item[0];	
	            $skill = $this->fms_skill_model->getEntityById($projectSkill['skillid']);
	            if ($skill === NULL) {
	            	$error_message = "One of the new skills is invalid. Please refresh and try again.";
	            	$error_code = self::ERR_CODE_FAILED_VALIDATION;
	            	//$this->debug("updateProjectSkills", "There is no skill with skill ID [". $projectSkill['skillid'] ."]");	            	
	            	$bool_failed_validation = true;	     
	            	break;       	
	            }	
	            //$this->debug("updateProjectSkills", "Going to create a new project skill with skill ID: [". $projectSkill['skillid'] ."]");
	            $projectSkillEn = $this->fms_project_skill_model->createEntity($project, $skill, $projectSkill['skilldesc']);
	            if (isset($projectSkill['genre'])) {
	                foreach ($projectSkill['genre'] as $genreId) {
	                    $genre = $this->fms_genre_model->getEntityById($genreId);
	                    if ($genre === NULL) {
	                    	$error_message = "One of the genre ID's is invalid. Please refresh and try again.";
	                    	$error_code = self::ERR_CODE_FAILED_VALIDATION;
	                    	$bool_failed_validation = true;
	                    	break 2;
	                    } else {
	                    	$projectSkillEn->addGenre($genre);
	                    }	                    
	                }
	            }
	            if (isset($projectSkill['influences'])) {
	                foreach ($projectSkill['influences'] as $influenceName) {
	                	if (strlen($influenceName) > self::LIMIT_MAX_INFLUENCE_CHARS) {
	                		$error_message = "One of the influences is invalid. Please refresh and try again.";
	                		$error_code = self::ERR_CODE_FAILED_VALIDATION;
	                		$bool_failed_validation = true;
	                		break 2;
	                	} else {
	                		$influence = $this->fms_influence_model->getEntityByName($influenceName);
	                		$projectSkillEn->addInfluence($influence);
	                	}	                    
	                }
	            }
	            if ($projectSkill['ownerskill'] == 'true') {
	                $projectSkillEn->setPerformer($ownerMember);
	                $projectSkillEn->setIsOpen(false);
	            }
	            $project->addSkill($projectSkillEn);
	        }
		        
	        // Wei: Update Project Status (ACTIVE <=> RECRUITING)
	        $skillsOpenFlag = 0;
	        $projectSkills = $project->getSkills();
	        if ($project->getStatus() == Fms_project_model::ACTIVE || $project->getStatus() == Fms_project_model::RECRUITING) {
	        	foreach ($projectSkills as $projectSkill) {
	        		if ($projectSkill->getIsOpen()) {
	        			$skillsOpenFlag++;
	        			break;
	        		}
	        	}	        
	        	if ($skillsOpenFlag === 1) {
	        		$project->setStatus(Fms_project_model::RECRUITING);
	        		$responseObj = array('errorcode'=>0);
	        		$this->encodeJSON($responseObj);
	        	} else {
	        		$project->setStatus(Fms_project_model::ACTIVE);
	        		$responseObj = array('errorcode'=>0);
	        		$this->encodeJSON($responseObj);
	        	}
	        } else {
	        	// Sanity Check: UNSAVED INACTIVE and COMPLETED project cannot access
	        	$curStatus = $project->getStatus();
	        	if($curStatus == Fms_project_model::UNSAVED || $curStatus == Fms_project_model::INACTIVE || $curStatus == Fms_project_model::COMPLETED) {
	        		$responseObj = array(
	        				'errorcode'	=> 1,
	        				'message'	=> 'You cannot update status of this project.',
	        		);
	        		$this->encodeJSON($responseObj);
	        		return;
	        	}
	        	
	        }
	        break;
        }
        if (!$bool_failed_validation) {
        	$error_code = 0;
			$error_message = 'Success';        	
        }
		
		$project->setLastEditTime( new DateTime() );
        $this->fms_general_model->flush();
		
        $responseObj = array(
        		'errorcode'	=> $error_code,
        		'message'	=> $error_message,
        );
        $this->encodeJSON($responseObj);
    }


    /**
     * NOT YET IMPLEMENTED
     * 
     * This function handles AJAX call from front-end to update the owner role.
     * It could actually be merged with updateProjectSkills() since they are
     * invoked one after the other when saving the project skill form. This 
     * function actually bind the owner with a skill of the project. $_POST data
     *  should contain project_id, skill_id.
     */
    public function updateOwnerSkills() {
        
    }

    /**
     * NOT YET IMPLEMENTED
     * 
     * This function handles AJAX call from front-end when user click Audtion
     * button. It will return the audition modal (maybe with JS/CSS). $_POST 
     * should contain project_id. The modal contains a dropdown menu of project 
     * list, so you should retrive that data from backend and load them in the 
     * view before echo. project_id should also be placed in the modal for submission.
     *
     * @author
     * @access public
     */
    public function getAuditionModal() {
        
    }

    /**
     * NOT YET IMPLEMENTED
     * 
     * This function handles AJAX call from front-end when user submit the
     * audition. $_POST data should contain project_id, skill_id (And maybe in 
     * the future: Cover letter and audition files).
     *
     * @author
     * @access public
     */
    public function createAudition() {
        
    }

    /**
     * This function handles AJAX call from front-end when user withdraw his/her
     * application to a project. $_POST data should contain project_id OR audition_id.
     *
     * @author Hao Cai
     * @access public
     */
    public function deleteAudition() {
        if ($auditionId = $this->input->post('audition_id')) {
            $audition = $this->fms_audition_model->getEntityById($auditionId);
            if ($audition->getApplicant()->getId() != $this->userId) {
                $this->responseObj = array(
                    'errorcode' => Project::ERROR_PERMISSION,
                    'message' => 'You do not have permission.'
                );
                $this->encodeJSON($this->responseObj);
                return;
            }
            $this->fms_audition_model->deleteAudition($audition);
            $this->responseObj = array(
                'errorcode' => Project::ERR_CODE_NO_ERROR,
                'message' => 'Withdraw successfully.'
            );
            $this->encodeJSON($this->responseObj);
        }
    }

    /**
     * This function handles AJAX call from front-end when owner of a project
     * accept/hide a user's application for a project. $_POST should contain 
     * audition_id and action code.
     *
     * @author Pankaj K.
     * @access public
     */
    public function updateAuditionStatus() {

        // NEED TO SANITIZE AND VALIDATE THE POST
        $projectId = (int) $_POST['project_id'];
        $userId = (int) $_POST['user_id'];
        $skillId = (int) $_POST['skill_id'];
        $status = $_POST['status'];

        $project = $this->fms_project_model->getEntityById($projectId);
        
		//sanity check for the POST data
		// Pankaj K.
		if(!$project){
			$responseObj = array(
				'errorcode' => 2,
				'message'	=> 'Project Not Found'
			);
			$this->encodeJSON($responseObj);
			return;
		}
		
		$user = $this->fms_user_model->getEntityById($userId);
		
		if(!$user){
			$responseObj = array(
				'errorcode' => 3,
				'message'	=> 'Applicant Not Found'
			);
			$this->encodeJSON($responseObj);
			return;
		}

		if($user->getStatus() == Fms_user_model::INACTIVE){
			$responseObj = array(
				'errorcode' => 4,
				'message'	=> 'Applicant is no longer an active member of Find My Song'
			);
			$this->encodeJSON($responseObj);
			return;
		}
		
        // Wei: Permission Check
        if($project->getOwner()->getId() != $this->userId) {
        	$responseObj = array(
        			'errorcode'	=> 1,
        			'message'	=> 'Permission Denied. You are not the owner of this project',
        	);
			$this->encodeJSON($responseObj);
        	return;
        }
        
        // Get Data
        $applicants = $project->getAuditions();
        $role = Fms_project_member_model::MEMBER;

        foreach ($applicants as $applicant) {
            if ($applicant->getApplicant()->getId() === $userId && $applicant->getSkill()->getSkill()->getId() === $skillId) {
                $currentApplicant = $applicant;
                break;
            }
        }
		
        // Wei: Do we have to delete this mapping? If so, the audition cannot be accessed through a project
        // $project->removeAudition($currentApplicant);
        $projectSkills = $project->getSkills();
		
        // Wei: Check whether skill is still open
        foreach ($projectSkills as $skill) {
            if ($skill->getSkill()->getId() === $skillId && $skill->getIsOpen()) {
                $projectSkill = $skill;
                break;
            }
        }
        
        // Wei: If no open skill found
        if(!isset($projectSkill)) {
        	$responseObj = array (
        			'errorcode' => 2,
        			'message'	=> 'No open skill as requested',
        	);
        	$this->encodeJSON($responseObj);
        	return;
        }
		
        // Action
        if ($status === 'accept') {
        	// Wei: 
        	foreach($project->getMembers() as $row) {
        		if($row->getUser()->getId() == $userId) { 
        			$projectMember = $row;
        			break;
        		}
        	}
        	if(!isset($projectMember)){
            	$projectMember = $this->fms_project_member_model->createEntity($currentApplicant->getApplicant(), $project, $role, $projectSkill);
            	$project->addMember($projectMember);
        	}
            $currentApplicant->setStatus(Fms_audition_model::ACCEPTED);
            $project->setLastEditTime( new DateTime() );
            $this->fms_general_model->flush();
            // Wei: Fix bug
            //$this->fms_project_model->addMember($projectMember);
            
            // Wei: Fix bug - should also change owner of the project skill !!!!!
            $projectSkill->setPerformer($projectMember);
            //$projectMember->addSkillForProject($projectSkill);
            
            // Wei: Fix bug - should also change IsOpen flag
            $projectSkill->setIsOpen(0);
			
			
			/**
			 * If the applicant is ACCEPTed, we need to check if the number of visible project
			 * for the applicant is < 12. If it is, we need to make the project visible and update it's 
			 * ranking accordingly.
			 * 
			 * If the user has the project in his list then, we don't need to do anything.
			 * 
			 * Pankaj K., Oct 08, 2013
			 */
			$projectMember = $this->fms_project_member_model->getMemberInProject($user, $project);
			// The default value of the ranking is NULL
			if($projectMember->getRanking() == ""){
				$numVisibleProjects = $this->fms_project_member_model->getRankedProjects($userId);
				if($numVisibleProjects<12){
					$this->fms_project_member_model->updateProjectRanking($userId, $projectId, ++$numVisibleProjects);
				}
			}
			
        } else if($status === 'reject'){
            $currentApplicant->setStatus(Fms_audition_model::REJECTED);
        } else if($status === 'reshow'){
            $currentApplicant->setStatus(Fms_audition_model::PENDING);
        }
        $this->fms_general_model->flush();
        
        // Wei: Added response
        $responseObj = array (
        		'errorcode' => 0,
        		'message'	=> 'success'//,
        		//'debug'		=> $userId.'/'.$projectMember->getId() .'/'. $projectMember->getUser()->getId(),
        );
        $this->encodeJSON($responseObj);
    }

    /**
     * This function is called by the invite modal to get all the projects of
     * current user
     *
     * @return JSON [
     *         {
     *         projectid : 10,
     *         projectname : 'rock on the rail'
     *         },
     *         {
     *         projectid : 11,
     *         projectname : 'Find my Song'
     *         }
     *         ]
     */
    public function getMyProjects() {
        $user = $this->fms_user_model->getEntityById($this->userId);
        $pojects = $user->getMyProjects();
        $response = array();
        foreach ($pojects as $project) {
            if ($project->getStatus() == Fms_project_model::RECRUITING || $project->getStatus() == Fms_project_model::ACTIVE) {
                $response[] = array(
                    'projectid' => $project->getId(),
                    'projectname' => $project->getName()
                );
            }
        }
        $this->encodeJSON($response);
    }
}