<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Search extends Authenticated_service {

    function __construct() {
        parent::__construct();

        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('docModels/fms_user_model');
        $this->load->model('docModels/fms_project_model');
        $this->load->model('docModels/fms_file_model');
		$this->load->model('docModels/fms_country_model');
		$this->load->model('docModels/fms_us_state_model');		
		
    }

    /**
     * This function displays the main page of Search Musician
     * 
     * @author Wei
     * @access public
     */
    public function index() {
        $data['title'] = "Musician Search";
        $data['css_ref'] = array(
            'css/dashboard/navigation.css',
            "css/FMS/dropdown.css",            
            "css/search/search_musicians.css",
            "css/textext.css",
			"css/search/fms_search_musician_result.css",
			"css/bootstrap-responsive.min.css",
			"css/mobile_search.css"
        );
        $data['extrascripts'] = array(
            "js/search/fms_search_filters.js",
            "js/search/fms_search_musicians.js",
            "js/textext.js",
            "js/jquery.slimscroll.min.js",
            "js/jquery.tinysort.min.js" 
        );

        $navigation_data = $this->getMainNavData();
        $data['skills'] = array(
            'Skills',
            'Name',
            'Influences',
            'Genres'
        );
        $sidebarData = $this->getSidebarData();
        //$searchResult = $this->getMusicianResult();
		$searchResult = array();
        $sidebarData['bool_showMyProjects'] = $this->authenticated();
        $data['sidebar'] = $this->load->view('view_search/view_musician_filter', $sidebarData, true);
        $data['results'] = $this->load->view('view_search/view_search_musicians_results', $searchResult, true);

		$data['current_nav'] = 'musician_search';
		
	
		
        $this->load->view('view_header', $data);
        $this->load->view('view_search/view_search_musicians', $data);
        $this->load->view('view_footer', $data);
    }

    /**
     *
     * @author Hao
     */
    private function getMainNavData() {
        $data = array();
        $data['arrow_class_name'] = 'none-arrow';
        $data['links'] = array(
            array(
                'value' => 'Search Project',
                'url' => site_url('/projects/search')
            ),
            array(
                'value' => 'Search User',
                'url' => site_url('/users/search')
            )
        );
        return $data;
    }

    /**
     * getSidebarData()
     * This function is used to test the search sidebar
     */
    private function getSidebarData() {
        if ($this->authenticated()) {

            $user = $this->fms_user_model->getEntityById($this->userId);

            $projects = $user->getMyProjects();
            $data['projects'] = array();
            $data['skills'] = array();
            $data['skillGenres'] = array();
            $data['skillInfluences'] = array();
            foreach ($projects as $project) {
                if ($project->getStatus() != fms_project_model::ACTIVE && $project->getStatus() != fms_project_model::RECRUITING)
                    continue;
                $tempArray = array();
                $tempArray['projectId'] = $project->getId();
                $tempArray['projectName'] = $project->getName();
                foreach ($project->getSkills() as $projectSkill) {
                    $skillsArray = array();
                    $skillsArray['skillId'] = $projectSkill->getSkill()->getId();
                    $skillsArray['categoryId'] = $projectSkill->getSkill()->getCategory()->getId();
                    $skillsArray['skillName'] = $projectSkill->getSkill()->getName();
                    $skillsArray['id'] = $projectSkill->getId();
                    $genreArray = array();
                    foreach ($projectSkill->getGenres() as $genre) {
                        $genreArray[] = array(
                            'id' => $genre->getId(),
                            'name' => $genre->getName()
                        );
                    }
                    $data['skillGenres'][$projectSkill->getId()] = $genreArray;
                    $influenceArray = array();
                    foreach ($projectSkill->getInfluences() as $influence) {
                        $influenceArray[] = array(
                            'id' => $influence->getId(),
                            'name' => $influence->getName()
                        );
                    }
                    $data['skillInfluences'][$projectSkill->getId()] = $influenceArray;
                    $data['skills'][$project->getId()][] = $skillsArray;
                }
                $data['projects'][] = $tempArray;
            }
        }
        $data['languages'] = array(
            "English",
            "Chinese",
            "Spanish"
        );
		
		$data['states'] = $this->fms_us_state_model->getAllStates();
		
        return $data;
    }


    public function searchByProjectSkill() {

        // print_r($this->cleanPost);
        $sid = $this->cleanPost['skillId'];
        $cid = $this->cleanPost['categoryId'];
        $result = array();
        $searchGenres = array();
        $searchInfluences = array();
        $genreFlag = false;
        $influenceFlag = false;
        if (isset($this->cleanPost['genres'])) {
            $genreFlag = true;
            $searchGenres = $this->cleanPost['genres'];
        }
        if (isset($this->cleanPost['influences'])) {
            $influenceFlag = true;
            $searchInfluences = $this->cleanPost['influences'];
        }

        $users = $this->fms_user_model->getAllEntities();
        foreach ($users as $user) {
            $match = 0;
            $userSkills = $user->getSkills();

            foreach ($userSkills as $userSkill) {
                $genreMatched = 0;
                $influenceMatched = 0;
                if ($userSkill->getSkill()->getId() == $sid) {
                    $match = 50;
                    if ($genreFlag) {
                        foreach ($userSkill->getGenres() as $genre) {
                            foreach ($searchGenres as $genreId) {
                                if ($genre->getId == $genreId) {
                                    $genreMatched += 1;
                                }
                            }
                        }
                    }
                    if ($influenceFlag) {
                        foreach ($userSkill->getInfluences() as $influence) {
                            foreach ($searchInfluences as $influenceId) {
                                if ($influence->getId == $influenceId) {
                                    $influenceMatched += 1;
                                }
                            }
                        }
                    }

                    $result[] = array(
                        '0' => $user,
                        '1' => $match
                    );

                    break;
                }
            }
        }
        $this->prepareResponse($result);
    }

    public function generalSearch() {
    	
        $searchBy = $this->cleanPost['searchBy'];
        $keyWords = $this->cleanPost['keywords'];
        if (strlen($keyWords) < 2) {
            echo "false";
            return;
        }

        $result = array();
        $users = $this->fms_user_model->getAllEntities();
        switch ($searchBy) {
            case 'Skills' :
                $this->searchBySkill($keyWords, $result, $users);
                break;
            case 'Name' :
                $this->searchByName($keyWords, $result, $users);
                break;
            case 'Influences' :
                $this->searchByInfluence($keyWords, $result, $users);
                break;
            case 'Genres' :
                $this->searchByGenre($keyWords, $result, $users);
                break;
        }
    }

    public function searchByName($keys, $result, $users) {
        $clean_keys = strtolower(str_replace(' ', '', $keys));
        foreach ($users as $user) {
            $name_match_array = array(
                strtolower($user->getFirstName()),
                strtolower($user->getLastName()),
                strtolower($user->getFirstName() . $user->getLastName()),
                strtolower($user->getLastName() . $user->getFirstName())
            );
            if (in_array($clean_keys, $name_match_array)) {
                $match = 50;
                $result[] = array(
                    '0' => $user,
                    '1' => $match
                );
            }
        }

        $this->prepareResponse($result);
    }

    public function searchBySkill($keys, $result, $users) {
        $clean_keys = strtolower($keys);
        foreach ($users as $user) {
            $userSkills = $user->getSkills();
            foreach ($userSkills as $userSkill) {
                if (strtolower($userSkill->getSkill()->getName()) == $clean_keys) {
                    $match = 50;
                    $result[] = array(
                        '0' => $user,
                        '1' => $match
                    );
                    break;
                }
            }
        }
        $this->prepareResponse($result);
    }

    public function searchByGenre($keys, $result, $users) {
        $clean_keys = strtolower($keys);
        foreach ($users as $user) {
            $bool_found = false;
            foreach ($user->getSkills() as $userSkill) {
                foreach ($userSkill->getGenres() as $genre) {
                    if (strtolower($genre->getName()) == $clean_keys) {
                        $bool_found = true;
                        $match = 50;
                        $result[] = array(
                            '0' => $user,
                            '1' => $match
                        );
                        break;
                    }
                }
                if ($bool_found) {
                    break;
                }
            }
        }
        $this->prepareResponse($result);
    }

    public function searchByInfluence($keys, $result, $users) {
        $clean_keys = strtolower($keys);
        foreach ($users as $user) {
            $bool_found = false;
            foreach ($user->getSkills() as $userSkill) {
                foreach ($userSkill->getInfluences() as $influence) {
                    if (strtolower($influence->getName()) == $clean_keys) {
                        $bool_found = true;
                        $match = 50;
                        $result[] = array(
                            '0' => $user,
                            '1' => $match
                        );
                        break;
                    }
                }
                if ($bool_found) {
                    break;
                }
            }
        }
        $this->prepareResponse($result);
    }

    private function prepareResponse($result) {
        $response = array();
        foreach ($result as $row) {
            $user = $row['0'];
            $tempArray = array();
            $name = $user->getFirstName() . ' ' . $user->getLastName();
			
			// Country
            $country = $user->getCountry();
            if ($country) {
                $countryName = $country->getCountryName();
            } else {
                $countryName = false;
            }
			
			// State
            $state = $user->getState();
            if ($state) {
                $stateid = $state->getAbbreviatedName();
            } else {
                $stateid = false;
            }			

            $lastActive = $user->getLastLoginTime()->getTimestamp();
			$lastActiveSort = $user->getLastLoginTime()->format("Ymd");
            $numOfProj = count($user->getProjects());

            // Profile picture
            $profilePicture = $user->getProfilePicture();
			$profilePic="";
            if ($profilePicture) {
                $profilePic = base_url() . $profilePicture->getPath() . $profilePicture->getName();
				if(!file_exists($profilePicture->getPath() . $profilePicture->getName() ) ){
	                $profilePic = base_url('/img/default_avatar_photo.jpg');
	            }
            } else {
            	$profilePic = base_url('/img/default_avatar_photo.jpg');
            }

            // Audio Preview with Ranking = 1
            $audioUrl = 0;
            $audio = $this->fms_file_model->getAudioFiles($user);
            if ($audio) {
                foreach ($audio as $spotlight) {
                    if ($spotlight->getSubtype() == 1) {
                        $audioUrl = base_url() . $spotlight->getPath() . $spotlight->getName();
						if(!file_exists($spotlight->getPath() . $spotlight->getName())){
							$audioUrl=0;						
						}
                    }
                }
            }
			
			// Biography
			$moreFlag = 0;
			$biography = $user->getBiography();
			if(!$biography){
				$biography = "No description is provided by the user.";
			}else{
                     $truncateLength = 120;
                     $biography = htmlspecialchars(substr(strip_tags($biography), 0, $truncateLength));
                     if (strlen($biography) >= $truncateLength ) {
                         $biography .= "...";
						 $moreFlag = 1;
                     }
				
			}

            $profileUrl = base_url('/users/profile/'.$user->getId());
            $tempArray['name'] = $name;
            $tempArray['city'] = $user->getCity();
            $tempArray['state'] = $stateid ? $stateid:'Unknown';
			if($user->getLanguage()) {
            	$tempArray['language'] = $user->getLanguage()->getLanguageName();
			} else {
				$tempArray['language'] = false;
			}
            $tempArray['gender'] = $user->getGender();
            $tempArray['lastActive'] = $lastActive;
			$tempArray['lastActiveSort'] = $lastActiveSort;
            $tempArray['numOfProjects'] = $numOfProj;
            $tempArray['audioUrl'] = $audioUrl;
            $tempArray['profilePic'] = $profilePic;
            $tempArray['userId'] = $user->getId();
            $tempArray['profileUrl'] = $profileUrl;
            $tempArray['match'] = $row['1'];
            $tempArray['firstName'] = $user->getFirstName();
            $tempArray['lastName'] = $user->getLastName();
			$tempArray['moreFlag'] = $moreFlag;
			$tempArray['biography'] = $biography;
			
			
            $response[] = $tempArray;
        }

        $this->encodeJSON($response);
    }
    
    
    /**
     * @author Yongbin Wei
     * 
     * This function will randomly pick 5 musicians to display on inital page load 
     * of the search musician page.  
     */
	public function getRandomUser() {
		
		// Get all the users.
		$users = $this->fms_user_model->getAllEntities();
		// $random_users=array_rand($users,5);
		$result = array();
		if (count( $users ) >= 5) {
			$arr_user = array();
			// Build a list of indices for the random operations
			$user_coord = range( 0, count( $users ) - 1 );

			
			// DEBUG - testing when to shuffle() and when to array_rand for
			// the most "random" looking results.
			/*
			 * Waylan notes: Originally, Yongbin used shuffle on the $user_coord
			 * before randomly picking 5 with array_rand(), however, that resulted
			 * in results that still looked puzzlingly in-order. var_dumping the 
			 * shuffle->array_rand() results showed that even though the indices 
			 * were chosen randomly, they were still sorted more or less in-order.
			 * Shuffling the indices AFTER the random pick resulted in a more 
			 * random order.
			 *
			//shuffle( $user_coord );
			$arr_user = array_rand( $user_coord, 5 );			
			var_dump($arr_user);
			$arr_user = array_rand( $user_coord, 5 );
			shuffle($arr_user);
			var_dump($arr_user);
			$arr_user = array_rand( $user_coord, 5 );
			shuffle($arr_user);
			var_dump($arr_user);
			$arr_user = array_rand( $user_coord, 5 );
			shuffle($arr_user);
			var_dump($arr_user);
			$arr_user = array_rand( $user_coord, 5 );
			shuffle($arr_user);
			var_dump($arr_user);
			*/
			
			// Pick out 5 indices at random.
			$arr_user = array_rand( $user_coord, 5 );
			// Reorder them for maximum "random" effect
			shuffle($arr_user);
			//var_dump($arr_user);			
			
			for($i = 0; $i < 5; $i ++) {
				$match = 50;
				$result[] = array(
						'0' => $users[$arr_user[$i]],
						'1' => $match 
				);
			}
		} else {
			for($i = 0; $i < count( $users ); $i ++) {
				$match = 50;
				$result[] = array(
						'0' => $users[$i],
						'1' => $match 
				);
			}
		}
		
		$this->prepareResponse( $result );
	}

}

?>