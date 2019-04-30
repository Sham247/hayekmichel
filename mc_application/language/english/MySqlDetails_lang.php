<?php
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 16 Oct, 2014
* Modified date : 16 Oct, 2014
* Description 	: Page contains  MYSQL Table, Storedprocedure, View name
*/
/* Table name starts here */
$lang['Tbl_LoginUsers']				= 'mc_users';
$lang['Tbl_LoginHistory']			= 'mc_user_login_history';
$lang['Tbl_UserLevel']				= 'mc_groups';
$lang['Tbl_GroupStateMap']			= "mc_group_state_map";
$lang['Tbl_UserConfirm']			= "mc_user_email_confirm";
$lang['Tbl_UserModules']			= "mc_group_modules";
$lang['Tbl_UserLevelAccessModules']	= "mc_group_access_module";
$lang['Tbl_SiteSettings']			= "mc_site_settings";
$lang['Tbl_SiteMailContent']		= "mc_site_mail_content";
$lang['Tbl_CreatedUserList']		= "mc_created_user_list";
$lang['Tbl_CreatedLevelList']		= "mc_created_level_list";
$lang['Tbl_Carriers']				= "mc_carriers";
$lang['Tbl_StateCarriers']			= "mc_carrier_state_map";
$lang['Tbl_PhysicianCarriers']		= "mc_carrier_physician_map";
$lang['Tbl_StateList']				= 'mc_state_list';
$lang['Tbl_UserResetPassword']		= "mc_user_reset_password";
$lang['Tbl_HospitalGroupScore'] 	= "mc_hospital_group_details";
$lang['Tbl_HospitalMeasureList']	= "mc_hospital_measure_details";
$lang['Tbl_ReportsCategory']		= "mc_dashboard_category";
$lang['Tbl_ReportsSubCategory']		= "mc_dashboard_sub_category";
$lang['Tbl_SubConditionLaymanText']	= "mc_sub_condition_laymantext";

$lang['Tbl_DoctorList']			= 'providermaster_state';
$lang['Tbl_ConditionList']		= 'newconditionsv3';
$lang['Tbl_SpecialtyList']		= 'specialtymaster';
$lang['Tbl_HospitalGroupList']	= "hospital_group_list";
$lang['Tbl_HospitalStar']		= "hospitalstar";
$lang['Tbl_LaymanText']			= "newconditionsv3tooltip";
/* Table name ends here */

/* MYSQL view name starts here */
$lang['View_DoctorList']	= 'vwgetdoctors';
/* MYSQL view name ends here */

/* MYSQL stored procedure name starts here */
$lang['Sp_QuickSearchDetails']		= 'GetQuickSearchDetails';
$lang['Sp_ConditionDoctorDetails']	= 'GetDoctorsListCond';
$lang['Sp_SpecialtyDoctorDetails']	= 'GetDoctorsListSpec';
$lang['Sp_NearestDoctorDetails']	= 'GetNearestDoctorList';
/* MYSQL stored procedure name ends here */ ?>
