<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_us_state_model extends CI_Model
{
	private $em;
    
    public function __construct()
    {
      
      $this -> em = $this -> doctrine -> em;
	  // $params = array('em' => $this->em);
	  // $this->load->library('EntitySerializer',$params);  
    }
	
	public function getEntityById($id){
    	$state = $this -> em ->find('Entity\USState',$id);
		return $state;				
	}
	
	public function getEntityByName($name){
		$state = $this->em->getRepository('Entity\USState')->findOneBy(array('fullName' => $name));
		return $state;					
	}

    /**
     * This function return a list of skills.       	
     * 
     */	
	public function getAllStates(){
		$states = $this->em->getRepository('Entity\USState')->findAll();
		if($states){
			return $states;
		}else{
			return false;
		}		
	}
	
	public function flush(){
		$this->em->flush();
	}    

}

?>