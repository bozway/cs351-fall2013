<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

class MY_Controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		//$this->debug("construct", "Constructed the MY_Controller.");
	}	
	
	
	/**
	 * This function is for developer use only. A wrapper function on the 
	 * php echo function, to easily format debug messages.
	 * 
	 * 
	 * @param string $function	The function name.
	 * @param string $message	The debug output.
	 */
	protected function debug($function, $message) {		
		if ($ENVIRON === "DEVELOPMENT") {
			echo "<pre>$function::DEBUG:: $message\r\n</pre>";
		}		
	}
	
	/**
	 * This function will return the response as a JSON object.
	 * It is important to set the header before echo the JSON object
	 * because for some reason, sometimes it makes it into a string
	 * intead of a JSON object.
	 *
	 * @param 		Anything you want encoded as a JSON object
	 */
	protected function encodeJSON($encodeThis) {
		$this->output->set_content_type('application/json')->set_output(json_encode($encodeThis));
	}

}


require_once('application/core/Authenticated_service.php');

