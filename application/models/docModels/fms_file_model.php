<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_file_model extends CI_Model
{
	private $em;
    /*USER STATUS CODES*/
    const PROFILETYPE      = 1; //this need to be deleted, keep it here just so code won't crash
    const COVERTYPE        = 2;
    const SPOTLIGHTTYPE    = 0;
	const AUDIOTYPE        = 3;
	
    public function __construct()
    {
      $this -> em = $this -> doctrine -> em;
    }
	

    /**
     * used to add new audio files to the main audio file table.
     * An array is passed
     * as the parameter, in which the keys are as follows:
     *
     * <ul>
     * <li>photo_file_path - relative path of the file</li>
     * <li>photo_file_name_hashed - hash name of the file </li>
     * <li>timestamp_uploaded - timestamp, when the file had been uploaded</li>
     * <li>ip_uploaded - ip address of the machine, used for uploading the file</li>
     * </ul>
     *
     * @return true false success - true, else false
     */
    public function addFile($userId,$arr) {
    	$user = $this -> em ->find('Entity\User',$userId);
    	$file = new Entity\File;
		$file->setCreationTime(new DateTime());
		$file->setName($arr["fileName"]);
		$file->setPath($arr["filePath"]);
		$file->setUploadIp($arr["uploadIp"]);
		$file->setType($arr['type']);
		$file->setSubtype($arr['subtype']);
		$file->setOwner($user);
	    $this ->em-> persist($file);
		$this ->em-> flush();
		return $file; 	
	}	
	
	public function getEntityById($fileid) {
		$file = $this -> em ->find('Entity\File',$fileid);
		return $file;
	}
	
	public function getAudioFiles($user) {
		$files = $this->em->getRepository('Entity\File')->findBy(array('owner'=>$user, 'type'=>0));
		return $files;
	}
}

?>