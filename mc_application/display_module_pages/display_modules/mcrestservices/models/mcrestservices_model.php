<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 09 Dec, 2014
* Modified date : 09 Dec, 2014
* Description 	: Page contains REST webservices model functions
*/ 
class Mcrestservices_model  extends CI_Model
{
	function Mcrestservices_model()
	{
		parent::__Construct();
	}
	/* Function to get search list - created by Karthik K on 09 Dec, 2014 */
	public function GetQuickSearchedList($SearchKeyword='',$SearchState='',$SearchZipcode='')
	{
		$JsonEncode		= array();
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
				$SearchedList['category_name']	= $DisplayString;
				$SearchedList['category']		= 'Conditions';
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
				$SearchedList['category_name']	= ucwords(strtolower($DisplayString));
				$SearchedList['category']		= 'Specialty';
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
				$SearchedList['category_name']	= ucwords(strtolower($DisplayDoctors['DoctorName']));
				$SearchedList['category']		= 'Doctors';
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
				$SearchedList['category_name']	= ucwords(strtolower($DisplayHospital['HospitalName']));
				$SearchedList['category']		= 'Hospitals';
				$InnerList[]				= $SearchedList;
				$JsonEncode 				= $InnerList;
			}
		}
		return $JsonEncode;
	}
	/* Function used to get condition result - created by Karthik K on 09 Dec, 2014 */
	public function GetConditionList($Gender)
	{
		$Query 		= "SELECT `CondNum` as condNum, `Condition` as conditionName  FROM ".GetLangLabel('Tbl_ConditionList')." WHERE `CondNum` is not null 
		         	   AND `Gender` IN ('Both','".$Gender."') GROUP BY `CondNum`, `Condition` ORDER BY `Condition`";
		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}
	/* Function used to get sub condition result - created by Karthik K on 09 Dec, 2014 */
	public function GetSubConditionList($ConditionId='',$Gender='')
	{
    	$AddGenderCond	= "";
		if($Gender == 'M')
		{
			$AddGenderCond	= " AND `Gender` IN ('Both','M') ";
		}
		elseif($Gender == 'F')
		{
			$AddGenderCond	= " AND `Gender` IN ('Both','F') ";
		}
		$Query 		= "SELECT subcondnum as subCondNum , SubCondition as subCondName FROM ".GetLangLabel('Tbl_ConditionList')." 
					   WHERE CondNum = '".$ConditionId."' ".$AddGenderCond." group by subcondnum, SubCondition ORDER BY SubCondition";
		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}
	/* Function used to get sub sub condition result - created by Karthik K on 09 Dec, 2014 */
	public function GetSubSubConditionList($SubConditionId='',$Gender='')
	{
    	$AddGenderCond		= "";
		if($Gender == 'M')
		{
			$AddGenderCond	= " AND `Gender` IN ('Both','M') ";
		}
		elseif($Gender == 'F')
		{
			$AddGenderCond	= " AND `Gender` IN ('Both','F') ";
		}
		$Query 		= "SELECT cl.`SSCondNum` as subSubCondNum,cl.`Sub sub condition` as subSubCondName FROM ".GetLangLabel('Tbl_ConditionList')."  cl
					   LEFT JOIN ".GetLangLabel('Tbl_LaymanText')." lt ON (lt.Sscondnum=cl.SSCondNum)
					   WHERE cl.`SubCondNum` = $SubConditionId ".$AddGenderCond." GROUP BY cl.`SSCondNum`,cl.`Sub sub condition` 
					   ORDER BY cl.`Sub sub condition`";
		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result_array();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}
	/* Function used to get specialty list - created by Karthik K on 09 Dec, 2014 */
	public function GetSpecialtyList($Gender='')
	{
		if($Gender == 'M')
		{
			$Query = "SELECT SpecialtyNo as specialtyNum,Specialty as specialtyName FROM ".GetLangLabel('Tbl_SpecialtyList')." WHERE SpecialtyNo NOT IN ('38')
					  GROUP BY Specialty,SpecialtyNo ORDER BY Specialty";
		}
		else
		{
			$Query	= "SELECT SpecialtyNo as specialtyNum,Specialty as specialtyName FROM ".GetLangLabel('Tbl_SpecialtyList')." GROUP BY Specialty,SpecialtyNo 
					   ORDER BY Specialty";
		}
		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}
	/* Function used to get second specialty result - created by Karthik K on 09 Dec, 2014 */
	public function GetSubSpecialtyList($SpecialtyId)
	{
		$Query 			= "SELECT SubSpecialtyNo as subSpecialtyNum, SubSpecialty as subSpecialtyName FROM ".GetLangLabel('Tbl_SpecialtyList')." 
					  	   WHERE SpecialtyNo = IFNULL($SpecialtyId, SubSpecialtyNo) 
					  	   AND SubSpecialtyNo NOT IN ('103','144') ORDER BY SubSpecialty";
		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			return $Result;
		}
	}
	/* Function used to get third specialty result - created by Karthik K on 09 Dec, 2014 */
	public function GetSpecialtyProcedureCodesList($SubSpecialtyId)
	{
		$Query 		= "SELECT DISTINCT `Procedure_Group_Control_No` as procedureNum,`Procedure_Group` as procedureName 
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
			$ResultValue	= (object)array('procedureNum'=>'0','procedureName'=>'All');
			$Result[]		= $ResultValue;
			return $Result;
		}
	}
	/* Function used to get quick search doctor details - created by Karthik K on 09 Dec, 2014*/
	public function GetQuickSearchResult($SearchName='',$Zipcode='',$StateId='',$SortBy='',$StartLimit=0,$EndLimit=9)
	{
		$DoctorList = $this->common_model->CallSp(GetLangLabel('Sp_QuickSearchDetails')."('".$SearchName."','".$Zipcode."','".$StartLimit."','".$EndLimit."','".$SortBy."','".$StateId."')",true);
		if(isset($DoctorList) && count($DoctorList)>0)
		{
			return $DoctorList;
		}
	}
	/* Function used to get condition matched doctor details - created by Karthik K on 09 Dec, 2014 */
	public function GetConditionDoctorResult($ConditionId='',$SubConditionId='',$SubSubConditionId='',$Zipcode='',$Mileage='',$SortBy='',$StartLimit=0,$EndLimit=9,$StateId='',$CarrierId='')
	{
		$DoctorList = $this->common_model->CallSp(GetLangLabel('Sp_ConditionDoctorDetails')."('".$Zipcode."','".$Mileage."','".$ConditionId."','".$SubConditionId."','".$SubSubConditionId."','".$StartLimit."','".$EndLimit."','".$SortBy."','".$StateId."','".$CarrierId."')",true);
		if(isset($DoctorList) && count($DoctorList)>0)
		{
			return $DoctorList;
		}
	}
	/* Function used to get specialty matched doctor details - created by Karthik K on 9 Dec, 2014*/
	public function GetSpecialtyDoctorResult($SpecialtyId='',$SubSpecialtyId='',$ControlNumber='',$Zipcode='',$Mileage='',$SortBy='',$StartLimit=0,$EndLimit=9,$StateId='',$CarrierId='')
	{
		$DoctorList = $this->common_model->CallSp(GetLangLabel('Sp_SpecialtyDoctorDetails')."('".$Zipcode."','".$Mileage."','".$SpecialtyId."','".$SubSpecialtyId."','".$ControlNumber."','".$StartLimit."','".$EndLimit."','".$SortBy."','".$StateId."','".$CarrierId."')",true);
		if(isset($DoctorList) && count($DoctorList)>0)
		{
			return $DoctorList;
		}
	}
	/* Function used to get nearest doctor list - created by Karthik K on 4 Feb, 2015 */
	public function GetNearestDoctorList($Zipcode='',$DoctorId='',$Mileage='5',$StartLimit='0',$EndLimit='20')
	{
		if($Zipcode != '' && $DoctorId != '')
		{
			$DoctorList = $this->common_model->CallSp(GetLangLabel('Sp_NearestDoctorDetails')."('".$Zipcode."','".$Mileage."','".$StartLimit."','".$EndLimit."','".$DoctorId."')",true);
		if(isset($DoctorList) && count($DoctorList)>0)
		{
			return $DoctorList;
		}
		}
	}
}?>
