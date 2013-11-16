<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_country_model extends CI_Model
{
	private $em;
    
    public function __construct()
    {
      
      $this -> em = $this -> doctrine -> em;
	  // $params = array('em' => $this->em);
	  // $this->load->library('EntitySerializer',$params);  
    }
	
	public function getEntityById($id){
    	$country = $this -> em ->find('Entity\Country',$id);
		return $country;				
	}
	
	public function getEntityByName($name){
		$country = $this->em->getRepository('Entity\Country')->findOneBy(array('countryName' => $name));
		return $country;					
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

}

?>