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

$route['default_controller'] 	= "login";
$route['404_override'] 			= "invalidprocess";
$route['404'] 					= 'invalidprocess';
$route['logout']				= "login/logout";
$route['confirm(:any)']			= "login/userconfirmaccount";
$route['confirm']				= "login/userconfirmaccount";
$route['restricted']			= "invalidprocess/modulerestricted";
$route['forgotpass']			= "login/forgotpassword";
$route['resetpass']				= "login/resetuserpass";
$route['resetpass(:any)']		= "login/resetuserpass";
$route['profile']				= "userprofile";

// Search Physician module URL alias name starts here
$route['home']					= "searchphysician";
$route['getzipcode']			= "searchphysician/getzipcode";
$route['firstcondition']		= "searchphysician/firstcondition";
$route['secondcondition']		= "searchphysician/secondcondition";
$route['thirdcondition']		= "searchphysician/thirdcondition";
$route['firstspecialty']		= "searchphysician/firstspecialty";
$route['secondspecialty']		= "searchphysician/secondspecialty";
$route['thirdspecialty']		= "searchphysician/thirdspecialty";
$route['searchlist']			= "searchphysician/searchlist";
$route['viewcomparelist']		= "searchphysician/viewcomparelist";
$route['doctorprofile']			= "searchphysician/doctorprofile";
$route['hospitalprofile']		= "searchphysician/hospitalprofile";
$route['getlist']				= "searchphysician/getdoctorlist";
$route['generatedetails']		= "searchphysician/generatepdfdetails";
$route['sendcomparelist']		= "searchphysician/senddoctorcomparelist";
$route['printcomparelist']		= "searchphysician/printdoctorcomparelist";
$route['hospitalfinallist']		= "searchphysician/gethospitalfinalscore";
// Search Physician module URL alias name ends here

// Manage users module URL alias name starts here
$route['viewusers']				= "manageusers";
$route['viewusers(:any)']		= "manageusers";
$route['adduser']				= "manageusers/registernewuser";
$route['edituser']				= "manageusers";
$route['edituser(:any)']		= "manageusers/edituserdetails";
$route['deleteuser']			= "manageusers";
$route['deleteuser(:any)']		= "manageusers/deleteuserdetails";
$route['filterusers']			= "manageusers/filteruserlist";
$route['filterusers(:any)']		= "manageusers/filteruserlist";
$route['viewlog']				= "manageusers";
$route['viewlog(:any)']			= "manageusers/getuseraccesslog";
// Manage users module URL alias name ends here

// Manage user level module URL alias name starts here
$route['viewgroups']			= "managelevels";
$route['viewgroups(:any)']		= "managelevels";
$route['addgroup']				= "managelevels/registernewlevel";
$route['addgroup(:any)']		= "managelevels/registernewlevel";
$route['editgroup']				= "managelevels";
$route['editgroup(:any)']		= "managelevels/editaddeduserlevel";
$route['deletegroup']			= "managelevels";
$route['deletegroup(:any)']		= "managelevels/deleteaddeduserlevel";
$route['filtergroups']			= "managelevels/filterlevellist";
$route['filtergroups(:any)']	= "managelevels/filterlevellist";
// Manage user level module URL alias name ends here

$route['reports']				= "common/viewreports";
$route['sendmailto']			= "common/sendmailtouserlevels";

// New reports module URL alias name starts here
$route['showreports']			= "getreports";
$route['reportresult']			= "getreports/viewreports";
// New reports module URL alias name ends here

// Admin site settings alias name starts here
$route['sitesettings']			= "adminsitesettings/index";
$route['statemetrics']			= "adminsitesettings/updatestatemetrics";

$route['getdashboardresult']	= "dashboard/getdashboardresult";

// REST webservice alias name starts here
$route['applogin']				= "mcrestservices/login";
$route['appstatelist']			= "mcrestservices/GetStateList";
$route['appstatezipcode']		= "mcrestservices/GetZipcodeList";
$route['appsearchlist']			= "mcrestservices/QuickSearchList";
$route['appquicksearch']		= "mcrestservices/QuickSearchResult";
$route['appcarriers']			= "mcrestservices/GetCarrierList";
$route['appstatecarriers']		= "mcrestservices/GetStateCarrierList";
$route['appfirstcond']			= "mcrestservices/GetCondition";
$route['appsecondcond']			= "mcrestservices/GetSubCondition";
$route['appthirdcond']			= "mcrestservices/GetSubSubCondition";
$route['appspecialty']			= "mcrestservices/GetSpecialty";
$route['appsecondspecialty']	= "mcrestservices/GetSubSpecialty";
$route['appthirdspecialty']		= "mcrestservices/GetSubSpecialtyProcedureCodes";
$route['appconditiondoclist']	= "mcrestservices/GetDoctorListUsingCondition";
$route['appspecialtydoclist']	= "mcrestservices/GetDoctorListUsingSpecialty";
$route['appdocprofile']			= "mcrestservices/GetDoctorProfile";
$route['apphosprofile']			= "mcrestservices/GetHospitalProfile";
$route['apphosfulldetails']		= "mcrestservices/GetHospitalFullDetails";
$route['appcomparedoclist']		= "mcrestservices/GetComparedDoctorList";
$route['appcomparedlisttomail'] = "mcrestservices/SendDoctorComparedListToMail";

/* End of file routes.php */
/* Location: ./application/config/routes.php */
