<?php

class Fms_404 extends Authenticated_service {
	public function __construct() {
		parent::__construct();
		$this->load->helper ( 'url' );
		$this->load->helper ( 'html' );
	}
	
	public function index() {
		$this->output->set_status_header('404');
		$data ['title'] = "Server Busy";
		/* $data['font_ref'] = array(
				"Open+Sans:700",	
		); */
		$data ['css_ref'] = array (
				"css/404.css" 
		);
		/* $data ['extrascripts'] = array (
				"js/jquery.validate.min.js",
				"js/jquery-queryParser.min.js",
				"js/fms_home.js",
		); */
		
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
		$this->load->view ( 'view_404', $data );		
		//$this->load->view ( 'view_footer', $data );	
	}
}

?>