<?php

/**
 *
 * The message controller contains bunch of AJAX handlers that are
 * related to messaging and contacts.
 *
 * Notice: 
 * <ul>
 *      <li>Most of the function require checking login, and checking permission.</li>
 *      <li>Do remember to update the description and author when you work on the
 * functions.</li>
 * </ul>
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Message extends Authenticated_service {
	const ERROR_SUCCESS = 0;
	const ERROR_MISSING_PARAM = 100;
	const ERROR_MESSAGE_EMPTY = 200;
	const ERROR_DUPLICATE_THREADUSER = 201;
	const ERROR_DUPLICATE_AUDITION = 202;
	const ERROR_INVALID_AUDITION = 203;
	const ERROR_MESSAGE_MAXLENGHT = 204;
	const ERROR_PARTICIPANT_NO_MATCH = 205;
	const ERROR_THREAD_NO_FOUND = 300;
	const ERROR_THREADUSER_NO_FOUND = 301;
	const ERROR_USER_NO_FOUND = 302;
	const ERROR_PROJECTSKILL_NO_FOUND = 303;
	const ERROR_DATABASE = 400;
	
    public function __construct() {
        parent::__construct();

        $this->load->model('docModels/fms_user_model');
        $this->load->model('docModels/fms_thread_model');
        $this->load->model('docModels/fms_thread_user_model');
        $this->load->model('docModels/fms_message_model');
        $this->load->model('docModels/fms_general_model');
        $this->load->model('docModels/fms_audition_model');
        $this->load->model('docModels/fms_project_model');
        $this->load->model('docModels/fms_project_skill_model');
    }

    /**
     * This function get all the threads by user
     *
     * @author Hao Cai
     */
	public function getMyThreads() {
		$user = $this->fms_user_model->getEntityById( $this->userId );
		$threadUsers = $user->getThreads();
		
		$threads_array = array();
		foreach ( $threadUsers as $threadUser ) {
			// Get the participant ids and names in current thread, for title
			$participants = $threadUser->getThread()->getParticipants();
			$title = '';
			$participants_array = array();
			foreach ( $participants as $participant ) {
				//collect participants id for front-end use
				$participants_array[] = $participant->getUser()->getId();
				
				//concatenate the participant names to be title
				if(strlen( $title ) < 20){
					$title .= $participant->getUser()->getFirstName() . ' ';
					if(strlen( $title ) > 20){
						$title = substr( $title, 0, 20 ) . '...';
					}
				}
			}
			
			//Get a photo from participant except the current user
			$img = site_url('/img/default_avatar_photo.jpg');
			foreach ( $participants as $participant ) {
				//Get a photo from participants except the current user
				if($participant->getUser()->getId() != $this->userId){
					if($participant->getUser()->getProfilePicture()){
						$img = base_url($participant->getUser()->getProfilePicture()->getPath() . $participant->getUser()->getProfilePicture()->getName());
						break;
					}
				}
			}
			
			// Get the last message in current thread, for preview and date
			$preview = '';
			$messages = $threadUser->getThread()->getMessages();
			if (count( $messages ) > 0) {	//when the thread has messages
				$last_message = $messages[count( $messages ) - 1];
				$preview = $last_message->getContent();
				if (strlen( $preview ) > 26) {
					$preview = substr( $preview, 0, 25 ) . '...';
				}
				$date = $last_message->getCreationTime();
			}
			else{
				$date = $threadUser->getThread()->getCreationTime();
			}
			
			$threads_array[] = array(
					'id' => $threadUser->getThread()->getId(),
					'img' => $img,
					'title' => $title,
					'preview' => $this->decodeSlashes($preview),
					'date' => $date,
					'is_read' => $threadUser->getReadFlag(),
					'participants' => $participants_array
			);
		}
		//sort the thread by the last message creation time
		usort($threads_array, function($a, $b){
			if($a['date'] == $b['date']){
				return 0;
			}
			return ($a['date'] <  $b['date']) ? -1 : 1;
		});
		//prepare the display time format
		foreach($threads_array as $key => $thread){
			$threads_array[$key]['date'] = $thread['date']->format('M d Y');
		}
		$response = array(
			'errorcode' => Message::ERROR_SUCCESS,
			'threads' => $threads_array
		);
		$this->encodeJSON( $response );
	}

    /**
     * This funciton is used to get the message for a thread with thread id
     *
     * @param
     *        	bool Default is true, which measns it returns json. If it's
     *        	false, it returns a php array
     */
	public function getMessagesByThread($bool_json = TRUE) {
		if ($thread_id = $this->input->get( 'thread_id' )) {
			$thread = $this->fms_thread_model->getEntityById( $thread_id );
			$messages = $thread->getMessages();
			$messages_array = array();
			foreach ( $messages as $message ) {
				$img = site_url('/img/default_avatar_photo.jpg');
				if($message->getSender()->getProfilePicture()){
					$img = site_url($message->getSender()->getProfilePicture()->getPath() . $message->getSender()->getProfilePicture()->getName());
				}
				$messages_array[] = array(
						'id' => $message->getId(),
						'sender' => $message->getSender()->getFirstName() . ' ' . $message->getSender()->getLastName(),
						'senderid' => $message->getSender()->getId(),
						'img' => $img,
						'content' => $this->decodeSlashes($message->getContent()),
						'time' => $message->getCreationTime()->format( 'H:i a' ),
						'date' => $message->getCreationTime()->format( 'M d Y' )
				);
			}
			
			//set the read flag of thread to be 1
			$this->setThreadRead($thread);
			
			if ($bool_json) {
				$this->encodeJSON( $messages_array );
			} else {
				return $messages_array;
			}
		}
	}

    private function setThreadRead($thread) {
        $user = $this->fms_user_model->getEntityById($this->userId);
        $threadUsers = $this->fms_thread_user_model->getThreadUserByConditions($user, $thread);
        foreach ($threadUsers as $threadUser) {
            if($threadUser->getReadFlag() == Fms_thread_user_model::UNREAD){
            	$threadUser->setReadFlag(Fms_thread_user_model::READ);
            }
            if($threadUser->getReadFlag() == Fms_thread_user_model::UNREAD_INV){
            	$threadUser->setReadFlag(Fms_thread_user_model::READ_INV);
            } 
        }
        $this->fms_general_model->flush();
    }

    private function setThreadUnRead($thread, $is_invitation = FALSE) {
        $threadUsers = $this->fms_thread_user_model->getThreadUserByConditions(null, $thread);
        foreach ($threadUsers as $threadUser) {
            if ($threadUser->getUser()->getId() != $this->userId) {
	            if($is_invitation){
	            	$threadUser->setReadFlag(Fms_thread_user_model::UNREAD_INV);
	            }
				else{
					$threadUser->setReadFlag(Fms_thread_user_model::UNREAD);
				}
            }
        }
        $this->fms_general_model->flush();
    }
	
	private function decodeSlashes($value){
		$newValue = str_replace('\n', '<br>', $value);
		$newValue = stripslashes($newValue);
		return $newValue;
	}

    /**
     * getContactsByThread($bool_json)
     *
     * @param
     *        	bool Default is true, which measns it returns json. If it's
     *        	false, it returns a php array
     */
    public function getContactsByThread($bool_json = true) {
        if ($thread_id = $this->input->get('thread_id')) {
            $thread = $this->fms_thread_model->getEntityById($thread_id);
            $participants = $thread->getParticipants();
            $contact_name_array = array();
			$contact_id_array = array();
            foreach ($participants as $participant) {
                $contact_name_array[] = $participant->getUser()->getFirstName() . ' ' . $participant->getUser()->getLastName();
				$contact_id_array[] = $participant->getUser()->getId();
            }
			$contact_array = array(
				'ids' => $contact_id_array,
				'names' => $contact_name_array
			);
            if ($bool_json) {
                $this->encodeJSON($contact_array);
            } else {
                return $contact_array;
            }
        }
    }

    /**
     * This function makes two operations. One is to retrive all messages by
     * given thread, another one is to retrive all involved contacts by given 
     * thread. By passing false to these two functions, it gets php array returned 
     * instead of JSON.
     */
    public function getMessagesAndContactsByThread() {
    	//validation
    	if($thread_id = $this->input->get('thread_id')){
			$thread = $this->fms_thread_model->getEntityById($thread_id);
			if(!isset($thread)){
		        $this->encodeJSON(array(
		            0 => array(),
		            1 => array()
		        ));
				return;
			}
		}
		else{
	        $this->encodeJSON(array(
	            0 => array(),
	            1 => array()
	        ));
			return;
		}
		//action
        $messages = $this->getMessagesByThread(FALSE);
        $contacts = $this->getContactsByThread(FALSE);
        $this->encodeJSON(array(
            0 => $messages,
            1 => $contacts
        ));
    }

