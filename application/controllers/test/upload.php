<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller
{


	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

	}

	public function index()
	{
		$this->load->view('leo_img_upload_testing.php');
	}

   public function test(){
   	

	$up = new Uploadhandler();

   }
   
}