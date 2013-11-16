<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );


class Pankaj_test extends CI_Controller {
          
    public function __construct() {
            parent::__construct ();
            
            session_start();
            $this->load->helper ('html');
            $this->load->helper ('url');	
            $this->load->helper ('form');			
            $this->load->library('encrypt');
            $this->load->model('fms_user_profile_model');


    }
        
    public function index() 
    {
        $data['title'] = 'Hello';
        //$this->load->view('pankaj_twitter', $data);

        $file = "05 Bryan Adams - I Will Always Return.mp3";
        $start  = microtime();
        echo hash_file("md5", "user_files/1/images/" . $file);
        $end = microtime();
        echo '<pre>' . ($end - $start) . '</pre>';

        $file_name = "i am naked.jpg";
        $email = "leo.wei@writ.com";
        $curr_timestamp = date("Y-m-d H:i:s");
        $file_name_hashed = $this->encrypt->encode($file_name . ";" . $email . ";" . $curr_timestamp);
        $filename_plain = $this->encrypt->decode($file_name_hashed,$this->config->config['encryption_key']);
        $file_attr = explode(";", $filename_plain);
        
        redirect('pankaj_test/delete_check'); 


   }
   
    public function testfirst(){
    
      $r=  $this->fms_user_profile_model->getUserIdByEmail( "Aa123456");
      if($r){
      	echo "true";
		  
      }else{
      	echo "false";
      }

    }
    
    public function callback()
    {
        $data['title'] = "Call back";
        $this->load->view ( 'view_header', $data);
        
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        //$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
		$token_credentials = $connection->getAccessToken($_REQUEST['oauth_verifier']);
        //$_SESSION['access_token'] = $access_token;
        // $_SESSION['access_token'] = $access_token['oauth_token'];
        // $_SESSION['access_token_secret'] = $access_token['oauth_token_secret'];
        
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token_credentials['oauth_token'],$token_credentials['oauth_token_secret']);
		$account = $connection->get('account/verify_credentials');
		print_r($account);
		echo "---------------------------------<br><br>";
        $content = $connection->get('/1.1/users/show.json?screen_name=kumarpankajit');
		print_r($content);
        //$this->load->view ( 'view_footer', $data);

    }
    
     //====================================================================================================
    
    public function save_file_to_server() 
    {

        $user_id = 1;
        $data['upload_audio_dir'] = 'user_files/' . $user_id . '/audio/';       // directory for uploading the audio for user_id
        $data['upload_image_dir'] = 'user_files/' . $user_id . '/images/';      // directory for uploading the images for user_id

        if($_FILES['userfile']['type'] == 'audio/mp3'){
                $audio_file = $data['upload_audio_dir'] . basename($_FILES['userfile']['name']);
                move_uploaded_file($_FILES['userfile']['tmp_name'], $audio_file);
                $data['audio'] = 1;
        }
        else if($_FILES['userfile']['type'] == 'image/png' || $_FILES['userfile']['type'] == 'image/gif' || $_FILES['userfile']['type'] == 'image/jpg' || $_FILES['userfile']['type'] == 'image/jpeg'){
                $image_file = $data['upload_image_dir'] . basename($_FILES['userfile']['name']);
                move_uploaded_file($_FILES['userfile']['tmp_name'], $image_file);
                $data['images'] = 1;
        }
        else{                                       // mime-type NOT supported
            echo "MIME type not supported."; 
            $data['invalid_mime_type'] = 1;
        }
    }
        
    
    public function password_check()
    {
        //redirect('pankaj_test/password_check');           add this to index() function

        $data ['title'] = "Password Check";
        $this->load->view ( 'view_header', $data);


        $email = 'leo.ljflkd@writ.com';
        $password = 'writ@11123';
        $encrypted_password = $this->encrypt->encode($password);
        $curr_timestamp = date('Y-m-d H:i:s');

        $new_user = array(
            'email' => $email, 'name_first' => 'leo', 'name_last' => 'fdfdf',
            'password' => $encrypted_password, 'ip_registration' => '192.168.2.4', 'ip_last_login' => '192.168.2.5',
            'timestamp_regis' => '2013-06-02', 'timestamp_last_login' => $curr_timestamp, 'sign_stage' => '1'
        );

            if(!$this->fms_user_profile_model->addUser($new_user))
            {
                $data['add_user_error'] = 1;
            }
////            
//      $hashUserPassword = $this->fms_user_profile_model->getHashPasswordByEmail($email);
//        $decrypted_password = $this->encrypt->decode($hashUserPassword,$this->config->config['encryption_key']);
//        echo $decrypted_password;
//        if($password == $decrypted_password)
//           echo "Hello, " . $this->fms_user_profile_model->getUserIdByEmail($email) .  " !!! Go get in...";
//        else
//           echo "Sorry bro...you are unknown in this territory...!!!";

        //$this->load->view ( 'view_footer', $data);
    }
    
    
    
//        redirect('pankaj_test/password_check');
//        $data['title'] = 'Upload File';
//
//        $this->load->view ( 'view_header', $data);
//        $this->load->view ('pankaj_audio_test', $data);                        // view containing the form for uploading the files
//
//        $this->load->view ( 'view_footer', $data);
    
    
    
    
    public function delete_check(){
        
        $photo_file_path = "user_files/1/photo";
        $photo_file_name = "pankaj.jpeg";
        $curr_timestamp = date('Y-m-d H:i:s');
        
//        $arr = array(
//            'photo_file_path' => $photo_file_path, 'photo_file_name' => $photo_file_name, 
//            'timestamp_uploaded' => $curr_timestamp, 'ip_uploaded' => '192.168.2.5' 
//        );
        
        
        
        
        $arr = array('27','5',$curr_timestamp);
        
        $result = $this->fms_user_profile_model->deleteAllSkills(29);
        echo $result;
    }
}
?>
