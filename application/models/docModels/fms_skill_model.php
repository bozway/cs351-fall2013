<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_skill_model extends CI_Model
{
	private $em;
    
    public function __construct()
    {
      $this -> em = $this -> doctrine -> em;
    }
	
	public function createEntity($arr){
		$skill = new Entity\Skill;
		$skill->setName($arr['name']);
		$skill->setIconPath($arr['iconPath']);
		$skill->setType($arr['type']);
		$this->em->persist($skill);
		$this->em->flush();
		return $skill;
	}
	
	public function getEntityById($id){
		$skill = $this->em->getRepository('Entity\Skill')->find($id);
		return $skill;
	}
	
    /**
     * This function return a list of skills.       	
     * 
     */	
	public function getAllSkills(){
		$skills = $this->em->getRepository('Entity\skill')->findAll();
		if($skills){
			return $skills;
		}else{
			return false;
		}		
	} 	
	
}