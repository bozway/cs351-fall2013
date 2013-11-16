<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_thread_user_model extends CI_Model
{
	const UNREAD 		= 0;
	const READ 			= 1;
	const UNREAD_INV 	= 2;
	const READ_INV 		= 3;
	private $em;
    
    public function __construct(){
    	$this -> em = $this -> doctrine -> em;
    }
	
	/**
	 * This function create a new user thread.
	 * 
	 * )
	 * @param array $arr
	 * @return object $threadUser
	 */
	public function createEntity($user,$thread,$readFlag){
		$threadUser= new Entity\ThreadUser;		
		$threadUser->setUser($user);
		$threadUser->setThread($thread);
		$threadUser->setReadFlag($readFlag);
		$this->em->persist($threadUser);
		$this->em->flush();
		return $threadUser;
	}
	
	public function getEntityById($id){
		$threadUser = $this->em->getRepository('Entity\ThreadUser')->find($id);
		return $threadUser;
	}
	
	public function getThreadUserByConditions($user = null, $thread = null){
		$conditions = array();
		if($user != null){
			$conditions['user'] = $user->getId();
		}
		if($thread != null){
			$conditions['thread'] = $thread->getId();
		}
		$threadUsers = $this->em->getRepository('Entity\ThreadUser')->findBy($conditions);
		return $threadUsers;
	}
	
	public function deleteThreadUser($threadUser){
		$this->em->remove($threadUser);
		$this->em->flush();
	}
	
	/**
	 * This function just returns the number of unread threads 
	 */
	public function getUnreadThreadNum($user_id){
		$THREADUSER = "Entity\ThreadUser";
		$UNREAD_FLAG = Fms_thread_user_model::UNREAD;
		$UNREAD_INV_FLAG = Fms_thread_user_model::UNREAD_INV;
		$query = $this->em->createQuery("
			SELECT COUNT(tu.id) 
			FROM $THREADUSER tu
			WHERE tu.user = $user_id AND (tu.readFlag = $UNREAD_FLAG OR tu.readFlag = $UNREAD_INV_FLAG)
		");
		$count = $query->getSingleScalarResult();
		return $count;
	}
}