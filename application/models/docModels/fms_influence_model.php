<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_influence_model extends CI_Model
{
	private $em;
    
    public function __construct()
    {
      
      $this -> em = $this -> doctrine -> em;
	  // $params = array('em' => $this->em);
	  // $this->load->library('EntitySerializer',$params);  
    }
	
	public function createEntity($name){
		$influence = new Entity\Influence;
		$influence->setName($name);

		$this->em->persist($influence);
		$this->em->flush();
		return $influence;
	}
	
	public function getEntityByName($name){
		$influence = $this->em->getRepository('Entity\Influence')->findOneBy(array('name'=>$name));
		if(!$influence){
			$influence = $this->createEntity($name);
		}
		return $influence;
	}
	
	public function getAllInfluence(){
		$influences = $this->em->getRepository('Entity\Influence')->findAll();
		if($influences){
			return $influences;
		}else{
			return false;
		}		
	}		


}

?>