<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_user_skill_model extends CI_Model
{
	private $em;
    
    public function __construct(){
    	$this -> em = $this -> doctrine -> em;
    }
	
	/**
	 * This function create a new user skill.
	 * Required:
	 * array(
	 * 	'userId' => '',
	 * 	'skillId' => '',
	 * 	'ranking' => '',
	 * 	'videoPreview' => '';
	 * )
	 * @param array $arr
	 * @return object $userSkill
	 */
	public function createEntity($user,$skill,$ranking,$videoPreview){
		$userSkill = new Entity\UserSkill;		
		$userSkill->setUser($user);
		$userSkill->setSkill($skill);
		$userSkill->setRanking($ranking);
		$userSkill->setVideoPreview($videoPreview);
		$this->em->persist($userSkill);
		$this->em->flush();
		return $userSkill;
	}
	
	public function getEntityById($id){
		$userSkill = $this->em->getRepository('Entity\UserSkill')->find($id);
		return $userSkill;
	}
	
	/**
	 * This function create a bunch of user skills.
	 * Required:
	 * array(
	 * 	'skillId' => '',
	 * 	'ranking' => ''
	 * )
	 * @param int $userId, array $arr
	 * @return boolean
	 */
	public function createUserSkills($userId, $arr){
		$user = $this->em->getRepository('Entity\User')->find($userId);
		foreach($arr as $item){
			$userSkill = new Entity\UserSkill;
			$skill = $this->em->getRepository('Entity\Skill')->find($item['$skillId']);
			$userSkill->setProject($project);
			$userSkill->setSkill($skill);
			$userSkill->setRanking($item['ranking']);
			$this->em->persist($userSkill);
		}
		$this->em->flush();
		return true;
	}
	
	/**
	 * This function delete all user skills.
     *
	 * @param $user Object
	 * @return boolean
	 */
	 public function deleteAllUserSkills($user){
	 	foreach($user->getSkills() as $userskill){
			$this->em->remove($userskill);
	 	}
		$this->em->flush();
		return true;
	 }
}