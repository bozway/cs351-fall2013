<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Signin extends CI_Controller {
	
        public function __construct() {
		parent::__construct ();
                $this->load->library	('fms_profile_library');
                
	}
        
       /*
        * Load signin page. If size of $_POST is greater than 0 authenticate user.
        */
        
        public function index(){
            if(is_array($_POST) && count($_POST) != 2){
                echo "load sign in";
            }
            else{
                echo "start authentication process";
                if(isset($_POST["emailKey"])&&isset($_POST["passwordKey"])){
                    $cleanArray=$this->fms_user_profile->cleanInput();
                    $signStage=$this->signin($cleanArray["emailKey"], $cleanArray["passwordKey"]);
                    if ($signStage){
                        echo "sign success!";
                        if($signStage==1){
                            echo "sign page2";
                        }
                        if ($signStage==2){
                            echo "sign page3";
                        }
                    }
                    else{
                        echo "sign failed";
                    }
                }
                else{
                    echo "required field not set";
                }
                
                
            }
            
        }
       /*
        * Check if user's credential is correct
        * 
        * @param email
        * @param password
        * 
        * @return true/false  if signin success return true otherwise false
        */ 
        
        public function signin($email,$password){
        	
            
            
        }
       /*
        * Get user's sign stage to determine which page should user be directer to
        * 
        * 
        * @param userID
        * 
        * @return sign stage  
        * 
        */ 
        
        public function checkSignStage($userID){
            
        }
        
        
}
?>
