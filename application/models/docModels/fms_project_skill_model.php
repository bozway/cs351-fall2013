<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_project_skill_model extends CI_Model
{
	private $em;
    
    public function __construct()
    {
  		$this -> em = $this -> doctrine -> em;
    }
	
	/**
	 * This function create a new project skill.
	 * Required:
	 * array(
	 * 	'projectId' => '',
	 * 	'skillId' => ''
	 * )
	 * @param array $arr
	 * @return object $projectSkill
	 */
	public function createEntity($project,$skill,$description=null){
		$projectSkill = new Entity\ProjectSkill;	
		$projectSkill->setProject($project);
		$projectSkill->setSkill($skill);
		$projectSkill->setDescription($description);
        $projectSkill->setIsOpen(TRUE);
		
		$this->em->persist($projectSkill);
		$this->em->flush();
		return $projectSkill;
	}
	
	public function getEntityById($id){
		$projectSkill = $this->em->getRepository('Entity\ProjectSkill')->find($id);
		return $projectSkill;
	}
	
	/**
	 * This function create a bunch of project skills.
	 * Required:
	 * array(
	 * 	'skillId' => ''
	 * )
	 * @param int projectId, array $arr
	 * @return boolean
	 */
	public function createProjectSkills($projectId, $arr){
		$project = $this->em->getRepository('Entity\Project')->find($projectId);
		foreach($arr as $item){
			$projectSkill = new Entity\ProjectSkill;
			$skill = $this->em->getRepository('Entity\Skill')->find($item['$skillId']);
			$projectSkill->setProject($project);
			$projectSkill->setSkill($skill);
			if(isset($item['description'])){
				$projectSkill->setDescription($item['description']);
			}
			$this->em->persist($projectSkill);
		}
		$this->em->flush();
		return true;
	}
	
	/**
	 * This function delete all projectskill of given project
	 */
	public function deleteAllProjectSkills($project){
		foreach($project->getSkills() as $projectSkill){
			$this->em->remove($projectSkill);
		}
		$this->em->flush();
		return true;
	}
}