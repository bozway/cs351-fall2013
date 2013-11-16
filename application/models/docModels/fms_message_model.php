<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_message_model extends CI_Model
{
	private $em;
    
    public function __construct()
    {
      
      $this -> em = $this -> doctrine -> em;
	  // $params = array('em' => $this->em);
	  // $this->load->library('EntitySerializer',$params);  
    }
	
	/**
	 * This function create a new message.
	 * Required:
	 * array(
	 * 	'userId' => '',
	 * 	'threadId' => '',
	 * 	'content' => ''
	 * )
	 * @param array $arr
	 * @return object $message
	 */
	public function createEntity($user,$thread,$content){
		$message = new Entity\Message;	
		$message->setContent($content);
		$message->setCreationTime(new DateTime());
		$message->setSender($user);
		$message->setThread($thread);
		
		$this->em->persist($message);
		$this->em->flush();
		return $message;
	}
	
	public function getEntityById($id){
    	$message = $this -> em ->find('Entity\Message',$id);
		return $message;	
	} 

}

?>