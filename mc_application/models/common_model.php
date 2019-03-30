<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 26 Sep, 2014
* Modified date : 26 Sep, 2014
* Description 	: Page contains common model functions
*/ 
class Common_model extends CI_Model 
{
	var $UserLevelId;
	var $LoggedUserId;
	var $SuperAdmin;
	function __construct()
	{
		parent::__construct();
		$this->UserLevelId 	= GetUserLevel();
		$this->LoggedUserId = UserSessionId();
		$this->SuperAdmin 	= IsSuperAdmin();
	}
	/* Common select query - created by Karthik K on 26, Sep 2014 */
	public function SelectData($TableName='',$Fields=array(),$WhereCond=array(),$OrderBy=array(),$Limit=array())
	{
		if($TableName != '')
		{
			$this->db->select($Fields);
			$this->db->from($TableName);
			if(count($WhereCond) > 0)
			{
				$this->db->where($WhereCond);
			}
			/*else
			{
				$this->db->where($WhereCond);
			}*/
			if(count($OrderBy)>0)
			{
				foreach($OrderBy as $OrderField=>$SortBy)
				{
					$this->db->order_by($OrderField,$SortBy);
				}
			}
			if(is_array($Limit) && count($Limit)>0)
			{
				foreach($Limit as $StartLimit=>$EndLimit)
				{
					$this->db->limit($StartLimit,$EndLimit);
				}
			}
			else if(is_array($Limit) == false && $Limit != '')
			{
				$this->db->limit($Limit);
			}
			$GetDetails = $this->db->get();
			$GetResult	= $GetDetails->result();
			return $GetResult;
		}
	}
	/* Function used to insert data to table - created by Karthik K on 26 Sep, 2014 */
	public function InsertData($TableName='',$InsertData=array())
	{
		if($TableName != '' && is_array($InsertData) && count($InsertData)>0)
		{
			$this->db->insert($TableName,$InsertData);
		}
	}
	/* Function used to update data to table - created by Karthik K on 26 Sep, 2014 */
	public function UpdateData($TableName='',$UpdateData=array(),$UpdateCond=array())
	{
		if($TableName != '' && is_array($UpdateData) && count($UpdateData)>0)
		{
			if(is_array($UpdateCond) && count($UpdateCond)>0)
			{
				$this->db->where($UpdateCond);
			}
			$this->db->update($TableName,$UpdateData);
		}
	}
	/* Function used to delete data from table - created by Karthik K on 26 Sep, 2014 */
	public function DeleteData($TableName='',$DeleteCond=array())
	{
		if($TableName != '')
		{
			if(is_array($DeleteCond) && count($DeleteCond)>0)
			{
				$this->db->where($DeleteCond);
			}
			$this->db->delete($TableName);
		}
	}
	/* Function used to call stored procedure - created by Karthik K on 15 Oct, 2014 */
	public function CallSp($SpName,$ReturnArray=false)
	{
		
		//echo "sp name ".$SpName;exit;

		$GetDetails	= $this->db->query("CALL ".$SpName);
		if($ReturnArray == true)
		{
			$GetResult	= $GetDetails->result_array();
		}
		else
		{
			$GetResult	= $GetDetails->result();
		}
		$GetDetails->next_result();
  		$GetDetails->free_result();
		return $GetResult;
	}
	/* Method used to check valid user or not - created by Karthik K on 17 Nov, 2014 */
	public function CheckUserLogin($Username='',$Password='')
	{
		if($Username != '' && $Password != '')
		{
			$LoginQuery		= "SELECT lu.id,lu.name,lu.group_id,ul.is_super_admin,lu.is_confirmed,
							   lu.expire_date,ul.group_name,lu.user_status,ul.group_status 
							   FROM ".GetLangLabel('Tbl_LoginUsers')." lu,".GetLangLabel('Tbl_UserLevel')." ul
							   WHERE ul.id=lu.group_id AND lu.username='".$Username."' AND lu.password='".$Password."'";
			$LoginDetails 	= $this->db->query($LoginQuery);
			$LoginResult	= $LoginDetails->result();
			return $LoginResult;
		}
	}
	/* Function used to get state zipcode list - created by Karthik K on 17 Oct, 2014 */
	public function GetStateZipcode($StateId='',$Zipcode='')
	{
		if($StateId != '' && $Zipcode != '')
		{
			$SearchQuery 	= "SELECT rtrim(`Zip Code`) as label FROM ".GetLangLabel('View_DoctorList')." 
							   WHERE `state_id`='".$StateId."' AND `Zip Code` LIKE '".$Zipcode."%' GROUP BY `Zip Code`";
			/*$SearchQuery 	= "SELECT dl.`Zip Code` as label FROM ".GetLangLabel('View_DoctorList')." dl,
							  ".GetLangLabel('Tbl_StateList')." sl  
							   WHERE dl.state_id=sl.id AND dl.`state_id`='".$StateId."' AND dl.StateShortName=sl.short_name 
							   AND dl.`Zip Code` LIKE '".$Zipcode."%' GROUP BY dl.`Zip Code`";*/
			$SearchDetails	= $this->db->query($SearchQuery);
			$SearchResult	= $SearchDetails->result();
			if(isset($SearchResult) && count($SearchResult)>0)
			{
				return $SearchResult;
			}
		}
	}
	/* Method used to get all state list - created by Karthik k on 05 Nov, 2014 */
	public function GetStateList()
	{
		$GetStateList = $this->common_model->SelectData(GetLangLabel('Tbl_StateList'),array('id','state_name','physicians_ranked','state_covered'),array('status'=>'1'));
		if(isset($GetStateList) && count($GetStateList)>0)
		{
			return $GetStateList;
		}
	}
	/* Method used to get user leve list - created by Karthik k on 06 Nov, 2014 */
	public function GetUserLevel($RestrictStatus='')
	{
		$StatusCond	= '';
		if($RestrictStatus != '')
		{
			$StatusCond	= " AND ul.group_status=1";
		}
		if($this->SuperAdmin == 1)
		{
			$LevelQuery		= "SELECT ul.id,ul.group_name,ul.description,ul.redirect,ul.group_status 
						   	   FROM ".GetLangLabel('Tbl_UserLevel')." ul 
						   	   WHERE ul.id !='".$this->UserLevelId."' ".$StatusCond;
		}
		else
		{
			$LevelQuery		= "SELECT ul.id,ul.group_name,ul.description,ul.redirect,ul.group_status 
						   	   FROM ".GetLangLabel('Tbl_UserLevel')." ul 
						   	   JOIN ".GetLangLabel('Tbl_CreatedLevelList')." cll ON (cll.created_by='".$this->LoggedUserId."' AND cll.group_id=ul.id) 
						   	   WHERE cll.id AND ul.id !='".$this->UserLevelId."' ".$StatusCond;
		}
		$LevelDetails	= $this->db->query($LevelQuery);
		$LevelResult	= $LevelDetails->result();
		if(isset($LevelResult) && count($LevelResult)>0)
		{
			return $LevelResult;
		}
	}
	/* Function used to get state name - created by Karthik K on 17 Oct, 2014 */
	public function GetStateName($StateId='')
	{
		$StateQuery 	= "SELECT state_name FROM ".GetLangLabel('Tbl_StateList')." WHERE id='".$StateId."'";
		$StateDetails	= $this->db->query($StateQuery);
		$StateResult	= $StateDetails->result();
		if(isset($StateResult) && count($StateResult)>0)
		{
			return $StateResult[0]->state_name;
		}
	}
	/* Function used to get site mail content details - created by Karthik K on 07 Nov, 2014 */
	public function GetSiteMailContent($TypeName='')
	{
		if($TypeName != '')
		{
			$GetResult	= $this->SelectData(GetLangLabel('Tbl_SiteMailContent'),array('mail_subject','mail_content'),array('type_name'=>$TypeName));
			if(isset($GetResult) && count($GetResult)>0)
			{
				return $GetResult;
			}
		}
	}
	/* Method used to update logged in user profile details - created by Karthik K on 28 Oct, 2014 */
	public function UpdateProfileDetails()
	{
		$GetName 		= $this->input->post('UserFullName');
		$GetEmail		= $this->input->post('UserEmail');
		$NewPassword	= $this->input->post('NewPassword');
		$UpdateData['name']	= $GetName;
		$UpdateData['email']= $GetEmail;
		if($NewPassword != '')
		{
			$UpdateData['password']= md5($NewPassword);
			// $this->load-
		}
		$this->UpdateData(GetLangLabel('Tbl_LoginUsers'),$UpdateData,array('id'=>$this->LoggedUserId));
		// Update user session details
		$this->UpdateUserSessionDetails();
	}
	public function UpdateUserSessionDetails()
	{
		$Query		= "SELECT lu.id,lu.name,lu.group_id,ul.is_super_admin 
				   	   FROM ".GetLangLabel('Tbl_LoginUsers')." lu,".GetLangLabel('Tbl_UserLevel')." ul
				       WHERE ul.id=lu.group_id AND lu.id='".$this->LoggedUserId."'";
	    $Details 	= $this->db->query($Query);
	    $Result 	= $Details->result();
	    if(isset($Result) && count($Result)>0)
	    {
	    	$UpdateSesssion['UserSessionId']	= $Result[0]->id;
			$UpdateSesssion['UserGroupId']		= $Result[0]->group_id;
			$UpdateSesssion['DisplayName']		= $Result[0]->name;
			$UpdateSesssion['IsSuperAdmin']		= $Result[0]->is_super_admin;
			$this->session->set_userdata('MonocleUserSession',$UpdateSesssion);
	    }
	}
	/* Method used to get compared doctor list - created by Karthik K on 28 Oct, 2014 */
	public function GetComparedList($GetCompareId='')
	{
		if($GetCompareId == '')
		{
			$GetCompareId 	= isset($_POST['compareids'])?$_POST['compareids']:'';
		}
		if($GetCompareId != '')
		{
			$CompareQuery	= "SELECT `id`,`NPI`,`ProvName`,`Education`,`Efficiency`,`Quality`,`Languages`,`Gender`,
(case when BoardAddress != '' then BoardAddress else `Address` end ) `Address`,
							  `Organizationlegalname`,`PrimarySpecialty`,`Medical school name`,`Graduation year`,`O_Publications`,
							  `O_Organizations`,`Phone` 
							   FROM `".GetLangLabel('View_DoctorList')."` WHERE id IN (".$GetCompareId.") 
							   ORDER BY `Quality` DESC, `Efficiency` DESC limit 0,4";
			$CompareDetails	= $this->db->query($CompareQuery);
			$CompareResult	= $CompareDetails->result_array();
			if(isset($CompareResult) && count($CompareResult)>0)
			{
				return $CompareResult;
			}
		}
	}
	/* Method used to get doctor profile details - created by Karthik K on 04 Nov, 2014 */
	public function GetDoctorProfile($DoctorNpi='')
	{
		if($DoctorNpi == '')
		{
			$DoctorNpi		= isset($_POST['get_id'])?$_POST['get_id']:'';
		}
		if($DoctorNpi != '')
		{
			$ProfileQuery = "SELECT `id`,`NPI`,`ProvName`,`Education`,`Efficiency`,`Quality`,`Languages`,`Gender`,`Address`,
						    `Organizationlegalname`,`PrimarySpecialty`,`Medical school name`,`Graduation year`,`O_Publications`,
						    `O_Organizations`,`Phone`,`FinalDisAction`,`NCQA_Recognization`,`CriminalOffences`,`MedicalSchool`,
	                   	    `accepts_medicare_assignment`,`erx`,`pqrs`,`ehr`,`residency`,`O_Appointments`,`Doctor_certificate` 
						     FROM `".GetLangLabel('View_DoctorList')."`
	                         WHERE NPI='".$DoctorNpi."' GROUP BY NPI";
	        $ProfileDetails		= $this->db->query($ProfileQuery);
			$ProfileResult   	= $ProfileDetails->result_array();
			if(isset($ProfileResult) && count($ProfileResult) > 0)
			{
				return $ProfileResult;
			}
		}
	}
	/* Method used to get hospital profile details - created by Karthik K on 06 Nov, 2014 */
	public function GetHospitalProfile($HospitalProviderId='')
	{
		if($HospitalProviderId != '')
		{
			$HospitalQuery	= "SELECT a.`id`,a.`group_name`,
							  (case when b.group_score is null then 0 else b.group_score end) as star_value,
							   c.HospitalName as hospital_name,c.`ProviderNumber` as provider_number
							  FROM ".GetLangLabel('Tbl_HospitalGroupList')." a 
							  LEFT JOIN ".GetLangLabel('Tbl_HospitalGroupScore')." b on (b.hospital_group_id=a.id AND b.`provider_number`='".$HospitalProviderId."')
							  LEFT JOIN ".GetLangLabel('Tbl_HospitalStar')." c ON (c.`ProviderNumber`='".$HospitalProviderId."') 
							  GROUP BY a.id ORDER BY a.group_name ASC ";
			$HospitalDetails	= $this->db->query($HospitalQuery);
			$HospitalResult   	= $HospitalDetails->result_array();
			if(isset($HospitalResult) && count($HospitalResult) > 0)
			{
				return $HospitalResult;
			}
		}

	}
	/* Method used to get hospital full details  - created by Karthik K on 04 Dec, 2014 */
	public function GetHospitalFinalResult($HospitalId='')
	{
		if($HospitalId != '')
		{
			 $HospitalQuery	= "SELECT hmd.measure_code,hmd.measure_name,
			 (case when hgd.group_score is null then 0 else hgd.group_score end) as star_value,
			 hgd.ranking_score,hgd.state_id as stateid,hgd.group_name as groupname,hgd.hospital_points,hgd.measure_percentile as measure_percentile,hs.HospitalName as hospital_name   
							   FROM ".GetLangLabel('Tbl_HospitalGroupScore')." hgd 
							   JOIN ".GetLangLabel('Tbl_HospitalMeasureList')." hmd ON (hgd.measure_id=hmd.id AND  hgd.provider_number='".$HospitalId."' )
							   LEFT JOIN ".GetLangLabel('Tbl_HospitalStar')." hs ON (hs.`ProviderNumber`=hgd.provider_number) 
							   WHERE ifnull(hmd.measure_name,'')!='' ORDER BY hgd.group_name,hmd.measure_name ASC";
			$HospitalDetails	= $this->db->query($HospitalQuery);
			$HospitalResult   	= $HospitalDetails->result_array();
			if(isset($HospitalResult) && count($HospitalResult) > 0)
			{
				return $HospitalResult;
			}
		}
	}
	/* Method used to get doctor worked hospital details - created by Karthik K on 04 Nov, 2014 */
	public function GetDoctorWorkedHospitals($DoctorNpi='')
	{
		if($DoctorNpi != '')
		{
			/*$HospitalQuery	= "(SELECT b.ProviderNumber, b.HospitalName, a.LBN1 as newHospitalName, b.Star FROM ".GetLangLabel('Tbl_DoctorList')." a, ".GetLangLabel('Tbl_HospitalStar')." b 
							  WHERE a.CCN1 = b.ProviderNumber AND a.NPI = '".$DoctorNpi."')
							  UNION (
							  SELECT b.ProviderNumber, b.HospitalName, a.LBN2  as newHospitalName, b.Star FROM ".GetLangLabel('Tbl_DoctorList')." a ,".GetLangLabel('Tbl_HospitalStar')." b
							  WHERE a.CCN2 = b.ProviderNumber AND a.NPI = '".$DoctorNpi."')
							  UNION ( 
							  SELECT b.ProviderNumber, b.HospitalName, a.LBN3  as newHospitalName, b.Star 
							  FROM ".GetLangLabel('Tbl_DoctorList')." a, ".GetLangLabel('Tbl_HospitalStar')." b 
							  WHERE a.CCN3 = b.ProviderNumber AND a.NPI = '".$DoctorNpi."')
							  ORDER BY Star DESC";*/
			$HospitalQuery   = "(SELECT b.ProviderNumber,b.HospitalName,a.LBN1 as NewHospitalName,b.Star from ".GetLangLabel('Tbl_DoctorList')." a 
								JOIN ".GetLangLabel('Tbl_HospitalStar')." b ON (b.ProviderNumber=a.CCN1) where a.NPI='".$DoctorNpi."' 
									AND (HospitalName != '' OR a.LBN1 != '' )) 
								UNION 
								(SELECT b.ProviderNumber,b.HospitalName, a.LBN2  as NewHospitalName,b.Star from ".GetLangLabel('Tbl_DoctorList')." a
								JOIN ".GetLangLabel('Tbl_HospitalStar')." b ON (b.ProviderNumber=a.CCN2) where a.NPI='".$DoctorNpi."' 
									AND (HospitalName != '' OR a.LBN2 != '' )) 
								UNION 
								(SELECT b.ProviderNumber,b.HospitalName,a.LBN3  as NewHospitalName,b.Star from ".GetLangLabel('Tbl_DoctorList')." a
								JOIN ".GetLangLabel('Tbl_HospitalStar')." b ON (b.ProviderNumber=a.CCN3) where a.NPI='".$DoctorNpi."' 
									AND (HospitalName != '' OR a.LBN3 != '' )) 
								UNION 
								(SELECT b.ProviderNumber,b.HospitalName,a.LBN4 as NewHospitalName,b.Star from ".GetLangLabel('Tbl_DoctorList')." a
								JOIN ".GetLangLabel('Tbl_HospitalStar')." b ON (b.ProviderNumber=a.CCN4) where a.NPI='".$DoctorNpi."' 
									AND (HospitalName != '' OR a.LBN4 != '' )) 
								UNION 
								(SELECT b.ProviderNumber,b.HospitalName,a.LBN5 as NewHospitalName,b.Star from ".GetLangLabel('Tbl_DoctorList')." a
								JOIN ".GetLangLabel('Tbl_HospitalStar')." b ON (b.ProviderNumber=a.CCN5) where a.NPI='".$DoctorNpi."' 
									AND (HospitalName != '' OR a.LBN5 != '' )) 
								order by Star DESC LIMIT 0,3";
			$HospitalDetails	= $this->db->query($HospitalQuery);
			$HospitalResult   	= $HospitalDetails->result_array();
			if(isset($HospitalResult) && count($HospitalResult) > 0)
			{
				return $HospitalResult;
			}
		}
	}
	/* Method used to get doctor address details - created by Karthik K on 04 Nov, 2014 */
	public function GetDoctorAddress($DoctorNpi='')
	{
		if($DoctorNpi != '')
		{
			$AddressQuery 	= "SELECT `BoardAddress`,`Address` FROM ".GetLangLabel('View_DoctorList')." WHERE NPI='".$DoctorNpi."' group by `BoardAddress`,`Address`";
 			$AddressDetails	= $this->db->query($AddressQuery);
 			$AddressResult	= $AddressDetails->result_array();
 			if(isset($AddressResult) && count($AddressResult)>0)
 			{
 				return $AddressResult;
 			}
 		}
	}
	/* Method used to cofirm user Account details - created by Karthik K on 10 Nov, 2014*/
	public function CofirmUserAccount($ConfirmKey='')
	{
		if($ConfirmKey != '')
		{
			$Query 	= "SELECT lu.id,lu.is_confirmed,uc.id as confirm_id FROM ".GetLangLabel('Tbl_LoginUsers')." lu
					   LEFT JOIN ".GetLangLabel('Tbl_UserConfirm')." uc ON (uc.user_id=lu.id) 
					   WHERE uc.confirm_key='".$ConfirmKey."'";
			$Details 	= $this->db->query($Query);
			$Result 	= $Details->result();
			if(isset($Result) && count($Result)>0)
			{
				if($Result[0]->is_confirmed == 0)
				{
					$UpdateData['confirmed_on']	= GetTime();
					// Update user email confirm with MYSQL trigger using in user_confirm table
					$this->UpdateData(GetLangLabel('Tbl_UserConfirm'),$UpdateData,array('id'=>$Result[0]->confirm_id));
					$this->session->set_flashdata('SuccessMsg',GetLangLabel('MsgNewUserAccountConfirmed'));
				}
				else
				{
					$this->session->set_flashdata('SuccessMsg',GetLangLabel('MsgNewUserAccountAlreadyConfirmed'));
				}

			}
			else
			{
				$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgInvalidConfirmKey'));
			}
		}
		else
		{
			$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgInvalidConfirmKey'));
		}
	}
	/* Method used to get user access module permisssion - created by Karthik K on 10 Nov, 2014 */
	public function CheckLevelPermission($ModuleId='')
	{
		if($ModuleId != '')
		{
			$GetResult	= $this->SelectData(GetLangLabel('Tbl_UserLevelAccessModules'),array('id'),array('group_id'=>$this->UserLevelId,'module_id'=>$ModuleId));
			if(isset($GetResult) && count($GetResult)>0)
			{
				return $GetResult;
			}
		}
	}
	/* Method used to send mail for selected user levels - created by Karthi K on 10 Nov, 2014 */
	public function SendMailToSelectedLevels()
	{
		$MailToLevel 	= $this->input->post('SltMailToLevel');
		$MailSubject	= $this->input->post('TxtMailSubject');
		$MailContent	= $this->input->post('TxtMailMessage');
		$SendToLevelId	= array();
		if(count($MailToLevel) > 0)
		{
			foreach ($MailToLevel as $GetValue) 
			{
				$SendToLevelId[]	= DecodeValue($GetValue);
			}
		}
		if(count($SendToLevelId)>0)
		{
			$ImplodeLevel	= implode(',',$SendToLevelId);
			// Get selected userlevel users list
			if($this->SuperAdmin == 1)
			{
				$Query 	= "SELECT lu.email,lu.name FROM ".GetLangLabel('Tbl_LoginUsers')." lu
						   JOIN ".GetLangLabel('Tbl_UserLevel')." ul ON (ul.id=lu.group_id)  
						   WHERE ul.id IN (".$ImplodeLevel.")";
			}
			else
			{
				$Query 	= "SELECT lu.email,lu.name FROM ".GetLangLabel('Tbl_LoginUsers')." lu
						   JOIN ".GetLangLabel('Tbl_UserLevel')." ul ON (ul.id=lu.group_id)  
						   JOIN ".GetLangLabel('Tbl_CreatedLevelList')." cll ON (cll.created_by='".$this->LoggedUserId."' AND cll.group_id=ul.id )
						   WHERE ul.id IN (".$ImplodeLevel.")";
			}
			$Details = $this->db->query($Query);
			$Result  = $Details->result();
			if(isset($Result) && count($Result)>0)
			{
				$this->load->model('sendmail_model');
				foreach($Result as $GetUserValue)
				{
					// Send mail to selected userlevel users
					$MailConfig['Email'] 		= $GetUserValue->email;
					$MailConfig['MailSubject'] 	= $MailSubject;
					$MailConfig['UserFullName'] = $GetUserValue->name;
					$MailConfig['MailContent'] 	= $MailContent;
					$this->sendmail_model->SendMailToGroupMembers($MailConfig);
				}
				$this->session->set_flashdata('SuccessMsg',GetLangLabel('MsgMailToLevel'));
			}
			else
			{
				$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgMailToLevelUserNotFound'));
			}
		}
	}
	/* Method used to display carrier list details - created by Karthik K on 18 Nov, 2014 */
	public function GetCarrierList($GetStateId='')
	{
		$AddCond	= "";
		if($GetStateId != '')
		{
			$AddCond = " AND c.id='".$GetStateId."'";
		}
		$Query 	= "SELECT b.id,c.id as state_id,c.state_name,a.carrier_name  
				   FROM ".GetLangLabel('Tbl_Carriers')." a,".GetLangLabel('Tbl_StateCarriers')." b 
				   JOIN ".GetLangLabel('Tbl_StateList')." c ON (c.id=b.state_id".$AddCond.")
				   WHERE a.status=1 AND a.id=b.carrier_id ORDER BY b.state_id ASC,carrier_name ASC";
		$Details = $this->db->query($Query);
		$Result  = $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}
	/* Method used to get user access modules - created by Karthik K on 20 Nov, 2014 */
	public function GetUserAccessModules($UserLevelId='')
	{
		$AddSubQuery	= "";
		if($UserLevelId != '')
		{
			$AddSubQuery	= ",(SELECT count(id) FROM ".GetLangLabel('Tbl_UserLevelAccessModules')." WHERE group_id='".$UserLevelId."' AND module_id=um.id) as is_level_view";
		}
		if($this->SuperAdmin == 1)
		{
			$Query 	= "SELECT um.id,um.module_name".$AddSubQuery." FROM ".GetLangLabel('Tbl_UserModules')." um WHERE um.public_view=1";
		}
		else
		{
			$Query 	= "SELECT um.id,um.module_name".$AddSubQuery." FROM ".GetLangLabel('Tbl_UserModules')." um WHERE um.public_view=1 AND um.id !=1";
		}
		$Details = $this->db->query($Query);
		$Result  = $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}
	/* Method used to get module access menus - created by Karthik K on 20 Nov, 2014 */
	public function GetModulesMenus()
	{
		$Query 	= "SELECT um.module_name,um.module_url FROM ".GetLangLabel('Tbl_UserModules')." um,
				  ".GetLangLabel('Tbl_UserLevelAccessModules')." ula 
				  WHERE um.public_view=1 AND um.id=ula.module_id AND ula.group_id='".$this->UserLevelId."' 
				  ORDER BY um.id DESC";
		$Details = $this->db->query($Query);
		$Result  = $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}
	/* Method used to get account created between two dates user details - created by Karthik K on 26 Nov, 2014 */
	public function GetAccountCreatedReports($FromDate='',$ToDate='')
	{
		if($this->SuperAdmin == 1)
		{
			$Query 	= "SELECT lu.created_on,COUNT(lu.created_on) AS RangeCount 
					   FROM ".GetLangLabel('Tbl_LoginUsers')." lu
					   WHERE lu.id!='".$this->LoggedUserId."' AND DATE(FROM_UNIXTIME(lu.created_on)) BETWEEN DATE(FROM_UNIXTIME(".$FromDate.")) 
					   AND DATE(FROM_UNIXTIME(".$ToDate."))  GROUP BY DATE(FROM_UNIXTIME(lu.created_on))";
		}
		else
		{
			$Query 	= "SELECT lu.created_on,COUNT(lu.created_on) AS RangeCount 
					   FROM ".GetLangLabel('Tbl_LoginUsers')." lu,
					   ".GetLangLabel('Tbl_CreatedUserList')." cul
					   WHERE  lu.id!='".$this->LoggedUserId."' AND cul.created_by='".$this->LoggedUserId."' AND cul.created_to=lu.id AND DATE(FROM_UNIXTIME(lu.created_on)) 
					   BETWEEN DATE(FROM_UNIXTIME(".$FromDate.")) AND DATE(FROM_UNIXTIME(".$ToDate.")) GROUP BY DATE(FROM_UNIXTIME(lu.created_on))";
		}
		$Details = $this->db->query($Query);
		$Result  = $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}
	/* Method used to get most frequent logged in users list - created by Karthik K on 26 Nov, 2014 */
	public function GetMostLoginUserList()
	{
		if($this->SuperAdmin == 1)
		{
			/*$UserQuery = "SELECT lu.name,lu.email,
						  (SELECT COUNT(id) FROM ".GetLangLabel('Tbl_LoginHistory')." WHERE user_id=lu.id) AS LoginCount
						  FROM ".GetLangLabel('Tbl_LoginUsers')." lu,
						  ".GetLangLabel('Tbl_UserLevel')." ll
						  WHERE lu.group_id=ll.id AND lu.id!='".$this->LoggedUserId."' ORDER BY LoginCount DESC Limit 0,10";*/
			$UserQuery	= "SELECT lu.name,lu.email,COUNT(ulh.id) As LoginCount  
						   FROM ".GetLangLabel('Tbl_LoginUsers')." lu 
						   JOIN ".GetLangLabel('Tbl_UserLevel')." ll on (ll.id=lu.group_id)
						   LEFT JOIN mc_user_login_history ulh ON (ulh.user_id=lu.id) 
						   WHERE ulh.id != '' AND lu.id!='".$this->LoggedUserId."' GROUP BY lu.id ORDER BY LoginCount DESC Limit 0,10";

			
		}
		else
		{
			/*$UserQuery = "SELECT lu.name,lu.email,
						  (SELECT COUNT(id) FROM ".GetLangLabel('Tbl_LoginHistory')." WHERE user_id=lu.id) AS LoginCount
						   FROM ".GetLangLabel('Tbl_LoginUsers')." lu,
						  ".GetLangLabel('Tbl_UserLevel')." ll,".GetLangLabel('Tbl_CreatedUserList')." cul
						  WHERE cul.created_by='".$this->LoggedUserId."' AND cul.created_to=lu.id AND 
						  lu.group_id=ll.id  ORDER BY LoginCount DESC Limit 0,10";*/
			$UserQuery	= "SELECT lu.name,lu.email,COUNT(ulh.id) As LoginCount  
						   FROM ".GetLangLabel('Tbl_LoginUsers')." lu 
						   JOIN ".GetLangLabel('Tbl_CreatedUserList')." cul ON (cul.created_by='".$this->LoggedUserId."' AND cul.created_to=lu.id) 
						   JOIN ".GetLangLabel('Tbl_UserLevel')." ll on (ll.id=lu.group_id)
						   LEFT JOIN mc_user_login_history ulh ON (ulh.user_id=lu.id) 
						   WHERE ulh.id != '' GROUP BY lu.id ORDER BY LoginCount DESC Limit 0,10";
		}
		$UserDetails	= $this->db->query($UserQuery);
		$UserResult		= $UserDetails->result();
		if(isset($UserResult) && count($UserResult)>0)
		{
			return $UserResult;
		}
	}
	/* Method used to send reset password details - created by Karthik K on 01 Dec, 2014 */
	public function SendResetPasswordDetails()
	{
		$GetUsername	= $this->input->post('TxtForgotUser');
		$Query 			= "SELECT id,email,name FROM ".GetLangLabel('Tbl_LoginUsers')." WHERE username='".$GetUsername."' OR email='".$GetUsername."'";
		$Details  		= $this->db->query($Query);
		$Result 		= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			// Insert user reset password details
			$ResetKey 	= random_string('alnum',25);
			$InsertResetData['user_id']		= $Result[0]->id;
			$InsertResetData['reset_key']	= $ResetKey;
			$InsertResetData['requested_on']= GetTime();
			$this->InsertData(GetLangLabel('Tbl_UserResetPassword'),$InsertResetData);
			// Send mail to reset password details
			$this->load->model('sendmail_model');
			$MailData['Email']			= $Result[0]->email;
			$MailData['UserFullName']	= $Result[0]->name;
			$MailData['ResetLink']		= "<a href='".base_url('resetpass/'.EncodeValue($ResetKey))."'>".GetLangLabel('ClickHere')."</a>";
			$this->sendmail_model->SendMailToResetPassword($MailData);
			return true;
		}
	}
	/* Method used to change user login password using reset link - created by Karthik K on 01 Dec, 2014 */
	public function ChangeUserPassword($ResetPassId='',$UserId='')
	{
		if($ResetPassId != '' && $UserId != '')
		{
			$NewPassword	= $this->input->post('TxtNewPassword');
			// Update new password
			$this->UpdateData(GetLangLabel('Tbl_LoginUsers'),array('password'=>md5($NewPassword)),array('id'=>$UserId));
			// Update password changed time
			$this->UpdateData(GetLangLabel('Tbl_UserResetPassword'),array('changed_on'=>GetTime()),array('id'=>$ResetPassId));
			$UserDetails 	= $this->SelectData(GetLangLabel('Tbl_LoginUsers'),array('name','email'),array('id'=>$UserId));
			if(isset($UserDetails) && count($UserDetails)>0)
			{
				$this->load->model('sendmail_model');
				$MailData['Email']			= $UserDetails[0]->email;
				$MailData['UserFullName']	= $UserDetails[0]->name;
				$this->sendmail_model->SendMailToPasswordChanged($MailData);
			}
		}
	}
	/* Method used to get user access log details - created by Karthik K on 01 Dec, 2014 */
	public function GetUserAccessLogDetails($UserId='')
	{
		if($UserId == '')
		{
			$UserId = $this->LoggedUserId;
		}
		$GetAccessList	= $this->common_model->SelectData(GetLangLabel('Tbl_LoginHistory'),array('logged_on','ip_address'),array('user_id'=>$UserId),array('id'=>'DESC'));
		if(isset($GetAccessList) && count($GetAccessList)>0)
		{
			return $GetAccessList;
		}
	}
}
?>
