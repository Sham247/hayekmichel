<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 17 Oct, 2014
* Modified date : 17 Oct, 2014
* Description 	: Page contains Search physician page model functions
*/ 
class Searchphysician_model  extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	/* Function used to get quick search doctor details - created by Karthik K on 17 Oct, 2014 */
	public function GetQuickSearchDetails($SearchName='',$Zipcode='',$StateId='',$SortBy='',$StartLimit=0,$EndLimit=9)
	{
		$DoctorList = $this->common_model->CallSp(GetLangLabel('Sp_QuickSearchDetails')."('".$SearchName."','".$Zipcode."','".$StartLimit."','".$EndLimit."','".$SortBy."','".$StateId."')");
		if(isset($DoctorList) && count($DoctorList)>0)
		{
			return $DoctorList;
		}
	}
	/* Function used to get condition matched doctor details - created by Karthik K on 05 Nov, 2014 */
	public function GetConditionDoctorList($ConditionId='',$SubConditionId='',$SubSubConditionId='',$Zipcode='',$IsEhc='',$Mileage='',$SortBy='',$StartLimit=0,$EndLimit=9,$StateId='',$CarrierId='')
	{

// $DoctorList = $this->common_model->CallSp(GetLangLabel('Sp_ConditionDoctorDetails')."('".$Zipcode."','".$Mileage."','".$ConditionId."','".$SubConditionId."','".$SubSubConditionId."','".$StartLimit."','".$EndLimit."','".$SortBy."','".$StateId."','".$CarrierId."','".$IsEhc."')");

/****
 *
 * the code below generates a random number for quality and efficiency --- just for demos
 *
 ****/
		$Query =	"SELECT p.id, p.`NPI`, p.`PAC ID`,  CONCAT(p.`First Name`, ' ', p.`Last Name`) as ProvName, p.`Gender`, p.`Credential`, CONCAT(p.`Line 1 Street Address`, ' ', p.City, ', ', p.State, ' ',  SUBSTR(p.`Zip Code`,1,5)) as Address	, p.`Phone Number` as Phone, p.`Organization legal name`, p.`Primary specialty` as PrimarySpecialty, p.`Secondary specialty 1`, p.`Secondary specialty 2`, p.`Credential` as Education, p.`Graduation year`, p.`Professional accepts Medicare Assignment` as Accepts_Medicare, p.`Reported Quality Measures`	 as PQRS, p.`Used electronic health records` as EHR,  p.`heart_health_initiative` as HHI, p.`Hospital affiliation CCN 1`, p.`Hospital affiliation LBN 1`,  p.`Hospital affiliation CCN 2`, p.`Hospital affiliation LBN 2`,  p.`Hospital affiliation CCN 3`, p.`Hospital affiliation LBN 3`,  p.`Hospital affiliation CCN 4`,  p.`Hospital affiliation LBN 4`,  p.`Hospital affiliation CCN 5`, p.`Hospital affiliation LBN 5`, FLOOR(RAND()*(10-6+1)+1) as Quality, FLOOR(RAND()*(10-6+1)+1) as Efficiency from _phys_compare_medicare_raw_april2019 p, mc_state_list where mc_state_list.id = ".$StateId." AND p.state = mc_state_list.short_name AND p.`Primary specialty` IN (select specialty from specialities_conditions where condition_".$ConditionId." = 'X') group by p.NPI order by Quality desc, Efficiency desc LIMIT ".$StartLimit.", ".$EndLimit;
		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}

	/* Function used to get specialty matched doctor details - created by Karthik K on 05 Nov, 2014 */
	public function GetSpecialtyDoctorList($SpecialtyId='',$SubSpecialtyId='',$ControlNumber='',$Zipcode='',$IsEhc='',$Mileage='',$SortBy='',$StartLimit=0,$EndLimit=9,$StateId='',$CarrierId='')
	{
		if($IsEhc=='')
		{
			$IsEhc = 'no';
		}
		$DoctorList = $this->common_model->CallSp(GetLangLabel('Sp_SpecialtyDoctorDetails')."('".$Zipcode."','".$Mileage."','".$SpecialtyId."','".$SubSpecialtyId."','".$ControlNumber."','".$StartLimit."','".$EndLimit."','".$SortBy."','".$StateId."','".$CarrierId."','".$IsEhc."')");
		if(isset($DoctorList) && count($DoctorList)>0)
		{
			return $DoctorList;
		}
	}
	/* Function used to get first condition result - created by Karthik K on 19 Oct, 2014 */
	public function GetFirstCondition()
	{
		$Gender 	= $this->input->post('gender');
		$Query 		= "SELECT id as CondNum, TRIM(condition_name) as `Condition` FROM ".GetLangLabel('Tbl_ConditionList')." WHERE 
		         	   condition_name != '' AND Gender IN ('Both','".$Gender."') GROUP BY condition_name ORDER BY condition_name";
		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}
	/* Function used to get second condition result - created by Karthik K on 19 Oct, 2014 */
	public function GetSecondCondition()
	{
    $ConditionId 	= $this->input->post('getcondno');
    	$Gender 		= $this->input->post('gender');
    	$AddGenderCond	= "";
		if($Gender == 'M')
		{
			$AddGenderCond	= " AND `Gender` IN ('Both','M') ";
		}
		elseif($Gender == 'F')
		{
			$AddGenderCond	= " AND `Gender` IN ('Both','F') ";
		}
  $Query 		= "SELECT TRIM(primarydiagnosis) as SubCondition, id as SubConditionNo from ".GetLangLabel('Tbl_ConditionList')." WHERE  conditionid =  $ConditionId $AddGenderCond ORDER BY primarydiagnosis";
		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}

	/* Function used to get third condition result - created by Karthik K on 19 Oct, 2014 */
	public function GetThirdCondition()
	{
		$SubConditionId 	= $this->input->post('getsubcondno');
    	$Gender 			= $this->input->post('gender');
    	$AddGenderCond		= "";
		if($Gender == 'M')
		{
			$AddGenderCond	= " AND `Gender` IN ('Both','M') ";
		}
		elseif($Gender == 'F')
		{
			$AddGenderCond	= " AND `Gender` IN ('Both','F') ";
		}
    		$Query 		= "SELECT TRIM(secondarydiagnosis) as Description, primaryid as SSCondNum FROM ".GetLangLabel('Tbl_ConditionList')." 
    					   where primaryid = '".$SubConditionId."' ".$AddGenderCond."  AND SUBSTR(secondarydiag_code,4,1) != '.' group by secondarydiagnosis order by secondarydiagnosis";

		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result_array();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}


 /* Function used to get fourth condition result - created by Jvolk 4/16/19 */
  public function GetFourthCondition()
  {
    $SubSubConditionId 	= $this->input->post('getsubsubcondno');
    $Gender 			= $this->input->post('gender');
    $AddGenderCond		= "";
    if($Gender == 'M')
    {
      $AddGenderCond	= " AND `Gender` IN ('Both','M') ";
    }
    elseif($Gender == 'F')
    {
      $AddGenderCond	= " AND `Gender` IN ('Both','F') ";
    }
   // $Query 		= "select id as SSSCondNum, TRIM(secondarydiagnosis) as Description, secondarydiag_code from ".GetLangLabel('Tbl_ConditionList')."  where primaryid = '".$SubSubConditionId."' ".$AddGenderCond." AND SUBSTR(secondarydiag_code,4,1) = '.' group by secondarydiagnosis order by secondarydiagnosis";
		$Query 		= "select  Sscondnum as SSSCondNum, TRIM(secondarydiagnosis) as Description, secondarydiag_code from ".GetLangLabel('Tbl_ConditionList')."  where primaryid = '".$SubSubConditionId."' ".$AddGenderCond." AND SUBSTR(secondarydiag_code,4,1) = '.' group by secondarydiagnosis order by secondarydiagnosis";

		$Details 	= $this->db->query($Query);
    $Result 	= $Details->result_array();
    if(isset($Result) && count($Result)>0)
    {
      return $Result;
    }
  }






	/* Function used to get first specialty result - created by Karthik K on 31 Oct, 2014 */
	public function GetFirstSpecialty()
	{
		$Gender 	= $this->input->post('gender');
		if($Gender == 'M')
		{
			$Query = "SELECT Specialty, SpecialtyNo FROM ".GetLangLabel('Tbl_SpecialtyList')." WHERE SpecialtyNo NOT IN ('38')
					  GROUP BY Specialty,SpecialtyNo ORDER BY Specialty";
		}
		else
		{
			$Query	= "SELECT Specialty, SpecialtyNo FROM ".GetLangLabel('Tbl_SpecialtyList')." GROUP BY Specialty,SpecialtyNo 
					   ORDER BY Specialty";
		}
		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}
	/* Function used to get second specialty result - created by Karthik K on 31 Oct, 2014 */
	public function GetSecondSpecialty()
	{
		$Gender 		= $this->input->post('gender');
		$SpecialtyId 	= $this->input->post('getspecial');
		$Query 			= "SELECT SpecialtyNo,Specialty, SubSpecialtyNo, SubSpecialty FROM ".GetLangLabel('Tbl_SpecialtyList')." 
					  	   WHERE SpecialtyNo = IFNULL($SpecialtyId, SubSpecialtyNo) 
					  	   AND SubSpecialtyNo NOT IN ('103','144') ORDER BY SubSpecialty";
		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}
	/* Function used to get third specialty result - created by Karthik K on 31 Oct, 2014 */
	public function GetThirdSpecialty()
	{
		$Gender 			= $this->input->post('gender');
		$SubSpecialtyId 	= $this->input->post('getsubspecial');
		$Query 		= "SELECT DISTINCT `Procedure_Group_Control_No`,`Procedure_Group`,`Speciality_Control_No` 
					   FROM `procedurecodes` 
					   WHERE `Speciality_Control_No` in (SELECT ControlNo FROM ".GetLangLabel('Tbl_SpecialtyList')." WHERE SubSpecialtyNo = IFNULL($SubSpecialtyId, SubSpecialtyNo))
					   ORDER BY `Procedure_Group`";
		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
		else
		{
			$ResultValue	= (object)array('Procedure_Group'=>'All','Procedure_Group_Control_No'=>'0');
			$Result[]		= $ResultValue;
			return $Result;
		}
	}
	/* Function to get search list - created by Karthik K on 27 Oct, 2014 */
	public function GetSearchedList()
	{
		$JsonEncode		= array();
		$SearchKeyword	= isset($_REQUEST['search_name'])?trim($_REQUEST['search_name']):'';
		$SearchState	= isset($_REQUEST['search_state'])?$_REQUEST['search_state']:'';
		$SearchZipcode	= isset($_REQUEST['search_zipcode'])?$_REQUEST['search_zipcode']:'';
		if($SearchState == '')
		{
			$SearchZipcode	= '';
		}
		$SearchCondition	= "";
		$AddZipcodeCond		= "";
		if($SearchState != '')
		{
			if($SearchZipcode != '')
			{
				$AddZipcodeCond	= " AND `Zip Code`='".$SearchZipcode."' ";
			}
			$SearchCondition	= " AND `state_id`='".$SearchState."' ".$AddZipcodeCond;
		}
		// Get condition list
		$ConditionQuery	= "SELECT `Condition`,`Subcondition`,`Sub sub condition` FROM `newconditionsv3` 
						   WHERE `Condition` LIKE '%".$SearchKeyword."%' OR `Subcondition` LIKE '%".$SearchKeyword."%' 
						   OR `Sub sub condition` LIKE '%".$SearchKeyword."%' GROUP BY `Condition` LIMIT 0,25";
		$ConditionDetails	= $this->db->query($ConditionQuery);
		$ConditionResult   	= $ConditionDetails->result_array();
		if(isset($ConditionResult) && count($ConditionResult)>0)
		{
			foreach($ConditionResult as $DisplayCondition)
			{
				$DisplayString			= '';
				if(preg_match('/'.$SearchKeyword.'/i',$DisplayCondition['Condition']))
				{
					$DisplayString			= $DisplayCondition['Condition'];
				}
				else if(preg_match('/'.$SearchKeyword.'/i',$DisplayCondition['Subcondition']))
				{
					$DisplayString			= $DisplayCondition['Subcondition'];
				}
				else if(preg_match('/'.$SearchKeyword.'/i',$DisplayCondition['Sub sub condition']))
				{
					$DisplayString			= $DisplayCondition['Sub sub condition'];
				}
				$SearchedList['id']		= '';
				$SearchedList['value']		= $DisplayString;
				$SearchedList['label']		= $DisplayString;
				$SearchedList['category']	= 'Conditions';
				$InnerList[]				= $SearchedList;
				$JsonEncode 				= $InnerList;
			}
		}
		// Get Specialty list
		$SpecialtyQuery		= "SELECT sm.`Specialty`,sm.`SubSpecialty` FROM ".GetLangLabel('Tbl_SpecialtyList')." sm
							   LEFT JOIN `procedurecodes` pc ON ( pc.`Speciality_Control_No` = sm.`ControlNo` )
							   WHERE sm.`Specialty` LIKE '%".$SearchKeyword."%' OR sm.`SubSpecialty` LIKE '%".$SearchKeyword."%'
							   GROUP BY sm.`Specialty` LIMIT 0 , 25";
		$SpecialtyDetails	= $this->db->query($SpecialtyQuery);
		$SpecialtyResult   	= $SpecialtyDetails->result_array();
		if(isset($SpecialtyResult) && count($SpecialtyResult)>0)
		{
			foreach($SpecialtyResult as $DisplaySpecialty)
			{
				$DisplayString			= '';
				if(preg_match('/'.$SearchKeyword.'/i',$DisplaySpecialty['Specialty']))
				{
					$DisplayString			= $DisplaySpecialty['Specialty'];
				}
				else if(preg_match('/'.$SearchKeyword.'/i',$DisplaySpecialty['SubSpecialty']))
				{
					$DisplayString			= $DisplaySpecialty['SubSpecialty'];
				}
				/*else if(preg_match('/'.$search_keyword.'/i',$search_result['Procedure_Group']))
				{
					$display_string			= $search_result['Procedure_Group'];
				}*/
				$SearchedList['id']		= '';
				$SearchedList['value']		= ucwords(strtolower($DisplayString));
				$SearchedList['label']		= ucwords(strtolower($DisplayString));
				$SearchedList['category']	= 'Specialty';
				$InnerList[]				= $SearchedList;
				$JsonEncode 				= $InnerList;
			}
		}
		// Get Doctor list
		$SplitKeyword 	= explode(',',$SearchKeyword); 
		if(count($SplitKeyword) == 1)
		{
			$SplitKeyword = explode(' ',$SearchKeyword);
		}
		if(count($SplitKeyword)>1)
		{
			$DoctorQuery	= "SELECT CONCAT(`Last Name`,', ',`First Name`) AS DoctorName FROM ".GetLangLabel('Tbl_DoctorList')." 
							   WHERE `Status`='active' AND ((`Last Name` LIKE '%".trim($SplitKeyword[0])."%' AND `First Name` LIKE '%".trim($SplitKeyword[1])."%' )
							   OR (`First Name` LIKE '%".trim($SplitKeyword[0])."%' AND `Last Name` LIKE '%".trim($SplitKeyword[1])."%')) ".$SearchCondition." 
							   GROUP BY `NPI` ORDER BY StarProduct desc, QualStar desc, EffStar desc LIMIT 0,25";
		}
		else
		{
			$DoctorQuery	= "SELECT CONCAT(`Last Name`,', ',`First Name`) AS DoctorName FROM ".GetLangLabel('Tbl_DoctorList')."  
							   WHERE `Status`='active' AND (`Last Name` LIKE '%".$SearchKeyword."%' OR `First Name` LIKE '%".$SearchKeyword."%')  ".$SearchCondition."  
							   GROUP BY `NPI` ORDER BY StarProduct desc, QualStar desc, EffStar desc LIMIT 0,25";
		}
		$DoctorDetails		= $this->db->query($DoctorQuery);
		$DoctorResult   	= $DoctorDetails->result_array();
		if(isset($DoctorResult) && count($DoctorResult) > 0)
		{
			foreach($DoctorResult as $DisplayDoctors)
			{
				$SearchedList['id']		= '';
				$SearchedList['value']		= ucwords(strtolower($DisplayDoctors['DoctorName']));
				$SearchedList['label']		= ucwords(strtolower($DisplayDoctors['DoctorName']));
				$SearchedList['category']	= 'Doctors';
				$InnerList[]				= $SearchedList;
				$JsonEncode 				= $InnerList;
			}
		}
		// Get Hospital list
		$HospitalQuery	= "(SELECT `LBN1` AS HospitalName FROM ".GetLangLabel('Tbl_DoctorList')."  WHERE (`LBN1` LIKE '%".$SearchKeyword."%') ".$SearchCondition." ) 
						   UNION
						   (SELECT `LBN2` AS HospitalName  FROM ".GetLangLabel('Tbl_DoctorList')."  WHERE (`LBN2` LIKE '%".$SearchKeyword."%') ".$SearchCondition." )
						   UNION
						   (SELECT `LBN3` AS HospitalName  FROM ".GetLangLabel('Tbl_DoctorList')."  WHERE (`LBN3` LIKE '%".$SearchKeyword."%') ".$SearchCondition." )
						   UNION
						   (SELECT `LBN4` AS HospitalName  FROM ".GetLangLabel('Tbl_DoctorList')."  WHERE (`LBN4` LIKE '%".$SearchKeyword."%') ".$SearchCondition." )
						   UNION
						   (SELECT `LBN5` AS HospitalName  FROM ".GetLangLabel('Tbl_DoctorList')."  WHERE (`LBN5` LIKE '%".$SearchKeyword."%') ".$SearchCondition." ) 
						   LIMIT 0,25";
		$HospitalDetails	= $this->db->query($HospitalQuery);
		$HospitalResult   	= $HospitalDetails->result_array();
		if(isset($HospitalResult) && count($HospitalResult) > 0)
		{
			foreach($HospitalResult as $DisplayHospital)
			{
				$SearchedList['id']			= '';
				$SearchedList['value']		= ucwords(strtolower($DisplayHospital['HospitalName']));
				$SearchedList['label']		= ucwords(strtolower($DisplayHospital['HospitalName']));
				$SearchedList['category']	= 'Hospitals';
				$InnerList[]				= $SearchedList;
				$JsonEncode 				= $InnerList;
			}
		}
		return $JsonEncode;
	}
} ?>
