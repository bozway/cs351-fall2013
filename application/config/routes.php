<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$default_controller = "fms";

$controller_exceptions = array(		
		'create',	
		'fms_404',		
		'ajax/project',
		'ajax/message',
		'ajax/profile',
		'users/profile',
		'users/search',		
		'projects/search',
		'projects/profile',
		'dashboard/profile',
        'dashboard/project',
        'dashboard/account',
		'dashboard/message',
		'ajax/uploadhandler',
		'hot',
		'ajax/account'
);


// $route['default_controller'] = "wr_index/underconstruction";
// $route['(:any)'] = $default_controller."/$1";
// $route['default_controller'] = $default_controller."/underconstruction";

$route['default_controller'] = $default_controller;
$route["^((?!\b" . implode( '\b|\b', $controller_exceptions ) . "\b).*)$"] = $default_controller . '/$1';
$route['dashboard/message/(:any)'] = 'dashboard/message/index/$1';
$route['dashboard/profile/(:any)'] = 'dashboard/profile/index/$1';
$route['(:any)/profile/(:num)'] = '$1/profile/index/$2';
$route['404'] = 'fms_404';
$route['404_override'] = 'fms_404';

// add our own routes AFTER the reserved routes
//$route['contact_us'] = "waylan_controller/contact_us";


/* End of file routes.php */
/* Location: ./application/config/routes.php */