<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Project extends Authenticated_service {
	const UNPUBLISHED = 'unpublished';
	const ACTIVE = 'active';
	const COMPLETED = 'completed';
	const MYAPPLICATIONS = 'myapplications';
    public function __construct() {
        parent::__construct(array(
            "flag_restricted_page" => true
        ));
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('form');

        $this->load->model('docModels/fms_user_model');
        $this->load->model('docModels/fms_skill_model');
        $this->load->model('docModels/fms_project_model');
        $this->load->model('docModels/fms_project_member_model');
        $this->load->model("docModels/fms_genre_model");
        $this->load->model("docModels/fms_influence_model");		
        $this->load->model("docModels/fms_general_model");
		$this->load->model("docModels/fms_audition_model");
		$this->load->model("docModels/fms_language_model");
		$this->load->model("docModels/fms_us_state_model");
    }

    /**
     * This function display the default page.
     */
    public function index() {
        $data['title'] = "My Projects";
        $data['css_ref'] = array(
			'css/dashboard/dashboard_default.css',
            'css/dashboard/navigation.css'
        );
        $data['extrascripts'] = array(
        	'js/dashboard/default.js',
			'js/dashboard/navigation.js');
        $mainNavData = $this->getMainNavData();
		
		$data['freeze_header'] = 'dashboard_project';
		$data['show_navigation'] = 'true';

        $this->load->view('view_header', $data);
        $this->load->view('view_dashboard/navigation/view_navbar', $mainNavData);
        $this->load->view('view_dashboard/project/view_dashboard_projects_default', $data);
        $this->load->view('view_footer', $data);
    }

    /**
     * 
     * This function should load the project basic settings page. It takes the
     * project ID as parameter.  
     * 
     * @param integer $projectId row id of the project which the user wants to edit
     * 
     */
    public function edit_basic($projectId) {

        // JS CSS and Static data
        $data['css_ref'] = array(
        	'css/FMS/dropdown.css',
            'css/textext.css',
            'css/dashboard/project_basic.css',
            'css/dashboard/navigation.css',
            'css/test/jquery.Jcrop.css',
            'css/utility.css'
        );
        $data['extrascripts'] = array(
        	'js/jquery.slimscroll.min.js',
            "js/utility.js",
            "js/jquery.validate.min.js",
            "js/textext.min.js",
            "js/vendor/jquery.ui.widget.js",
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
            'js/dashboard/project_basic.js',
            "js/dashboard/nicEdit-latest.js",
            "js/utility.js"
        );
        $data['duration'] = array(
            "1 week",
            "1 month",
            "3 month",
            "6 month and more"
        );
        $data['listing_len'] = array(
            "1 week",
            "1 month",
            "3 month",
            "6 month and more"
        );
        $data['language'] = array(
            "English",
            "Chinese",
            "Spanish"
        );

        if ($projectId) {
            // Find existing project
            $project = $this->fms_project_model->getEntityById($projectId);
            if (!$project) {
				redirect('dashboard/project');
				return;
			}

            // Checking permission
            if ($project->getOwner()->getId() != $this->userId) {
                echo "You do not have permission to edit this project. Project Owner Id = " . $project->getOwner()->getId();
                return;
            }

            // Load data of the page
            $data['userId'] = $this->userId;
            $data['title'] = "Edit Project";
            $data['content_title'] = $project->getName();
            $data['project'] = $project;
            $data['edit_basic'] = 1;
            $data['strDuration'] = "Choose Duration";
            $data['strListLength'] = "Choose Length";
            $data['strLanguage'] = "Choose Language";
            $data['projectId'] = $projectId;
            $data['flag'] = 'edit';

            // Project Status
            switch ($project->getStatus()) {
                case Fms_project_model::UNPUBLISHED :
				case Fms_project_model::UNSAVED :
                    $data['project_status'] = "Unpublished";
                case Fms_project_model::ACTIVE :
                case Fms_project_model::RECRUITING :
                    $data['project_status'] = "Active";
                case Fms_project_model::COMPLETED :
                    $data['project_status'] = "Completed";
                default :
                    $data['project_status'] = "Undefined Status";
            }

            // Project Country
            $country = $project->getCountry();
            if ($country)
                $data['project_country'] = $country->getCountryName();

            // Load image upload module
            $profilePicture = $project->getPhoto();
            if ($profilePicture) {
                $profilePictureUploader['picUrl'] = base_url($profilePicture->getPath() . $profilePicture->getName());
                $profilePictureUploader['picName'] = $profilePicture->getName();
            } else {
            	$profilePictureUploader['picUrl'] = false;
                $profilePictureUploader['picName'] = false;
            }
            $profilePictureUploader['uploader_id'] = 'profile_picture_module';
            $data['profile_picture_module'] = $this->load->view('view_img_uploader', $profilePictureUploader, true);
			
            // Load audio upload module
			$spotlights['files'] = $project->getFiles();
			$spotlights['uploader_id'] = 'audio_preview';
			$data['audio_preview_module'] = $this->load->view( 'view_audio_uploader', $spotlights, true );
            
			// Timezone
			$timezone = new DateTimeZone('America/Los_Angeles');
			if($project->getStartDate()) {
				$startDate = $project->getStartDate();
				$startDate->setTimezone($timezone);
				$data['startDate'] = $startDate->format('j F, Y');
			} else {
		        $startDate = new DateTime(date('Y-m-d'));
				$startDate->setTimezone($timezone);
				$data['startDate'] = $startDate->format('j F, Y');
			}
			
			// Language dropdown
			$data['languageList'] = $this->fms_language_model->getAllLanguages();
            
			// State Dropdown
			$data['stateList'] = $this->fms_us_state_model->getAllStates();
			
            // Load the view
            $navMainData = $this->getMainNavData();
            $data = $this->getVerticalNavData($data, $projectId);
            
            $data['freeze_header'] = 'edit_project';
            $data['show_navigation'] = true;
			
            $this->load->view('view_header', $data);
            $this->load->view('view_dashboard/navigation/view_navbar', $navMainData);
        	if($project->getStatus() === Fms_project_model::UNSAVED || $project->getStatus() === Fms_project_model::UNPUBLISHED) {
				$this->load->view( 'view_dashboard/project/view_dashboard_projects_basic', $data );
			} else {
				$this->load->view( 'view_dashboard/project/view_dashboard_projects_edit_basic', $data );
			}
            $this->load->view('view_xtmpl');
            $this->load->view('view_footer', $data);
        } else {
            redirect('fms_404');
			return;
        }
    }

	/**
	 * This function display the confirmation page for completing a project.
	 * 
	 * @author Wei
	 */
	public function confirm_completion($projectId) {
		$user = $this->fms_user_model->getEntityById($this->userId);
    	if($projectId) {
    		$project = $this->fms_project_model->getEntityById($projectId);
			if (!$project) {
				redirect('dashboard/project');
				return;
			}
    	} else {
			redirect('fms_404');
			return;
    	}
        

        $data['css_ref'] = array(
        	'css/FMS/dropdown.css',
            'css/textext.css',
            'css/dashboard/project_basic.css',
            'css/dashboard/navigation.css',
            'css/test/jquery.Jcrop.css',
            'css/utility.css'
        );
        $data['extrascripts'] = array(
        	'js/jquery.slimscroll.min.js',
            "js/jquery.validate.min.js",
            "js/textext.min.js",
            "js/vendor/jquery.ui.widget.js",
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
            'js/dashboard/project_basic.js',
            "js/dashboard/nicEdit-latest.js",
            "js/utility.js"
        );

        $data['duration'] = array(
            "1 week",
            "1 month",
            "3 month",
            "6 month and more"
        );
        $data['listing_len'] = array(
            "1 week",
            "1 month",
            "3 month",
            "6 month and more"
        );
        $data['language'] = array(
            "English",
            "Chinese",
            "Spanish"
        );
        $data['userId'] = $this->userId;
        $data['title'] = "Create Project";
        $data['project'] = $project;
        $data['projectId'] = $projectId;
        $data['project_status'] = "Unpublished";
        $data['content_title'] = "Create New Project";
        $data['create_flag'] = true;
        $data['edit_basic'] = 2;
        $data['strDuration'] = "Choose Duration";
        $data['strListLength'] = "Choose Length";
        $data['strLanguage'] = "Choose Language";
        $data['flag'] = 'create';
		
		$data['current_page'] = 'create_project';
		$data['freeze_header'] = 'create_project';
		$data['show_navigation'] = 'true';

        $navMainData = $this->getMainNavData();

        // Load image upload module
        $profilePictureUploader['uploader_id'] = 'profile_picture_module';
        $profilePictureUploader['picUrl'] = false;
        $data['profile_picture_module'] = $this->load->view('view_img_uploader', $profilePictureUploader, true);
		
        // Load audio upload module
        $spotlights['files'] = array();
        $spotlights['files'] = $project->getFiles();
        $spotlights['uploader_id'] = 'audio_preview';
        $data['audio_preview_module'] = $this->load->view( 'view_audio_uploader', $spotlights, true );
		
		// Language dropdown
		$data['languageList'] = $this->fms_language_model->getAllLanguages();
		
		// State Dropdown
		$data['stateList'] = $this->fms_us_state_model->getAllStates();
		
        $this->load->view('view_header', $data);
        $this->load->view('view_dashboard/navigation/view_navbar', $navMainData);
        $this->load->view('view_dashboard/project/view_dashboard_projects_confirm_completion', $data);
        $this->load->view('view_xtmpl');
        $this->load->view('view_footer', $data);
	}
	 
    /**
     * This function get all the data of main navigation needs, the horizontal
     * nagivation
     */
    private function getMainNavData() {
        $mainNavData = array();

        $mainNavData['arrow_class_name'] = 'project-arrow';
        $mainNavData['links'] = array(
            array(
            	'id' => 'create_project',
                'value' => 'Create a New Project',
                'url' => site_url('/dashboard/project/create_basic')
            ),
            array(
            	'id' => 'manage_project',
                'value' => 'Manage Your Projects',
                'url' => site_url('/dashboard/project/manage')
            )
        );
        return $mainNavData;
    }

    /**
     * This function get all the data of sub navigation needs, the vertical
     * nagivation
     */
    private function getVerticalNavData($data, $projectId) {
        $verticalNavData = array();
        $verticalNavData['vertical_links'] = array(
            array(
                'value' => 'Overview',
                'url' => site_url('/dashboard/project/overview/' . $projectId)
            ),
            array(
	                'value' => 'Applicants',
	                'status' => 'pending_applicants',
	            	'group' => '1',
	            	'child_links' => array(
	            			array(
					                'value' => 'Current',
					                'status' => 'hidden_applicants',
	            					'url' => site_url('/dashboard/project/applicant/' . $projectId),
				            ),
	            			array(
	            					'value' => 'Hidden',
	            					'status' => 'hidden_applicants',
	            					'url' => site_url('/dashboard/project/applicant_hidden/' . $projectId),
	            			),
	            	),
            ),
            array(
                'value' => 'Edit Settings',
            	'group'	=> '1',
            	'child_links' => array(
            			array(
            					'value' => 'Edit Profile',
            					'url' => site_url('/dashboard/project/edit_basic/' . $projectId),
            			),
            			array(
            					'value' => 'Edit Skills',
            					'url' => site_url('/dashboard/project/edit_skills/' . $projectId),
            			),
            			array(
            					'value' => 'General',
            					'url' => site_url('/dashboard/project/settings/' . $projectId)
            			)
            	),
            ),
        );

        $data['vertical_nav'] = $this->load->view('view_dashboard/navigation/view_vertical_navbar', $verticalNavData, true);
        return $data;
    }

    public function overview($projectId) {
        $data['logged_in_userid'] = $this->userId;

        // Get input parameters
        $data['project_id'] = $projectId;

        // Retrieve data from DB and check if it exists
        $project = $this->fms_project_model->getEntityById($projectId);
        if (!$project) { // add by yongbin
            redirect('dashboard/project');
			return;
        }
        $data['project'] = $project;
        $data['members'] = $project->getMembers();
        $data['num_members'] = count($data['members']);
		
        // Check whether user belongs to the project and user role in the project
//         if ($project->getOwner()->getId() == $this->userId) {
//             $data = $this->getVerticalNavData($data, $projectId);
//             $logged_in_role = 'owner';
//         }
		foreach ($data['members'] as $member) {
			if ($member->getUser()->getId() === $this->userId) {
				$data['user_member'] = $member;
				if ($member->getRole() === Fms_project_member_model::OWNER) {
					$logged_in_role = 'owner';
					if($project->getStatus() != Fms_project_model::COMPLETED)
						$data = $this->getVerticalNavData($data, $projectId);
				} else if ($member->getRole() === Fms_project_member_model::MEMBER) {
					$logged_in_role = 'participant';
				} else {
					$logged_in_role = 'past-participant';
				}
			}
		}
        if (isset($logged_in_role)) {
            $data['role'] = $logged_in_role;
            $data['logged_in_role'] = $data['role'];
        } else {
            echo "You do not have permission to see this page.";
            return;
        }    
        
        $data['fb_share_link'] = $this->getFacebookShareLink_project(
        		$projectId,
        		"Share",
        		'linkFB',
        		'btn btn-small btn-block btn-primary share-facebook',
        		FALSE); 

        // Static data, JS and CSS
        $data['title'] = "Projects Overview";
        $data['css_ref'] = array(
            "css/dashboard/project_overview.css",
            "css/dashboard/navigation.css"
        );
        $data['extrascripts'] = array(
            "js/dashboard/project_overview.js"
        );
        $data['logged_in_userid'] = $this->userId;

        // Load the view
        $data['freeze_header'] = 'edit_project';
        $data['show_navigation'] = true;
        
        $mainNavData = $this->getMainNavData();
        $this->load->view('view_header', $data);
        $this->load->view('view_dashboard/navigation/view_navbar', $mainNavData);
        $this->load->view('view_dashboard/project/view_dashboard_projects_overview', $data);
        $this->load->view('view_footer', $data);
    }

    /**
     * This function displays the Dashboard/Manage main page and the navigation bar.
     *
     */
    public function manage($active_tab = false) {
        $data['title'] = "Project Manage";
        $data['css_ref'] = array(
            'css/textext.css',
            'css/dashboard/project_manage.css',
            'css/dashboard/navigation.css'
        );
        $data['extrascripts'] = array(
            'js/jquery.slimscroll.min.js',
            "js/textext.min.js",
            'js/jquery.tinysort.min.js',
            'js/dashboard/project_manage.js'
        );

        $nav_data = $this->getMainNavData();

        $data['sortby'] = array(
            "name",
            "role",
            "time"
        );

		$data['current_page'] = 'manage_project';
		$data['freeze_header'] = 'manage_project';
		$data['show_navigation'] = 'true';
		
        $user = $this->fms_user_model->getEntityById($this->userId);
        $project_members = $user->getProjects();
		$data['noProjectFlag'] = 0;
		if (count($project_members) == 0){
			$data['noProjectFlag'] = 1;
		}
        $applied_applications = $user->getAuditions();
		
		$data['noApplicationFlag'] = 1;
		
		foreach ($applied_applications as $application) {
			//echo $application->getStatus(); // DEBUG check if the Audition is valid
			if($application->getStatus() !== Fms_audition_model::ACCEPTED) {
				$data['noApplicationFlag'] = 0;
				break;
			}
		}   
		
        $data['project_members']['unpublished'] = array();
        $data['project_members']['active'] = array();
        $data['project_members']['completed'] = array();
        foreach ($project_members as $project_member) {
            switch ($project_member->getProject()->getStatus()) {
                case Fms_project_model::UNSAVED :
                case Fms_project_model::UNPUBLISHED:
                    $data['project_members']['unpublished'][] = $project_member;
                    break;
                case Fms_project_model::ACTIVE :
                case Fms_project_model::RECRUITING :
                    $data['project_members']['active'][] = $project_member;
                    break;
                case Fms_project_model::COMPLETED :
                    $data['project_members']['completed'][] = $project_member;
                    break;
            }
        }
		$data['noUnpublishedFlag'] = 0;
		$data['noActiveFlag'] = 0; 
		$data['noCompletedFlag'] = 0; 
		if (count( $data['project_members']['unpublished']) == 0){
			$data['noUnpublishedFlag'] = 1;
		}
		if (count($data['project_members']['active']) == 0){
			$data['noActiveFlag'] = 1;	
		}
		if (count($data['project_members']['completed']) == 0){
			$data['noCompletedFlag'] = 1; 	
		}				
        $data['applied_applications'] = $applied_applications;
		
		$data['active_tab'] = $active_tab;
		
		$data['createUrl'] = site_url('/dashboard/project/create_basic');
		$data['searchUrl'] = site_url('/projects/search');

        $this->load->view('view_header', $data);
        $this->load->view('view_dashboard/navigation/view_navbar', $nav_data);
        $this->load->view('view_dashboard/project/view_dashboard_projects_manage', $data);
        $this->load->view('view_footer', $data);
    }

    public function removeProjectMember($projectId) {
        $user = $this->fms_user_model->getEntityById($this->userId);
        $project = $this->fms_project_model->getEntityById($projectId);
        $this->fms_project_member_model->deleteEntity($user, $project);
    }

    /**
     * This function display the Dashboard Project/ Manage - Applicants page
     * 
     * @param integer $pid  row id of the project, whose applicants we want to observe
     *
     */
    public function applicant($pid) {
        $data['title'] = "Project Manage - Applicants";
        $data['css_ref'] = array(
            'css/dashboard/project_applicants.css',
            'css/dashboard/navigation.css'
        );
        $data['extrascripts'] = array(
        	'js/jquery.tinysort.min.js',
            'js/dashboard/project_applicants.js'
        );
        
        $project = $this->fms_project_model->getEntityById($pid);
		if(!$project) {
			redirect('dashboard/project');
			return;
		}
		
        $data['project'] = $project;
        $auditions = $project->getAuditions();
		$curent_auditions = array();
		foreach($auditions as $audition){
			if($audition->getStatus() == Fms_audition_model::PENDING || $audition->getStatus() == Fms_audition_model::UNREAD){
				$curent_auditions[] = $audition;
			}
		}
        $data['auditions'] = $curent_auditions;
		
		$data['freeze_header'] = 'applicants';
        $data['show_navigation'] = true;
		
        // For permission Check
        if ($project) { // add by yongbin 2013-8-6
            $project_id = $project->getId();
            $owner_id = $project->getOwner()->getId();
        }

        $mainNavData = $this->getMainNavData();
        $data = $this->getVerticalNavData($data, $project_id);

        $this->load->view('view_header', $data);
		
        // Permission Check
        if ($this->userId === $owner_id) {
            $this->load->view('view_dashboard/navigation/view_navbar', $mainNavData);
            $this->load->view('view_dashboard/project/view_dashboard_projects_applicants', $data);
        } else {
            echo "Not authorized to view this page.";
            return;
        }
        $this->load->view('view_footer', $data);
    }
    
    public function applicant_hidden($pid) {
    	$data['title'] = "Project Manage - Applicants";
    	$data['css_ref'] = array(
    			'css/dashboard/project_applicants.css',
    			'css/dashboard/navigation.css'
    	);
    	$data['extrascripts'] = array(
    			'js/jquery.tinysort.min.js',
    			'js/dashboard/project_applicants.js'
    	);
    
        $project = $this->fms_project_model->getEntityById($pid);
		if(!$project) {
			redirect('dashboard/project');
			return;
		}
        $data['project'] = $project;
		$auditions = $project->getAuditions();
        $hidden_auditions = array();
		foreach ($auditions as $audition) {
			if($audition->getStatus() == Fms_audition_model::REJECTED){
				$hidden_auditions[] = $audition;
			}
		}
        $data['auditions'] = $hidden_auditions;
		
    	// For permission Check
    	if ($project) { // add by yongbin 2013-8-6
    		$project_id = $project->getId();
    		$owner_id = $project->getOwner()->getId();
    	}
    
    	$mainNavData = $this->getMainNavData();
    	$data = $this->getVerticalNavData($data, $project_id);
		
		$data['freeze_header'] = 'hidden_applicant';
        $data['show_navigation'] = true;
    
    	$this->load->view('view_header', $data);
    
    	// Permission Check
    	if ($this->userId === $owner_id) {
    		$this->load->view('view_dashboard/navigation/view_navbar', $mainNavData);
    		$this->load->view('view_dashboard/project/view_dashboard_projects_applicants', $data);
    	} else {
    		echo "Not authorized to view this page.";
    		return;
    	}
    	$this->load->view('view_footer', $data);
    }

    /**
     * This function display the Dashboard Project/ Settings page
     * 
     * @param integer $project_id  row id of the project, whose settings 
     *                              we want to observe
     */
    public function settings($project_id) {
        $data['title'] = "Project Manage - Applicants";
        $data['css_ref'] = array(
            'css/dashboard/project_settings.css',
            'css/dashboard/navigation.css'
        );
        $data['extrascripts'] = array(
            'js/dashboard/project_settings.js'
        );

        $project = $this->fms_project_model->getEntityById($project_id);
		if(!$project) {
			redirect('dashboard/project');
			return;
		}
        $data['project'] = $project;

        $skillsOpenFlag = 0;
        $data['currentStatus'] = '';

        $projectSkills = $project->getSkills();
        foreach ($projectSkills as $projectSkill) {
            if ($projectSkill->getIsOpen()) {
                $skillsOpenFlag++;
                break;
            }
        }

        if ($project->getStatus() === Fms_project_model::UNPUBLISHED) {
            $data['currentStatus'] = 'Unpublished';
        } else if ($project->getStatus() === Fms_project_model::COMPLETED) {
            $data['currentStatus'] = 'Completed';
        } else if ($skillsOpenFlag === 1) {
            $project->setStatus(Fms_project_model::RECRUITING);
            $data['currentStatus'] = 'Active';
        } else {
            $project->setStatus(Fms_project_model::ACTIVE);
            $data['currentStatus'] = 'Active';
        }

        $this->fms_general_model->flush();

        $mainNavData = $this->getMainNavData();
        $data = $this->getVerticalNavData($data, $project_id);
		
		$data['freeze_header'] = 'settings';
        $data['show_navigation'] = true;
		
        $this->load->view('view_header', $data);

        if ($this->userId === $project->getOwner()->getId()) {
            $this->load->view('view_dashboard/navigation/view_navbar', $mainNavData);
            $this->load->view('view_dashboard/project/view_dashboard_projects_settings', $data);
        } else {
            echo "No sufficient permission for making the view available...!!!!";
        }

        $this->load->view('view_footer', $data);
    }

    /**
     * This function loads the Dashboard Project/ Create project page
     */
    public function create_basic($projectId = 0) {
    	$user = $this->fms_user_model->getEntityById($this->userId);
    	if($projectId) {
    		$project = $this->fms_project_model->getEntityById($projectId);
			if(!$project) {
				redirect('dashboard/project');
				return;
			}
    	} else {
    		$project = $this->fms_project_model->createEntity($user);
    		$projectId = $project->getId();
    	}
        

        $data['css_ref'] = array(
        	'css/FMS/dropdown.css',
            'css/textext.css',
            'css/dashboard/project_basic.css',
            'css/dashboard/navigation.css',
            'css/test/jquery.Jcrop.css',
            'css/utility.css'
        );
        $data['extrascripts'] = array(
        	'js/jquery.slimscroll.min.js',
            "js/jquery.validate.min.js",
            "js/textext.min.js",
            "js/vendor/jquery.ui.widget.js",
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
            'js/dashboard/project_basic.js',
            "js/dashboard/nicEdit-latest.js",
            "js/utility.js"
        );

        $data['duration'] = array(
            "1 week",
            "1 month",
            "3 month",
            "6 month and more"
        );
        $data['listing_len'] = array(
            "1 week",
            "1 month",
            "3 month",
            "6 month and more"
        );
        $data['language'] = array(
            "English",
            "Chinese",
            "Spanish"
        );
        $data['userId'] = $this->userId;
        $data['title'] = "Create Project";
        $data['project'] = $project;
        $data['projectId'] = $projectId;
        $data['project_status'] = "Unpublished";
        $data['content_title'] = "Create New Project";
        $data['create_flag'] = true;
        $data['edit_basic'] = 2;
        $data['strDuration'] = "Choose Duration";
        $data['strListLength'] = "Choose Length";
        $data['strLanguage'] = "Choose Language";
        $data['flag'] = 'create';
		
		$data['current_page'] = 'create_project';
		$data['freeze_header'] = 'create_project';
		$data['show_navigation'] = 'true';

        $navMainData = $this->getMainNavData();

        // Load image upload module
        $profilePictureUploader['uploader_id'] = 'profile_picture_module';
        $profilePictureUploader['picUrl'] = false;
        $data['profile_picture_module'] = $this->load->view('view_img_uploader', $profilePictureUploader, true);
		
        // Load audio upload module
        $spotlights['files'] = array();
        $spotlights['files'] = $project->getFiles();
        $spotlights['uploader_id'] = 'audio_preview';
        $data['audio_preview_module'] = $this->load->view( 'view_audio_uploader', $spotlights, true );
        
        // Timezone
        $timezone = new DateTimeZone('America/Los_Angeles');
		if($project->getStartDate()) {
			$startDate = $project->getStartDate();
			$startDate->setTimezone($timezone);
			$data['startDate'] = $startDate->format('j F, Y');
		} else {
	        $startDate = new DateTime(date('Y-m-d'));
			$startDate->setTimezone($timezone);
			$data['startDate'] = $startDate->format('j F, Y');
		}
        
		// Language dropdown
		$data['languageList'] = $this->fms_language_model->getAllLanguages();
		
		// State Dropdown
		$data['stateList'] = $this->fms_us_state_model->getAllStates();
		
        $this->load->view('view_header', $data);
        $this->load->view('view_dashboard/navigation/view_navbar', $navMainData);
        $this->load->view('view_dashboard/project/view_dashboard_projects_basic', $data);
        $this->load->view('view_xtmpl');
        $this->load->view('view_footer', $data);
    }

    /**
     * This function will return an array of all the skills in the database
     * simulating the results we may eventually get when we implement the
     * backend.
     *
     * @author Waylan Wong <waylan.wong@willrainit.com>
     */
    private function getAllSkillCategories() {
        $skillObjects = $this->fms_skill_model->getAllSkills();
        foreach ($skillObjects as $row) {
            $category = $row->getCategory();
            if ($row->getType() === 2) {
                $category_id = $category->getId();
                $skill_item = array(
                    'skill_name' => $row->getName(),
                    'skillid' => $row->getId()
                );
                $skills[$category_id]['skills'][] = $skill_item;
            } else {
                $category_id = $row->getId();
                $skills[$category_id]['category_name'] = $row->getName();
                $skills[$category_id]['category_id'] = $category_id;
                $skills[$category_id]['iconPath'] = $row->getIconPath();
            }
        }

        return $skills;
    }

    /**
     *
     * @author Waylan Wong <waylan.wong@willrainit.com>
     *        
     *         Process the giant category and skill data dump into a skill data
     *         array
     *        
     * @param array $categorydata
     *        	The giant array of skills and their categories
     */
    private function extractSkillData($categorydata) {
        $skilldata = array();

        foreach ($categorydata as $aCat) {
            foreach ($aCat['skills'] as $skill) {
                $skilldata[] = array(
                    'skill_name' => $skill['skill_name'],
                    "skillid" => $skill['skillid'],
                    'categoryid' => $aCat['category_id']
                );
            }
        }

        usort($skilldata, function ($a, $b) {
                    return strcmp($a['skill_name'], $b['skill_name']);
                });
        return $skilldata;
    }

    /**
     *
     * @author Waylan Wong <waylan.wong@willrainit.com>
     *        
     *         Take in test data, and extract only the skill names
     *        
     *         @pre The array is sorted by skill names.
     * @param array $skilldata
     *        	Associative array of skill data
     */
    private function flattenSkillsToNamesOnly($skilldata) {
        $skillnames = array();

        foreach ($skilldata as $aSkill) {
            $skillnames[] = $aSkill["skill_name"];
        }

        return $skillnames;
    }

    /**
     *
     * @author Waylan Wong <waylan.wong@willrainit.com>
     *        
     *         AJAX function to return our database of genres to the frontend.
     *        
     * @return JSON array of all musical genres.
     */
    public function getGenreTags() {
        $genres = $this->fms_genre_model->getAllGenres();
        $iter = 0;
        foreach ($genres as $row) {
        	if ($row->getType() != 1) {
	            $genreData[0][$iter]['name'] = $row->getName();
	            $genreData[0][$iter]['id'] = $row->getId();
	            $genreData[1][$iter] = $row->getName();
	            $iter++;
        	}
        }

        // sort these suckers before we pass it to the front because textext
        // automatically sorts and will mess up the index.
        usort($genreData[0], function ($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
        sort($genreData[1]);

        $this->encodeJSON($genreData);
    }
    public function getAllInflunces() {
        $influence = $this->fms_influence_model->getAllInfluence();
        $iter = 0;
        foreach ($influence as $row) {
            $genreData[0][$iter]['name'] = $row->getName();
            $genreData[0][$iter]['id'] = $row->getId();
            $genreData[1][$iter] = $row->getName();
            $iter++;
        	
        }

        // sort these suckers before we pass it to the front because textext
        // automatically sorts and will mess up the index.
        usort($genreData[0], function ($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
        sort($genreData[1]);

        $this->encodeJSON($genreData);
    }   
    
    /**
     *
     * @author Waylan Wong <waylan.wong@willrainit.com>
     *        
     *         AJAX function to a full list of skills.
     *        
     * @return JSON An array of skill data
     */
    public function getAllSkillData() {
        $catdata = $this->getAllSkillCategories();
        $data[0] = $this->extractSkillData($catdata);
        $data[1] = $this->flattenSkillsToNamesOnly($data[0]);

        $this->encodeJSON($data);
    }

    /**
     * This function will load the interface to add skills to
     * the project.
     * Skill, genre, and influence data are AJAX'ed
     * at runtime.
     *
     * @author Waylan Wong <waylan.wong@willrainit.com>
     */
    public function create_skills($projectId) {
        $data['title'] = "Create Project - Add Skills";
        $data['css_ref'] = array(
            'css/textext.css',
            'css/dashboard/project_skills.css',
            'css/dashboard/navigation.css'
        );
        $data['extrascripts'] = array(
        	'js/jquery.slimscroll.min.js',
            'js/textext.min.js',
            'js/dashboard/project_skills.js'
        );
		
		$project = $this->fms_project_model->getEntityById($projectId);
		if(!$project) {
			redirect('dashboard/project');
			return;
		}
		
        $data['skilldata'] = $this->getAllSkillCategories();
        $data['create_flag'] = true;
        $data['projectId'] = $projectId;
        $navMainData = $this->getMainNavData();
        
        $data['savedskills'] = $this->getUserProfileSkills();
		
        $data['freeze_header'] = 'edit_project';
        $data['show_navigation'] = true;
        
        $this->load->view('view_header', $data);
        $this->load->view('view_dashboard/navigation/view_navbar', $navMainData);
        $this->load->view('view_dashboard/project/view_dashboard_projects_skills', $data);
        $this->load->view('view_footer', $data);
    }

    public function edit_skills($projectId) {
        $data['title'] = "Project Edit - skills";
        $data['css_ref'] = array(
            'css/textext.css',
            'css/dashboard/project_skills.css',
            'css/dashboard/navigation.css'
        );
        $data['extrascripts'] = array(
        	'js/jquery.slimscroll.min.js',
            'js/textext.min.js',
            'js/dashboard/project_skills.js'
        );
		
        // Permission Check
        $project = $this->fms_project_model->getEntityById($projectId);
		if(!$project) {
			redirect('dashboard/project');
			return;
		}
        if($project->getOwner()->getId() != $this->userId){
        	echo "Permission Denied";
        	return;
        }
        
        
        $data['projectId'] = $projectId;
        $data['skilldata'] = $this->getAllSkillCategories();
        $navMainData = $this->getMainNavData();
        $data = $this->getVerticalNavData($data, $projectId);
        
        $data['savedskills'] = $this->getUserProfileSkills();
		
        
        $data['freeze_header'] = 'edit_project';
        $data['show_navigation'] = true;
        $this->load->view('view_header', $data);
        $this->load->view('view_dashboard/navigation/view_navbar', $navMainData);
        
        
        if($project->getStatus() == Fms_project_model::UNSAVED || $project->getStatus() == Fms_project_model::UNPUBLISHED) {
        	$this->load->view('view_dashboard/project/view_dashboard_projects_skills', $data);
        } else {
        	$this->load->view('view_dashboard/project/view_dashboard_projects_edit_skills', $data);
        }
        
        $this->load->view('view_footer', $data);
    }

    public function create_finish($param) {
    	
    	if (isset($param)) {
    		$project = $this->fms_project_model->getEntityById($param);
			if(!$project) {
				redirect('dashboard/project');
				return;
			}
    		$proj_title = $project->getName();
    		$data['title'] = $proj_title;
    	} else {
    		redirect('dashboard/project');
			return;
    	}    	
        
        $data['css_ref'] = array(
            'css/dashboard/project_create_finish.css',
            'css/dashboard/navigation.css'
        );
        $data['extrascripts'] = array(
            'js/dashboard/project_create_finish.js'
        );

        $navMainData = $this->getMainNavData();
		
		/**
		 * If the project is PUBLISHed, we need to check if the number of visible project
		 * for the user is < 12. If it is, we need to make the project visible and update it's 
		 * ranking accordingly
		 *
		 * Pankaj K., Oct 08, 2013
		 */
		 $numVisibleProjects = $this->fms_project_member_model->getRankedProjects($this->userId);
		 $user = $this->fms_user_model->getEntityById($this->userId);
		 if($this->fms_project_member_model->getMemberInProject($user, $project)->getRanking() == "" && $numVisibleProjects<12 ){
		 	$this->fms_project_member_model->updateProjectRanking($this->userId, $param, ++$numVisibleProjects);
		 }
		 
        $data['project_id'] = $param;
        $data['user_id'] = $this->userId;        
        $data['fb_share_link'] = $this->getFacebookShareLink_project(
        		$param,
        		"Share with Facebook",
        		'',
        		'btn btn-large btn-block btn-primary');
        
        $this->load->view('view_header', $data);
        $this->load->view('view_dashboard/navigation/view_navbar', $navMainData);
        $this->load->view('view_dashboard/project/view_dashboard_projects_create_finish', $data);
        $this->load->view('view_footer', $data);
    }

    /**
     *
     * @author Pankaj Kumar
     *        
     *         AJAX function to return a list of artist names based on the
     *         partial name
     *         we received from the frontend user input. A limit on the number
     *         of suggested
     *         names is set to reduce the amount of data we are passing to the
     *         frontend.
     *        
     * @param
     *        	string	The partial name will be taken from $_POST data.
     */
    public function getInfluenceSuggestions() {
        $partial = (isset($_GET['partial'])) ? $_GET['partial'] : '';

        if (strlen($partial) > 0) {

            $lastfm_api_key = "9d7e7333fdcd9c94807d90fab0f6cde5";
            $limit = 10;
            $influence_array = array();

            $influence_search_url = "http://ws.audioscrobbler.com/2.0/?method=artist.search&artist=" . $partial . "&api_key=" . $lastfm_api_key . "&limit=" . $limit . "&format=json";

            $curl_object = curl_init();
            curl_setopt_array($curl_object, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $influence_search_url
            ));

            $json_result = curl_exec($curl_object);
            curl_close($curl_object);
            $info = json_decode($json_result);
			if (isset($info)) {
				for ($i = 0; $i < 10; $i++) {
					array_push($influence_array, $info->results->artistmatches->artist[$i]->name);
				}
				
				if (count($influence_array) > 0) {
					$this->encodeJSON($influence_array);
				} else {
					$this->encodeJSON(array( "Is that a new artist?"));
				}	
			} else {
				$this->encodeJSON(array( "Is that a new artist?"));
			}            
        } else {
            $this->encodeJSON(array(
                "Who's the inspiration?"
            ));
        }
    }
    
    /**
     * @author Waylan Wong <waylan.wong@willrainit.com>
     * 
     * This function will retrieve and package the authenticated user's skills 
     * into an associative array.
     * 
     * @return array User's profile skills as an associative array.
     */
    private function getUserProfileSkills() {
    	// Retrive user data from DB
    	$user = $this -> fms_user_model -> getEntityById($this -> userId);
    	$userSkills = $user -> getSkills();
    	$skilldata = array();
    	foreach ($userSkills as $key => $userSkill) {    		
    		$skilldata[$key]['name'] = $userSkill -> getSkill() -> getName();
    		$skilldata[$key]['id'] = $userSkill -> getSkill() -> getId();
    		$skilldata[$key]['iconPath'] = $userSkill -> getSkill() -> getIconPath();
    		$skilldata[$key]['ranking'] = $userSkill -> getRanking();    		
    		$skilldata[$key]['genres'] = array();
    		$skilldata[$key]['influences'] = array();    		
    		foreach ($userSkill->getGenres() as $genre) {
    			$skilldata[$key]['genres'][] = $genre -> getId();
    		}
    		foreach ($userSkill->getInfluences() as $influence) {
    			$skilldata[$key]['influences'][] = $influence -> getName();
    		}
    	}
    	//var_dump($skilldata);
    	return $skilldata; 
    }

}

