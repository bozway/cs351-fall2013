<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );


class Leo_test extends CI_Controller {
      
   public function __construct() {
        parent::__construct ();

        // $this->load->helper ('html');
        // $this->load->helper ('url');	
        // $this->load->helper ('form');			
        // $this->load->library('encrypt');
        // $this->load->library('twitteroauth');
         $this->load->model('docModels/fms_user_model');
        // session_start();
    }
        
    public function index() {
    	$arr = array();
		$arr['name_last']="leo";
		$arr['password']="sdsads";
		$arr['email']="dasd@dsad.com";
		$arr['timestamp_regis']=new DateTime();
		$arr['ip_registration']="239";
		$arr['sign_stage']="1";
		$arr['name_first']="sss";
		$email = "dasd@dsad.com";
		$skills = array(1 => 1,2 => 2);
		$uid = 1;
		$skarr = $this->fms_user_model->getSkills($uid);
		if ($skarr){
			echo count($skarr);
			foreach($skarr as $key => $value){
				echo $key." => ".$value->getRanking();
			}
		}
    	//$this->fms_user_model->addSkills($uid,$skills);
    }
   
    //====================================================================================================
    
    public function upload() 
    {

        // $user_id = 1;
        // $data['upload_audio_dir'] = 'user_files/' . $user_id . '/audio/';       // directory for uploading the audio for user_id
        // $data['upload_image_dir'] = 'user_files/' . $user_id . '/images/';      // directory for uploading the images for user_id
        
        print_r( $_FILES['userfile']);

        // if($_FILES['userfile']['type'] == 'audio/mp3'){
                // $audio_file = $data['upload_audio_dir'] . basename($_FILES['userfile']['name']);
                // move_uploaded_file($_FILES['userfile']['tmp_name'], $audio_file);
                // $data['audio'] = 1;
        // }
        // else if($_FILES['userfile']['type'] == 'image/png' || $_FILES['userfile']['type'] == 'image/gif' || $_FILES['userfile']['type'] == 'image/jpg' || $_FILES['userfile']['type'] == 'image/jpeg'){
                // $image_file = $data['upload_image_dir'] . basename($_FILES['userfile']['name']);
                // move_uploaded_file($_FILES['userfile']['tmp_name'], $image_file);
                // $data['images'] = 1;
        // }
        // else{                                       // mime-type NOT supported
            // echo "MIME type not supported."; 
            // $data['invalid_mime_type'] = 1;
        // }
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
}
?>
