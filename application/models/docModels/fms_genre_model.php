<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_genre_model extends CI_Model
{
	private $em;
    
    public function __construct()
    {
      
      $this -> em = $this -> doctrine -> em;
	  // $params = array('em' => $this->em);
	  // $this->load->library('EntitySerializer',$params);  
    }
	
	// public function createEntity($arr){
		// $skill = new Entity\Skill;
		// $skill->setName($arr['name']);
		// $skill->setIconPath($arr['iconPath']);
		// $skill->setType($arr['type']);
		// $this->em->persist($skill);
		// $this->em->flush();
		// return $skill;
	// }
// 	
	public function getEntityById($id){
		$genre = $this->em->getRepository('Entity\Genre')->find($id);
		return $genre;
	}	
	
    /**
     * This function return a list of skills.       	
     * 
     */	
	public function getAllGenres(){
		$genres = $this->em->getRepository('Entity\Genre')->findAll();
		if($genres){
			return $genres;
		}else{
			return false;
		}		
	}
}

?>