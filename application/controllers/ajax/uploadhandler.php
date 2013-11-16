<?php
/*
 * jQuery File Upload Plugin PHP Class 6.6.2 https://github.com/blueimp/jQuery-File-Upload Copyright 2010, Sebastian Tschan https://blueimp.net Licensed under the MIT license: http://www.opensource.org/licenses/MIT
 */
class Uploadhandler extends Authenticated_service {
	
	const ERROR_FORMAT = 1;
	const ERROR_EXCEED_MAX = 2;
	
	protected $options;
	private $filetype;
	// PHP File Upload error message codes:
	// http://php.net/manual/en/features.file-upload.errors.php
	protected $error_messages = array (
			1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
			2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
			3 => 'The uploaded file was only partially uploaded',
			4 => 'No file was uploaded',
			6 => 'Missing a temporary folder',
			7 => 'Failed to write file to disk',
			8 => 'A PHP extension stopped the file upload',
			'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
			'max_file_size' => 'File is too big',
			'min_file_size' => 'File is too small',
			'accept_file_types' => 'Filetype not allowed',
			'max_number_of_files' => 'Maximum number of files exceeded',
			'max_width' => 'Image exceeds maximum width',
			'min_width' => 'Image requires a minimum width',
			'max_height' => 'Image exceeds maximum height',
			'min_height' => 'Image requires a minimum height' 
	);
	function __construct($options = null, $initialize = true, $error_messages = null) {
		parent::__construct ();
		$this->load->library ( 'session' );
		$this->load->library ( 'encrypt' );
		$this->load->model ( "docModels/fms_user_model" );
		$this->load->model ( "docModels/fms_file_model" );
		$this->load->model ( "docModels/fms_project_model" );
		$this->load->model ( "docModels/fms_project_file_model" );
		$this->load->model ( "docModels/fms_general_model" );
		$this->options = array (
				'script_url' => $this->get_full_url () . '/',
				// 'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/user_files/',
				'upload_dir' => 'user_files/',
				'upload_url' => $this->get_full_url () . '/user_files/',
				// 'upload_url' => $this->get_full_url().'/files/',
				'user_dirs' => true,
				'mkdir_mode' => 0755,
				'param_name' => 'files',
				// Set the following option to 'POST', if your server does not support
				// DELETE requests. This is a parameter sent to the client:
				'delete_type' => 'DELETE',
				'access_control_allow_origin' => '*',
				'access_control_allow_credentials' => false,
				'access_control_allow_methods' => array (
						'OPTIONS',
						'HEAD',
						'GET',
						'POST',
						'PUT',
						'PATCH',
						'DELETE' 
				),
				'access_control_allow_headers' => array (
						'Content-Type',
						'Content-Range',
						'Content-Disposition' 
				),
				// Enable to provide file downloads via GET requests to the PHP script:
				// 1. Set to 1 to download files via readfile method through PHP
				// 2. Set to 2 to send a X-Sendfile header for lighttpd/Apache
				// 3. Set to 3 to send a X-Accel-Redirect header for nginx
				// If set to 2 or 3, adjust the upload_url option to the base path of
				// the redirect parameter, e.g. '/files/'.
				'download_via_php' => false,
				// Read files in chunks to avoid memory limits when download_via_php
				// is enabled, set to 0 to disable chunked reading of files:
				'readfile_chunk_size' => 10 * 1024 * 1024, // 10 MiB
				                                           // Defines which files can be displayed inline when downloaded:
				'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
				// Defines which files (based on their names) are accepted for upload:
				'accept_file_types' => '/.+$/i',
				// The php.ini settings upload_max_filesize and post_max_size
				// take precedence over the following max_file_size setting:
				'max_file_size' => null,
				'min_file_size' => 1,
				// The maximum number of files for the upload directory:
				'max_number_of_files' => null,
				// Image resolution restrictions:
				'max_width' => null,
				'max_height' => null,
				'min_width' => 1,
				'min_height' => 1,
				// Set the following option to false to enable resumable uploads:
				'discard_aborted_uploads' => true,
				// Set to true to rotate images based on EXIF meta data, if available:
				'orient_image' => false,
				'image_versions' => array (
						// Uncomment the following version to restrict the size of
						// uploaded images:
						/*
						 * '' => array( 'max_width' => 1920, 'max_height' => 1200, 'jpeg_quality' => 95 ),
						 */
						// Uncomment the following to create medium sized images:
						/*
						 * 'medium' => array( 'max_width' => 800, 'max_height' => 600, 'jpeg_quality' => 80 ),
						 */
						'thumbnail' => array (
								// Uncomment the following to force the max
								// dimensions and e.g. create square thumbnails:
								// 'crop' => true,
								'max_width' => 80,
								'max_height' => 80 
						) 
				) 
		);
		if ($options) {
			$this->options = array_merge ( $this->options, $options );
		}
		if ($error_messages) {
			$this->error_messages = array_merge ( $this->error_messages, $error_messages );
		}
		// if ($initialize) {
		// $this->index();
		// }
		//print_r( $this->options);
	}
	public function index($param = 'user_image') {
		/**
		 * In order to upload a user/project file, the user must login first
		 * This part will check user login. And for uploading project files user should
		 * be owner of the project, so we also need permission check
		 * 
		 * @author Wei
		 */
		$this->options['param'] = $param;
		
		if(!$this->userId) {
			$responseObj = array('errorcode'	=> 1);
			$this->encodeJSON($responseObj);
			return;
		}
		$this->options['project_id'] = 0;
		if($param === 'project_image' || $param === 'project_audio') {
			$this->options['project_id'] = $_GET['project_id'];
			$project = $this->fms_project_model->getEntityById($this->options['project_id']);
			if(!$project) {
				$responseObj = array('errorcode'	=> 2);
				$this->encodeJSON($responseObj);
				return;
			}
			if($project->getOwner()->getId() != $this->userId) {
				$responseObj = array('errorcode'	=> 3);
				$this->encodeJSON($responseObj);
				return;
			}
		}
		
		/** 
		 * The file path for user file or project file, and for audio or image 
		 * will be different. This part will determine the file path
		 */
		switch ($param) {
			case 'user_image':
				$this->filetype = 'image';
				break;
			case 'user_audio':
				$this->filetype = 'audio';
				break;
			case 'user_cover':
				$this->filetype = 'cover';
				break;
			case 'project_image':
				$this->filetype = 'image';
				$this->options['upload_dir'] = 'project_files/';
				$this->options['upload_url'] = $this->get_full_url () . '/project_files/';
				$this->options['project_id'] = $this->options['project_id'];
				break;
			case 'project_audio':
				$this->filetype = 'audio';
				$this->options['upload_dir'] = 'project_files/';	
				$this->options['upload_url'] = $this->get_full_url () . '/project_files/';
				$this->options['project_id'] = $this->options['project_id'];
				break;
			default:
				$this->filetype = 'default';
		}
		
		// Check Method
		switch ($this->get_server_var ( 'REQUEST_METHOD' )) {
			case 'OPTIONS' :
			case 'HEAD' :
				$this->head ();
				break;
			case 'GET' :
				$this->get ();
				break;
			case 'PATCH' :
			case 'PUT' :
			case 'POST' :
				$this->post ();
				break;
			case 'DELETE' :
				$this->delete ();
				break;
			default :
				$this->header ( 'HTTP/1.1 405 Method Not Allowed' );
		}
	}
	protected function get_full_url() {
		$https = ! empty ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] !== 'off';
		
