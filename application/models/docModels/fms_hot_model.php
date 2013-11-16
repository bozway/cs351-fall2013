<?php
if (! defined( 'BASEPATH' ))
	exit( 'No direct script access allowed' );
class Fms_hot_model extends CI_Model {
	const PROJECT = 'Entity\Project';
	const AUDITION = 'Entity\Audition';
	const HOTPROJECT = 'Entity\HotProject';
	
	const genre = 1;
	const influence = 2;
	const tag       = 3;
	private $em;
	
	public function __construct() {
		$this->em = $this->doctrine->em;
	}
	
	public function caculateHotProjects(){
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
	
	public function getHotProjects(){
		return $this->em->getRepository('Entity\HotProject')->findBy(array(),array('popularity'=>'DESC'),4);
	}
	
	public function getHotUsers(){
		return $this->em->getRepository('Entity\HotUser')->findBy(array(),array('popularity'=>'DESC'),6);		
	}
	
	public function getHotGenres(){
		return $this->em->getRepository('Entity\HotTags')->findBy(array('type'=>Fms_hot_model::genre),array('popularity'=>'DESC'),12);
	}
	public function getHotInfluences(){
		return $this->em->getRepository('Entity\HotTags')->findBy(array('type'=>Fms_hot_model::influence),array('popularity'=>'DESC'),12);
	}
	public function getHotTags(){
		return $this->em->getRepository('Entity\HotTags')->findBy(array('type'=>Fms_hot_model::tag),array('popularity'=>'DESC'),12);
	}		
}
?>