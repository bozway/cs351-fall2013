<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Wzong_test extends Authenticated_service {
    public function __construct() {
            parent::__construct ();

            $this->load->helper ('html');
            $this->load->helper ('url');	
            $this->load->helper ('form');
            $this->load->library('encrypt');
            $this->load->library( 'session' );
    }
    
    public function index () {
    	$data ['title'] = "Upload";
    	
    	$data ['css_ref'] = array (
    			//"http://blueimp.github.com/cdn/css/bootstrap.min.css",
    			//"css/test/style.css",
    			//"http://blueimp.github.com/cdn/css/bootstrap-responsive.min.css",
    			//'http://blueimp.github.com/Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css',
    			//'css/test/jquery.fileupload-ui.css',
    			//'css/test/jquery.fileupload-ui-noscript.css',
    	);
    	$data ['extrascripts'] = array (
    			//"//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
    			//"http://blueimp.github.com/cdn/js/bootstrap.min.js",
    			"js/test/vendor/jquery.ui.widget.js",
    			//"http://blueimp.github.com/JavaScript-Templates/tmpl.min.js",
    			//"http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js",
    			//"http://blueimp.github.com/JavaScript-Canvas-to-Blob/canvas-to-blob.min.js",
    			//"http://blueimp.github.com/Bootstrap-Image-Gallery/js/bootstrap-image-gallery.min.js",
    			"js/test/jquery.iframe-transport.js",
    			"js/test/jquery.fileupload.js",
    			"js/test/jquery.fileupload-process.js",
    			"js/test/jquery.fileupload-resize.js",
    			"js/test/jquery.fileupload-validate.js",
    			"js/test/jquery.fileupload-ui.js",
    			"js/test/main.js"
    	);
    	$this->load->view('view_header', $data);
    	$this->load->view('test/jquery_upload_demo');
    	$this->load->view('view_footer', $data);
    	
    }
    
    /**
     * 
     */
    public function upload() {
    	$this->load->library('uploadhandler');
    }
    
    public function imageCrop() {
    	
    	$responseObj = array();
    	
    	// Read Configurations
    	$file_name = $_POST['filename'];
    	$crop_x = $_POST['crop_x'];
    	$crop_y = $_POST['crop_y'];
    	$crop_w = $_POST['crop_w'];
    	$crop_h = $_POST['crop_h'];
    	$option = $_POST['option'];
    	
    	$userid = $this->session->userdata( 'userid' );
    	
    	// For remote file, use URL as path and rename the file accordingly
    	if($option == "LOCAL")
    		$file_path = "user_files/".$userid."/image/".$file_name;
    	if($option == "URL") {
    		$file_path = $file_name;
    		$file_name = "profile_url.jpg";
    	}
    	
    	// Get source image dimensions
    	if (!function_exists('getimagesize')) {
    		error_log('Function not found: getimagesize');
    		$responseObj['success'] = 0;
    		echo $this->encodeJSON($responseObj);
    		return;
    	}
    	list($img_width, $img_height) = @getimagesize($file_path);
    	if (!$img_width || !$img_height) {
    		$responseObj['success'] = 0;
    		$responseObj['filepath'] = $file_path;
    		echo $this->encodeJSON($responseObj);
    		return;
    	}
    	
    	// Create Destionation Image 
    	if (!function_exists('imagecreatetruecolor')) {
    		error_log('Function not found: imagecreatetruecolor');
    		$responseObj['success'] = 0;
    		echo $this->encodeJSON($responseObj);
    		return;
    	}
    	$new_img = @imagecreatetruecolor(160, 160);
    	
    	// Read source image
    	switch (strtolower(substr(strrchr($file_name, '.'), 1))) {
    		case 'jpg':
    		case 'jpeg':
    			$src_img = @imagecreatefromjpeg($file_path);
    			$write_image = 'imagejpeg';
    			$image_quality = 75;
    			$new_file_path = "user_files/".$userid."/image/".$file_name.".jpg";
    			break;
    		case 'gif':
    			@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
    			$src_img = @imagecreatefromgif($file_path);
    			$write_image = 'imagegif';
    			$image_quality = null;
    			$new_file_path = "user_files/".$userid."/image/".$file_name.".gif";
    			break;
    		case 'png':
    			@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
    			@imagealphablending($new_img, false);
    			@imagesavealpha($new_img, true);
    			$src_img = @imagecreatefrompng($file_path);
    			$write_image = 'imagepng';
    			$image_quality = 9;
    			$new_file_path = "user_files/".$userid."/image/".$file_name.".png";
    			break;
    		default:
    			$src_img = null;
    	}
    	
    	// Copy to destination
    	$success = $src_img && @imagecopyresampled(
    			$new_img,
    			$src_img,
    			0,
    			0,
    			$crop_x,
    			$crop_y,
    			160,
    			160,
    			$crop_w,
    			$crop_h
    	) && $write_image($new_img, $new_file_path, $image_quality);;
    	if($success) $responseObj['success'] = 1;
    	else {
    		$responseObj['success'] = 0;
    	}
    	echo $this->encodeJSON($responseObj);
    }
}
?>