/**
 *  getMyContacts()
 *  Retrieve contact names, contact data, and curent user data
 *  Response:
 *  {
 *		0 => ["waylan", "wei"],
 * 		1 => [{id=>1, name=>"waylan"}, {id=>2, name=>"wei"}]
 * 		2 => {id-=>3, name=>"hao"}
 *  }
 */
    public function getMyContacts() {
        $user = $this->fms_user_model->getEntityById($this->userId);
        $contacts = $user->getContacts();
        $contact_names_array = array();
        $contact_data_array = array();
        foreach ($contacts as $key => $contact) {
            $contact_names_array[] = $contact->getFirstName() . ' ' . $contact->getLastName();
            $contact_data_array[] = array(
                'id' => $contact->getId(),
                'name' => $contact_names_array[$key]
            );
        }
		$current_user_data = array(
			'id' => $this->userId,
			'name' => $user->getFirstName() . ' ' . $user->getLastName()
		);
        $contact_array = array(
            $contact_names_array,
            $contact_data_array,
            $current_user_data
        );
        $this->encodeJSON($contact_array);
    }

    /**
     * This function handles AJAX call from front-end when user click to unsave
     * another user. It remove the user to his/her contact list. $_POST should 
     * contain target user_id.
     *
     * @author Pankaj K.
     * @access public
     */
    public function deleteContact() {
        $contactId = $_POST['contactId'];
        $user = $this->fms_user_model->getEntityById($this->userId);
        $user->removeContact($this->fms_user_model->getEntityById($contactId));
        $this->fms_general_model->flush();

        $this->encodeJSON('true');
    }

    /**
     * This function handles AJAX call from front-end when user clicks to send
     * another use a message or invitation. $_POST should contain target user_id, 
     * and message content.
     *
     * @author Hao Cai
     * @access public
     */
    public function messageUser() {
        $user_id = $this->input->post('user_id');
        $content = $this->input->post('content');
		
		//validate
		$errorcode = $this->validate_messageUser($user_id, $content);
		if($errorcode != Message::ERROR_SUCCESS){
            $response = array(
                'errorcode' => $errorcode,
                'message' => 'data is invalid'
            );
			$this->encodeJSON($response);
			return;
		}
		//action
		$content = $this->cleanPost['content'];
		$errorcode = $this->sendMessageToOneUser($user_id, $content);
        if ($errorcode == Message::ERROR_SUCCESS) {
            $response = array(
                'errorcode' => $errorcode,
                'message' => 'message is sent'
            );
        } else {
            $response = array(
                'errorcode' => $errorcode,
                'message' => 'failed to send the message'
            );
        }
        $this->encodeJSON($response);
    }

	private function validate_messageUser($user_id, $content){
		$errorcode = Message::ERROR_SUCCESS;
		if($user_id !== FALSE && $content !== FALSE){
			if($this->userId == $user_id){
				$errorcode = Message::ERROR_DUPLICATE_THREADUSER;
				return $errorcode;
			}	
			$user = $this->fms_user_model->getEntityById($user_id);
			if(!isset($user)){
				$errorcode = Message::ERROR_USER_NO_FOUND;
				return $errorcode;
			}
			if($content == "" || $content == null){
				$errorcode = Message::ERROR_MESSAGE_EMPTY;
				return $errorcode;
			}
			if(strlen($content) > 800){
				$errorcode = Message::ERROR_MESSAGE_MAXLENGHT;
				return $errorcode;
			}
		}
		else{
			$errorcode = Message::ERROR_MISSING_PARAM;
			return $errorcode;
		}
		return $errorcode;
	}
	
	public function inviteUser(){
        $user_id = $this->input->post('user_id');
        $content = $this->input->post('content');
		
		//validate
		$errorcode = $this->validate_inviteUser($user_id, $content);
		if($errorcode != Message::ERROR_SUCCESS){
            $response = array(
                'errorcode' => $errorcode,
                'message' => 'data is invalid'
            );
			$this->encodeJSON($response);
			return;
		}
		//action
		$content = $this->cleanPost['content'];
		$errorcode = $this->sendMessageToOneUser($user_id, $content, TRUE);
        if ($errorcode == Message::ERROR_SUCCESS) {
            $response = array(
                'errorcode' => $errorcode,
                'message' => 'message is sent'
            );
        } else {
            $response = array(
                'errorcode' => $errorcode,
                'message' => 'failed to send the message'
            );
        }
        $this->encodeJSON($response);
	}
	
	private function validate_inviteUser($user_id, $content){
		$errorcode = Message::ERROR_SUCCESS;
		if($user_id !== FALSE && $content !== FALSE){
			if($this->userId == $user_id){
				$errorcode = Message::ERROR_DUPLICATE_THREADUSER;
				return $errorcode;
			}	
			$user = $this->fms_user_model->getEntityById($user_id);
			if(!isset($user)){
				$errorcode = Message::ERROR_USER_NO_FOUND;
				return $errorcode;
			}
			if($content == "" || $content == null){
				$errorcode = Message::ERROR_MESSAGE_EMPTY;
				return $errorcode;
			}
			if(strlen($content) > 800){
				$errorcode = Message::ERROR_MESSAGE_MAXLENGHT;
				return $errorcode;
			}
		}
		else{
			$errorcode = Message::ERROR_MISSING_PARAM;
			return $errorcode;
		}
		return $errorcode;
	}
	
	private function sendMessageToOneUser($user_id, $content, $is_invitation = FALSE){
		if($this->userId == $user_id){
			return Message::ERROR_DUPLICATE_THREADUSER;
		}
		$user = $this->fms_user_model->getEntityById($this->userId);
		$thread_id = 0;
        foreach($user->getThreads() as $threadUser){
			$temp_thread = $threadUser->getThread();
			$participants = $temp_thread->getParticipants();
			if(count($participants) == 2){		//if it is a two person conversation
				//get the another participant id
				if($participants[0]->getUser()->getId() == $this->userId){		
					$another_participant_id = $participants[1]->getUser()->getId();
				}
				else{
					$another_participant_id = $participants[0]->getUser()->getId();
				}
				//check if this thread exists
				if($user_id == $another_participant_id){
					$thread_id = $temp_thread->getId();
					$this->setThreadUnRead($temp_thread, $is_invitation);
					break;
				}
			}
        }
		//this two participants' thread dosen't exists
        if($thread_id == 0){
        	$participant_ids = array($this->userId, $user_id);
        	$thread_response = $this->createThread($participant_ids, $is_invitation);
			$thread_id = $thread_response['id'];
        }

        // create a new message to thread
		$thread = $this->fms_thread_model->getEntityById($thread_id);
        $message = $this->fms_message_model->createEntity($user, $thread, $content);
        if (isset($message)) {
            return Message::ERROR_SUCCESS;
        } else {
            return Message::ERROR_DATABASE;
        }
	}

    /**
     * This function handles AJAX from front-end when user audition a project.
     * It creates a audition to the given project and it also sends a message of 
     * the project owner to notify him.
     *
     * @author Hao Cai
     */
    public function auditionProject() {
    	$project_id = $this->input->post('project_id');
        $projectskill_id = $this->input->post('projectskill_id');
		
		//validate
		$errorcode = $this->validate_auditionProject($projectskill_id);
		if($errorcode != Message::ERROR_SUCCESS){
            $response = array(
                'errorcode' => $errorcode,
                'message' => 'Data is invalid.'
            );
			$this->encodeJSON($response);
			return;
		}
		//action
        $user = $this->fms_user_model->getEntityById($this->userId);
        $projectskill = $this->fms_project_skill_model->getEntityById($projectskill_id);
		$project = $projectskill->getProject();
		// create an audition
        $audition = $this->fms_audition_model->createEntity($project, $user, $projectskill);
        if(isset($audition)){
        // create a notification message
	        $project_owner = $project->getOwner();
	        $content = 'Hi '.$project_owner->getFirstName().' '.$project_owner->getLastName().', I just applied to your project '.$project->getName().' with the skill '.$projectskill->getSkill()->getName();
			$errorcode = $this->sendMessageToOneUser($project_owner->getId(), $content);
		}
		else{
			$errorcode = Message::ERROR_DATABASE;
		}
        // response
        if ($errorcode == Message::ERROR_SUCCESS) {
            $response = array(
                'errorcode' => $errorcode,
                'message' => 'project is auditioned'
            );
        } else {
            $response = array(
                'errorcode' => $errorcode,
                'message' => 'failed to audition the project'
            );
        }
        $this->encodeJSON($response);
    }

	private function validate_auditionProject($projectskill_id){
		$errorcode = Message::ERROR_SUCCESS;
		if($projectskill_id){
			$projectskill = $this->fms_project_skill_model->getEntityById($projectskill_id);
			$project = $projectskill->getProject();
			$user = $this->fms_user_model->getEntityById($this->userId);
			//check the project skill exist
			if(!isset($projectskill)){
				$errorcode = Message::ERROR_PROJECTSKILL_NO_FOUND;
				return $errorcode;
			}
            // check if the user is the project member
            foreach($project->getMembers() as $projectmember){
            	if($projectmember->getUser()->getId() == $this->userId){
	                $errorcode = Message::ERROR_INVALID_AUDITION;
	               	return $errorcode;
            	}
            }
            // check if it is a duplicate audition
            $auditions = $this->fms_audition_model->getAuditionsByConditions($project, $user, $projectskill);
            if (count($auditions) > 0) {
                $errorcode = Message::ERROR_DUPLICATE_AUDITION;
				return $errorcode;
            }
		}
		else{
			$errorcode = Message::ERROR_MISSING_PARAM;
			return $errorcode;
		}
		return $errorcode;
	}

    /**
     * This function handles AJAX call from front-end when user write and send a
     * new message. It will determine if a new thread should be created.
     *
     * @author Hao Cai
     * @access public
     */
    public function addMessageToThread() {
        $thread_id = $this->input->post('thread_id');
		$participants = $this->input->post('participants');
		$content = $this->input->post('content');
		foreach($participants as $key => $participant){
			$participants[$key] = intval($participant);
		}
		//validate
		$errorcode = $this->validate_addMessageToThread($thread_id, $participants, $content);
		if($errorcode != Message::ERROR_SUCCESS){
			$response = array(
				'errorcode' => $errorcode,
				'message' => "Data is invalid"
			);
			$this->encodeJSON($response);
			return;
		}
		//Action
		//Get or Create a thread
        $is_new = FALSE;
		$thread_response = array();
        if($thread_id == 0){
        	$is_new = TRUE;
			$thread_response = $this->createThread($participants);
			$thread_id = $thread_response['id'];
		}
		else{
			$thread_response = array(
				'id' => $thread_id
			);
		}
		//Create a message
		$user = $this->fms_user_model->getEntityById($this->userId);
        $thread = $this->fms_thread_model->getEntityById($thread_id);
		$content = $this->cleanPost['content'];
        $message = $this->fms_message_model->createEntity($user, $thread, $content);

        //set the read flag of other participants to be 0 
        $this->setThreadUnRead($thread);

        $img = site_url('/img/default_avatar_photo.jpg');
        if ($user->getProfilePicture()) {
            $img = site_url($user->getProfilePicture()->getPath() . $user->getProfilePicture()->getName());
        }
        $message_response = array(
            'id' => $message->getId(),
            'sender' => $user->getFirstName() . ' ' . $user->getLastName(),
            'senderid' => $user->getId(),
            'img' => $img,
            'content' => $this->decodeSlashes($content),
            'time' => $message->getCreationTime()->format('H:i a'),
            'date' => $message->getCreationTime()->format('M d Y')
        );
		$response = array(
			'errorcode' => Message::ERROR_SUCCESS,
			'message' => $message_response,
			'thread' => $thread_response,
			'is_new' => $is_new
		);
		$this->encodeJSON($response);
    }

	private function validate_addMessageToThread($thread_id, $participants, $content){
        $errorcode = Message::ERROR_SUCCESS;
        if ($thread_id === FALSE && $content === FALSE && $participants === FALSE) {
			$errorcode =  Message::ERROR_MISSING_PARAM;
			return $errorcode;
		}
		//if thread is not new
		if($thread_id != 0){
			//check if thread exists
			$thread = $this->fms_thread_model->getEntityById($thread_id);
			if(!isset($thread)){
				$errorcode =  Message::ERROR_THREAD_NO_FOUND;
				return $errorcode;
			}
			//check if the thread contains and only contains the given participants
			$threadUsers = array();
			foreach($thread->getParticipants() as $threadUser){
				$threadUsers[] = $threadUser->getUser()->getId();
			}
			if(count($participants) != count($threadUsers)){
				$errorcode =  Message::ERROR_PARTICIPANT_NO_MATCH;
				return $errorcode;
			}
			for($i=0; $i<count($participants); $i++){
				if(array_search($threadUsers[$i], $participants) === FALSE){
					$errorcode =  Message::ERROR_PARTICIPANT_NO_MATCH;
					return $errorcode;
				}
				if(array_search($participants[$i], $threadUsers) === FALSE){
					$errorcode =  Message::ERROR_PARTICIPANT_NO_MATCH;
					return $errorcode;
				}
			}
		}
		//if thread is new
		else{
			//check all participants exist
			foreach ($participants as $participant) {
				$user = $this->fms_user_model->getEntityById($participant);
				if(!isset($user)){
					$errorcode =  Message::ERROR_USER_NO_FOUND;
					return $errorcode;
				}
			}
		}

		//check if the message is not empty
		if($content == "" || $content == null){
			$errorcode = Message::ERROR_MESSAGE_EMPTY;
			return $errorcode;
		}
		//check if the message is over max length
		if(strlen($content) > 800){
			$errorcode = Message::ERROR_MESSAGE_MAXLENGHT;
			return $errorcode;
		}
		return $errorcode;
	}

    /**
     * This function create a new thread with given participants. It returns the prepared thread
	 * response for fornt-end
     *
     * @author Hao Cai
     * @access private
     */
    private function createThread($participant_ids, $is_invitation = FALSE) {
        $thread = $this->fms_thread_model->createEntity();
		$title = '';
        foreach($participant_ids as $participant_id){
	        $participant = $this->fms_user_model->getEntityById($participant_id);
			//Concatenate the participant names to be title
			if(strlen( $title ) < 20){
				$title .= $participant->getFirstName() . ' ';
				if(strlen( $title ) > 20){
					$title = substr( $title, 0, 20 ) . '...';
				}
			}
			//Decide the readflag for threadUser
	        if($participant_id == $this->userId){
	        	$readFlag = Fms_thread_user_model::READ;
	        }
			else{
				if($is_invitation){
					$readFlag = Fms_thread_user_model::UNREAD_INV;
				}
				else{
					$readFlag = Fms_thread_user_model::UNREAD;
				}
				if($participant->getProfilePicture()){
					$img = base_url($participant->getProfilePicture()->getPath() . $participant->getProfilePicture()->getName());
				}
			}
			//create new threadUser
	        $threadUser = $this->fms_thread_user_model->createEntity($participant, $thread, $readFlag);
        }
		
		//Get a photo from participants except current user
		$img = site_url('/img/default_avatar_photo.jpg');
		foreach($participant_ids as $participant_id){
			$participant = $this->fms_user_model->getEntityById($participant_id);
			if($participant->getProfilePicture()){
				$img = site_url($participant->getProfilePicture()->getPath() . $participant->getProfilePicture()->getName());
				break;
			}
		}
		
        $thread_response = array(
            'id' => $thread->getId(),
            'img' => $img,
            'title' => $title,
            'preview' => '',
            'date' => $thread->getCreationTime()->format('M d Y'),
            'is_read' => Fms_thread_user_model::READ,
            'participants' => $participant_ids
        );
        return $thread_response;
    }

    /**
     * This function returns more contacts for the current logged in
     * user
     * 
     * @access public
     */
    public function getMoreContacts() {
        $nextTopContactId = $_POST['nextTopContactId'];
        $count = 2;
        $moreContacts = array();
        $i = 0;

        $userContacts = $this->fms_user_model->getEntityById($this->userId)->getContacts();

        foreach ($userContacts as $userContact) {
            if ($i < $count && $userContact->getId() === ($nextTopContactId + $i)) {
                array_push($moreContacts, $userContact);
                $i++;
            }
        }

        $this->encodeJSON($moreContacts);
    }

    /**
     * This function returns the number of unread thread numbers.
     * 
     * @access public
     */
    public function getUnreadThreadNum() {
        $count = $this->fms_thread_user_model->getUnreadThreadNum($this->userId);
		$this->encodeJSON(array('num' => $count));
    }
	
    /**
     * This function is called by the audition modal to get all project skills
     * @param integer project_id id of the project for which we need the skill names
     * @return JSON [
     *         {
     *         projectskillid : 12
     *         projectskillname : 'voilin'
     *         },
     *         {
     *         projectskillid : 13
     *         projectskillname : 'guita'
     *         }
     *         ]
     *        
     * @author Hao Cai
     */	
	public function getAuditionModalData(){
        $response = array(
			'project_skill_data' => array(),
			'project_name' => ''
		);
		
        if($projectId = $this->input->get('project_id')){
	        $project = $this->fms_project_model->getEntityById($projectId);
			$user_id = $this->userId;
			$user = $this->fms_user_model->getEntityById($user_id);
			$auditions = $user->getAuditions();
			
			$response['project_name'] = $project->getName();
	        $skills = $project->getSkills();
	        foreach ($skills as $skill) {
	        	if($skill->getIsOpen() != 0) {
	        		$breakLoop = 0;
					foreach($auditions as $audition){
						if($audition->getSkill()->getSkill()->getId() === $skill->getSkill()->getId()){
							$breakLoop = 1;
							break;
						}
					}
					
					if($breakLoop){
						continue;
					}
					
	            	$tempArray = array();
	            	$tempArray['projectskillid'] = $skill->getId();
	            	$tempArray['projectskillname'] = $skill->getSkill()->getName();
	            	$response['project_skill_data'][] = $tempArray;
	        	}
	        }
        }
		
		$this->encodeJSON($response);
	}
	
	public function getInvitationModalData(){
        $response = array(
			'userid' => 0,
			'username' => '',
			'userimg' => '',
			'project_data' => array()
		);
	
        if ($user_id = $this->input->get('user_id')) {
            //Get target user infomation
            $target_user = $this->fms_user_model->getEntityById($user_id);
            if($target_user->getProfilePicture()){ 
            	$img = base_url($target_user->getProfilePicture()->getPath() . $target_user->getProfilePicture()->getName());
			}
			else{
				$img = base_url('img/fms_user_portal/demo_photo.png');
			}
			$response['userid'] = $target_user->getId();
			$response['username'] = $target_user->getFirstName() . ' ' . $target_user->getLastName();
			$response['userimg'] = $img;
			//Get My Open Projects
			$current_user = $this->fms_user_model->getEntityById($this->userId);
	        $pojects = $current_user->getMyProjects();
	        foreach ($pojects as $project) {
	            if ($project->getStatus() == Fms_project_model::RECRUITING || $project->getStatus() == Fms_project_model::ACTIVE) {
	                $response['project_data'][] = array(
	                    'projectid' => $project->getId(),
	                    'projectname' => $project->getName()
	                );
	            }
	        }
        }

		$this->encodeJSON($response);
	}
	
	public function getMessageModalData(){
		$response = array(
			'userid' => 0,
			'username' => '',
			'userimg' => ''
		);
		
        if ($user_id = $this->input->get('user_id')) {
            $target_user = $this->fms_user_model->getEntityById($user_id);
            if($target_user->getProfilePicture()){ 
            	$img = base_url($target_user->getProfilePicture()->getPath() . $target_user->getProfilePicture()->getName());
			}
			else{
				$img = base_url('img/fms_user_portal/demo_photo.png');
			}
			$response['userid'] = $target_user->getId();
			$response['username'] = $target_user->getFirstName() . ' ' . $target_user->getLastName();
			$response['userimg'] = $img;
        }

		$this->encodeJSON($response);
	}

}
