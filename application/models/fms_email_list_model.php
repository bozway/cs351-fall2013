<?php


class Fms_email_list_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

	/**
	 * Function to add a validated email to the database	 
	 * @parm  	$validatedEmail	The email parameter that has been validated and sanitized.
	 * @param 	$acquiredIP		The IP address captured from the user.
	 * @return	FALSE if email address was not added to the DB.
	 */
	public function add_email_to_list($validatedEmail, $acquiredIP) {
		$testUserEmailMatch = array ('email'=>$validatedEmail);
		$data = array(
				'email' => 	$validatedEmail,		
				'IP'=> 		$acquiredIP		
		);
		$query = $this->db->get_where('email_list',$testUserEmailMatch);
		if($query->num_rows() > 0){
			return FALSE;
		}else{
			$result =  $this->db->insert("email_list",$data);
			return TRUE;
		}
	}
	public function getAllRecords() {
		$query = $this->db->get('email_list');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return "Got nothing.";
		}	
	}
}

?>