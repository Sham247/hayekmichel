<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Managelevels extends MX_Controller 
{
	var $UserLevelId;
	var $SuperAdmin;
	public function __construct()
	{
		parent::__construct();
		// Check login session 
		RedirectToLogin();
		// Check user access permission
		CheckUserAccessPermission(1);
		$this->UserLevelId = GetUserLevel();
		$this->SuperAdmin 	= IsSuperAdmin();
		$this->load->model('managelevels_model','ummodel');
	}
	/* Method used to display added user levels - created by Karthik K on 06 Nov, 2014 */
	public function index()
	{
		$GetPageCount				= $this->uri->segment(2,0);
		$GetStateList				= $this->common_model->GetStateList();
		$Result['ScriptPage']		= 'PageScripts';
		$Result['StateList']		= $GetStateList;
		$Result['DisplayTitle']		= '';
		$Result['RemoveAboutUs']	= "Remove";
		$Result['RemoveGoToTop']	= "Remove";
		$Result['RemoveQuickSearch']	= "Remove";
		$Result['DisplayPage']		= "ViewCreatedLevels";
		$TotalList					= $this->ummodel->GetDisplayUserLevels();
		$TotalListCount				= count($TotalList);
		$Result['UserLevelList']	= $this->ummodel->GetDisplayUserLevels($GetPageCount,GetLangLabel('DisplayLevelLimit'));
		// Generate pagination
		$Result['Pagination']		= GeneratePagination(GetLangLabel('DisplayLevelLimit'),base_url('viewlevels'),$TotalListCount);
		// Store filter data into session
		$FilterSessionData['FilTxtLevelName']	= '';
		$FilterSessionData['FilSltLevelStatus']	= '';
		$this->session->set_userdata($FilterSessionData);
		$this->load->view('index',$Result);
	}
	/* Method used to edit and update user level details - created by Karthik K on 07 Nov, 2014 */
	public function editaddeduserlevel()
	{
		$LevelId = $this->uri->segment(2,0);
		if($LevelId != '')
		{
			$DecodeLevelId				= DecodeValue($LevelId);
			// Get view level details
			$GetLevelDetails			= $this->ummodel->GetViewLevelDetails($DecodeLevelId);
			if(count($GetLevelDetails)>0)
			{
				if($this->input->post('updateleveldetails') != '')
				{
					// Do form validation
					$this->load->library('form_validation');
					$this->form_validation->set_rules('TxtLevelName','lang:ErrLevelName','required|xss_clean|callback_checklevelname');
					$this->form_validation->set_rules('TxtLevelDesc');
					if($this->form_validation->run($this))
					{
						// Update user details
						$this->ummodel->UpdateLevelDetails($DecodeLevelId);
						$this->session->set_flashdata('SuccessMsg',GetLangLabel('MsgLevelDetailsUpdated'));
						redirect('viewgroups');
					}
			
				}
				$GetStateList				= $this->common_model->GetStateList();
				$Result['ScriptPage']		= 'PageScripts';
				$Result['StateList']		= $GetStateList;
				$Result['DisplayTitle']		= '';
				$Result['RemoveAboutUs']	= "Remove";
				$Result['RemoveGoToTop']	= "Remove";
				$Result['RemoveQuickSearch']= "Remove";
				$Result['DisplayPage']		= "FormEditLevelDetails";
				$Result['LevelDetails']		= $GetLevelDetails;
				$Result['LevelAccessModules'] = $this->common_model->GetUserAccessModules($DecodeLevelId);
				$Result['GroupStateList']	= $this->ummodel->GetGroupAddedStateList($DecodeLevelId); 
				$this->load->view('index',$Result);
			}
			else
			{
				$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgLevelEditFailed'));
				redirect('viewlevels');
			}
		}
	}
	/* Method used to add new user  - created by Karthik K on 07 Nov, 2014 */
	public function registernewlevel()
	{
		$GetStateList				= $this->common_model->GetStateList();
		$Result['ScriptPage']		= 'PageScripts';
		$Result['StateList']		= $GetStateList;
		$Result['DisplayTitle']		= '';
		$Result['RemoveAboutUs']	= "Remove";
		$Result['RemoveGoToTop']	= "Remove";
		$Result['DisplayPage']		= "FormAddNewLevel";
		$Result['RemoveQuickSearch']	= "Remove";
		// Get registered user level list
		$Result['UserLevelList']	= $this->ummodel->GetDisplayUserLevels();
		$Result['LevelAccessModules'] = $this->common_model->GetUserAccessModules();
		if($this->input->post('BtnAddNewLevel') != '')
		{
			// Do form validation
			$this->load->library('form_validation');
			$this->form_validation->set_rules('TxtLevelName','lang:ErrLevelName','requried|xss_clean|callback_checklevelname');
			$this->form_validation->set_rules('TxtLevelDesc');
			if($this->form_validation->run($this))
			{
				$this->ummodel->InsertNewUserLevelDetails();
				$this->session->set_flashdata('SuccessMsg',GetLangLabel('MsgNewLevelAdded'));
				redirect("viewgroups");
			}
		}
		$this->load->view('index',$Result);
		/*$GetRequestType	= DecodeValue($this->input->post('RequestType'));
		$ReturnJsonData	= array();
		$Status			= "error";
		$FoundError		= true;
		// Do form validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('TxtLevelName','lang:ErrLevelName','requried|xss_clean|callback_checklevelname');
		$this->form_validation->set_rules('TxtLevelDesc');
		if($this->form_validation->run($this))
		{
			$Status			= "success";
			$FoundError		= false;
			$this->ummodel->InsertNewUserLevelDetails();
		}
		if($GetRequestType == 'Ajax')
		{
			$ReturnJsonData['Status']	= $Status;
			if($FoundError)
			{
				$ReturnJsonData['TxtLevelName']	= form_error('TxtLevelName');
			}
			else
			{
				// Get registered user level list
				$GetUserLevelList	= $this->ummodel->GetDisplayUserLevels();
				$GetLevelData		= array();
				if(isset($GetUserLevelList) && count($GetUserLevelList)>0)
				{
					foreach($GetUserLevelList as $DisplayLevels)
					{
						$InnerData['LevelId']	= EncodeValue($DisplayLevels->id);
						$InnerData['LevelName']	= $DisplayLevels->group_name;
						$LevelStatus = GetLangLabel('StatusActive');
						if($DisplayLevels->group_status == 0)
						{
							$LevelStatus = GetLangLabel('StatusInActive');
						}
						$InnerData['LevelStatus']	= $LevelStatus;
						$InnerData['TotalUsers']	= $DisplayLevels->total_users;
						$InnerData['ActiveUsers']	= $DisplayLevels->active_users;
						$GetLevelData[]				= $InnerData;
					}
				}
				$ReturnJsonData['LevelList']		= $GetLevelData;
				$ReturnJsonData['Msg']				= GetLangLabel('MsgNewLevelAdded');
			}
			echo json_encode($ReturnJsonData);
		}
		else
		{
			if($FoundError)
			{
				$GetStateList				= $this->common_model->GetStateList();
				$Result['ScriptPage']		= 'PageScripts';
				$Result['StateList']		= $GetStateList;
				$Result['DisplayTitle']		= '';
				$Result['RemoveAboutUs']	= "Remove";
				$Result['RemoveGoToTop']	= "Remove";
				$Result['DisplayPage']		= "ViewCreatedLevels";
				// Get registered user level list
				$Result['UserLevelList']	= $this->ummodel->GetDisplayUserLevels();
				$Result['AddLevelForm']		= "Show";
				$this->load->view('index',$Result);
			}
			else
			{
				$this->session->set_flashdata('SuccessMsg',GetLangLabel('MsgNewLevelAdded'));
				redirect("viewlevels");
			}
		}*/
	}
	/* Method used to check unique levelname or not - created by Karthik K on 07 Nov, 2014 */
	public function checklevelname($LevelName='')
	{
		if($LevelName != '')
		{
			$LevelId = DecodeValue($this->uri->segment(2,0));
			if($LevelId != '')
			{
				$WhereCond	= array('id !='=>$LevelId,'group_name'=>$LevelName);
			}
			else
			{
				$WhereCond	= array('group_name'=>$LevelName);
			}
			$GetDetails	= $this->common_model->SelectData(GetLangLabel('Tbl_UserLevel'),array('id'),$WhereCond);
			if(count($GetDetails)>0)
			{
				$this->form_validation->set_message('checklevelname',GetLangLabel('ErrLevelAlreadyInUse'));
				return false;
			}
			else
			{
				return true;
			}
		}
	}
	/* Method used to delete added user level details - created by Karthik K on 07 Nov, 2014 */
	public function deleteaddeduserlevel()
	{
		$LevelId = $this->uri->segment(2,0);
		if($LevelId != '')
		{
			$DecodeLevelId	= DecodeValue($LevelId);
			$this->ummodel->DeleteAddedUserLevel($DecodeLevelId);
			redirect('viewgroups');
		}
	}
	/* Method used to filter user level details - created by Karthik K on 17 Nov, 2014 */
	public function filterlevellist()
	{
		$GetPageCount				= $this->uri->segment(2,0);
		$GetStateList				= $this->common_model->GetStateList();
		$Result['ScriptPage']		= 'PageScripts';
		$Result['StateList']		= $GetStateList;
		$Result['DisplayTitle']		= '';
		$Result['RemoveAboutUs']	= "Remove";
		$Result['RemoveGoToTop']	= "Remove";
		$Result['DisplayPage']		= "ViewCreatedLevels";
		$Result['RemoveQuickSearch']	= "Remove";
		if($this->input->post('BtnFilterLevel') != '')
		{
			// Store filter data into session
			$FilterSessionData['FilTxtLevelName']	= $this->input->post('FilTxtLevelName');
			$FilterSessionData['FilSltLevelStatus']	= $this->input->post('FilSltLevelStatus');
			$this->session->set_userdata($FilterSessionData);
		}
		$TotalList					= $this->ummodel->FilterDisplayLevelList();
		$TotalListCount				= count($TotalList);
		$Result['UserLevelList']	= $this->ummodel->FilterDisplayLevelList($GetPageCount,GetLangLabel('DisplayLevelLimit'));
		// Generate pagination
		$Result['Pagination']		= GeneratePagination(GetLangLabel('DisplayLevelLimit'),base_url('filterlevels'),$TotalListCount);
		$this->load->view('index',$Result);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */