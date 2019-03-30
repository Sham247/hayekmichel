<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 17 Nov, 2014
* Modified date : 17 Nov, 2014
* Description 	: Page contains REST webservice functions
*/ 
require(APPPATH.'libraries/REST_Controller.php');
class Mcrestservices extends REST_Controller 
{
	function __Construct()
	{
		parent::__Construct();
		$this->load->model('mcrestservices_model','restmodel');
		$this->load->helper('rest_webservice');
		CheckWebserviceAuthentication();
	}
	/* Method used to check login user credentials - created by Karthik K on 17 Nov, 2014 */
	public function login_post()
	{
		$GetUserName	= $this->post('username');
		$Password		= $this->post('password');
		$Status			= BAD;
		$Message		= "";
		$LoginResult	= $this->common_model->CheckUserLogin($GetUserName,md5($Password));
		if(isset($LoginResult) && count($LoginResult)>0)
		{
			$GetDateTime	= time();
			if(($LoginResult[0]->expire_date > $GetDateTime) || $LoginResult[0]->expire_date == null)
			{
				if($LoginResult[0]->is_confirmed == 1)
				{
					$Status		= SUCCESS;
					$Message	= "Logged in successfully";
					$UserResult['userId']		= $LoginResult[0]->id;
					$UserResult['userFullName']	= $LoginResult[0]->name;
					$UserResult['userLevelId'] 	= $LoginResult[0]->group_id;
					$UserResult['userLevelName']= $LoginResult[0]->group_name;
					$ReturnResult['ret_userResult']	= $UserResult;
					// Update login history details
					$HistoryData['user_id']		= $LoginResult[0]->id;
					$HistoryData['ip_address']	= GetIp();
					$HistoryData['logged_on']	= GetTime();
					$this->common_model->InsertData(GetLangLabel('Tbl_LoginHistory'),$HistoryData);
				}
				else
				{
					$Message = GetLangLabel('MsgConfirmEmail');
				}
			}
			else
			{
				$Message = GetLangLabel('MsgAccountExpired');
			}
		}
		else
		{
			$Message = GetLangLabel('MsgInvalidLoginDetails');
		}
		$ReturnResult['ret_status']		= $Status;
		$ReturnResult['ret_message']	= $Message;
		$this->response($ReturnResult);
	}
	/* Method used to get state list - created by Karthik K on 17 Nov, 2014 */
	public function GetStateList_post()
	{
		$ReturnStateValues	= array();
		$GetStateData		= $this->common_model->GetStateList();
		if(isset($GetStateData) && count($GetStateData)>0)
		{
			foreach($GetStateData as $StateValues)
			{
				$InnerState['stateId']	= $StateValues->id;
				$InnerState['stateName']= $StateValues->state_name;
				/*$InnerState['physicianRanked']	= $StateValues->physicians_ranked;
				$InnerState['stateCovered']		= $StateValues->state_covered;*/
				$ReturnStateValues[]	= $InnerState;
			}
		}
		$ReturnResult['ret_stateResult'] 	= $ReturnStateValues;
		$ReturnResult['ret_status']			= SUCCESS;
		$this->response($ReturnResult);
	}
	/* Method used to get zipcode based on selected state - created by Karthik K on 17 Nov, 2014 */
	public function GetZipcodeList_post()
	{
		$Status 	= BAD;
		$StateId 	= (int)$this->post('stateId');
		$Zipcode 	= $this->post('zipcode');
		if($StateId > 0 )
		{
			$ZipcodeResult = array();
			$GetZipcodeList 	= $this->common_model->GetStateZipcode($StateId,$Zipcode);
			if(isset($GetZipcodeList) && count($GetZipcodeList)>0)
			{
				foreach($GetZipcodeList as $GetValues)
				{
					$ZipcodeResult[]['zipcode']	= $GetValues->label;
				}
				$Status = SUCCESS;
			}
			$ReturnResult['ret_zipcodeResult']	= $ZipcodeResult;
		}
		$ReturnResult['ret_status']	= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to get all carrier list - created by Karthik K on 09 Dec, 2014 */
	public function GetCarrierList_post()
	{
		$GetCarrierList			= $this->common_model->GetCarrierList();
		$JsonCarrierArray		= array();
		if(isset($GetCarrierList) && count($GetCarrierList)>0)
		{
			foreach ($GetCarrierList as $CarrierValues) 
			{
				$InnerCarreirVal['carrierId']	= $CarrierValues->id;
				$InnerCarreirVal['carrierName']	= $CarrierValues->carrier_name;
				$InnerCarreirVal['stateId']		= $CarrierValues->state_id;
				$InnerCarreirVal['stateName']	= $CarrierValues->state_name;
				$InnerCarreirVal['joinCarrierName']	= $CarrierValues->state_name.": ".$CarrierValues->carrier_name;
				$JsonCarrierArray[]					= $InnerCarreirVal;
			}
		}
		$ReturnResult['ret_carrierList']= $JsonCarrierArray;
		$ReturnResult['ret_status']		= SUCCESS;
		$this->response($ReturnResult);
	}
	/* Method used to get state carriers list - created by Karthik K on 09 Dec, 2014 */
	public function GetStateCarrierList_post()
	{
		$StateId 	= (int)$this->post('stateId');
		$Status 	= BAD;
		if($StateId > 0)
		{
			$JsonCarrierArray	= array();
			$GetCarrierList		= $this->common_model->GetCarrierList($StateId);
			if(isset($GetCarrierList) && count($GetCarrierList)>0)
			{
				foreach ($GetCarrierList as $CarrierValues) 
				{
					$InnerCarreirVal['carrierId']	= $CarrierValues->id;
					$InnerCarreirVal['carrierName']	= $CarrierValues->carrier_name;
					$InnerCarreirVal['stateId']		= $CarrierValues->state_id;
					$InnerCarreirVal['stateName']	= $CarrierValues->state_name;
					$JsonCarrierArray[]				= $InnerCarreirVal;
				}
			}
			$Status = SUCCESS;
			$ReturnResult['ret_stateCarrierList']= $JsonCarrierArray;
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to get quick search list (Condition, Specialty, Doctors and Hospital names) - created by Karthik K on 09 Dec, 2014 */
	public function QuickSearchList_post()
	{
		$SearchName = $this->post('searchName');
		$StateId 	= (int)$this->post('stateId');
		$Zipcode 	= $this->post('zipcode');
		$Status 	= BAD;
		if($SearchName != '' || $StateId > 0)
		{
			$SearchList = array();
			$GetResult	= $this->restmodel->GetQuickSearchedList($SearchName,$StateId,$Zipcode);
			if(isset($GetResult) && count($GetResult)>0)
			{
				foreach ($GetResult as $GetValue) 
				{
					$InnerSearchList['searchedName']	= $GetValue['category_name'];
					$InnerSearchList['searchedCategory']= $GetValue['category'];
					$SearchList[]						= $InnerSearchList;
				}
			}
			$ReturnResult['ret_searchList']	= $SearchList;
			$Status 	= SUCCESS;
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to get quick search result - created by Karthik K on 09 Dec, 2014 */
	public function QuickSearchResult_post()
	{
		$SearchName = $this->post('searchName');
		$StateId 	= (int)$this->post('stateId');
		$Zipcode 	= $this->post('zipcode');
		$StartLimit = $this->post('startLimit');
		$StartLimit = ($StartLimit != '')?$StartLimit:'0';
		$EndLimit 	= $this->post('endLimit');
		$EndLimit   = ($EndLimit != '')?$EndLimit:'8';
		$Status 	= BAD;
		if($SearchName != '' || ($StateId != '' AND $StateId != 0))
		{
			$GetResult	= $this->restmodel->GetQuickSearchResult($SearchName,$Zipcode,$StateId,'',$StartLimit,$EndLimit);
			$DoctorList = array();
			if(isset($GetResult) && count($GetResult)>0)
			{
				foreach($GetResult as $GetValue)
				{
					$InnerDocList['docId']			= $GetValue['id'];
					$InnerDocList['docNpi']			= $GetValue['NPI'];
					$InnerDocList['docName']		= trim($GetValue['ProvName']);
					$InnerDocList['docEducation']	= trim($GetValue['Education']);
					$InnerDocList['docSpecialty']	= trim($GetValue['PrimarySpecialty']);
					// $InnerDocList['docStarProduct']			= $GetValue->StarProduct;
					$InnerDocList['docEfficiency']	= $GetValue['Efficiency'];
					$InnerDocList['docQuality']		= $GetValue['Quality'];
					// Show languages list
					// Explode doctor known languages
					$Languages = "English";
					if($GetValue['Languages']  != '' && $GetValue['Languages'] != '0')
					{
						$ExplodeLanguage	= explode('@',$GetValue['Languages']);
						if(count($ExplodeLanguage)>0)
						{
							$ReturnLanguage	= array();
							foreach($ExplodeLanguage as $GetLanguage)
							{
								// Change display language format
								$ReturnLanguage[]	= ucfirst(strtolower($GetLanguage));
							}
						} 
						// Implode known language
						$Languages = implode(' | ',$ReturnLanguage);
						$Languages = 'English | '.$Languages;
					}
					$InnerDocList['docLanguages']	= $Languages;
					$InnerDocList['docGender']		= $GetValue['Gender'];
					$Gendername = "Not Specified";
					if($GetValue['Gender'] == 'M')
					{
						$Gendername = "Male";
					}
					else if($GetValue['Gender'] == 'F')
					{
						$Gendername = "Female";
					}
					$InnerDocList['docGenderName']	= $Gendername;
					$InnerDocList['docAddress']		= $GetValue['Address'];
					$InnerDocList['docPhone']		= ($GetValue['Phone'] != '')?$GetValue['Phone']:'';
					$FindDocZipcode =  $GetValue['Zip Code'];
					if(trim($Zipcode) != '')
					{
						$FindDocZipcode = $Zipcode;
					}
					$GetNearestDoctors			= $this->restmodel->GetNearestDoctorList($FindDocZipcode,$GetValue['id']);	
					$NearestDoctors = array();
					if(isset($GetNearestDoctors) && count($GetNearestDoctors)>0)
					{
						foreach($GetNearestDoctors as $LoopValue)
						{
							$InnerNearestDoc['docId'] 	= $LoopValue['id'];
							$InnerNearestDoc['docNpi'] 	= $LoopValue['NPI'];
							$InnerNearestDoc['docName'] 	= $LoopValue['ProvName'];
							$InnerNearestDoc['docAddress'] 	= $LoopValue['Address'];
							$NearestDoctors[]		= $InnerNearestDoc;
						}
					}
					$InnerDocList['nearestDoctors']	= $NearestDoctors;
					$DoctorList[]					= $InnerDocList;
				}
			}
			$Status 	= SUCCESS;
			$ReturnResult['ret_quickSearchDocList']	= $DoctorList;
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to get condition list - created by Karthik K on 09 Dec, 2014 */
	public function GetCondition_post()
	{
		$GetGender	= $this->post('gender');
		$Status = BAD;
		if($GetGender != '')
		{
			$Status = SUCCESS;
			$ConditionValues =array();
			$GetConditionList = $this->restmodel->GetConditionList($GetGender);
			if(isset($GetConditionList) && count($GetConditionList)>0)
			{
				$ConditionValues	=  $GetConditionList;
			}
			$ReturnResult['ret_conditionList'] = $ConditionValues;
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to get sub condition list - created by Karthik K on 09 Dec, 2014 */
	public function GetSubCondition_post()
	{
		$ConditionId	= (int)$this->post('conditionId');
		$GetGender		= $this->post('gender');
		$Status = BAD;
		if($ConditionId > 0 && $GetGender != '')
		{
			$Status = SUCCESS;
			$ReturnList	= array();
			$GetResult 	= $this->restmodel->GetSubConditionList($ConditionId,$GetGender);
			if(isset($GetResult) && count($GetResult)>0)
			{
				$ReturnList = $GetResult;
			}
			$ReturnResult['ret_subConditionList'] = $ReturnList;
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to get sub sub condition list - created by Karthik K on 09 Dec, 2014 */
	public function GetSubSubCondition_post()
	{
		$SubConditionId	= (int)$this->post('subConditionId');
		$GetGender		= $this->post('gender');
		$Status = BAD;
		if($SubConditionId > 0 && $GetGender != '')
		{
			$Status = SUCCESS;
			$ReturnList = array();
			$GetResult = $this->restmodel->GetSubSubConditionList($SubConditionId,$GetGender);
			if(isset($GetResult) && count($GetResult)>0)
			{
				$ReturnList = $GetResult;
			}
			$ReturnResult['ret_subSubConditionList'] = $ReturnList;
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to get specialty list  - created by Karthik K on 09 Dec, 2014 */
	public function GetSpecialty_post()
	{
		$GetGender	= $this->post('gender');
		$ReturnResult['ret_specialtyList'] = $this->restmodel->GetSpecialtyList($GetGender);
		$ReturnResult['ret_status']		= SUCCESS;
		$this->response($ReturnResult);
	}
	/* Method used to get sub specialty list - created by Karthik K on 09 Dec, 2014 */
	public function GetSubSpecialty_post()
	{
		$SpecialtyId	= (int)$this->post('specialtyId');
		$Status 		= BAD;
		if($SpecialtyId > 0)
		{
			$ReturnList = array();
			$GetResult 	= $this->restmodel->GetSubSpecialtyList($SpecialtyId);
			if(isset($GetResult) && count($GetResult)>0)
			{
				$ReturnList = $GetResult;
			}
			$ReturnResult['ret_subSpecialtyList'] = $ReturnList;
			$Status 	= SUCCESS;
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to get sub specialty prodecurecodes list - created by Karthik K on 09 Dec, 2014  */
	public function GetSubSpecialtyProcedureCodes_post()
	{
		$SubSpecialtyId	= (int)$this->post('subSpecialtyId');
		$Status 		= BAD;
		if($SubSpecialtyId > 0)
		{
			$ReturnResult['ret_procedureCodesList'] = $this->restmodel->GetSpecialtyProcedureCodesList($SubSpecialtyId);
			$Status 	= SUCCESS;
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to get doctor list based on selected condition details - created by Karthik K on 09 Dec, 2014 */
	public function GetDoctorListUsingCondition_post()
	{
		$ConditionId 		= (int)$this->post('conditionId');
		$SubConditionId 	= (int)$this->post('subConditionId');
		$SubSubConditionId 	= $this->post('subSubConditionId');
		$Zipcode 			= $this->post('zipcode');
		$Mileage			= $this->post('mileage');
		$StateId 			= $this->post('stateId');
		$CarrierId 			= $this->post('carrierId');
		$StartLimit = $this->post('startLimit');
		$StartLimit = ($StartLimit != '')?$StartLimit:'0';
		$EndLimit 	= $this->post('endLimit');
		$EndLimit   = ($EndLimit != '')?$EndLimit:'8';
		$CarrierId 			= '';
		$Status 	= BAD;
		if($ConditionId > 0 && $SubConditionId > 0 && $Zipcode != '')
		{
			$GetResult	= $this->restmodel->GetConditionDoctorResult($ConditionId,$SubConditionId,$SubSubConditionId,$Zipcode,$Mileage,'',$StartLimit,$EndLimit,$StateId,$CarrierId);
			$DoctorList = array();
			if(isset($GetResult) && count($GetResult)>0)
			{
				foreach($GetResult as $GetValue)
				{
					$InnerDocList['docId']			= $GetValue['id'];
					$InnerDocList['docNpi']			= $GetValue['NPI'];
					$InnerDocList['docName']		= trim($GetValue['ProvName']);
					$InnerDocList['docEducation']	= trim($GetValue['Education']);
					$InnerDocList['docSpecialty']	= trim($GetValue['PrimarySpecialty']);
					// $InnerDocList['docStarProduct']			= $GetValue->StarProduct;
					$InnerDocList['docEfficiency']			= $GetValue['Efficiency'];
					$InnerDocList['docQuality']				= $GetValue['Quality'];
					// Show languages list
					// Explode doctor known languages
					$Languages = "English";
					if($GetValue['Languages'] != '0')
					{
						$ExplodeLanguage	= explode('@',$GetValue['Languages']);
						if(count($ExplodeLanguage)>0)
						{
							$ReturnLanguage	= array();
							foreach($ExplodeLanguage as $GetLanguage)
							{
								// Change display language format
								$ReturnLanguage[]	= ucfirst(strtolower($GetLanguage));
							}
						} 
						// Implode known language
						$Languages = implode(' | ',$ReturnLanguage);
						$Languages = 'English | '.$Languages;
					}
					$InnerDocList['docLanguages']	= $Languages;
					$InnerDocList['docGender']		= $GetValue['Gender'];
					$Gendername = "Not Specified";
					if($GetValue['Gender'] == 'M')
					{
						$Gendername = "Male";
					}
					else if($GetValue['Gender'] == 'F')
					{
						$Gendername = "Female";
					}
					$InnerDocList['docGenderName']	= $Gendername;
					$InnerDocList['docAddress']		= $GetValue['Address'];
					$InnerDocList['docPhone']		= $GetValue['Phone'];
					$GetNearestDoctors			= $this->restmodel->GetNearestDoctorList($Zipcode,$GetValue['id']);	
					$NearestDoctors = array();
					if(isset($GetNearestDoctors) && count($GetNearestDoctors)>0)
					{
						foreach($GetNearestDoctors as $LoopValue)
						{
							$InnerNearestDoc['docId'] 	= $LoopValue['id'];
							$InnerNearestDoc['docNpi'] 	= $LoopValue['NPI'];
							$InnerNearestDoc['docName'] 	= $LoopValue['ProvName'];
							$InnerNearestDoc['docAddress'] 	= $LoopValue['Address'];
							$NearestDoctors[]		= $InnerNearestDoc;
						}
					}
					$InnerDocList['nearestDoctors']	= $NearestDoctors;
					$DoctorList[]					= $InnerDocList;
				}
			}
			$Status 	= SUCCESS;
			$ReturnResult['ret_conditionDocList']	= $DoctorList;
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to get doctor list based on selected specialty details - created by Karthik K on 09 Dec, 2014 */
	public function GetDoctorListUsingSpecialty_post()
	{
		$SpecialtyId 		= (int)$this->post('specialtyId');
		$SubSpecialtyId 	= (int)$this->post('subSpecialtyId');
		$ProcedureCodeId 	= $this->post('procedureCodeId');
		$Zipcode 			= $this->post('zipcode');
		$Mileage			= $this->post('mileage');
		$StateId 			= $this->post('stateId');
		$CarrierId 			= $this->post('carrierId');
		$StartLimit = $this->post('startLimit');
		$StartLimit = ($StartLimit != '')?$StartLimit:'0';
		$EndLimit 	= $this->post('endLimit');
		$EndLimit   = ($EndLimit != '')?$EndLimit:'8';
		$CarrierId 			= '';
		$Status 	= BAD;


$SpecialtyId 		= 17;
		$SubSpecialtyId 	= 109;
		$ProcedureCodeId 	= 9108;
		$Zipcode 		= 30309;

		if($SpecialtyId != 0 && $SubSpecialtyId != 0)
		{
			$GetResult	= $this->restmodel->GetSpecialtyDoctorResult($SpecialtyId,$SubSpecialtyId,$ProcedureCodeId,$Zipcode,$Mileage,'',$StartLimit,$EndLimit,$StateId,$CarrierId);
			$DoctorList = array();
			if(isset($GetResult) && count($GetResult)>0)
			{
				foreach($GetResult as $GetValue)
				{
					$InnerDocList['docId']			= $GetValue['id'];
					$InnerDocList['docNpi']			= $GetValue['NPI'];
					$InnerDocList['docName']		= trim($GetValue['ProvName']);
					$InnerDocList['docEducation']	= trim($GetValue['Education']);
					$InnerDocList['docSpecialty']	= trim($GetValue['PrimarySpecialty']);
					// $InnerDocList['docStarProduct']			= $GetValue->StarProduct;
					$InnerDocList['docEfficiency']	= $GetValue['Efficiency'];
					$InnerDocList['docQuality']		= $GetValue['Quality'];
					$Languages = "English";
					if($GetValue['Languages'] != '0')
					{
						$ExplodeLanguage	= explode('@',$GetValue['Languages']);
						if(count($ExplodeLanguage)>0)
						{
							$ReturnLanguage	= array();
							foreach($ExplodeLanguage as $GetLanguage)
							{
								// Change display language format
								$ReturnLanguage[]	= ucfirst(strtolower($GetLanguage));
							}
						} 
						// Implode known language
						$Languages = implode(' | ',$ReturnLanguage);
						$Languages = 'English | '.$Languages;
					}
					$InnerDocList['docLanguages']	= $Languages;
					$InnerDocList['docGender']		= $GetValue['Gender'];
					$Gendername = "Not Specified";
					if($GetValue['Gender'] == 'M')
					{
						$Gendername = "Male";
					}
					else if($GetValue['Gender'] == 'F')
					{
						$Gendername = "Female";
					}
					$InnerDocList['docGenderName']	= $Gendername;
					$InnerDocList['docAddress']		= $GetValue['Address'];
					$InnerDocList['docPhone']		= $GetValue['Phone'];
					$GetNearestDoctors			= $this->restmodel->GetNearestDoctorList($Zipcode,$GetValue['id']);	
					$NearestDoctors = array();
					if(isset($GetNearestDoctors) && count($GetNearestDoctors)>0)
					{
						foreach($GetNearestDoctors as $LoopValue)
						{
							$InnerNearestDoc['docId'] 	= $LoopValue['id'];
							$InnerNearestDoc['docNpi'] 	= $LoopValue['NPI'];
							$InnerNearestDoc['docName'] 	= $LoopValue['ProvName'];
							$InnerNearestDoc['docAddress'] 	= $LoopValue['Address'];
							$NearestDoctors[]		= $InnerNearestDoc;
						}
					}
					$InnerDocList['nearestDoctors']	= $NearestDoctors;
					$DoctorList[]					= $InnerDocList;
				}
			}
			$Status 	= SUCCESS;
			$ReturnResult['ret_specialtyDocList']	= $DoctorList;
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to get doctor profile details - created by Karthik K on 09 Dec, 2014  */
	public function GetDoctorProfile_post()
	{
		$DoctorNpi	= $this->post('docNpi');
		$Status 	= BAD;
		if($DoctorNpi != '')
		{
			$GetDoctorProfile 	= $this->common_model->GetDoctorProfile($DoctorNpi);
			if(isset($GetDoctorProfile) && count($GetDoctorProfile)>0)
			{
				$InnerDocProfile['docId']			= $GetDoctorProfile[0]['id'];
				$InnerDocProfile['docNpi']			= $GetDoctorProfile[0]['NPI'];
				$InnerDocProfile['docName']			= trim($GetDoctorProfile[0]['ProvName']);
				$InnerDocProfile['docEducation']	= trim($GetDoctorProfile[0]['Education']);
				$InnerDocProfile['docEfficiency']	= $GetDoctorProfile[0]['Efficiency'];
				$InnerDocProfile['docQuality']		= $GetDoctorProfile[0]['Quality'];
				// Show languages list
				// Explode doctor known languages
				$Languages 	= "English";
				if($GetDoctorProfile[0]['Languages'] != '0')
				{
					$ExplodeLanguage	= explode('@',$GetDoctorProfile[0]['Languages']);
					if(count($ExplodeLanguage)>0)
					{
						$ReturnLanguage	= array();
						foreach($ExplodeLanguage as $GetLanguage)
						{
							// Change display language format
							$ReturnLanguage[]	= ucfirst(strtolower($GetLanguage));
						}
					} 
					// Implode known language
					$Languages = implode(' | ',$ReturnLanguage);
					$Languages = 'English | '.$Languages;
				}
				$InnerDocProfile['docLanguages']	= $Languages;
				$InnerDocProfile['docSpecialty']	= trim($GetDoctorProfile[0]['PrimarySpecialty']);
				$InnerDocProfile['docOrganizationName']	= trim($GetDoctorProfile[0]['Organizationlegalname']);

				$MedicalShoolValue	= array();
				if($GetDoctorProfile[0]['Medical school name'] == 'OTHER' || $GetDoctorProfile[0]['Medical school name'] == null || $GetDoctorProfile[0]['Medical school name'] == '')
				{
					$ExplodeMedSchool	= explode('@@@',$GetDoctorProfile[0]['MedicalSchool']);
					$ExplodeMedSchool	= array_filter($ExplodeMedSchool);
					if(count($ExplodeMedSchool)>0)
					{
						foreach($ExplodeMedSchool as $DisplaySchoolVal)
						{
							$ExplodeSchlName	= explode('|',$DisplaySchoolVal);
							if(count($ExplodeSchlName)>0)
							{
								$MedicalShoolValue[]['medicalSchool'] = $ExplodeSchlName[0];
							}
						}
					}
				}
				else
				{
					if(trim($GetDoctorProfile[0]['Medical school name']) != '')
					{
						$ExplodeMedSchool	= explode('|',$GetDoctorProfile[0]['Medical school name']);
						if(count($ExplodeMedSchool)>0)
						{
							$MedicalShoolValue[]['medicalSchool'] = $ExplodeMedSchool[0];
						}
					}
				}
				$InnerDocProfile['docMedicalSchoolList']= $MedicalShoolValue;
				$InnerDocProfile['docGraduationYear']	= $GetDoctorProfile[0]['Graduation year'];
				$InnerDocProfile['docOrganizationList']	= GetMultipleDoctorList($GetDoctorProfile[0]['O_Organizations'],'|','organization');
				$InnerDocProfile['docPhone']			= $GetDoctorProfile[0]['Phone'];
				$InnerDocProfile['docFinalDisActionList']	= GetMultipleDoctorList($GetDoctorProfile[0]['FinalDisAction'],'@','disAction');
				$InnerDocProfile['docNCQAList']				= GetMultipleDoctorList($GetDoctorProfile[0]['NCQA_Recognization'],'@','ncqaRecongnization');
				$InnerDocProfile['docGender']				= $GetDoctorProfile[0]['Gender'];
				$Gendername = "Not Specified";
				if($GetDoctorProfile[0]['Gender'] == 'M')
				{
					$Gendername = "Male";
				}
				else if($GetDoctorProfile[0]['Gender'] == 'F')
				{
					$Gendername = "Female";
				}
				$InnerDocProfile['docGenderName']	= $Gendername;
				// Show multiple address list
				$DoctorAddressList	= $this->common_model->GetDoctorAddress($DoctorNpi);
				$AddressListVal		= array();
				if(isset($DoctorAddressList) && count($DoctorAddressList)>0)
				{
					foreach($DoctorAddressList as $GetAddressVal)
					{
						$AddressListVal[]['address']	= $GetAddressVal['Address'];
					}
				}
				$InnerDocProfile['docAddressList']		= $AddressListVal;
				$InnerDocProfile['docCriminalOffencesList']	= GetMultipleDoctorList($GetDoctorProfile[0]['CriminalOffences'],'@','crimeOffence');
				$AcceptMedcareText	= '-';
				if($GetDoctorProfile[0]['accepts_medicare_assignment'] != '')
				{
					if($GetDoctorProfile[0]['accepts_medicare_assignment'] != '')
					{
						if($GetDoctorProfile[0]['accepts_medicare_assignment'] == 'Y')
						{
							$AcceptMedcareText = 'Accepts';
						}
						else if($GetDoctorProfile[0]['accepts_medicare_assignment'] == 'M')
						{
							$AcceptMedcareText = 'May Accept';
						}
						else
						{
							$AcceptMedcareText = 'No';
						}
					}
				}
				$InnerDocProfile['docAcceptMedicareAssignment']= $AcceptMedcareText;
				$ErxText	= "-";
				if($GetDoctorProfile[0]['erx'] != '')
				{
					if($GetDoctorProfile[0]['erx'] == 'Y')
					{
						$ErxText = 'Yes';
					}
					else
					{
						$ErxText = 'No';
					}
				}
				$InnerDocProfile['docErx']		= $ErxText;
				$PqrsText	= "-";
				if($GetDoctorProfile[0]['pqrs'] != '')
				{
					if($GetDoctorProfile[0]['pqrs'] == 'Y')
					{
						$PqrsText = 'Yes';
					}
					else
					{
						$PqrsText = 'No';
					}
				}
				$InnerDocProfile['docPqrs']		= $PqrsText;
				$EhrText	= "-";
				if($GetDoctorProfile[0]['ehr'] != '')
				{
					if($GetDoctorProfile[0]['ehr'] == 'Y')
					{
						$EhrText = 'Yes';
					}
					else
					{
						$EhrText = 'No';
					}
				}
				$InnerDocProfile['docEhr']		= $EhrText;
				$InnerDocProfile['docPublicationList']	= GetMultipleDoctorList($GetDoctorProfile[0]['O_Publications'],'@','publication');
				$InnerDocProfile['docAppoinmentList']	= GetMultipleDoctorList($GetDoctorProfile[0]['O_Appointments'],'@','appoinment');
				$InnerDocProfile['docCertificateList']	= GetMultipleDoctorList($GetDoctorProfile[0]['Doctor_certificate'],'|@@|','certificate');
				$ReturnResult['ret_doctorProfile'] = $InnerDocProfile;
				$Status 	= SUCCESS;
			}
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to get hospital profile details - created by Karthik K on 09 Dec, 2014  */
	public function GetComparedDoctorList_post()
	{
		$CompareDocId	= $this->post('compareDocId');
		$Status 		= BAD;
		if($CompareDocId != '')
		{
			if(is_array($CompareDocId))
			{
				$CompareDocId = implode(',',$CompareDocId);
			}
			$GetCompareList = $this->common_model->GetComparedList($CompareDocId);
			if(isset($GetCompareList) && count($GetCompareList)>0)
			{
				$Status = SUCCESS;
				$ComparedDoctorList = array();
				foreach($GetCompareList as $GetValue)
				{
					$InnerDocValue['docId']			= $GetValue['id'];
					$InnerDocValue['docNpi']		= $GetValue['NPI'];
					$InnerDocValue['docName']		= trim($GetValue['ProvName']);
					$InnerDocValue['docEducation']	= trim($GetValue['Education']);
					$InnerDocValue['docEfficiency']	= $GetValue['Efficiency'];
					$InnerDocValue['docQuality']	= $GetValue['Quality'];
					// Show languages
					// Explode doctor known languages
					$Languages 	= "English";
					if($GetValue['Languages'] != '' && $GetValue['Languages'] != '0')
					{
						$ExplodeLanguage	= explode('@',$GetValue['Languages']);
						if(count($ExplodeLanguage)>0)
						{
							$ReturnLanguage	= array();
							foreach($ExplodeLanguage as $GetLanguage)
							{
								// Change display language format
								$ReturnLanguage[]	= ucfirst(strtolower($GetLanguage));
							}
						} 
						// Implode known language
						$Languages = implode(' | ',$ReturnLanguage);
						$Languages = 'English | '.$Languages;
					}
					$InnerDocValue['docLanguages']	= $Languages;
					$InnerDocValue['docGender']		= $GetValue['Gender'];
					$Gendername = "Not Specified";
					if($GetValue['Gender'] == 'M')
					{
						$Gendername = "Male";
					}
					else if($GetValue['Gender'] == 'F')
					{
						$Gendername = "Female";
					}
					$InnerDocValue['docGenderName']	= $Gendername;
					$InnerDocValue['docAddress']	= trim($GetValue['Address']);
					$InnerDocValue['docSpecialty']	= trim($GetValue['PrimarySpecialty']);
					$InnerDocValue['docPhone']		= $GetValue['Phone'];
					$DisplayHospitalList			= array();
					$GetHospitalList				= $this->common_model->GetDoctorWorkedHospitals($GetValue['NPI']);
					if(isset($GetHospitalList) && count($GetHospitalList)>0)
					{
						foreach($GetHospitalList as $GetHospitalVal)
						{
							$InnerHospitalVal['hospitalId']		= $GetHospitalVal['ProviderNumber'];
							$InnerHospitalVal['hospitalName']	= $GetHospitalVal['HospitalName'];
							$InnerHospitalVal['hospitalRating']	= $GetHospitalVal['Star'];
							$DisplayHospitalList[]	= $InnerHospitalVal;
						}
					}
					$InnerDocValue['docHospitalList']= $DisplayHospitalList;
					$ComparedDoctorList[]			= $InnerDocValue;
				}
				$ReturnResult['ret_comparedDocList']	= $ComparedDoctorList;
			}
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}

	/* Method used to get hospital profile details - created by Karthik K on 09 Dec, 2014  */
	public function GetHospitalProfile_post()
	{
		$HospitalId	= $this->post('hospitalId');
		$Status 		= BAD;
		if($HospitalId != '')
		{
			$HospitalDetails = $this->common_model->GetHospitalProfile($HospitalId);
			if(isset($HospitalDetails) && count($HospitalDetails)>0)
			{
				$HospitalProfile = array();
				foreach($HospitalDetails as $GetValue)
				{
					$InnerProfile['groupName']	= $GetValue['group_name'];
					$InnerProfile['groupScore']	= $GetValue['star_value'];
					$HospitalProfile[]	= $InnerProfile;
				}
				$ReturnResult['ret_hospitalName']	= $HospitalDetails[0]['hospital_name'];
				$ReturnResult['ret_hospitalProfile'] = $HospitalProfile;
				$Status 	= SUCCESS;
			}
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to get hospital profile details - created by Karthik K on 09 Dec, 2014  */
	public function GetHospitalFullDetails_post()
	{
		$HospitalId	= $this->post('hospitalId');
		$Status 	= BAD;
		if($HospitalId != '')
		{
			$HospitalDetails = $this->common_model->GetHospitalFinalResult($HospitalId);
			if(isset($HospitalDetails) && count($HospitalDetails)>0)
			{
				$HospitalProfile = array();
				foreach($HospitalDetails as $GetValue)
				{

					$DisplayStatusText = "Below Average";
		 			if($GetValue['ranking_score'] == 0)
		 			{
		 				$DisplayStatusText	= "Average";
		 			}
		 			else if($GetValue['ranking_score'] == 1)
		 			{
		 				$DisplayStatusText	= "Above Average";
		 			}
		 			$HospitalPoints = $GetValue['hospital_points'];
		 			if($GetValue['hospital_points'] == null)
		 			{
		 				$HospitalPoints = "-";
		 				$DisplayStatusText	= "-";
		 			}

					$InnerProfile['measureCode']	= $GetValue['measure_code'];
					$InnerProfile['measureName']	= $GetValue['measure_name'];
					$InnerProfile['measureScore']	= $GetValue['ranking_score'];
					$InnerProfile['hospitalPoints']	= $HospitalPoints;
					$InnerProfile['comparisonText']	= $DisplayStatusText;
					$HospitalProfile[]	= $InnerProfile;
				}
				$ReturnResult['ret_hospitalName']	= $HospitalDetails[0]['hospital_name'];
				$ReturnResult['ret_hospitalFullProfile'] = $HospitalProfile;
				$Status 	= SUCCESS;
			}
		}
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
	/* Method used to send compared doctor list to email - created by Karthik K on 10 Dec, 2014 */
	public function SendDoctorComparedListToMail_post()
	{
		$CompareDocId	= $this->post('compareDocId');
		$GetEmailId 	= $this->post('sendEmailId');
		$Status 		= BAD;
		$Message 		= "Mail send failed";
		if($CompareDocId != '' && $GetEmailId != '' && filter_var($GetEmailId,FILTER_VALIDATE_EMAIL))
		{
			if(is_array($CompareDocId))
			{
				$CompareDocId = implode(',',$CompareDocId);
			}
			$GetCompareList = $this->common_model->GetComparedList($CompareDocId);
			if(isset($GetCompareList) && count($GetCompareList)>0)
			{
				$Status = SUCCESS;
				$Message = "Mail send successfully";
				$Result['DoctorList']	= $GetCompareList;
				// create send mail page design
				$GetMailContent = $this->load->view('searchphysician/SendMailComparedList',$Result,true);
				$this->load->model('sendmail_model');
				$MailConfig['MailSubject']	= "Monocle Doctor compare List";
				$MailConfig['MailContent']	= $GetMailContent;
				$MailConfig['DisplayName']	= "Dear, ";
				$MailConfig['Email']		= $GetEmailId;
				$this->sendmail_model->CommonEmailTemplate($MailConfig);
			}
		}
		$ReturnResult['ret_message']	= $Message;
		$ReturnResult['ret_status']		= $Status;
		$this->response($ReturnResult);
	}
}





