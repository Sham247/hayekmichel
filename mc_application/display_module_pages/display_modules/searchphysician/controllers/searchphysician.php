<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Searchphysician extends MX_Controller 
{
	public function __construct()
	{
		parent::__construct();
		// Check login session 
		RedirectToLogin();
		$this->load->model('searchphysician_model','spmodel');
	}
	/* Method used to display home page with quick search details - created by Karthik K on 17 Oct, 2014 */
	public function index()
	{
		
		// Change form access method
		$_POST	= $_GET;
		$this->load->library('form_validation');
		$this->form_validation->set_rules('state_id');
		$this->form_validation->set_rules('zipcode');
		$this->form_validation->set_rules('search_name');
		$this->form_validation->run();
		$StateId 		= $this->input->post('state_id');
		$Zipcode 		= trim($this->input->post('zipcode'));
		$SearchName 	= trim($this->input->post('search_name'));
		$DisplayTitle	= '';
		$RankedText		= '<h2>18,000</h2><h4>Georgia Physicians Ranked</h4>';
		$CoveredText	= '<h2>100 %</h2><h4>Of Georgia Covered</h4>';
		$StateImage		= 'georgia.png';
		$is_ehc 		= $this->input->post('is_ehc');
		// Get state list details
		$GetStateList			= $this->common_model->GetStateList();
		if($SearchName != '' || ($Zipcode != '' && $StateId != '') || ($Zipcode == '' && $StateId != ''))
		{
			if($StateId == '')
			{
				$Zipcode	= '';
			}
			// Store quick search value in session
			$this->session->set_userdata('StateId',$StateId);
			$this->session->set_userdata('Zipcode',$Zipcode);
			$this->session->set_userdata('SearchName',$SearchName);
			if($StateId=='4')
			{
			$this->session->set_userdata('is_ehc',$is_ehc);
			}
			// Get quick search details
			$GetDoctorList 			= $this->spmodel->GetQuickSearchDetails($SearchName,$Zipcode,$StateId);
			$Result['DoctorList']	= $GetDoctorList;
			$GetStateName 	= '';
			if($StateId != '')
			{
				// Get state name
				$GetStateName 	= $this->common_model->GetStateName($StateId);
			}
			if($SearchName != '' && $Zipcode == '' && $StateId == '')
			{
				$DisplayTitle	= ' - '.$SearchName;
			}
			else if($SearchName == '' && ($Zipcode == '' && $StateId != ''))
			{
				$DisplayTitle	= ' - '.$GetStateName;
			}
			else if($SearchName == '' && ($Zipcode != '' && $StateId != ''))
			{
				$DisplayTitle	= ' - '.$GetStateName.', '.$Zipcode;
			}
			else if($SearchName != '' && ($Zipcode != '' && $StateId != ''))
			{
				$DisplayTitle	= ' - '.$GetStateName.', '.$Zipcode.', '.$SearchName;
			}
			else if($SearchName != '' && $Zipcode == '' && $StateId != '')
			{
				$DisplayTitle	= ' - '.$GetStateName.', '.$SearchName;
			}
		}
		$JsonStateData	= array();
		// Get selected state display details
		if(isset($GetStateList) && count($GetStateList)>0)
		{
			foreach($GetStateList as $DisplayState)
			{
				if($StateId != '')
				{
					if($StateId == $DisplayState->id && $DisplayState->physicians_ranked != '' && $DisplayState->state_covered != '')
					{
						$RankedText		= $DisplayState->physicians_ranked;
						$CoveredText 	= $DisplayState->state_covered;
						$StateImage		= strtolower($DisplayState->state_name).".png";
					}
				}
				$InnerStateData['StateId']		= $DisplayState->id;
				$InnerStateData['StateName']	= $DisplayState->state_name;
				$InnerStateData['RankedText']	= trim($DisplayState->physicians_ranked);
				$InnerStateData['CoveredText']	= trim($DisplayState->state_covered);
				$InnerStateData['StateImg']		= ImageUrl("state_images/".strtolower($DisplayState->state_name).".png");
				$InnerStateData['is_ehc']		= $is_ehc;
				$JsonStateData[]				= $InnerStateData;
			}
		}
		$Result['ScriptPage']	= 'PageScripts';
		$Result['StateList']	= $GetStateList;
		$Result['JsonStateData']= $JsonStateData;
		$Result['RankedText']	= $RankedText;
		$Result['CoveredText'] 	= $CoveredText;
		$Result['StateImage']	= $StateImage; 
		$Result['DisplayTitle']	= $DisplayTitle;
		$Result['RemoveQuickSearch']	= "Remove";
		// Unset search condition, specialty session data
		$UnsetSessionData['Adv_SearchType']	= '';
		$UnsetSessionData['Adv_Id']			= '';
		$UnsetSessionData['Adv_SubId'] 		= '';
		$UnsetSessionData['Adv_SubSubId'] 	= '';
		$UnsetSessionData['Adv_Zipcode'] 	= '';
		$UnsetSessionData['Adv_Miles'] 	= '';
		$UnsetSessionData['Adv_CarrierId'] 	= '';
		$this->session->set_userdata($UnsetSessionData);
		// Get carrier list details
		$GetCarrierList			= $this->common_model->GetCarrierList();
		$JsonCarrierArray		= array();
		if(isset($GetCarrierList) && count($GetCarrierList)>0)
		{
			foreach ($GetCarrierList as $CarrierValues) 
			{
				$InnerCarreirVal['CarrierId']	= EncodeValue($CarrierValues->id);
				$InnerCarreirVal['StateId']		= $CarrierValues->state_id;
				$InnerCarreirVal['StateName']	= $CarrierValues->state_name;
				$InnerCarreirVal['CarrierName']	= $CarrierValues->carrier_name;
				$JsonCarrierArray[]				= $InnerCarreirVal;
			}
		}
		$Result['JsonCarrierList']	= json_encode($JsonCarrierArray);
		$Result['CarrierList']	= $GetCarrierList;
		$this->load->view('SearchPhysician',$Result);
	}
	/* Method used to get state zipcode list - created by Karthik K on 17 Oct, 2014 */
	public function getzipcode()
	{
		$GetStateId				= $this->input->post('state_id');
		$GetZipcode				= $this->input->post('zipcode');
		$GetZipcodeList			= $this->common_model->GetStateZipcode($GetStateId,$GetZipcode);
		echo json_encode($GetZipcodeList);
	}
	/* Method used to get first condition list - created by Karthik K on 19 Oct, 2014 */
	public function firstcondition()
	{
		$Result['FirstCondition']	= $this->spmodel->GetFirstCondition();
		$this->load->view('DisplayConditionList',$Result);
	}
	/* Method used to get second condition list - created by Karthik K on 19 Oct, 2014 */
	public function secondcondition()
	{
		$Result['SecondCondition']	= $this->spmodel->GetSecondCondition();
		$this->load->view('DisplayConditionList',$Result);
	}
	/* Method used to get third condition list - created by Karthik K on 19 Oct, 2014 */
	public function thirdcondition()
	{
		$Result['ThirdCondition']	= $this->spmodel->GetThirdCondition();
		$this->load->view('DisplayConditionList',$Result);
	}

  /* Method used to get fourth condition list - created by Jay V on 4/16/2019 */
  public function fourthcondition()
  {
    $Result['FourthCondition']	= $this->spmodel->GetFourthCondition();
    $this->load->view('DisplayConditionList',$Result);
  }

	/* Method used to get first specialty list - created by Karthik K on 19 Oct, 2014 */
	public function firstspecialty()
	{
		$Result['FirstSpecialty']	= $this->spmodel->GetFirstSpecialty();
		$this->load->view('DisplaySpecialtyList',$Result);
	}
	/* Method used to get second specialty list - created by Karthik K on 19 Oct, 2014 */
	public function secondspecialty()
	{
		$Result['SecondSpecialty']	= $this->spmodel->GetSecondSpecialty();
		$this->load->view('DisplaySpecialtyList',$Result);
	}
	/* Method used to get third specialty list - created by Karthik K on 19 Oct, 2014 */
	public function thirdspecialty()
	{
		$Result['ThirdSpecialty']	= $this->spmodel->GetThirdSpecialty();
		$this->load->view('DisplaySpecialtyList',$Result);
	}
	/* Method used to get searched list - created by Karthik K on 27 Oct, 2014 */
	public function searchlist()
	{
		$GetSearchedResult	= $this->spmodel->GetSearchedList();
		echo json_encode($GetSearchedResult);
	}
	/* Method used to get compared doctor list - created by Karthik K on 28 Oct, 2014 */
	public function viewcomparelist()
	{
		$Result['ComparedList']	= $this->common_model->GetComparedList();
		$this->load->view('DisplayComparedList',$Result);
	}
	/* Method used to get doctor profile details - created by Karthik K on 04 Nov, 2014 */
	public function doctorprofile()
	{
		$Result['DoctorProfile']	= $this->common_model->GetDoctorProfile();
		$Result['GetType']			= (isset($_POST['get_type']) && $_POST['get_type'] == 'condition')?'fadeandscale':'fadeandscale_special';
		$Result['GetPageType'] 		= isset($_POST['page_type'])?$_POST['page_type']:'';
		$this->load->view('DisplayDoctorProfile',$Result);
	}
	/* Method used to get doctor profile details - created by Karthik K on 04 Nov, 2014 */
	public function hospitalprofile()
	{
		$HospitalProviderId 		= isset($_POST['hos_prof_id'])?$_POST['hos_prof_id']:'';
		$Result['HospitalProfile']	= $this->common_model->GetHospitalProfile($HospitalProviderId);
		$this->load->view('DisplayHospitalProfile',$Result);
	}
	/* Method used to get hospital full details - created by Karthik K on 04 Dec, 2014*/
	public function gethospitalfinalscore()
	{
		$HospitalProviderId 			= $this->input->post('HosProId');
		$Result['HospitalFinalResult']	= $this->common_model->GetHospitalFinalResult(DecodeValue($HospitalProviderId));
		$this->load->view('DisplayHospitalFullDetails',$Result);
	}
	/* Method used to get searched, sort and pagination doctor list - created by Karthik K on 05 Nov, 2014 */
	public function getdoctorlist()
	{
		//$GetEhc = $_POST['is_ehc'];
		//$GetStateid = $_POST['state_id'];
		//echo "Testing ";//exit;
		//print_r($_POST);exit;
		// Get posting values
		$GetStateid='';
		if(isset($_POST['state_id']) && $_POST['state_id']!='4')
		{
			$_POST['is_ehc']="No";
			$GetEhc = "No";
			
		}
		if((isset($_POST['is_ehc']) && $_POST['is_ehc']=='') || !isset($_POST['is_ehc']))
		{
			$_POST['is_ehc']="NO";
			$GetEhc = "No";
		}
	if(isset($_POST['is_ehc']))
		{
			$GetEhc = $_POST['is_ehc'];
		}
		if(isset($_POST['is_ehc_hidden']))
		{
			$GetEhc = $_POST['is_ehc_hidden'];
			$_POST['is_ehc']=$GetEhc;
		}
		
		if(isset($_POST['state_id']))
		{
			$GetStateid =$_POST['state_id'];
		}
		if(isset($_POST['state_id_hidden']))
		{
			$GetStateid = $_POST['state_id_hidden'];
			$_POST['state_id'] = $GetStateid;
		}
		$_POST['proceduregroup'] = '0';
		$_POST['subsubconditionname'] = '0';
		
		$ListCount		= (isset($_POST['list_count']) && trim($_POST['list_count']) != '')?$_POST['list_count']:'';
		$SortBy			= (isset($_REQUEST['list_sort_by']) && $_REQUEST['list_sort_by'] != '')?$_REQUEST['list_sort_by']:'';
		if($ListCount == '' && $SortBy == '')
		{
			// Get form posting values
			$GetAge 			= (isset($_POST['age']) && trim($_POST['age']) != '')?$_POST['age']:'';
			$GetCondition 		= (isset($_POST['conditionname']) && trim($_POST['conditionname']) != '')?$_POST['conditionname']:'';
			$GetSubCond 		= (isset($_POST['subconditionname']) && trim($_POST['subconditionname']) != '')?$_POST['subconditionname']:'';
			$GetSsubCond 		= (isset($_POST['subsubconditionname']) && trim($_POST['subsubconditionname']) != '')?$_POST['subsubconditionname']:'';
      $GetSSsubCond 		= (isset($_POST['subsubsubconditionname']) && trim($_POST['subsubsubconditionname']) != '')?$_POST['subsubsubconditionname']:'';
			$GetMiles 			= (isset($_POST['Range']) && trim($_POST['Range']) != '')?$_POST['Range']:'';
			$GetZipcode			= (isset($_POST['preferred_zip']) && trim($_POST['preferred_zip']) != '')?$_POST['preferred_zip']:'';
			$GetIsEhc			= (isset($_POST['is_ehc']) && trim($_POST['is_ehc']) != '')?$_POST['is_ehc']:'';
			$GetStateid			= (isset($_POST['state_id']) && trim($_POST['state_id']) != '')?$_POST['state_id']:'';
			
			$GetSpecialty 		= (isset($_POST['specialtyname']) && trim($_POST['specialtyname']) != '')?$_POST['specialtyname']:'';
			$GetSubSpecialty	= (isset($_POST['subspecialtyname']) && trim($_POST['subspecialtyname']) != '')?$_POST['subspecialtyname']:'';
			$GetSsubSpecialty 	= (isset($_POST['proceduregroup']) && trim($_POST['proceduregroup']) != '')?$_POST['proceduregroup']:'';
			$CarrierId			= (isset($_POST['carrier_id']) && trim(DecodeValue($_POST['carrier_id'])) != '')?$_POST['carrier_id']:'';
			$CarrierId 			= '';
			$ListCount 			= 0;
			//echo "is ehc ".$GetIsEhc;exit;
			// Store session values
			if($GetCondition != '')
			{
				$SetSessionData['Adv_SearchType']	= "Condition";
				$SetSessionData['Adv_Id']			= $GetCondition;
				$SetSessionData['Adv_SubId'] 		= $GetSubCond;
				$SetSessionData['Adv_SubSubId'] 	= $GetSsubCond;
			}
			else
			{
				$SetSessionData['Adv_SearchType']	= "Specialty";
				$SetSessionData['Adv_Id']			= $GetSpecialty;
				$SetSessionData['Adv_SubId'] 		= $GetSubSpecialty;
				$SetSessionData['Adv_SubSubId'] 	= $GetSsubSpecialty;
			}
			$SetSessionData['Adv_Zipcode'] 		= $GetZipcode;
			$SetSessionData['Adv_Miles'] 		= $GetMiles;
			$SetSessionData['Adv_CarrierId'] 	= $CarrierId;
			$SetSessionData['Adv_Ehc'] 	= $GetIsEhc;
			$SetSessionData['Adv_Stateid'] 	= $GetStateid;
			$this->session->set_userdata($SetSessionData);
		}
		else
		{
			// Get pagination and sorting values based on stored session values
			$GetType 	= GetSessionVal('Adv_SearchType');
			if($GetType == 'Condition')
			{
				$GetCondition 	= GetSessionVal('Adv_Id'); 
				$GetSubCond 	= GetSessionVal('Adv_SubId');
				$GetSsubCond	= GetSessionVal('Adv_SubSubId');
				$GetSpecialty 		= '';
				$GetSubSpecialty 	= '';
				$GetSsubSpecialty	= '';

			}
			else
			{
				$GetCondition 		= ''; 
				$GetSubCond 		= '';
				$GetSsubCond		= '';
				$GetSpecialty 		= GetSessionVal('Adv_Id'); 
				$GetSubSpecialty 	= GetSessionVal('Adv_SubId');
				$GetSsubSpecialty	= GetSessionVal('Adv_SubSubId');
			}
			$GetZipcode = GetSessionVal('Adv_Zipcode');
			$GetMiles	= GetSessionVal('Adv_Miles');
			$CarrierId 	= GetSessionVal('Adv_CarrierId');
			$GetEhc 	= GetSessionVal('Adv_Ehc');
			$ListCount = ($ListCount != '')?$ListCount:'0';
		if($GetEhc=='No' || $GetEhc=='')
				{
					$GetEhc = $_POST['is_ehc'];
					$SetSessionData['Adv_Ehc'] 	= $GetEhc;
				}
		}
		//$GetEhc ='yes';
		// Get condition or specialty result
		if($GetCondition != '' || $GetSpecialty != '')
		{
			$this->session->unset_userdata('StateId');			
			$this->session->unset_userdata('Zipcode');
			$this->session->unset_userdata('SearchName');
			if($GetCondition != '') // Get searched
			{
				if($GetEhc=='No' || $GetEhc=='')
				{
					$GetEhc = $_POST['is_ehc'];
					$SetSessionData['Adv_Ehc'] 	= $GetEhc;
				}
				
				$GetEhcsession 	= GetSessionVal('Adv_Ehc');
				
				$GetDoctorList 		= $this->spmodel->GetConditionDoctorList($GetCondition,$GetSubCond,$GetSsubCond,$GetZipcode,$GetEhc,$GetMiles,$SortBy,$ListCount,'9',$GetStateid,$CarrierId);
			}
			elseif($GetSpecialty != '')
			{
				
				if($GetEhc=='No' || $GetEhc=='')
				{
					$GetEhc = $_POST['is_ehc'];
					$SetSessionData['Adv_Ehc'] 	= $GetEhc;
				}
				
				$GetEhcsession 	= GetSessionVal('Adv_Ehc');
				
		
				$GetDoctorList 		= $this->spmodel->GetSpecialtyDoctorList($GetSpecialty,$GetSubSpecialty,$GetSsubSpecialty,$GetZipcode,$GetEhc,$GetMiles,$SortBy,$ListCount,'9',$GetStateid,$CarrierId);
			}
		}
		else
		{
			// Get stored session quick search values
			$SessionStateId		= $this->session->userdata('StateId');
			$SessionZipcode		= $this->session->userdata('Zipcode');
			$SessionSearchName	= $this->session->userdata('SearchName');
			if($SessionSearchName != '' || $SessionZipcode != '' || $SessionStateId != '') // Get session search details
			{
				$GetDoctorList 	= $this->spmodel->GetQuickSearchDetails($SessionSearchName,$SessionZipcode,$GetEhc,$SessionStateId,$SortBy,$ListCount);
			}
		}
		$EncodeList		= array();
		$DoctorCount    = 0;
		if(isset($GetDoctorList) && count($GetDoctorList)>0)
		{
			foreach($GetDoctorList as $DisplayDoctor)
			{
				$DoctorCount++;
				if($DoctorCount <= 8)
				{
					if(isset($DisplayDoctor->ordid) && $DisplayDoctor->ordid != '')
					{
						$DoctorList['order_id']	= $DisplayDoctor->ordid;
					}
					else
					{
						$DoctorList['order_id']	= '';
					}
					$DoctorList['doc_id']		= $DisplayDoctor->id;
					$DoctorList['doc_npi']		= $DisplayDoctor->NPI;
					$DoctorList['name']			= $DisplayDoctor->ProvName;
					$DoctorList['education']	= (trim($DisplayDoctor->Education) != '')?' | '.$DisplayDoctor->Education:'';
					$DoctorList['primary']		= $DisplayDoctor->PrimarySpecialty;
					$Quality 					= $DisplayDoctor->Quality;
					$Efficiency 				= $DisplayDoctor->Efficiency;
					if($Quality > 0)
					{
						$QualityImg = ImageUrl("ratings/".$Quality.".png");
					}
					else
					{
						$QualityImg = ImageUrl("ratings/0.png");
						$Quality 	= 0;
					}
					$DoctorList['quality_img']	= $QualityImg;
					$DoctorList['quality']		= $Quality;
					if($Efficiency > 0)
					{
						$EfficiencyImg = ImageUrl("ratings/".$Efficiency.".png");
					}
					else
					{
						$EfficiencyImg = ImageUrl("ratings/0.png");
						$Efficiency 	= 0;
					}
					$DoctorList['efficiency_img']	= $EfficiencyImg;
					$DoctorList['efficiency']		= $Efficiency;
					if($DisplayDoctor->Gender == 'M')
					{
						$DoctorImg 	= ImageUrl('MaleImageBig.png');
					}
					else if($DisplayDoctor->Gender == 'F')
					{
						$DoctorImg 	= ImageUrl('FemaleImageBig.png');
					}
					else
					{
						$DoctorImg 	= ImageUrl('NoImageBig.png');
					}
					$DoctorList['image']		= $DoctorImg;
					$DoctorList['gender']		= $DisplayDoctor->Gender;
					$Languages 					= $DisplayDoctor->Languages;
					if($Languages == '' || $Languages == '0')
					{
						$Languages = 'English';
					}
					else
					{
						// Explode doctor known languages
						$ExplodeLanguage	= explode('@',$Languages);
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
					$DoctorList['language']	= $Languages;
					$DoctorAddress			= $DisplayDoctor->Address;
					if($DoctorAddress == null)
					{
						$DoctorAddress	= '';
					}
					$DoctorList['address']		= $DoctorAddress;
					$DoctorList['phone']		= ($DisplayDoctor->Phone != '')?$DisplayDoctor->Phone:'';
					$MapAddress 				= str_replace("#","",$DoctorAddress);
					$MapAddress 				= str_replace("'","",$MapAddress);
					$MapAddress 				= str_replace("<br>",",",$MapAddress);
					$DoctorList['map_address']	= $MapAddress;
					$InnerList[]				= $DoctorList;
					$EncodeList['list'] 		= $InnerList;
				}
			}
		}
		$RecordExist = 'no';
		if($DoctorCount > 8)
		{
			$RecordExist = 'yes';
		}
		if($DoctorCount == 0)
		{
			$EncodeList['empty'] = 'empty';
		}
		$EncodeList['record_exist'] = $RecordExist;
		echo json_encode($EncodeList);
	}
	/* Method used to create compared doctor list details in PDF format - created by Karthik K on 06 Nov, 2014 */
	public function generatepdfdetails()
	{
		// Load all views as normal
		$GetDoctorId		= (isset($_GET['hid_doctor_id']) && $_GET['hid_doctor_id'] != '')?$_GET['hid_doctor_id']:array();
		$ImplodeDoctorId	= '';
		if(count($GetDoctorId)>0)
		{
			$ImplodeDoctorId	= implode(",",$GetDoctorId);
		}
		$DoctorList 			= $this->common_model->GetComparedList($ImplodeDoctorId);
		if(count($DoctorList)>0)
		{
			$Result['DoctorList']	= $DoctorList;
			$this->load->view('GeneratePdfForComparedList',$Result);
			// Get output html
			$html = $this->output->get_output();
			require_once APPPATH.'third_party/tcpdf/tcpdf_include.php';
			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

			// set header and footer fonts
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			// set some language-dependent strings (optional)
			/*if (@file_exists(APPPATH.'third_party/tcpdf/lang/eng.php')) 
			{
				require_once(APPPATH.'third_party/tcpdf/lang/eng.php');
				$pdf->setLanguageArray($l);
			}*/
			// set font
			$pdf->SetFont('helvetica', '', 10);
			// add a page
			$pdf->AddPage();
			$pdf->writeHTML($html, true, 0, true, 0);
			// reset pointer to the last page
			$pdf->lastPage();
			$pdf->setPage( 1 );

			// Get the page width/height
			$myPageWidth = $pdf->getPageWidth();
			$myPageHeight = $pdf->getPageHeight();

			// Find the middle of the page and adjust.
			$myX = ( $myPageWidth / 2 ) - 75;
			$myY = ( $myPageHeight / 2 ) + 25;

			// Set the transparency of the text to really light
			$pdf->SetAlpha(0.05);

			// Rotate 45 degrees and write the watermarking text
			$pdf->StartTransform();
			$pdf->Rotate(45, $myX, $myY);
			$pdf->SetFont("courier", "", 60);
			$pdf->Text($myX, $myY,WATERMARK_TEXT);
			$pdf->StopTransform();

			// Reset the transparency to default
			$pdf->SetAlpha(1);
			//Close and output PDF document
			$pdf->Output('MonocleDoctorCompareList.pdf','I');
		}
		else
		{
			redirect('home');
		}
	}
	/* Method used to send compared doctor detials vid email - created by Karthik K on 06 Nov, 2014 */
	public function senddoctorcomparelist()
	{
		$GetEmailId 	= isset($_POST['email_id'])?$_POST['email_id']:'';
		$GetCompareId	= (isset($_POST['compareids']) && $_POST['compareids'] != '')?$_POST['compareids']:array();
		$ReturnValue	= array();
		$Status 		= "error";
		$Msg 			= "Please Try Again...!";
		if($GetEmailId != '' && $GetCompareId != '')
		{
			// Get doctor details
			$GetDoctorList 			= $this->common_model->GetComparedList($GetCompareId);
			if(count($GetDoctorList)>0)
			{
				$Status 	= "success";
				$Msg 		= "Email Sent Successfully!";
				$Result['DoctorList']	= $GetDoctorList;
				// create send mail page design
				$GetMailContent = $this->load->view('SendMailComparedList',$Result,true);
				$this->load->model('sendmail_model');
				$MailConfig['MailSubject']	= " - Doctor compare List";
				$MailConfig['MailContent']	= $GetMailContent;
				$MailConfig['DisplayName']	= "Dear, ";
				$MailConfig['Email']		= $GetEmailId;
				$this->sendmail_model->CommonEmailTemplate($MailConfig);
			}
		}
		$ReturnValue['status']	= $Status;
		$ReturnValue['msg']		= $Msg;
		echo json_encode($ReturnValue);
	}
	/* Method used to send compared doctor detials vid email - created by Karthik K on 06 Nov, 2014 */
	public function printdoctorcomparelist()
	{
		$GetDoctorId		= (isset($_GET['hid_doctor_id']) && $_GET['hid_doctor_id'] != '')?$_GET['hid_doctor_id']:array();
		$ImplodeDoctorId	= '';
		if(count($GetDoctorId)>0)
		{
			$ImplodeDoctorId	= implode(",",$GetDoctorId);
		}
		$GetDoctorList 			= $this->common_model->GetComparedList($ImplodeDoctorId);
		if(count($GetDoctorList)>0)
		{
			$Result['DoctorList']	= $GetDoctorList;
			$this->load->view('PrintComparedList',$Result);
		}
		else
		{
			redirect('home');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */