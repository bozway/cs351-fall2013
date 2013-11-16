<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

	use Doctrine\Common\Collections\Criteria;

class Fms_project_member_model extends CI_Model
{
	private $em;
	
        /*VARIOUS ROLES*/
    const ALL			= 0;
    const OWNER         = 1;
	const MEMBER        = 2;
	const PAST_MEMBER   = 3;
	const APPLICANT   	= 4;
        
    public function __construct()
    {
      $this -> em = $this -> doctrine -> em;
	  	$this->load->model("docModels/fms_project_model");
    	$this->load->model("docModels/fms_general_model");
		$this->load->model("docModels/fms_user_model");
    }
	
	/**
	 * This function create a new project member.
	 * Required:
	 * array(
	 * 	'userId' => '',
	 * 	'projectId' => '',
	 * 	'role' => ''
	 * )
	 * @param array $arr
	 * @return object $project_member
	 */
	public function createEntity($user,$project,$role,$projectSkill=null){
		$projectMember = new Entity\ProjectMember;
		
		$projectMember->setProject($project);
		$projectMember->setUser($user);
		if ($projectSkill!=null){
			$projectMember->addSkillForProject($projectSkill);			
		}
		$projectMember->setRole($role);
		$projectMember->setVisibility(true);
		$projectMember->setCreationTime(new DateTime());
		$this->em->persist($projectMember);
		$this->em->flush();
		return $projectMember;
	}
	
	public function getEntityById($id){
    	$projectMember = $this -> em ->find('Entity\ProjectMember',$id);
		return $projectMember;		
		
	}
	
	public function getOwnerMember($project){
		
		foreach($project->getMembers() as $member){
			if($member->getRole() == Fms_project_member_model::OWNER){
				return $member;
			}
		}	
		return false;
	}
	
	public function deleteEntity($user, $project){
		$projectMember = $this->em->getRepository('Entity\ProjectMember')->findOneBy(array('project' => $project->getId(), 'user' => $user->getId()));
		$this->em->remove($projectMember);
		$this->em->flush();
	}
	
	public function getMemberInProject($user, $project) {
		$projectMember = $this->em->getRepository('Entity\ProjectMember')->findOneBy(array('project' => $project->getId(), 'user' => $user->getId()));
		return $projectMember;
	}
	
	
	 /**
	 * This function returns an array of top 12 projects for a given user.
	 * The projects in the array are sorted according to their ranks.
	 * 
	 * @param integer $userId		The user_id for the user for whom we want the projects
	 * 
	 * @return $projects = array(project);
	 * 
	 * @author Pankaj K.
	 */
	public function getRankedProjects($userId){
		$user = $this->fms_user_model->getEntityById($userId);
		$userProjects = $this->em->getRepository('Entity\ProjectMember')
				->findBy(array('user'=>$user), (array('ranking' => 'DESC')));

		$numRankedProjects = 0;
		
		foreach($userProjects as $project){
			if($project->getRanking()>0){
				$numRankedProjects++;
			}else{
				break;
			}
		}
		return $numRankedProjects;
	}
	
	/**
	 * This function updates the rank of a given project for a given user with the given rank
	 * 
	 * @param $userId		user_id of the user for which the project rank should be updated
	 * @param $projectId	project_id of the project for which rank is to be updated
	 * @param $rank			rank 
	 * 
	 * @author Pankaj K.
	 */
	public function updateProjectRanking($userId, $projectId, $rank){
		$user = $this->fms_user_model->getEntityById($userId);
		$project = $this->fms_project_model->getEntityById($projectId);
		
		$projectMember = $this->getMemberInProject($user, $project);
		$projectMember->setRanking($rank);
		$this->fms_general_model->flush();	
	}
	
}