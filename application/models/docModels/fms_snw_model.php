<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Fms_snw_model extends CI_Model
{
	private $em;
    
    public function __construct()
    {
      
      $this -> em = $this -> doctrine -> em;
	  // $params = array('em' => $this->em);
	  // $this->load->library('EntitySerializer',$params);  
    }
	
	public function getFbById($id){
    	$fb = $this -> em ->getRepository('Entity\Facebook')->findOneBy(array('facebookUserId' => $id));
		return $fb;				
	}
	public function getTwById($id){
    	$fb = $this -> em ->getRepository('Entity\Twitter')->findOneBy(array('twitterUserId' => $id));
		return $fb;				
	}	
	public function createFB($user,$token,$id,$expire){

		$fb = new Entity\Facebook;
		$fb->setAccessToken($token);
		$fb->setUser($user);
		$fb->setFacebookUserId($id);
		$fb->setExpire($expire);
		$this->em->persist($fb);
		$user->setFB($fb);		
		$this->em->flush();
		return $fb;
	}
	
	public function createTW($user,$id,$token,$secret){

		$tw = new Entity\Twitter;
		$tw->setAccessToken($token);
		$tw->setTokenSecret($secret);
		$tw->setUser($user);
		$tw->setTwitterUserId($id);		
		$this->em->persist($tw);
		$user->setTW($tw);		
		$this->em->flush();
		return $tw;
	}	
	   

}

?>