<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

//require_once('application/core/authenticated_service.php');
class Meeple extends Authenticated_service {
	public function __construct() {
		parent::__construct ();
		
		$this->load->helper ( 'html' );
		$this->load->helper ( 'url' );
					
		
	}
	
	
	/**
	 * When the URI /create is typed in, this is loaded.
	 */
	public function index() {	
	
            $data ['title'] = "Test Authentication Controller";

            $data ['css_ref'] = array (
                            "css/create.css",
                            "css/login_modal.css"
            );
            $data ['extrascripts'] = array (
                            "js/jquery.validate.min.js",
                            "js/textext.min.js",				
                            "js/fms_analytics_create.js",

            );

            // SEO
            $meta_data = array (
                            array (
                                            'name' => 'description',
                                            'content' => "At Find My Song you can create superfantasticwonderful music together!" 
                            ),
                            array (
                                            'name' => 'keywords',
                                            'content' => 'Music,Songs,Editing,Collaboration,Learn, Discovery, Song Writer, Guitar, Singer, Producer, Music Producer, Piano, Classical Music', 
                            ) 
            );
            $data ['metadata'] = $meta_data;

            $this->load->view ( 'view_header', $data );

            $this->debug("index", "Running the index of child class of Authenticated_service");

            $this->load->view ( 'view_footer', $data );

	}
}
?>