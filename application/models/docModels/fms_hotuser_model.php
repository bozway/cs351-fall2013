<?php
if (! defined( 'BASEPATH' ))
	exit( 'No direct script access allowed' );
class Fms_hotuser_model extends CI_Model {
	const PROJECT = 'Entity\Project';
	const USER = 'Entity\User';
	const PROJECT_MEMBER = 'Entity\ProjectMember';
	const HOTUSER = 'Entity\HotUser';
	private $em;
	
	public function __construct() {
		$this->em = $this->doctrine->em;
	}
	
	public function caculateHotUsers(){
		$USER = Fms_hotuser_model::USER;
		$ACTIVE = Fms_project_model::ACTIVE;
		$RECRUITING = Fms_project_model::RECRUITING;
		$query = $this->em->createQuery("
			SELECT u AS user, COUNT(p.id) AS popularity 
			FROM $USER u JOIN u.projects pm JOIN pm.project p
			WHERE (p.status = $ACTIVE OR p.status = $RECRUITING) AND size(p.members) > 1
			GROUP BY u.id
			ORDER BY popularity DESC
		")
		->setMaxResults(6);
		$results = $query->getResult();
		return $results;
	}
	
	public function getAllEntities(){
		$HOTUSER = Fms_hotuser_model::HOTUSER;
		return $this->em->getRepository($HOTUSER)->findAll();
	}
}
?>