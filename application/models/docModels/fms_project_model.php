<?php

if (! defined( 'BASEPATH' ))
	exit( 'No direct script access allowed' );
class Fms_project_model extends CI_Model {
	private $em;
	
	/* VARIOUS PROJECT STATUS */
	const UNPUBLISHED 		=	1;
	const RECRUITING 		=	2;
	const ACTIVE 			=	3;
	const COMPLETED 		= 	4;
	const INACTIVE 			= 	0;
	const UNSAVED 			=	6;
	
	
	public function __construct() {
		$this->em = $this->doctrine->em;
		// $params = array('em' => $this->em);
		// $this->load->library('EntitySerializer',$params);
	}
	public function createEntity($owner) {
		$project = new Entity\Project();
		$project->setCreationTime( new DateTime() );
		$project->setLastEditTime( new DateTime() );
		$project->setIsSave( false );
		$project->setStatus( Fms_project_model::UNSAVED );
		$project->setOwner( $owner );
		$project->setLastEditTime(new DateTime());
		$project->setShowAudioPreview( 1 );
		$project->setShowCountry( 1 );
		$project->setShowCity( 1 );
		$project->setShowStartDate( 1 );
		$project->setShowDuration( 1 );
		$project->setShowListLength( 1 );
		$project->setShowLanguage( 1 );
		$project->setShowVideoPreview( 1 );
		$project->setShowTags( 1 );
		$project->setShowDescription( 1 );
		$this->em->persist( $project );
		
		// Add owner member
		$ownerMember = $this->fms_project_member_model->createEntity( $owner, $project, Fms_project_member_model::OWNER );
		$project->addMember($ownerMember);
		
		$this->em->flush();
		return $project;
	}
	public function getAllEntities(){
		return $this->em->getRepository('Entity\Project')->findAll();
	}	
	
	public function getEntityById($id) {
		$project = $this->em->find( 'Entity\Project', $id );
		return $project;
	}
	public function getSpotLight($projectId) {
		$qd = $em->createQueryBuilder();
		$qd->select( 'f' )->from( 'Project', 'p' )->innerJoin( 'p.files', 'f' )->where( 'f.type = :type' )->setParameter( 'type', 'spotlight' );
		$spotlight = $qd->getQuery()->getSingleResult();
		return $spotlight;
	}
	public function deleteProject($project){
		$this->em->remove($project);
		$this->em->flush();
		return true;
	}
	/** 
	 * @author Huang Lu
	 * @param int $num The amount of random project ID's you want to retrieve.
	 * @return array An array of project ID's chosen at random.
	 */
	public function getrandomprojectID($num) {
		$Allprojects = $this->getAllEntities();
		foreach ( $Allprojects as $project ) {
			if ($project->getStatus() == self::RECRUITING || $project->getStatus() == self::ACTIVE) {
				$AllprojectId[] = $project->getId();
			}			
		}
		$randomprojectid_key = array_rand( $AllprojectId, $num );
		foreach ( $randomprojectid_key as $Arraykey ) {
			$randomprojectID[] = $AllprojectId[$Arraykey];
		}
		shuffle($randomprojectID);
		return $randomprojectID;
	}
	
	/**
	 * @author Waylan Wong
	 * 
	 * This function will process a project in preparation for displaying it 
	 * in the view as a project preview block.
	 * 
	 * @param int $projID The project ID that we want to get the summary for
	 * @return array An associative array of project data. 
	 */
	public function getProjectInfoSummary($projID) {
		$enProject = $this->getEntityById($projID);
		$projsummary = array();		
		
		if ($enProject) {
			
			$more = '...';	
			$more_dots_length = strlen($more);
			$bool_name_overflow = false;
						
			// process owner name length
			/* We will allow two lines for the owner name, and will truncate if the 
			 * first name is too long, and if the last name is too long.
			 * Limits were calculated by trying to fit capital letters into one line.
			 */
			$raw_owner_name_first = $enProject -> getOwner() -> getFirstName();
			$raw_owner_name_last = $enProject -> getOwner() -> getLastName();			
			
			$limit_name = 14;
			/*
			// DEBUG values - first name too long - one line
			$raw_owner_name_first = "Firstengineeringstudent";
			$raw_owner_name_last = "Lastname";
			*/
			/*
			// DEBUG values - last name too long - two lines
			$raw_owner_name_first = "Myrna";
			$raw_owner_name_last = "Supercalifragilistic";
			*/
			/*
			// DEBUG values - first name and last name juuuust right - one line
			$raw_owner_name_first = "Alberto";
			$raw_owner_name_last = "Fong";
			*/
			
			
			$processed_owner_name = $raw_owner_name_first . ' ' . $raw_owner_name_last;			
			if (strlen($raw_owner_name_first) <= $limit_name) {
				// owner first name will fit in the first row					
				if (strlen($raw_owner_name_last) >= $limit_name) {
					// owner last name will not fit in the first or the second row, trim it.
					$processed_owner_name = $raw_owner_name_first . ' ' . substr($raw_owner_name_last, 0, $limit_name) . $more;
					$bool_name_overflow = true;
				} else if (strlen($processed_owner_name) > $limit_name) {
					// owner last name is less than limit, but combined with first name, is more than limit
					// don't need to trim, but need to readjust the description size
					$bool_name_overflow = true;
				}
			} else {
				// owner first name is too long for one row, truncate to just the first name, this is one row only
				$processed_owner_name = substr($raw_owner_name_first, 0, $limit_name) . $more;			
			}
			
			
			// process title length
			/* We will always truncate the project title to one line. 
			 * Limits were calculated by trying to fit capital letters into one line.
			 */
			  
			$raw_title = $enProject -> getName();			
			$limit_title = 18;
			/*
			// DEBUG values - title is too long
			$raw_title = "We are the champions of Norath and we are here to ROCK!";
			*/
			$processed_title = $raw_title;
			//var_dump($raw_title);			
			if (strlen($raw_title) > $limit_title) {
				$processed_title = substr($raw_title, 0, $limit_title) . $more;
			}			
			//var_dump($processed_title);
			//Process Description
			$raw_projdesc = $enProject -> getDescription();
			$raw_projdesc = rtrim(ltrim($raw_projdesc));
			//var_dump($raw_projdesc);
			// DEBUG values - description is too long
			//$raw_projdesc = "We have too much time on our hands, we should just dance and sing and dance and sing, all day long";
						
			$processed_projdesc = "No description is provided by the user.";
			$limit_desc_nameoverflowed = 45;
			$limit_desc_nameisnormal = 80;
			if ($raw_projdesc) {				
				$limit_desc = ($bool_name_overflow) ? $limit_desc_nameoverflowed : $limit_desc_nameisnormal;
				$processed_projdesc = htmlspecialchars(substr(strip_tags($raw_projdesc), 0, $limit_desc));
				if (strlen($processed_projdesc) >= $limit_desc) {
					$processed_projdesc .= $more;
				}
			}
			
			
			//process photo
			$photo = base_url('img/default_avatar_photo.jpg');
			if ($enProject -> getPhoto()) {
				$photo = base_url($enProject -> getPhoto() -> getPath() . $enProject -> getPhoto() -> getName());
			}
			
			$projsummary = array(
				'id' => $projID, 
				'imgpath' => $photo, 
				'projtitle' => $processed_title, 
				'projowner' => $processed_owner_name, 
				'projdesc' => $processed_projdesc
			);
			
			return $projsummary;
		} else {
			return false;
		}
	}
}
?>