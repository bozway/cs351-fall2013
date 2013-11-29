<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Hot extends Authenticated_service {
	public $tagMaxLength = 80;
	public function __construct() {
		parent::__construct(array('flag_restricted_page' => true));
		$this -> load -> helper('html');
		$this -> load -> helper('url');
		$this -> load -> model('docModels/fms_project_model');
		$this -> load -> model('docModels/fms_hot_model');
		$this -> load -> model('docModels/fms_hotuser_model');
		$this -> load -> model('docModels/fms_general_model');
	}

	public function index() {
		$data['title'] = "What's Hot";
		$data['css_ref'] = array(
            "css/hot.css",
            "css/bootstrap-responsive.min.css"
        );
		$data['extrascripts'] = array("js/fms_hot.js", );
		// SEO
		$meta_data = array( array('name' => 'description', 'content' => "Keep updated on the latest in the FindMySong community!"), array('name' => 'keywords', 'content' => 'Music,Songs,Editing,Collaboration,Learn, Discovery, Song Writer, Guitar, Singer, Producer, Music Producer, Piano, Classical Music', ));
		$data['metadata'] = $meta_data;
		$data['userfirstname'] = $this -> fms_user_model -> getEntityById($this->userId) -> getFirstName();
		$data['musicians'] = $this -> getPopularMusicians();
		$data['projects'] = $this -> getPopularProjects();
		/**
		// DEBUG - test data!
		$data['tags'] = array(
			"O2 Live",
			"Eddie Vedder",
			"Modern",
			"Jazz Festival",
			"RIO 2013",
			"BrassJK",
			"O2 Live",
			"Eddie Vedder",
			"Modern"
		);
		$data['genres'] = array(
			"O2 Live",
			"Eddie Vedder",
			"Modern",
			"Jazz Festival",
			"RIO 2013",
			"BrassJK",
			"O2 Live",
			"Eddie Vedder",
			"Modern"
		);
		$data['influences'] = array(
			"O2 Live",
			"Eddie Vedder",
			"Modern",
			"Jazz Festival",
			"RIO 2013",
			"BrassJK",
			"O2 Live",
			"Eddie Vedder",
			"Modern"
		);

		**/
		$data['tags'] = $this -> getHotTags();
		$data['genres'] = $this -> getHotGenres();		
		$data['influences'] = $this -> getHotInfluences();

		$this -> load -> view('view_header', $data);
		$this -> load -> view('view_fms/view_hot', $data);
		$this -> load -> view('view_footer', $data);

	}

	/**
	 * @author Waylan Wong <waylan.wong@willrainit.com>
	 * @author Hao Cai <hao.cai@willrainit.com>
	 *
	 * @access private
	 *
	 * @uses This function will retrieve the top musicians and package the
	 * data for the view in the form of an associative array.
	 * @return array Associative array of musicians and information needed to build the view
	 */
	private function getPopularMusicians() {
		$popmusicians = array();
		$rows = $this -> fms_hot_model -> getHotUsers();
		foreach ($rows as $row) {
			$user = $row -> getUser();
			//process photo
			$photo = base_url('img/default_avatar_photo.jpg');
			if ($user -> getProfilePicture()) {
				$photo = base_url($user -> getProfilePicture() -> getPath() . $user -> getProfilePicture() -> getName());
			}
			//add to popmusicians
			$popmusicians[] = array('id' => $user -> getId(), 'imgpath' => $photo);
		}
		return $popmusicians;
	}

	private function getHotUsers(){
		$hotUsers = $this->fms_hot_model->getHotUsers();
		if ($hotUsers!=null){
			foreach($hotUsers as $hotUser){
				$user = $hotUser->getUser();
				echo $user->getFirstName();
			}
		}
	}	
	private function getHotGenres(){
		$nameArray = array();
		$lengthArray = array();
		$hotGenres = $this->fms_hot_model->getHotGenres();
		if($hotGenres!=null){
			foreach ($hotGenres as $hotGenre){
				if(strlen($hotGenre->getName()) > $this->tagMaxLength)continue;
				$lengthArray[] = strlen($hotGenre->getName());
				$nameArray[]=$hotGenre->getName();
			}
		}		
		return $this->tagArrayProcess($lengthArray,$nameArray);
		//return $nameArray;
	}		
	private function getHotInfluences(){
		$nameArray = array();
		$lengthArray = array();		
		$hotInfluences = $this->fms_hot_model->getHotInfluences();
		if($hotInfluences!=null){
			foreach($hotInfluences as $hotInfluence){
				if(strlen($hotInfluence->getName()) > $this->tagMaxLength)continue;
				$lengthArray[] = strlen($hotInfluence->getName());
				$nameArray[] = $hotInfluence->getName();
			}
		}
		return $this->tagArrayProcess($lengthArray,$nameArray);
	}		
	private function getHotTags(){
		$nameArray = array();
		$lengthArray = array();		
		$hotTags = $this->fms_hot_model->getHotTags();
		if($hotTags!=null){
			foreach($hotTags as $hotTag){
				if(strlen($hotTag->getName()) > $this->tagMaxLength)continue;
				$lengthArray[] = strlen($hotTag->getName());
				$nameArray[] = $hotTag->getName();
			}
		}
		return $this->tagArrayProcess($lengthArray,$nameArray);
	}		
	

	
	private function tagArrayProcess($array,$nameArray){
		$margin = 10;
		$padding = 10;
		
		
		$maxlen = 70+$margin;
		$arrayCopy = $array;
		$cantputCount = 0;
		$step = 1;
		$sign = 1;
		$len1 = 0;
		$len2 = 0;
		$len3 = 0;
		$tempLen = 0;
		$col1 = array();
		$col2 = array();
		$col3 = array();
		asort($array);
		$array = array_values($array);
		$index = 1;
		$i=0;
		$t=1;
		while ($i<count($array)){
			$tagPutFlag = 0;
			$curTag = $array[$i];
			switch ($index){
				case 1:
					$tempLen = $curTag + $padding + $margin + $len1;
					if($tempLen < $maxlen){
						$tagPutFlag = 1;
						$col1[] = $curTag;
						$len1 = $tempLen;
					}
					break;
				case 2:
					$tempLen = $curTag + $padding + $margin + $len2;
					if($tempLen < $maxlen){
						$tagPutFlag = 1;
						$col2[] = $curTag;
						$len2 = $tempLen;
					}
					break;
				case 3:
					$tempLen = $curTag + $padding + $margin + $len3;
					if($tempLen < $maxlen){
						$tagPutFlag = 1;
						$col3[] = $curTag;
						$len3 = $tempLen;
					}
					break;										
			}
			if (($t) % 3 == 0){
				$step = 0;
			}else{
				$step = 1;
			}
			$t++;
			$index = $index + $step*$sign;
			//echo $index;
			if($step == 0){
				$sign = $sign * -1;
			}
			if($tagPutFlag){
				$i++;
			}else{
				$cantputCount++;
			}
			if ($cantputCount > 6){
				break;
			}
		}
		
		foreach ($col1 as $key1 => $length){
			foreach ($nameArray as $key2 => $tag){
				if (strlen($tag) == $length){
					$col1[$key1] = $tag;
					unset($nameArray[$key2]);
					break;
				}
			}
		}
		foreach ($col2 as $key1 => $length){
			foreach ($nameArray as $key2 => $tag){
				if (strlen($tag) == $length){
					$col2[$key1] = $tag;
					unset($nameArray[$key2]);
					break;
				}
			}
		}		
		foreach ($col3 as $key1 => $length){
			foreach ($nameArray as $key2 => $tag){
				if (strlen($tag) == $length){
					$col3[$key1] = $tag;
					unset($nameArray[$key2]);
					break;
				}
			}
		}
		
		// print_r($col1);
		// print_r($col2);
		// print_r($col3);			
		
		$result = array_merge($col1,$col2,$col3);
		//print_r($result);	
		
		return $result;
				
	}	


	/**
	 * @author Waylan Wong <waylan.wong@willrainit.com>
	 * @author Hao Cai <hao.cai@willrainit.com>
	 *
	 * @access private
	 *
	 * @uses This function will retrieve the top projects and package the
	 * data for the view in the form of an associative array.
	 * @return array Associative array of musicians and information needed to build the view
	 */
	private function getPopularProjects() {
		$popprojects = array();
		$rows = $this -> fms_hot_model -> getHotProjects();
		foreach ($rows as $row) {
			$project = $row -> getProject();			
			$popprojects[] = $this -> fms_project_model -> getProjectInfoSummary($project->getId());
		}
		
		/**
		// DEBUG fake data to style the page correctly
		for ($count = 1; $count < 4; $count++) {
			$popprojects[] = $this -> fms_project_model -> getProjectInfoSummary(30);
		}
		**/
		return $popprojects;
	}

	/**
	 * Record the top 4 hot projects, caculate it and save it
	 */
	private function recordHotProjects() {
		$rows_new = $this -> fms_hot_model -> caculateHotProjects();
		foreach ($rows_new as $row) {
			echo $row['project'] -> getName() . " " . $row['popularity'] . "<br>";
		}
		$rows_old = $this -> fms_hot_model -> getAllEntities();
		foreach ($rows_old as $row) {
			echo $row -> getProject() -> getName() . " " . $row -> getPopularity() . "<br>";
		}

		/*
		 * Act as a queue here. Pop the last project of the list, change it's value to be the new hot project, prepend it to the beginning of the list
		 * If the new project has already existed in the list, update it's popularity and skip.
		 * */
		foreach ($rows_new as $row_new) {
			if (!$this -> checkProjectDuplicate($rows_old, $row_new)) {
				$temp_row = array_pop($rows_old);
				$temp_row -> setProject($row_new['project']);
				$temp_row -> setPopularity($row_new['popularity']);
				array_unshift($rows_old, $temp_row);
			}
		}
		foreach ($rows_old as $row) {
			echo $row -> getProject() -> getName() . " " . $row -> getPopularity() . "<br>";
		}
		$this -> fms_general_model -> flush();
	}

	private function checkProjectDuplicate($old_objects, $new_object) {
		$is_duplicate = false;
		foreach ($old_objects as $object) {
			if ($object -> getProject() -> getId() == $new_object['project'] -> getId()) {
				$object -> setPopularity($new_object['popularity']);
				$is_duplicate = true;
				return $is_duplicate;
			}
		}
		return $is_duplicate;
	}

	//*************************************************************************

	/**
	 * Record the top 6 hot users, calculate it and save it
	 */
	public function recordHotUsers() {
		$rows_new = $this -> fms_hotuser_model -> caculateHotUsers();
		foreach ($rows_new as $row) {
			echo $row['user'] -> getFirstName() . " " . $row['popularity'] . "<br>";
		}
		$rows_old = $this -> fms_hotuser_model -> getAllEntities();
		foreach ($rows_old as $row) {
			echo $row -> getUser() -> getFirstName() . " " . $row -> getPopularity() . "<br>";
		}

		//Do the same algorithm in recordHotProjects()
		foreach ($rows_new as $row_new) {
			if (!$this -> checkUserDuplicate($rows_old, $row_new)) {
				$temp_row = array_pop($rows_old);
				$temp_row -> setUser($row_new['user']);
				$temp_row -> setPopularity($row_new['popularity']);
				array_unshift($rows_old, $temp_row);
			}
		}
		foreach ($rows_old as $row) {
			echo $row -> getUser() -> getFirstName() . " " . $row -> getPopularity() . "<br>";
		}
		$this -> fms_general_model -> flush();
	}

	private function checkUserDuplicate($old_objects, $new_object) {
		$is_duplicate = false;
		foreach ($old_objects as $object) {
			if ($object -> getUser() -> getId() == $new_object['user'] -> getId()) {
				$object -> setPopularity($new_object['popularity']);
				$is_duplicate = true;
				return $is_duplicate;
			}
		}
		return $is_duplicate;
	}

	//****************************************************************************
}
?>