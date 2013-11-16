<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_thread_model extends CI_Model
{
	private $em;
    
    public function __construct()
    {
      
      $this -> em = $this -> doctrine -> em;
    }
	
	/**
	 * This function create a new thread.
	 * 
	 * @return object $thread
	 */
	public function createEntity(){
		$thread = new Entity\Thread;	
		$thread->setCreationTime(new DateTime());
		$this->em->persist($thread);
		$this->em->flush();
		return $thread;
	}
	
	public function getEntityById($id){
    	$thread = $this -> em ->find('Entity\Thread',$id);
		return $thread;
	} 
	
	public function deleteThread($thread){
		foreach($thread->getParticipants() as $participant){
			$this->em->remove($participant);
		}
		foreach($thread->getMessages() as $message){
			$this->em->remove($message);
			echo $message->getContent()." ";
		}
		$this->em->remove($thread);
		$this->em->flush();
	}
}

?>