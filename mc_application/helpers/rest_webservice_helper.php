<?php 
// if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 15 Oct, 2014
* Modified date : 15 Oct, 2014
* Description 	: Page contains common helper functions
*/ 
/* Function used to check REST webservice authentication details - created by Karthik K on 09 Dec, 2014 */
if(!function_exists('CheckWebserviceAuthentication'))
{
	function CheckWebserviceAuthentication()
	{
		$GetObj =& get_instance();
		$GetTokenKey = trim($GetObj->post('tokenKey'));
		if($GetTokenKey != REST_TOKEN_KEY)
		{
			$ReturnValue['ret_status']	= BAD;
			$ReturnValue['ret_message']	= "Invalid token key";
			$GetObj->response($ReturnValue);
		}
	}
}
/* Function used to get mutiple array list details using seperator - created by Karthik K on 10 Dec, 2014 */
if(!function_exists('GetMultipleDoctorList'))
{
	function GetMultipleDoctorList($DoctorValues='',$Seperator='',$ReturnName='')
	{
		$ReturnList		= array();
		if($DoctorValues != '' && $DoctorValues != '0' && $Seperator != '' && $ReturnName != '')
		{
			$SplitValues	= explode($Seperator,$DoctorValues);
			$SplitValues	= array_filter($SplitValues);
			if(count($SplitValues)>0)
			{
				foreach($SplitValues as $GetValues)
				{
					$ReturnList[][$ReturnName]	= $GetValues;
				}
			}
		}
		return $ReturnList;
	}
}
 ?>