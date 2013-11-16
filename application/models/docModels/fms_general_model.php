<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_general_model extends CI_Model
{
	private $em;
    
    public function __construct()
    {
      
      $this -> em = $this -> doctrine -> em;
	  // $params = array('em' => $this->em);
	  // $this->load->library('EntitySerializer',$params);  
    }
    /**
     * This function return a list of skills.       	
     * 
     */	
	public function getAllCountries(){
		$countries = $this->em->getRepository('Entity\Country')->findAll();
		if($countries){
			return $countries;
		}else{
			return false;
		}		
	}
	
	public function flush(){
		$this->em->flush();
	}
	
	public function remove($entity){
		$this->em->remove($entity);
	}		    
	
	public function persisit($entity){
		$this->em->persisit($entity);
	}
	
	public function detach($entity){
		$this->em->detach($entity);
	}
	
	public function merge($entity){
		$this->em->merge($entity);
	}

}

?>