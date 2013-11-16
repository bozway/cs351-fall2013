<?php
if (! defined( 'BASEPATH' ))
	exit( 'No direct script access allowed' );
class Fms_hotproject_model extends CI_Model {
	const PROJECT = 'Entity\Project';
	const AUDITION = 'Entity\Audition';
	const HOTPROJECT = 'Entity\HotProject';
	private $em;
	
	public function __construct() {
		$this->em = $this->doctrine->em;
	}
	
	public function calculateHotProjects(){
		$PROJECT = Fms_hotproject_model::PROJECT;
		$ACTIVE = Fms_project_model::ACTIVE;
		$RECRUITING = Fms_project_model::RECRUITING;
		$query = $this->em->createQuery("
			SELECT p AS project, COUNT(a.id) AS popularity 
			FROM $PROJECT p JOIN p.auditions a 
			WHERE (p.status = $ACTIVE OR p.status = $RECRUITING) AND DATE_DIFF(CURRENT_TIMESTAMP(), a.creationTime) < 1
			GROUP BY p.id 
			ORDER BY popularity DESC")
		->setMaxResults(4);
		$results = $query->getResult();
		return $results;
	}
	
	public function getAllEntities(){
		$HOTPROJECT = Fms_hotproject_model::HOTPROJECT;
		return $this->em->getRepository($HOTPROJECT)->findAll();
	}
}
?>