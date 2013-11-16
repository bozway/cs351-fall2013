<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Message extends Authenticated_service {
	const MESSAGES = 'messages';
	const CONTACTS = 'contacts';
    public function __construct() {
        parent::__construct(array("flag_restricted_page" => true));

        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('docModels/fms_user_model');
        $this->load->model('docModels/fms_project_model');
    }

    /**
     * index()
     *
     * This function is used to display Message-conversation page
     *
     * @author Wei
     */
    public function index($active_panel = false) {
        $data['title'] = "My Messages";
        $data['css_ref'] = array(
            'css/textext.css',
            'css/dashboard/message.css',
            'css/dashboard/navigation.css'
        );
        $data['extrascripts'] = array(
            'js/jquery.slimscroll.min.js',
            "js/textext.min.js",
            'js/jquery.tinysort.min.js',
            'js/jquery.validate.min.js',
            'js/dashboard/navigation.js',
            'js/dashboard/message_ajax.js',
            'js/dashboard/message.js',
            'js/dashboard/contact.js'
        );
		
		if($active_panel !== FALSE){
			if($active_panel != Message::MESSAGES && 
			   $active_panel != Message::CONTACTS){
			   		redirect(base_url('dashboard/message'));
			   }
		}

		$nav_data = $this->getNavData($active_panel);
		
        $data['loggedin_userid'] = $this->userId;
		$contacts_data['contacts'] = $this->getContactsData();
		$contacts_data['active_panel'] = $active_panel;
		$messages_data['active_panel'] = $active_panel;
		$data['freeze_header'] = 'dashboard_message';
		$data['show_navigation'] = 'true';
		$data['enableSlimFooter'] = TRUE;
       
        $this->load->view('view_header', $data);
        $this->load->view('view_dashboard/navigation/view_navbar', $nav_data);
        $this->load->view('view_dashboard/message/view_dashboard_message_conversation', $messages_data);
        $this->load->view('view_dashboard/message/view_dashboard_message_contacts', $contacts_data);
        $this->load->view('view_footer', $data);
    }

	private function getNavData($active_panel){
        $nav_data = array();
		$nav_data['arrow_class_name'] = 'message-arrow';
        $nav_data['links'] = array(
        	Message::MESSAGES =>
	            array(
	                'value' => 'Messages',
	                'id' => 'message_conversation_container'
	            ),
            Message::CONTACTS =>
	            array(
	                'value' => 'Contacts',
	                'id' => 'message_contacts_container'
	            )
        );
		if($active_panel !== FALSE){
			$nav_data['links'][$active_panel]['is_active'] = TRUE;
		}	
		return $nav_data;
	}

	private function getContactsData(){
		$user = $this->fms_user_model->getEntityById($this->userId);
		$contacts_array = array();
		foreach($user->getContacts() as $contact){
			if($contact->getProfilePicture()){
				$photo = base_url($contact->getProfilePicture()->getPath() . $contact->getProfilePicture()->getName());
			}
			else{
				$photo = base_url('img/default_avatar_photo.jpg');
			}
			$skills_array = array();
			$more_skills_array = array();
			foreach($contact->getSkills() as $key => $skill){
				if($key < 2){
					$skills_array[] = $skill->getSkill()->getName();
				}
				else{
					$more_skills_array[] = $skill->getSkill()->getName();
				}
			}
			$contact_data = array(
				'id' => $contact->getId(),
				'first_name' => $contact->getFirstName(),
				'last_name' => $contact->getLastName(),
				'last_login_time' => $contact->getLastLoginTime()->format('Y-m-d H:i:s'),
				'photo' => $photo,
				'skills' => $skills_array,
				'more_skills' => $more_skills_array
			);
			$contacts_array[] = $contact_data;
		}
		return $contacts_array;
	}
}