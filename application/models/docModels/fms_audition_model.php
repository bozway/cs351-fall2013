<?php

if (! defined( 'BASEPATH' ))
	exit( 'No direct script access allowed' );
class Fms_audition_model extends CI_Model {
	private $em;
	
	/* APPLICANT STATUS CODES */
	const ACCEPTED = 1;
	const REJECTED = - 1;
	const PENDING = 0;
	const UNREAD = 2;
	
	public function __construct() {
		$this->em = $this->doctrine->em;
		// $params = array('em' => $this->em);
		// $this->load->library('EntitySerializer',$params);
	}
	public function getEntityById($id) {
		$audition = $this->em->find( 'Entity\Audition', $id );
		return $audition;
	}
	public function createEntity($project, $applicant, $projectSkill, $cl = null) {
		$audition = new Entity\Audition();
		$audition->setProject( $project );
		$audition->setApplicant( $applicant );
		$audition->setSkill( $projectSkill );
		$audition->setCl( $cl );
		$audition->setCreationTime(new DateTime());
		$audition->setStatus( Fms_audition_model::PENDING );
		$this->em->persist( $audition );
		$this->em->flush();
		return $audition;
	}
	public function getAuditionsByConditions($project = null, $applicant = null, $projectSkill = null){
		$conditions = array();
		if($project != null){
			$conditions['project'] = $project->getId();
		}
		if($applicant != null){
			$conditions['applicant'] = $applicant->getId();
		}
		if($projectSkill != null){
			$conditions['skill'] = $projectSkill->getId();
		}
		$auditions = $this->em->getRepository( 'Entity\Audition')->findBy($conditions);
		return $auditions;
	}
	public function deleteAudition($audition) {
		$this->em->remove( $audition );
		$this->em->flush();
	}
}

?>