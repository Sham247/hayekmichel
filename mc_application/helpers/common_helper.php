<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 15 Oct, 2014
* Modified date : 15 Oct, 2014
* Description 	: Page contains common helper functions
*/ 
/* Function to get css file - created by Karthik K on 26 Sep, 2014 */
if(!function_exists('CssUrl'))
{
	function CssUrl($FileName='')
	{
		return base_url()."public/css/".$FileName;
	}
}
/* Function to get js file - created by Karthik K on 26 Sep, 2014 */
if(!function_exists('JsUrl'))
{	
	function JsUrl($FileName='')
	{
		return base_url()."public/js/".$FileName;
	}
}
/* Function to get image file - created by Karthik K on 29 Sep, 2014 */
if(!function_exists('ImageUrl'))
{	
	function ImageUrl($FileName='')
	{
		return base_url()."public/images/".$FileName;
	}
}
/* Function to list array valus - created by Karthik K on 29 Sep, 2014 */
if(!function_exists('Pr'))
{	
	function Pr($ArrayValue=array())
	{
		echo "<pre>";
		print_r($ArrayValue);
		echo "</pre>";
	}
}
/* Function used to get language label - created by Karthik K on 06 Oct, 2014 */
if(!function_exists('GetLangLabel'))
{
	function GetLangLabel($GetName='')
	{
		$GetObj	=& get_instance();
		return $GetObj->lang->line($GetName);
	}
}
/* Function used to get logged user session id - created by Karthik K on 09 Oct, 2014 */
if(!function_exists('UserSessionId'))
{
	function UserSessionId()
	{
		$GetObj	=& get_instance();
		$GetSessionData = $GetObj->session->userdata('MonocleUserSession');
		$GetSessionId	= '';
		if(isset($GetSessionData) && $GetSessionData != '')
		{
			$GetSessionId	= $GetSessionData['UserSessionId'];
		}
		return $GetSessionId;
	}
}
/* Function used to get logged user level - created by Karthik K on 15 Oct, 2014 */
if(!function_exists('GetUserLevel'))
{
	function GetUserLevel()
	{
		$GetObj	=& get_instance();
		$GetSessionData = $GetObj->session->userdata('MonocleUserSession');
		$UserLevel	= '';
		if(isset($GetSessionData) && $GetSessionData != '')
		{
			$UserLevel 	= $GetSessionData['UserGroupId'];
		}
		return $UserLevel;
	}
}
/* Function used to get logged user display name - created by Karthik K on 15 Oct, 2014 */
if(!function_exists('UserDisplayName'))
{
	function UserDisplayName()
	{
		$GetObj	=& get_instance();
		$GetSessionData = $GetObj->session->userdata('MonocleUserSession');
		$GetDisplayName	= '';
		if(isset($GetSessionData) && $GetSessionData != '')
		{
			$GetDisplayName	= $GetSessionData['DisplayName'];
		}
		return $GetDisplayName;
	}
}
/* Function used to find logged user superadmin or not - created by Karthik K on 08 Nov, 2014 */
if(!function_exists('IsSuperAdmin'))
{
	function IsSuperAdmin()
	{
		$GetObj	=& get_instance();
		$GetSessionData = $GetObj->session->userdata('MonocleUserSession');
		$SuperAdmin	= '';
		if(isset($GetSessionData) && $GetSessionData != '')
		{
			$SuperAdmin	= $GetSessionData['IsSuperAdmin'];
		}
		return $SuperAdmin;
	}
}
/* Function used to find user logged  - created by Karthik K on 09 Oct, 2014 */
if(!function_exists('RedirectToLogin'))
{
	function RedirectToLogin()
	{
		$GetSessionId = UserSessionId();
		if($GetSessionId == '')
		{
			$GetObj		=& get_instance();
			$GetObj->session->set_userdata('ViewedUrl',current_url());
			redirect('login');
		}
	}
}
/* Function used to check user access module permission - created by Karthik K on 10 Nov, 2014 */
if(!function_exists('CheckUserAccessPermission'))
{
	function CheckUserAccessPermission($ModuleId='',$EnableRedirect=true)
	{
		$GetObj		=& get_instance();
		$GetResult	= $GetObj->common_model->CheckLevelPermission($ModuleId);
		if($EnableRedirect == true)
		{
			if(isset($GetResult) && count($GetResult)>0)
			{
				return true;
			}
			else
			{
				redirect('restricted');
			}
		}
		else
		{
			if(isset($GetResult) && count($GetResult)>0)
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
	}
}
/* Function used to find user logged  - created by Karthik K on 09 Oct, 2014 */
if(!function_exists('RedirectToHome'))
{
	function RedirectToHome()
	{
		$GetSessionId = UserSessionId();
		if($GetSessionId != '')
		{
			redirect('home');
		}
	}
}
/* Function used to encode value  - created by Karthik K on 06 Nov, 2014 */
if(!function_exists('EncodeValue'))
{
	function EncodeValue($Value='')
	{
		if($Value != '')
		{
			$ReturnValue	= str_replace('=','',base64_encode($Value));
			return $ReturnValue;
		}
	}
}
/* Function used to decode value  - created by Karthik K on 09 Oct, 2014 */
if(!function_exists('DecodeValue'))
{
	function DecodeValue($Value='')
	{
		if($Value != '')
		{
			$ReturnValue	= base64_decode($Value);
			return $ReturnValue;
		}
	}
}
/* Function used to get current time for timestamp format - created by Karthik K on 07 Nov, 2014 */
if(!function_exists('GetTime'))
{
	function GetTime()
	{
		return time();
	}
}
/* Function used to get current IP address - created by Karthik K on 07 Nov, 2014 */
if(!function_exists('GetIp'))
{
	function GetIp()
	{
		$GetObj		=& get_instance();
		$IpAddress 	= $GetObj->input->ip_address();
		return $IpAddress;
	}
}
/* Function to display no direct script allowed message - created by Karthik K on 14 Nov, 2014 */
/*if(!function_exists('NoScriptAllowed'))
{
	function NoScriptAllowed()
	{
		if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	}
}*/
/* Function used to get session value - created by Karthik K on 19 Nov, 2014 */
if(!function_exists('GetSessionVal'))
{
	function GetSessionVal($Name='')
	{
		if($Name != '')
		{
			$GetObj =& get_instance();
			$GetSessionVal = $GetObj->session->userdata($Name);
			if(isset($GetSessionVal))
			{
				return $GetSessionVal;
			}
		}
	}
}
/* Function used to get pagination design - created by Karthik K on 19 Nov, 2014 */
if(!function_exists('GeneratePagination'))
{
	function GeneratePagination($PerPage='10',$PageUrl='',$TotalCount='')
	{
		$GetObj =& get_instance();
		$GetObj->load->library('pagination');
		$PaginationConfig['per_page'] 	= $PerPage;
		$PaginationConfig['base_url'] 	= $PageUrl;
		$PaginationConfig['total_rows'] = $TotalCount;
		$PaginationConfig['uri_segment']= 2;
		$PaginationConfig['num_links'] 	= 1;
		$GetObj->pagination->initialize($PaginationConfig); 
		$GetPagination = $GetObj->pagination->create_links();
		return $GetPagination;
	}
} ?>