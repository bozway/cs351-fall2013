<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Search extends Authenticated_service {

    public function __construct() {
        parent::__construct();

        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('docModels/fms_user_model');
        $this->load->model('docModels/fms_country_model');
        $this->load->model('docModels/fms_project_model');
        $this->load->model('docModels/fms_project_file_model');
        $this->load->model('docModels/fms_us_state_model');		
		
    }

    /**
     * This function creates the general page structure of Project Search.
     * 
     * @author Hao Cai
     * @access public        
     */
    public function index() {
        $data['title'] = "Project Search";
        $data['css_ref'] = array(
            'css/dashboard/navigation.css',
            "css/FMS/dropdown.css",            
            "css/search/search_projects.css",
            "css/fms_signup_elements.css",
            "css/textext.css",
			"css/search/fms_search_project_result.css",
            "css/bootstrap-responsive.min.css",
                )
        ;
        $data['extrascripts'] = array(
            "js/search/fms_search_projects.js",
            "js/search/fms_search_filters.js",
            "js/textext.js",
            "js/jquery.slimscroll.min.js",
            "js/jquery.tinysort.min.js"            
        );

        // Get Data
        $result_data = '';
        $sidebarData = $this->getSidebarData();
		
		$data['current_nav'] = 'project_search';

        // Load View
        $data['view_search_projects'] = $this->load->view('view_search/view_search_projects_results', $result_data, true);
        $sidebarData['bool_showMySkills'] = $this->authenticated();
        $data['view_search_filters'] = $this->load->view('view_search/view_search_filters', $sidebarData, true);
		
        $this->load->view('view_header', $data);
        $this->load->view('view_search/view_search_projects', $data);
        $this->load->view('view_footer', $data);
    }

    /**
     * This function is responsible for the data present in Main Navigation bar.
     * 
     * @author Hao
     * @access private
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
     * This function returns an array of three objects for the currently logged
     * in user, i.e. his skills, related genres and influences.
     *
     * @author Pankaj K.
     * @access public
     */
    public function getSidebarData() {
        $tempdata['languages'] = array(
            'English',
            'Spanish',
            'Chinese'
        );
        $tempdata['durations'] = array(
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9
        );
        $tempdata['countries'] = $this->fms_country_model->getAllCountries();
		$tempdata['states'] = $this->fms_us_state_model->getAllStates();		

        if ($this->authenticated()) {
            $user = $this->fms_user_model->getEntityById($this->userId);
            $tempdata['userGenres'] = array();
            $tempdata['userInfluences'] = array();
            $tempdata['userSkills'] = array();
            $userSkills = $user->getSkills();

            foreach ($userSkills as $userSkill) {
                $userSkillGenres = $userSkill->getGenres();
                $userSkillInfluences = $userSkill->getInfluences();
                $userGenresArray = array();
                $userInfluencesArray = array();
				$skillArray = array();
				$skillArray['name'] = $userSkill->getSkill()->getName();
				$skillArray['id'] = $userSkill->getId();
				$skillArray['skillid'] = $userSkill->getSKill()->getId();
				$skillArray['categoryid'] = $userSkill->getSkill()->getCategory()->getId();
                $tempdata['userGenres'][$skillArray['id']] =array();
                foreach ($userSkillGenres as $userSkillGenre) {
                    $userGenresArray['id'] = $userSkillGenre->getId();
                    $userGenresArray['name'] = $userSkillGenre->getName();
                    $tempdata['userGenres'][$skillArray['id']][] = $userGenresArray;
                }
				$tempdata['userInfluences'][$skillArray['id']] = array();
                foreach ($userSkillInfluences as $userSkillInfluence) {
                    $userInfluencesArray['id'] = $userSkillInfluence->getId();
                    $userInfluencesArray['name'] = $userSkillInfluence->getName();
                    $tempdata['userInfluences'][$skillArray['id']][] = $userInfluencesArray;
                }
				$tempdata['userSkills'][] = $skillArray;
            }
        }
        return $tempdata;
    }

    /**
     * This function is used to search projects by skills. The following parameter is 
     * passed as POST data:
     * 
     * @param integer skillId       id of the skill
     * @return array                The array contains the matching project(s)
     * @access public
     */
    public function searchByMySkill() {
        $result = array();
        $skill_id = $this->cleanPost['skillId'];
        $searchGenres = array();
        $searchInfluences = array();
        if (isset($this->cleanPost['genres'])) {
            $searchGenres = $this->cleanPost['genres'];
        }
        if (isset($this->cleanPost['influences'])) {
            $searchInfluences = $this->cleanPost['influences'];
        }
        $projects = $this->fms_project_model->getAllEntities();
        foreach ($projects as $project) {
            foreach ($project->getSkills() as $projectSkill) {
                if ($projectSkill->getSkill()->getId() == $skill_id) {
                    $match = 100;
                    foreach ($searchGenres as $genre_id) {
                        foreach ($projectSkill->getGenres() as $genre) {
                            if ($genre->getId() == $genre_id) {
                                $match++;
                            }
                        }
                    }
                    foreach ($searchInfluences as $influence_id) {
                        foreach ($projectSkill->getInfluences() as $influence) {
                            if ($influence->getId() == $influence_id) {
                                $match++;
                            }
                        }
                    }
                    $result[] = array(
                        '0' => $project,
                        '1' => $match
                    );
                }
            }
        }

        $this->prepareResponse($result);
    }

    /**
     * This function is used for the basic / general search.
     * The following parameters are passed as POST data:
     * 
     * @param integer searchBy      This represents the criteria by which the
     *                               user wants to search
     * @param string keywords       This represents the string of keywords that
     *                               the user has entered while performing search.
     * 
     * @access public
     */
    public function generalSearch() {
        $searchBy = $this->cleanPost['searchBy'];
        $keyWords = $this->cleanPost['keywords'];
        if (strlen($keyWords) < 2) {
            echo "false";
            return;
        }

        $result = array();
        $projects = $this->fms_project_model->getAllEntities();
        switch ($searchBy) {
            case 'Skills' :
                $this->searchBySkill($keyWords, $result, $projects);
                break;
            case 'Project Name' :
                $this->searchByName($keyWords, $result, $projects);
                break;
            case 'Influences' :
                $this->searchByInfluence($keyWords, $result, $projects);
                break;
            case 'Genres' :
                $this->searchByGenre($keyWords, $result, $projects);
                break;
            case 'Project Tags' :
                $this->searchByTags($keyWords, $result, $projects);
                break;		
            case 'People' :
                $this->searchByPeople($keyWords, $result, $projects);
                break;								
        }
    }

    /**
     * This function is used to search projects by Name.
     * 
     * @param string $keys      The string containing all the keywords
     * @param array $result     The array contains the search results
     * @param object $projects  This contains all projects registered in Fing My Song
     */
    public function searchByName($keys, $result, $projects) {
        $clean_keys = strtolower($keys);
        foreach ($projects as $project) {
            if (strtolower($project->getName()) == $clean_keys) {
                $match = 100;
                $result[] = array(
                    '0' => $project,
                    '1' => $match
                );
            }
        }
        $this->prepareResponse($result);
    }

    /**
     * This function is used to search projects by Skill.
     * 
     * @param string $keys      The string containing all the keywords
     * @param array $result     The array contains the search results
     * @param object $projects  This contains all projects registered in Fing My Song
     */
    public function searchBySkill($keys, $result, $projects) {
        $clean_keys = strtolower($keys);
        foreach ($projects as $project) {
            foreach ($project->getSkills() as $projectSkill) {
                if (strtolower($projectSkill->getSkill()->getName()) == $clean_keys) {
                    $match = 100;
                    $result[] = array(
                        '0' => $project,
                        '1' => $match
                    );
                    break;
                }
            }
        }
        $this->prepareResponse($result);
    }

    /**
     * This function is used to search projects by Genre.
     * 
     * @param string $keys      The string containing all the keywords
     * @param array $result     The array contains the search results
     * @param object $projects  This contains all projects registered in Fing My Song
     */
    public function searchByGenre($keys, $result, $projects) {
        $clean_keys = strtolower($keys);
        foreach ($projects as $project) {
            $bool_found = false;
            foreach ($project->getSkills() as $projectSkill) {
                foreach ($projectSkill->getGenres() as $genre) {
                    if (strtolower($genre->getName()) == $clean_keys) {
                        $match = 100;
                        $result[] = array(
                            '0' => $project,
                            '1' => $match
                        );
                        $bool_found = true;
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

    /**
     * This function is used to search projects by Influence.
     * 
     * @param string $keys      The string containing all the keywords
     * @param array $result     The array contains the search results
     * @param object $projects  This contains all projects registered in Fing My Song
     */
    public function searchByInfluence($keys, $result, $projects) {
        $clean_keys = strtolower($keys);
        foreach ($projects as $project) {
            $bool_found = false;
            foreach ($project->getSkills() as $projectSkill) {
                foreach ($projectSkill->getInfluences() as $influence) {
                    if (strtolower($influence->getName()) == $clean_keys) {
                        $match = 100;
                        $result[] = array(
                            '0' => $project,
                            '1' => $match
                        );
                        $bool_found = true;
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
	
    public function searchByTags($keys, $result, $projects) {
        $clean_keys = strtolower(trim($keys));
        foreach ($projects as $project) {
            $bool_found = false;
			$tags = $project->getTags();
			if (!$tags) break;		
            foreach ($tags as $tag) {
                if (strtolower(substr($tag,0,strlen($tag)-4)) == $clean_keys) {
                    $match = 100;
                    $result[] = array(
                        '0' => $project,
                        '1' => $match
                    );
                    $bool_found = true;
                    break;
                }
				//echo substr($tag,0,strlen($tag)-4)."^".$clean_keys."         ";
            }
        }
        $this->prepareResponse($result);
    }
		
    public function searchByPeople($keys, $result, $users) {
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
	public function searchByID() {
		$randomprojectID=$this->fms_project_model->getrandomprojectID(5);
		foreach($randomprojectID as $projectid){
			$projects[]=$this->fms_project_model->getEntityById($projectid);
		}
        foreach ($projects as $project) {
                $match = 100;
                $result[] = array(
                    '0' => $project,
                    '1' => $match
                );
        }
        $this->prepareResponse($result);
    }
    /**
     * This function creates the actual response for the respective search query.
     * 
     * @param array $result     an array containing all the matched project(s).
     * @return JSON             JSON containing all the search result(s).
     * @access public
     */
    public function prepareResponse($result) {
        $response = array();
        foreach ($result as $row) {
            $project = $row['0'];
            $tempArray = array();
            $name = $project->getName();
			
			$createDate = -1;
			
            if($project->getCreationTime()){
				 $createDate =$project->getCreationTime()->format("Ymd");
			}	
			$lastActive = -1;
			
            if($project->getLastEditTime()){
				 $lastActive = $project->getLastEditTime()->format("Ymd");
			}	
					
            $endDate = 'Unknown';
            if($project->getCompleteTime()){
				 $endDate =$project->getCompleteTime()->format("m/d/Y");
			}
			
			$duration = $project->getDuration();
			if(!$duration){
				$duration = -1;
			}
            // Location
            $state = $project->getState();
            if ($state) {
                $stateName = $state->getAbbreviatedName();
            } else {
                $stateName = false;
            }

            // audio preview
            $audioUrl = 0;
			$audioUrlFlag = 0;
            $audio = $this->fms_project_file_model->getAudioFiles($project);
            if ($audio) {
                foreach ($audio as $spotlight) {
                    if ($spotlight->getSubtype() == 1) {
                        $audioUrl = site_url().$spotlight->getPath() . $spotlight->getName();
						if(!file_exists($spotlight->getPath() . $spotlight->getName())){
							$audioUrl=0;						
						}
						$audioUrlFlag = 1;
						break;
                    }
                }
            }

            // Profile picture
            $profilePicture = $project->getPhoto();
            if ($profilePicture) {
                $profilePic = base_url($profilePicture->getPath() . $profilePicture->getName());
				if(!file_exists($profilePicture->getPath() . $profilePicture->getName() ) ){
	                $profilePic = base_url('/img/default_avatar_photo.jpg');
	            }
			}else{
				$profilePic = base_url('/img/default_avatar_photo.jpg');
			}
				
            $profileUrl = site_url('/projects/profile/');
            $tempArray['name'] = $name;
            $tempArray['last'] = $project->getDuration()?$project->getDuration()." moths":'Unknown';
            $tempArray['city'] = $project->getCity()?$project->getCity():'Unknown';
            $tempArray['state'] = $stateName ? $stateName:'Unknown';
			if($project->getLanguage()) {
            	$tempArray['language'] = $project->getLanguage()->getLanguageName();
			} else {
				$tempArray['language'] = false;
			}
            $tempArray['endDate'] = $endDate;
			$tempArray['duration'] = $duration;
            $tempArray['audioUrl'] = $audioUrl;
			$tempArray['audioUrlFlag'] =$audioUrlFlag;
            $tempArray['profilePic'] = $profilePic;
            $tempArray['projectId'] = $project->getId();
            $tempArray['profileUrl'] = $profileUrl;
            $tempArray['tags'] = $project->getTags();
            $tempArray['match'] = $row['1'];
			$tempArray['ownerId'] = $project->getOwner()->getId();
			$tempArray['firstname'] = $project->getOwner()->getFirstName();
			$tempArray['lastname'] = $project->getOwner()->getLastName();
			$tempArray['lastActive'] = $lastActive;
			$tempArray['creationDate'] = $createDate;
            $response[] = $tempArray;
        }
        $this->encodeJSON($response);
    }

}