<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_language_model extends CI_Model
{
	private $em;
    
    public function __construct()
    {
      
      $this -> em = $this -> doctrine -> em;
	  // $params = array('em' => $this->em);
	  // $this->load->library('EntitySerializer',$params);  
    }
	
	public function getEntityById($id){
    	$language = $this -> em ->find('Entity\Language',$id);
		return $language;				
	}
	
	public function getEntityByName($name){
		$language = $this->em->getRepository('Entity\Language')->findOneBy(array('languageName' => $name));
		return $language;					
	}

    /**
     * This function return a list of skills.       	
     * 
     */	
	public function getAllLanguages(){
		$languages = $this->em->getRepository('Entity\Language')->findAll();
		if($languages){
			return $languages;
		}else{
			return false;
		}		
	}

}

?>