		return ($https ? 'https://' : 'http://') . (! empty ( $_SERVER ['REMOTE_USER'] ) ? $_SERVER ['REMOTE_USER'] . '@' : '') . (isset ( $_SERVER ['HTTP_HOST'] ) ? $_SERVER ['HTTP_HOST'] : ($_SERVER ['SERVER_NAME'] . ($https && $_SERVER ['SERVER_PORT'] === 443 || $_SERVER ['SERVER_PORT'] === 80 ? '' : ':' . $_SERVER ['SERVER_PORT']))) . substr ( $_SERVER ['SCRIPT_NAME'], 0, strrpos ( $_SERVER ['SCRIPT_NAME'], '/' ) );
	}
	protected function get_user_id() {
		// @session_start();
		$uid = $this->userId;
		if ($uid) {
			return $uid;
		} else {
			return - 1;
		}
		// return 1;
	}
	protected function get_user_path() {
		if ($this->options ['upload_dir'] === 'user_files/') {
			return $this->get_user_id () . '/' . $this->filetype . '/';
		}
		if ($this->options ['upload_dir'] === 'project_files/') {
			return $this->options['project_id'] . '/' . $this->filetype . '/';
		}
		return '';
	}
	protected function get_upload_path($file_name = null, $version = null) {
		$file_name = $file_name ? $file_name : '';
		$version_path = empty ( $version ) ? '' : $version . '/';
		return $this->options ['upload_dir'] . $this->get_user_path () . $version_path . $file_name;
	}
	protected function get_query_separator($url) {
		return strpos ( $url, '?' ) === false ? '?' : '&';
	}
	protected function get_download_url($file_name, $version = null, $direct = false) {
		if (! $direct && $this->options ['download_via_php']) {
			$url = $this->options ['script_url'] . $this->get_query_separator ( $this->options ['script_url'] ) . 'file=' . rawurlencode ( $file_name );
			if ($version) {
				$url .= '&version=' . rawurlencode ( $version );
			}
			return $url . '&download=1';
		}
		$version_path = empty ( $version ) ? '' : rawurlencode ( $version ) . '/';
		return $this->options ['upload_url'] . $this->get_user_path () . $version_path . rawurlencode ( $file_name );
	}
	protected function set_additional_file_properties($file) {
		$file->delete_url = $this->options ['script_url'] . $this->get_query_separator ( $this->options ['script_url'] ) . 'file=' . rawurlencode ( $file->name );
		$file->delete_type = $this->options ['delete_type'];
		if ($file->delete_type !== 'DELETE') {
			$file->delete_url .= '&_method=DELETE';
		}
		if ($this->options ['access_control_allow_credentials']) {
			$file->delete_with_credentials = true;
		}
	}
	
	// Fix for overflowing signed 32 bit integers,
	// works for sizes up to 2^32-1 bytes (4 GiB - 1):
	protected function fix_integer_overflow($size) {
		if ($size < 0) {
			$size += 2.0 * (PHP_INT_MAX + 1);
		}
		return $size;
	}
	protected function get_file_size($file_path, $clear_stat_cache = false) {
		if ($clear_stat_cache) {
			clearstatcache ( true, $file_path );
		}
		return $this->fix_integer_overflow ( filesize ( $file_path ) );
	}
	protected function is_valid_file_object($file_name) {
		$file_path = $this->get_upload_path ( $file_name );
		if (is_file ( $file_path ) && $file_name [0] !== '.') {
			return true;
		}
		return false;
	}
	protected function get_file_object($file_name) {
		if ($this->is_valid_file_object ( $file_name )) {
			$file = new stdClass ();
			$file->name = $file_name;
			$file->size = $this->get_file_size ( $this->get_upload_path ( $file_name ) );
			$file->url = $this->get_download_url ( $file->name );
			foreach ( $this->options ['image_versions'] as $version => $options ) {
				if (! empty ( $version )) {
					if (is_file ( $this->get_upload_path ( $file_name, $version ) )) {
						$file->{$version . '_url'} = $this->get_download_url ( $file->name, $version );
					}
				}
			}
			$this->set_additional_file_properties ( $file );
			return $file;
		}
		return null;
	}
	protected function get_file_objects($iteration_method = 'get_file_object') {
		$upload_dir = $this->get_upload_path ();
		if (! is_dir ( $upload_dir )) {
			return array ();
		}
		return array_values ( array_filter ( array_map ( array (
				$this,
				$iteration_method 
		), scandir ( $upload_dir ) ) ) );
	}
	protected function count_file_objects() {
		return count ( $this->get_file_objects ( 'is_valid_file_object' ) );
	}
	protected function create_scaled_image($file_name, $version, $options) {
		$file_path = $this->get_upload_path ( $file_name );
		if (! empty ( $version )) {
			$version_dir = $this->get_upload_path ( null, $version );
			if (! is_dir ( $version_dir )) {
				mkdir ( $version_dir, $this->options ['mkdir_mode'], true );
			}
			$new_file_path = $version_dir . '/' . $file_name;
		} else {
			$new_file_path = $file_path;
		}
		if (! function_exists ( 'getimagesize' )) {
			error_log ( 'Function not found: getimagesize' );
			return false;
		}
		list ( $img_width, $img_height ) = @getimagesize ( $file_path );
		if (! $img_width || ! $img_height) {
			return false;
		}
		$max_width = $options ['max_width'];
		$max_height = $options ['max_height'];
		$scale = min ( $max_width / $img_width, $max_height / $img_height );
		if ($scale >= 1) {
			if ($file_path !== $new_file_path) {
				return copy ( $file_path, $new_file_path );
			}
			return true;
		}
		if (! function_exists ( 'imagecreatetruecolor' )) {
			error_log ( 'Function not found: imagecreatetruecolor' );
			return false;
		}
		if (empty ( $options ['crop'] )) {
			$new_width = $img_width * $scale;
			$new_height = $img_height * $scale;
			$dst_x = 0;
			$dst_y = 0;
			$new_img = imagecreatetruecolor ( $new_width, $new_height );
		} else {
			if (($img_width / $img_height) >= ($max_width / $max_height)) {
				$new_width = $img_width / ($img_height / $max_height);
				$new_height = $max_height;
			} else {
				$new_width = $max_width;
				$new_height = $img_height / ($img_width / $max_width);
			}
			$dst_x = 0 - ($new_width - $max_width) / 2;
			$dst_y = 0 - ($new_height - $max_height) / 2;
			$new_img = imagecreatetruecolor ( $max_width, $max_height );
		}
		switch (strtolower ( substr ( strrchr ( $file_name, '.' ), 1 ) )) {
			case 'jpg' :
			case 'jpeg' :
				$src_img = imagecreatefromjpeg ( $file_path );
				$write_image = 'imagejpeg';
				$image_quality = isset ( $options ['jpeg_quality'] ) ? $options ['jpeg_quality'] : 75;
				break;
			case 'gif' :
				imagecolortransparent ( $new_img, imagecolorallocate ( $new_img, 0, 0, 0 ) );
				$src_img = imagecreatefromgif ( $file_path );
				$write_image = 'imagegif';
				$image_quality = null;
				break;
			case 'png' :
				imagecolortransparent ( $new_img, imagecolorallocate ( $new_img, 0, 0, 0 ) );
				imagealphablending ( $new_img, false );
				imagesavealpha ( $new_img, true );
				$src_img = imagecreatefrompng ( $file_path );
				$write_image = 'imagepng';
				$image_quality = isset ( $options ['png_quality'] ) ? $options ['png_quality'] : 9;
				break;
			default :
				imagedestroy ( $new_img );
				return false;
		}
		$success = imagecopyresampled ( $new_img, $src_img, $dst_x, $dst_y, 0, 0, $new_width, $new_height, $img_width, $img_height ) && $write_image ( $new_img, $new_file_path, $image_quality );
		// Free up memory (imagedestroy does not delete files):
		imagedestroy ( $src_img );
		imagedestroy ( $new_img );
		return $success;
	}
	protected function get_error_message($error) {
		return array_key_exists ( $error, $this->error_messages ) ? $this->error_messages [$error] : $error;
	}
	function get_config_bytes($val) {
		$val = trim ( $val );
		$last = strtolower ( $val [strlen ( $val ) - 1] );
		switch ($last) {
			case 'g' :
				$val *= 1024;
			case 'm' :
				$val *= 1024;
			case 'k' :
				$val *= 1024;
		}
		return $this->fix_integer_overflow ( $val );
	}
	protected function validate($uploaded_file, $file, $error, $index) {
		if ($error) {
			$file->error = $this->get_error_message ( $error );
			return false;
		}
		$content_length = $this->fix_integer_overflow ( intval ( $this->get_server_var ( 'CONTENT_LENGTH' ) ) );
		$post_max_size = $this->get_config_bytes ( ini_get ( 'post_max_size' ) );
		if ($post_max_size && ($content_length > $post_max_size)) {
			$file->error = $this->get_error_message ( 'post_max_size' );
			return false;
		}
		if (! preg_match ( $this->options ['accept_file_types'], $file->name )) {
			$file->error = $this->get_error_message ( 'accept_file_types' );
			return false;
		}
		if ($uploaded_file && is_uploaded_file ( $uploaded_file )) {
			$file_size = $this->get_file_size ( $uploaded_file );
		} else {
			$file_size = $content_length;
		}
		if ($this->options ['max_file_size'] && ($file_size > $this->options ['max_file_size'] || $file->size > $this->options ['max_file_size'])) {
			$file->error = $this->get_error_message ( 'max_file_size' );
			return false;
		}
		if ($this->options ['min_file_size'] && $file_size < $this->options ['min_file_size']) {
			$file->error = $this->get_error_message ( 'min_file_size' );
			return false;
		}
		if (is_int ( $this->options ['max_number_of_files'] ) && ($this->count_file_objects () >= $this->options ['max_number_of_files'])) {
			$file->error = $this->get_error_message ( 'max_number_of_files' );
			return false;
		}
		list ( $img_width, $img_height ) = @getimagesize ( $uploaded_file );
		if (is_int ( $img_width )) {
			if ($this->options ['max_width'] && $img_width > $this->options ['max_width']) {
				$file->error = $this->get_error_message ( 'max_width' );
				return false;
			}
			if ($this->options ['max_height'] && $img_height > $this->options ['max_height']) {
				$file->error = $this->get_error_message ( 'max_height' );
				return false;
			}
			if ($this->options ['min_width'] && $img_width < $this->options ['min_width']) {
				$file->error = $this->get_error_message ( 'min_width' );
				return false;
			}
			if ($this->options ['min_height'] && $img_height < $this->options ['min_height']) {
				$file->error = $this->get_error_message ( 'min_height' );
				return false;
			}
		}
		return true;
	}
	protected function upcount_name_callback($matches) {
		$index = isset ( $matches [1] ) ? intval ( $matches [1] ) + 1 : 1;
		$ext = isset ( $matches [2] ) ? $matches [2] : '';
		return ' (' . $index . ')' . $ext;
	}
	protected function upcount_name($name) {
		return preg_replace_callback ( '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/', array (
				$this,
				'upcount_name_callback' 
		), $name, 1 );
	}
	protected function get_unique_filename($name, $type = null, $index = null, $content_range = null) {
		while ( is_dir ( $this->get_upload_path ( $name ) ) ) {
			$name = $this->upcount_name ( $name );
		}
		// Keep an existing filename if this is part of a chunked upload:
		$uploaded_bytes = $this->fix_integer_overflow ( intval ( $content_range [1] ) );
		while ( is_file ( $this->get_upload_path ( $name ) ) ) {
			if ($uploaded_bytes === $this->get_file_size ( $this->get_upload_path ( $name ) )) {
				break;
			}
			$name = $this->upcount_name ( $name );
		}
		return $name;
	}
	protected function trim_file_name($name, $type = null, $index = null, $content_range = null) {
		// Remove path information and dots around the filename, to prevent uploading
		// into different directories or replacing hidden system files.
		// Also remove control characters and spaces (\x00..\x20) around the filename:
		$name = trim ( basename ( stripslashes ( $name ) ), ".\x00..\x20" );
		// Use a timestamp for empty filenames:
		if (! $name) {
			$name = str_replace ( '.', '-', microtime ( true ) );
		}
		// Add missing file extension for known image types:
		if (strpos ( $name, '.' ) === false && preg_match ( '/^image\/(gif|jpe?g|png)/', $type, $matches )) {
			$name .= '.' . $matches [1];
		}
		return $name;
	}
	protected function get_file_name($name, $type = null, $index = null, $content_range = null) {
		return $this->get_unique_filename ( $this->trim_file_name ( $name, $type, $index, $content_range ), $type, $index, $content_range );
	}
	protected function handle_form_data($file, $index) {
		// Handle form data, e.g. $_REQUEST['description'][$index]
	}
	protected function imageflip($image, $mode) {
		if (function_exists ( 'imageflip' )) {
			return imageflip ( $image, $mode );
		}
		$new_width = $src_width = imagesx ( $image );
		$new_height = $src_height = imagesy ( $image );
		$new_img = imagecreatetruecolor ( $new_width, $new_height );
		$src_x = 0;
		$src_y = 0;
		switch ($mode) {
			case '1' : // flip on the horizontal axis
				$src_y = $new_height - 1;
				$src_height = - $new_height;
				break;
			case '2' : // flip on the vertical axis
				$src_x = $new_width - 1;
				$src_width = - $new_width;
				break;
			case '3' : // flip on both axes
				$src_y = $new_height - 1;
				$src_height = - $new_height;
				$src_x = $new_width - 1;
				$src_width = - $new_width;
				break;
			default :
				return $image;
		}
		imagecopyresampled ( $new_img, $image, 0, 0, $src_x, $src_y, $new_width, $new_height, $src_width, $src_height );
		// Free up memory (imagedestroy does not delete files):
		imagedestroy ( $image );
		return $new_img;
	}
	protected function orient_image($file_path) {
		if (! function_exists ( 'exif_read_data' )) {
			return false;
		}
		$exif = @exif_read_data ( $file_path );
		if ($exif === false) {
			return false;
		}
		$orientation = intval ( @$exif ['Orientation'] );
		if ($orientation < 2 || $orientation > 8) {
			return false;
		}
		$image = imagecreatefromjpeg ( $file_path );
		switch ($orientation) {
			case 2 :
				$image = $this->imageflip ( $image, defined ( 'IMG_FLIP_VERTICAL' ) ? IMG_FLIP_VERTICAL : 2 );
				break;
			case 3 :
				$image = imagerotate ( $image, 180, 0 );
				break;
			case 4 :
				$image = $this->imageflip ( $image, defined ( 'IMG_FLIP_HORIZONTAL' ) ? IMG_FLIP_HORIZONTAL : 1 );
				break;
			case 5 :
				$image = $this->imageflip ( $image, defined ( 'IMG_FLIP_HORIZONTAL' ) ? IMG_FLIP_HORIZONTAL : 1 );
				$image = imagerotate ( $image, 270, 0 );
				break;
			case 6 :
				$image = imagerotate ( $image, 270, 0 );
				break;
			case 7 :
				$image = $this->imageflip ( $image, defined ( 'IMG_FLIP_VERTICAL' ) ? IMG_FLIP_VERTICAL : 2 );
				$image = imagerotate ( $image, 270, 0 );
				break;
			case 8 :
				$image = imagerotate ( $image, 90, 0 );
				break;
			default :
				return false;
		}
		$success = imagejpeg ( $image, $file_path );
		// Free up memory (imagedestroy does not delete files):
		imagedestroy ( $image );
		return $success;
	}
	protected function handle_image_file($file_path, $file) {
		if ($this->options ['orient_image']) {
			$this->orient_image ( $file_path );
		}
		$failed_versions = array ();
		foreach ( $this->options ['image_versions'] as $version => $options ) {
			if ($this->create_scaled_image ( $file->name, $version, $options )) {
				if (! empty ( $version )) {
					$file->{$version . '_url'} = $this->get_download_url ( $file->name, $version );
				} else {
					$file->size = $this->get_file_size ( $file_path, true );
				}
			} else {
				$failed_versions [] = $version;
			}
		}
		switch (count ( $failed_versions )) {
			case 0 :
				break;
			case 1 :
				$file->error = 'Failed to create scaled version: ' . $failed_versions [0];
				break;
			default :
				$file->error = 'Failed to create scaled versions: ' . implode ( $failed_versions, ', ' );
		}
	}
	protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) {
		// echo "file type is ".$type;
		$file = new stdClass ();
		$file->name = $this->get_file_name ( $name, $type, $index, $content_range );
		$file->size = $this->fix_integer_overflow ( intval ( $size ) );
		$file->type = $type;
		if ($this->validate ( $uploaded_file, $file, $error, $index )) {
			$this->handle_form_data ( $file, $index );
			$upload_dir = $this->get_upload_path ();
			if (! is_dir ( $upload_dir )) {
				mkdir ( $upload_dir, $this->options ['mkdir_mode'], true );
			}
			$file_path = $this->get_upload_path ( $file->name );			
			$append_file = $content_range && is_file ( $file_path ) && $file->size > $this->get_file_size ( $file_path );
			if ($uploaded_file && is_uploaded_file ( $uploaded_file )) {
				// multipart/formdata uploads (POST method uploads)
				if ($append_file) {
					file_put_contents ( $file_path, fopen ( $uploaded_file, 'r' ), FILE_APPEND );
				} else {
					move_uploaded_file ( $uploaded_file, $file_path );
				}
			} else {
				// Non-multipart uploads (PUT method support)
				file_put_contents ( $file_path, fopen ( 'php://input', 'r' ), $append_file ? FILE_APPEND : 0 );
			}
			$file_size = $this->get_file_size ( $file_path, $append_file );
			if ($file_size === $file->size) {
				$file->url = $this->get_download_url ( $file->name );
				list ( $img_width, $img_height ) = @getimagesize ( $file_path );
				if (is_int ( $img_width ) && preg_match ( $this->options ['inline_file_types'], $file->name )) {
					$this->handle_image_file ( $file_path, $file );
				}
			} else {
				$file->size = $file_size;
				if (! $content_range && $this->options ['discard_aborted_uploads']) {
					unlink ( $file_path );
					$file->error = 'abort';
				}
			}
			$this->set_additional_file_properties ( $file );
		}
		
// ------------------------ Begin of Updating DB --------------------------------------

		// Crop the image if upload is successful
		//		NOT IMPLEMENTED YET
		//		duplicate the file instead
		$suffix = strtolower(substr(strrchr($file->name, '.'), 1));
		if($suffix == 'jpg' || $suffix == 'jpeg' || $suffix=='gif' || $suffix=='png') {
			copy($file_path, $file_path. '.' .$suffix );
			$status = $this->autoCrop(array(
					'filename'	=> $file->name,
					'option'	=> $this->options['param'],
					'project_id'	=> $this->options['project_id'],
			));
			
			if($status != 0) {
				echo $status;
				return;
			}
		}
		
		// For validation of FILE MIME TYPE
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$fmime = finfo_file($finfo, $file_path);
		$file->file_mime = $fmime;
		$file->file_errorcode = 0;
		
		if(!isset($file->error)) {
			
			$file->file_path2 = $file_path;
			/**
			 * User Image file
			 * 
			 * This part is to ensure the record is added to the file table; the ProfilePicture 
			 * of user table is updated; old profile picture is removed from file table; and 
			 * corresponding file is removed from the server. Type = 1 means profile picture. 
			 * Subtype is of no use in this case.
			 * 
			 * @author Wei
			 */ 
			if ($this->options['param'] === 'user_image') {
				if(strpos($fmime,"image")===0) {
					// Create new file entity
					$dataArray = array ();
					$dataArray ["fileName"] = $file->name;
					$dataArray ["uploadIp"] = $_SERVER ['REMOTE_ADDR'];
					$dataArray ["filePath"] = $upload_dir;
					$dataArray ["type"]		= 1;
					$dataArray ["subtype"]	= 100;
					$uploadedFile = $this->fms_file_model->addFile ( $this->get_user_id (), $dataArray );
					
					// Get Previous Profile Picture for deletion
					$user = $this->fms_user_model->getEntityById( $this->get_user_id() );
					$profilePicture = $user->getProfilePicture();
					
					// Set new profile picture
					$file->id = $uploadedFile->getId ();
					$user->setProfilePicture ($uploadedFile );
					
					// Delete the old file and record if exists
					if($profilePicture) {
						// Delete uploaded file
						$oldFilePath = $profilePicture->getPath() . $profilePicture->getName();
						if(file_exists($oldFilePath)) {
							unlink ( $oldFilePath );
						}
						
						// Delete cropped file
						$croppedImagePath = $oldFilePath.'.'.strtolower(substr(strrchr($profilePicture->getName(), '.'), 1));
						if(file_exists($croppedImagePath)) {
							unlink ( $croppedImagePath );
						}
						$this->fms_general_model->remove($profilePicture);
					}
				} else {
					$file->file_errorcode = 1;
					unlink($file_path);
					unlink($file_path.'.jpg');
				}
			}
			
			/**
			 * User Cover photo
			 *
			 * This part is to ensure the record is added to the file table; the CoverPhoto
			 * of user table is updated; old cover photo is removed from file table; and
			 * corresponding file is removed from the server. Type = 2 means cover photo.
			 * Subtype is of no use in this case.
			 *
			 * @author Wei
			 */
			if ($this->options['param'] === 'user_cover') {
				if(strpos($fmime,"image")===0) {
					// Create new file entity
					$dataArray = array ();
					$dataArray ["fileName"] = $file->name;
					$dataArray ["uploadIp"] = $_SERVER ['REMOTE_ADDR'];
					$dataArray ["filePath"] = $upload_dir;
					$dataArray ["type"]		= 2;
					$dataArray ["subtype"]	= 100;
					$uploadedFile = $this->fms_file_model->addFile ( $this->get_user_id (), $dataArray );
					
					// Get Previous Profile Picture for deletion
					$user = $this->fms_user_model->getEntityById( $this->get_user_id() );
					$profilePicture = $user->getCoverPhoto();
				
					// Set new profile picture
					$file->id = $uploadedFile->getId ();
					$user->setCoverPhoto ($uploadedFile );
				
					// Delete the old file and record if exists
					if($profilePicture) {
						// Delete uploaded file
						$oldFilePath = $profilePicture->getPath() . $profilePicture->getName();
						if(file_exists($oldFilePath)) {
							unlink ( $oldFilePath );
						}
						$this->fms_general_model->remove($profilePicture);
						
						// Delete cropped file
						$croppedImagePath = $oldFilePath.'.'.strtolower(substr(strrchr($profilePicture->getName(), '.'), 1));
						if(file_exists($croppedImagePath)) {
							unlink ( $croppedImagePath );
						}
					}
				} else {
					$file->file_errorcode = 1;
					unlink($file_path);
					unlink($file_path.'.jpg');
				}
			}
			
			/**
			 * User audio file
			 * 
			 * This part is to ensure new record is added to the file table and type and subtype
			 * are set properly. Type = 0 means spotlight audio. Subtype is the ranking of audio.
			 */ 
			if ($this->options['param'] === 'user_audio') {
				// mime type application/octet-stream was not valid on the server, it is showing as audio/mpeg for mp3
				if(strpos($fmime,"audio")===0 || strpos($fmime,"application/octet-stream")===0) {
					// Get audio ranking
					$user = $this->fms_user_model->getEntityById( $this->get_user_id() );
					$ranking = count($this->fms_file_model->getAudioFiles($user) ) + 1;
					
					if($ranking > 3) {
						unlink($file_path);
						$file->file_errorcode = $this::ERROR_EXCEED_MAX;
					} else {
						// Create new file entity
						$dataArray = array ();
						$dataArray ["fileName"] = $file->name;
						$dataArray ["uploadIp"] = $_SERVER ['REMOTE_ADDR'];
						$dataArray ["filePath"] = $upload_dir;
						$dataArray ["type"]		= 0;
						$dataArray ["subtype"]	= $ranking;
						$uploadedFile = $this->fms_file_model->addFile ( $this->get_user_id (), $dataArray );
						
						// Add new spotlight
						$file->id = $uploadedFile->getId ();
						$user->addFile($uploadedFile);
					}
					
				} else {
					unlink($file_path);
					$file->file_errorcode = $this::ERROR_FORMAT;
				}
			}
			
			/**
			 * Project image file
			 * 
			 * This part is to ensure new record is added to the projectfile table the profilepicture
			 * of project table is updated and existing file and records are deleted from DB or server.
			 * Type = 1 means profile picture.
			 * 
			 */
			if($this->options['param'] === 'project_image') {
				if(strpos($fmime,"image")===0) {
					// Create new file entity
					$dataArray = array ();
					$dataArray ["fileName"] = $file->name;
					$dataArray ["uploadIp"] = $_SERVER ['REMOTE_ADDR'];
					$dataArray ["filePath"] = $upload_dir;
					$dataArray ["type"]		= 1;
					$dataArray ["subtype"]	= 100;
					$uploadedFile = $this->fms_project_file_model->addFile ( $this->get_user_id (), $dataArray );
					
					// Get Previous Profile Picture for deletion
					$project = $this->fms_project_model->getEntityById( $this->options['project_id'] );
					$profilePicture = $project->getPhoto();
					
					// Set new profile picture
					$file->id = $uploadedFile->getId ();
					$project->setPhoto ($uploadedFile );
					$uploadedFile->setType(0);
					
					// Delete the old file and record if exists
					if($profilePicture) {
						// Delete uploaded file
						$oldFilePath = $profilePicture->getPath() . $profilePicture->getName();
						if(file_exists($oldFilePath)) {
							unlink ( $oldFilePath );
						}
						$this->fms_general_model->remove($profilePicture);
						
						// Delete cropped file
						$croppedImagePath = $oldFilePath.'.'.strtolower(substr(strrchr($profilePicture->getName(), '.'), 1));
						if(file_exists($croppedImagePath)) {
							unlink ( $croppedImagePath );
						}
					}
				} else {
					$file->file_errorcode = 1;
					unlink($file_path);
					unlink($file_path.'.jpg');
				}
			}
			
			/**
			 * Project audio file
			 *
			 * This part is to ensure new record is added to the projectfile table the files
			 * of project entity is updated and existing file and records are deleted from 
			 * DB or server. Type = 0 means project audio file.
			 *
			 */
			if($this->options['param'] === 'project_audio') {
				if(strpos($fmime,"audio")===0 || strpos($fmime,"application/octet-stream")===0) {
					// Get audio ranking
					$project = $this->fms_project_model->getEntityById( $this->options['project_id'] );
					$ranking = count($this->fms_project_file_model->getAudioFiles($project) ) + 1;
					
					// Validation (at most 3)
					if($ranking > 3) {
						unlink($file_path);
						$file->file_errorcode = $this::ERROR_EXCEED_MAX;
					} else {
						// Create new file entity
						$dataArray = array ();
						$dataArray ["fileName"] = $file->name;
						$dataArray ["uploadIp"] = $_SERVER ['REMOTE_ADDR'];
						$dataArray ["filePath"] = $upload_dir;
						$dataArray ["type"]		= 0;
						$dataArray ["subtype"]	= $ranking;
						$uploadedFile = $this->fms_project_file_model->addFile ( $this->get_user_id (), $dataArray );
						
						// Add new spotlight
						$file->id = $uploadedFile->getId ();
						$project->addFile($uploadedFile);
						$uploadedFile->setOwner($project);
					}
				} else {
					unlink($file_path);
					$file->file_errorcode = $this::ERROR_FORMAT;
				}
			}
			
			$this->fms_general_model->flush();
		}
// ------------------------ End of Updating DB --------------------------------------

		
		
		return $file;
	}
	protected function readfile($file_path) {
		$file_size = $this->get_file_size ( $file_path );
		$chunk_size = $this->options ['readfile_chunk_size'];
		if ($chunk_size && $file_size > $chunk_size) {
			$handle = fopen ( $file_path, 'rb' );
			while ( ! feof ( $handle ) ) {
				echo fread ( $handle, $chunk_size );
				ob_flush ();
				flush ();
			}
			fclose ( $handle );
			return $file_size;
		}
		return readfile ( $file_path );
	}
	protected function body($str) {
		echo $str;
	}
	protected function header($str) {
		header ( $str );
	}
	protected function get_server_var($id) {
		return isset ( $_SERVER [$id] ) ? $_SERVER [$id] : '';
	}
	protected function generate_response($content, $print_response = true) {
		if ($print_response) {
			$json = json_encode ( $content );
			$redirect = isset ( $_REQUEST ['redirect'] ) ? stripslashes ( $_REQUEST ['redirect'] ) : null;
			if ($redirect) {
				$this->header ( 'Location: ' . sprintf ( $redirect, rawurlencode ( $json ) ) );
				return;
			}
			$this->head ();
			if ($this->get_server_var ( 'HTTP_CONTENT_RANGE' )) {
				$files = isset ( $content [$this->options ['param_name']] ) ? $content [$this->options ['param_name']] : null;
				if ($files && is_array ( $files ) && is_object ( $files [0] ) && $files [0]->size) {
					$this->header ( 'Range: 0-' . ($this->fix_integer_overflow ( intval ( $files [0]->size ) ) - 1) );
				}
			}
			$this->body ( $json );
		}
		return $content;
	}
	protected function get_version_param() {
		return isset ( $_GET ['version'] ) ? basename ( stripslashes ( $_GET ['version'] ) ) : null;
	}
	protected function get_file_name_param() {
		return "Chrysanthemum.jpg";
		return isset ( $_GET ['file'] ) ? basename ( stripslashes ( $_GET ['file'] ) ) : null;
	}
	protected function get_file_type($file_path) {
		switch (strtolower ( pathinfo ( $file_path, PATHINFO_EXTENSION ) )) {
			case 'jpeg' :
			case 'jpg' :
				return 'image/jpeg';
			case 'png' :
				return 'image/png';
			case 'gif' :
				return 'image/gif';
			default :
				return '';
		}
	}
	protected function download() {
		switch ($this->options ['download_via_php']) {
			case 1 :
				$redirect_header = null;
				break;
			case 2 :
				$redirect_header = 'X-Sendfile';
				break;
			case 3 :
				$redirect_header = 'X-Accel-Redirect';
				break;
			default :
				return $this->header ( 'HTTP/1.1 403 Forbidden' );
		}
		$file_name = $this->get_file_name_param ();
		if (! $this->is_valid_file_object ( $file_name )) {
			return $this->header ( 'HTTP/1.1 404 Not Found' );
		}
		if ($redirect_header) {
			return $this->header ( $redirect_header . ': ' . $this->get_download_url ( $file_name, $this->get_version_param (), true ) );
		}
		$file_path = $this->get_upload_path ( $file_name, $this->get_version_param () );
		if (! preg_match ( $this->options ['inline_file_types'], $file_name )) {
			$this->header ( 'Content-Description: File Transfer' );
			$this->header ( 'Content-Type: application/octet-stream' );
			$this->header ( 'Content-Disposition: attachment; filename="' . $file_name . '"' );
			$this->header ( 'Content-Transfer-Encoding: binary' );
		} else {
			// Prevent Internet Explorer from MIME-sniffing the content-type:
			$this->header ( 'X-Content-Type-Options: nosniff' );
			$this->header ( 'Content-Type: ' . $this->get_file_type ( $file_path ) );
			$this->header ( 'Content-Disposition: inline; filename="' . $file_name . '"' );
		}
		$this->header ( 'Content-Length: ' . $this->get_file_size ( $file_path ) );
		$this->header ( 'Last-Modified: ' . gmdate ( 'D, d M Y H:i:s T', filemtime ( $file_path ) ) );
		$this->readfile ( $file_path );
	}
	protected function send_content_type_header() {
		$this->header ( 'Vary: Accept' );
		if (strpos ( $this->get_server_var ( 'HTTP_ACCEPT' ), 'application/json' ) !== false) {
			$this->header ( 'Content-type: application/json' );
		} else {
			$this->header ( 'Content-type: text/plain' );
		}
	}
	protected function send_access_control_headers() {
		$this->header ( 'Access-Control-Allow-Origin: ' . $this->options ['access_control_allow_origin'] );
		$this->header ( 'Access-Control-Allow-Credentials: ' . ($this->options ['access_control_allow_credentials'] ? 'true' : 'false') );
		$this->header ( 'Access-Control-Allow-Methods: ' . implode ( ', ', $this->options ['access_control_allow_methods'] ) );
		$this->header ( 'Access-Control-Allow-Headers: ' . implode ( ', ', $this->options ['access_control_allow_headers'] ) );
	}
	public function head() {
		$this->header ( 'Pragma: no-cache' );
		$this->header ( 'Cache-Control: no-store, no-cache, must-revalidate' );
		$this->header ( 'Content-Disposition: inline; filename="files.json"' );
		// Prevent Internet Explorer from MIME-sniffing the content-type:
		$this->header ( 'X-Content-Type-Options: nosniff' );
		if ($this->options ['access_control_allow_origin']) {
			$this->send_access_control_headers ();
		}
		$this->send_content_type_header ();
	}
	public function get($print_response = true) {
		if ($print_response && isset ( $_GET ['download'] )) {
			return $this->download ();
		}
		$file_name = $this->get_file_name_param ();
		if ($file_name) {
			$response = array (
					substr ( $this->options ['param_name'], 0, - 1 ) => $this->get_file_object ( $file_name ) 
			);
		} else {
			$response = array (
					$this->options ['param_name'] => $this->get_file_objects () 
			);
		}
		return $this->generate_response ( $response, $print_response );
	}
	public function post($print_response = true) {
		if (isset ( $_REQUEST ['_method'] ) && $_REQUEST ['_method'] === 'DELETE') {
			return $this->delete ( $print_response );
		}
		$upload = isset ( $_FILES [$this->options ['param_name']] ) ? $_FILES [$this->options ['param_name']] : null;
		// Parse the Content-Disposition header, if available:
		$file_name = $this->get_server_var ( 'HTTP_CONTENT_DISPOSITION' ) ? rawurldecode ( preg_replace ( '/(^[^"]+")|("$)/', '', $this->get_server_var ( 'HTTP_CONTENT_DISPOSITION' ) ) ) : null;
		// Parse the Content-Range header, which has the following form:
		// Content-Range: bytes 0-524287/2000000
		$content_range = $this->get_server_var ( 'HTTP_CONTENT_RANGE' ) ? preg_split ( '/[^0-9]+/', $this->get_server_var ( 'HTTP_CONTENT_RANGE' ) ) : null;
		$size = $content_range ? $content_range [3] : null;
		$files = array ();
		if ($upload && is_array ( $upload ['tmp_name'] )) {
			// param_name is an array identifier like "files[]",
			// $_FILES is a multi-dimensional array:
			foreach ( $upload ['tmp_name'] as $index => $value ) {
				$files [] = $this->handle_file_upload ( $upload ['tmp_name'] [$index], $file_name ? $file_name : $upload ['name'] [$index], $size ? $size : $upload ['size'] [$index], $upload ['type'] [$index], $upload ['error'] [$index], $index, $content_range );
			}
		} else {
			// param_name is a single object identifier like "file",
			// $_FILES is a one-dimensional array:
			$files [] = $this->handle_file_upload ( isset ( $upload ['tmp_name'] ) ? $upload ['tmp_name'] : null, $file_name ? $file_name : (isset ( $upload ['name'] ) ? $upload ['name'] : null), $size ? $size : (isset ( $upload ['size'] ) ? $upload ['size'] : $this->get_server_var ( 'CONTENT_LENGTH' )), isset ( $upload ['type'] ) ? $upload ['type'] : $this->get_server_var ( 'CONTENT_TYPE' ), isset ( $upload ['error'] ) ? $upload ['error'] : null, null, $content_range );
		}
		return $this->generate_response ( array (
				$this->options ['param_name'] => $files 
		), $print_response );
	}

	/**
	 * delete()
	 * 
	 * This function handles AJAX call from front-end when user clicks the delete icon of an audio file.
	 * This function only handles deleting of user and project audio files. Front-end should post the
	 * fileid and type (user or project).
	 * 
	 * @author Wei Zong
	 * 
	 */
	public function delete() {
		
		// Get parameters and entities
		$userId = $this->userId;
		$user = $this->fms_user_model->getEntityById($userId);
		$file_id = $_POST ["fileid"];
		if($_POST['type'] === 'user') {
			$fileEntity = $this->fms_file_model->getEntityById($file_id);
			$allAudioFiles = $this->fms_file_model->getAudioFiles($user);
			$ownerId = $fileEntity->getOwner()->getId();
		} else {
			$fileEntity = $this->fms_project_file_model->getEntityById($file_id);
			$project = $fileEntity->getOwner();
			$allAudioFiles = $this->fms_project_file_model->getAudioFiles($project);
			$ownerId = $project->getOwner()->getId();
		}
		
		// Check Login
		if(!$userId) {
			$result ['success'] = 0;
			$result ['message'] = 'You should login first.';
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
			return;
		}
		
		// Sanity check
		if(!$fileEntity) {
			$result ['success'] = 0;
			$result ['message'] = 'File does not exists.';
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
			return;
		}
		if($fileEntity->getName() !== $_POST ["filename"]) {
			$result ['success'] = 0;
			$result ['message'] = 'Invalid file name or file id.';
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
			return;
		}
		
		// Check Permission
		if($ownerId != $user->getId()) {
			$result ['success'] = 0;
			$result ['message'] = 'You donnot have permission to delete this file.';
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
			return;
		}
		
		// Action
		$file_name = $fileEntity->getName();
		$file_path = $fileEntity->getPath(). $file_name ;
		$success = is_file ( $file_path ) && $file_name [0] !== '.' && unlink ( $file_path );
		if ($success) {
			foreach ( $this->options ['image_versions'] as $version => $options ) {
				if (! empty ( $version )) {
					$file = $this->get_upload_path ( $file_name, $version );
					if (is_file ( $file )) {
						unlink ( $file );
					}
				}
			}
			$result ['success'] = 1;
			
			// update the rankings
			foreach($allAudioFiles as $row) {
				$ranking = $row->getSubtype();
				if($ranking > $fileEntity->getSubtype())
					$row->setSubtype($ranking - 1);
			}
			
			// delete existing records
			$this->fms_general_model->remove($fileEntity);
			$this->fms_general_model->flush();
			
			// $this -> fms_user_profile_model -> deleteSpotLight($userid,$file_id);
			// $this -> fms_user_profile_model ->audioFileDelete($file_id);
		} 		// return $this->generate_response(array('success' => $success), $print_response);
		else {
			$result ['success'] = 0;
			$result ['message'] = 'File to delete the file';
		}
		
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $result ) );
	}
	
	
	/**
	 * imageCrop()
	 * 
	 * This function handles AJAX call from front-end to crop image. 
	 * 
	 */
	public function imageCrop($param = 'user_image') {
		 
		$responseObj = array();
		
		// Read Configurations
		$file_name = $_POST['filename'];
		$crop_x = $_POST['crop_x'];
		$crop_y = $_POST['crop_y'];
		$crop_w = $_POST['crop_w'];
		$crop_h = $_POST['crop_h'];
		$option = $_POST['option'];
		
		$target_height = $this->input->post('target_height');
		$target_width = $this->input->post('target_width');
		
		if(!$target_height || !$target_width) {
			$target_height = 360;
			$target_width = 360;
		}
		
		
		$this->options['unique_dir_id'] = $this->userId;
		if($param == 'project_image') {
			$this->options['upload_dir'] = 'project_files/';
			$this->options['unique_dir_id'] = $_POST['project_id'];
		}
		
		if($param == 'user_cover') {
			$subdir = '/cover/';
		} else {
			$subdir = '/image/';
		}
		 
		// For remote file, use URL as path and rename the file accordingly
// 		if($option == "LOCAL")
// 			$file_path = $this->get_crop_path()."/image/".$file_name;
// 		if($option == "URL") {
// 			$file_path = $file_name;
// 			//$file_name = "profile_url.jpg";
// 		}
		$file_path = $this->get_crop_path(). $subdir .$file_name.'.'.strtolower(substr(strrchr($file_name, '.'), 1));
		 
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
		$new_img = @imagecreatetruecolor($target_width, $target_height);
		 
		// Read source image
		$new_file_path = $this->get_crop_path(). $subdir .$file_name;
		switch (strtolower(substr(strrchr($file_name, '.'), 1))) {
			case 'jpg':
			case 'jpeg':
				$src_img = @imagecreatefromjpeg($file_path);
				$write_image = 'imagejpeg';
				$image_quality = 75;
				// $new_file_path = $this->get_crop_path()."/image/".$file_name.'.jpg';
				break;
			case 'gif':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
				$src_img = @imagecreatefromgif($file_path);
				$write_image = 'imagegif';
				$image_quality = null;
				// $new_file_path = $this->get_crop_path()."/image/".$file_name.'.gif';
				break;
			case 'png':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
				@imagealphablending($new_img, false);
				@imagesavealpha($new_img, true);
				$src_img = @imagecreatefrompng($file_path);
				$write_image = 'imagepng';
				$image_quality = 9;
				// $new_file_path = $this->get_crop_path()."/image/".$file_name.'.png';
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
				$target_width,
				$target_height,
				$crop_w,
				$crop_h
		) && $write_image($new_img, $new_file_path, $image_quality);
		if($success) {
			$responseObj['success'] = 1;
		}
		else {
			$responseObj['success'] = 0;
		}
		echo $this->encodeJSON($responseObj);
	}
	
	private function get_crop_path() {
		return $this->options['upload_dir'] .$this->options['unique_dir_id'];
	}
	
	
	/**
	 * uploadFromUrl()
	 *
	 * This function handles AJAX call from front-end, which will download image
	 * file from posted URL and store into specified folder and update the database.
	 * This function doesn't use the jQuery file uploader plugin for MVP. We will look
	 * into how to use jQuery plugin to upload image from URL. So this function might
	 * be deprecated later on.
	 *
	 * @authro Wei
	 */
	public function uploadFromUrl($debugswitch = 0) {
		$option = $this->input->post('option');
		$type	= $this->input->post('type');
		$url	= $this->input->post('url');
	
		// Check login
		$userId = $this->userId;
		// DEBUGGING for liquidweb
		if ($debugswitch > 1) {
			$option = 'user_files';
			$type = 'image';
			$url = 'http://images2.fanpop.com/images/photos/5800000/happy-kitten-kittens-5890512-1600-1200.jpg';
			$userId = $debugswitch;
			$this->userId = $debugswitch;
		}	
		
		if(!$userId) {
			$responseObj = array(
					'errorcode'	=> 1,
					'message'	=> "You should login first."
			);
			$this->encodeJSON($responseObj);
			return;
		}
	
		// Checking and get file path
		if($option === "project_files") {
			// Get Project entity
			$projectId = $this->input->post('project_id');
			$project = $this->fms_project_model->getEntityById($projectId);
				
			// Validate ProjectId
			if(!$project) {
				$responseObj = array(
						'errorcode'	=> 2,
						'message'	=> "Invalid Project ID."
				);
				$this->encodeJSON($responseObj);
				return;
			}
				
			// Check Permission
			if($project->getOwner()->getId() != $userId) {
				$responseObj = array(
						'errorcode'	=> 3,
						'message'	=> "You do not have permission to modify this profile."
				);
				$this->encodeJSON($responseObj);
				return;
			}
				
			// Get file path
			$filepath = $option . '/' . $projectId . '/' . $type . '/';
		} else {
			$filepath = $option . '/' . $userId . '/' . $type . '/';
		}
	
		// Get file name and check file type
		if(preg_match('/.*\/(.*)$/', $url, $match)) {
			$file_name = 'image_uploaded_from_url_'.time().'.jpg';				
		} else {
			$responseObj = array(
					'errorcode'	=> 4,
					'message'	=> "Illegal URL."
			);
			$this->encodeJSON($responseObj);
			return;
		}	
	
		// Make file folder if it doesn't exist
		if(!is_dir($filepath)) {
			mkdir ( $filepath, $this->options ['mkdir_mode'], true );
		}
		
		// Get content and put content
		$target_path = $filepath . $file_name;
		file_put_contents($target_path, file_get_contents($url));
		copy($target_path, $target_path . '.jpg');
	
		// Update the database for user profile picture
		if($option == "user_files" && $type == "image") {
			// Get Previous Profile Picture for deletion
			$user = $this->fms_user_model->getEntityById( $this->get_user_id() );
			$profilePicture = $user->getProfilePicture();
				
			// Create new file entity
			$dataArray = array ();
			$dataArray ["fileName"] = $file_name;
			$dataArray ["uploadIp"] = $_SERVER ['REMOTE_ADDR'];
			$dataArray ["filePath"] = $filepath;
			$dataArray ["type"]		= 1;
			$dataArray ["subtype"]	= 100;
			$uploadedFile = $this->fms_file_model->addFile ( $this->get_user_id (), $dataArray );
				
			// Set new profile picture
			$user->setProfilePicture ($uploadedFile );
				
			// Delete the old file and record if exists
			if($profilePicture) {
				$oldFilePath = $profilePicture->getPath() . $profilePicture->getName();
				if(file_exists($oldFilePath)) {
					unlink ( $oldFilePath );
				}
				$this->fms_general_model->remove($profilePicture);
			}
		}
	
		// Update the database for user cover photo
		if($option == "user_files" && $type == "cover") {
			// Get Previous Profile Picture for deletion
			$user = $this->fms_user_model->getEntityById( $this->get_user_id() );
			$profilePicture = $user->getCoverPhoto();
				
			// Create new file entity
			$dataArray = array ();
			$dataArray ["fileName"] = $file_name;
			$dataArray ["uploadIp"] = $_SERVER ['REMOTE_ADDR'];
			$dataArray ["filePath"] = $filepath;
			$dataArray ["type"]		= 2;
			$dataArray ["subtype"]	= 100;
			$uploadedFile = $this->fms_file_model->addFile ( $this->get_user_id (), $dataArray );
	
			// Set new profile picture
			$user->setCoverPhoto ($uploadedFile );
	
			// Delete the old file and record if exists
			if($profilePicture) {
				$oldFilePath = $profilePicture->getPath() . $profilePicture->getName();
				if(file_exists($oldFilePath)) {
					unlink ( $oldFilePath );
				}
				
				if(file_exists($oldFilePath. '.jpg')) {
					unlink ( $oldFilePath. '.jpg' );
				}
				$this->fms_general_model->remove($profilePicture);
			}
		}
	
		// Update database for project profile picture
		if($option == "project_files" && $type == "image") {
			// Get Previous Profile Picture for deletion
			$project = $this->fms_project_model->getEntityById( $projectId );
			$profilePicture = $project->getPhoto();
				
			// Create new file entity
			$dataArray = array ();
			$dataArray ["fileName"] = $file_name;
			$dataArray ["uploadIp"] = $_SERVER ['REMOTE_ADDR'];
			$dataArray ["filePath"] = $filepath;
			$dataArray ["type"]		= 1;
			$dataArray ["subtype"]	= 100;
			$uploadedFile = $this->fms_project_file_model->addFile ( $this->get_user_id (), $dataArray );
				
			// Set new profile picture
			$project->setPhoto ($uploadedFile );
			$uploadedFile->setType(0);
				
			// Delete the old file and record if exists
			if($profilePicture) {
				$oldFilePath = $profilePicture->getPath() . $profilePicture->getName();
				if(file_exists($oldFilePath)) {
					unlink ( $oldFilePath );
				}
				$this->fms_general_model->remove($profilePicture);
			}
		}

		// Commit changes and return success message
		$this->fms_general_model->flush();
		$responseObj = array(
				'errorcode'	=> 0,
				'url'	=> base_url() . $filepath . $file_name,
				'name'	=> $file_name,
		);
		$this->encodeJSON($responseObj);
		return;
	}
	
	
	
	
	private function autoCrop($param) {
		 
		$responseObj = array();
		
		// Read Configurations
		$file_name = $param['filename'];
		$option = $param['option'];
		$projectId = $param['project_id'];
		
		$target_height = 360;
		$target_width = 360;	
		$desired_ratio = 1; // y = x * ratio; x = y / ratio	
		
		// Get path
		$this->options['unique_dir_id'] = $this->userId;
		if($option == 'project_image') {
			$this->options['upload_dir'] = 'project_files/';
			$this->options['unique_dir_id'] = $projectId;
		}
		
		if($option == 'user_cover') {
			$subdir = '/cover/';
			$target_height = 250;
			$target_width = 700;	
			$desired_ratio = 250/700; // y = x * ratio; x = y / ratio	
		} else {
			$subdir = '/image/';
		}
		
		$file_path = $this->get_crop_path(). $subdir .$file_name.'.'.strtolower(substr(strrchr($file_name, '.'), 1));
		 
		// Get source image dimensions
		if (!function_exists('getimagesize')) {
			return -1;
		}
		list($img_width, $img_height) = @getimagesize($file_path);
		if (!$img_width || !$img_height) {
			return -2;
		}
		
		// Calculate Cropping parameters
		if($img_width >= ($img_height / $desired_ratio)) {
			$crop_x = round(($img_width - $img_height / $desired_ratio) / 2);
			$crop_y = 0;
			$crop_w = $img_height / $desired_ratio;
			$crop_h = $img_height;
		} else {
			$crop_x = 0;
			$crop_y = round(($img_height - $img_width * $desired_ratio) / 2);
			$crop_w = $img_width;
			$crop_h = $img_width * $desired_ratio;
		}
		
		
		// Create Destionation Image
		if (!function_exists('imagecreatetruecolor')) {
			return -3;
		}
		$new_img = @imagecreatetruecolor($target_width, $target_height);
		 
		// Read source image
		$new_file_path = $this->get_crop_path(). $subdir .$file_name;
		switch (strtolower(substr(strrchr($file_name, '.'), 1))) {
			case 'jpg':
			case 'jpeg':
				$src_img = @imagecreatefromjpeg($file_path);
				$write_image = 'imagejpeg';
				$image_quality = 75;
				// $new_file_path = $this->get_crop_path()."/image/".$file_name.'.jpg';
				break;
			case 'gif':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
				$src_img = @imagecreatefromgif($file_path);
				$write_image = 'imagegif';
				$image_quality = null;
				// $new_file_path = $this->get_crop_path()."/image/".$file_name.'.gif';
				break;
			case 'png':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
				@imagealphablending($new_img, false);
				@imagesavealpha($new_img, true);
				$src_img = @imagecreatefrompng($file_path);
				$write_image = 'imagepng';
				$image_quality = 9;
				// $new_file_path = $this->get_crop_path()."/image/".$file_name.'.png';
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
				$target_width,
				$target_height,
				$crop_w,
				$crop_h
		) && $write_image($new_img, $new_file_path, $image_quality);
		if($success) {
			return 0;
		}
		else {
			return -4;
		}
	}
	
}

