<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_project_file_model extends CI_Model
{
	private $em;
    
    public function __construct()
    {
      $this -> em = $this -> doctrine -> em;
    }
	
    public function addFile($projectId,$arr) {
    	$project = $this -> em ->find('Entity\Project',$projectId);
    	$file = new Entity\ProjectFile;
		$file->setCreationTime(new DateTime());
		$file->setName($arr["fileName"]);
		$file->setPath($arr["filePath"]);
		$file->setUploadIp($arr["uploadIp"]);
		$file->setType($arr['type']);
		$file->setSubtype($arr['subtype']);
		$file->setOwner($project);
	    $this ->em-> persist($file);
		$this ->em-> flush();
		return $file; 	
	}	
	
	public function getEntityById($fileid) {
		$file = $this -> em ->find('Entity\ProjectFile',$fileid);
		return $file;
	}
	
	public function getAudioFiles($project) {
		$files = $this->em->getRepository('Entity\ProjectFile')->findBy(array('owner'=>$project, 'type'=>0));
		return $files;
	}

	/**
	 * This function deleted all the projectFiles belonging to given project 
	 */
	public function deleteAllFilesByProject($project){
		foreach ($project->getFiles() as $projectfile){
			$this->em->remove($projectfile);
		}
		$this->em->flush();
		return true;
	}
}

?>