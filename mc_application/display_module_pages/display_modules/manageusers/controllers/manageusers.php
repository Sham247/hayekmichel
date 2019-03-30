<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manageusers extends MX_Controller 
{
	var $UserLevelId;
	public function __construct()
	{
		parent::__construct();
		// Check login session 
		RedirectToLogin();
		// Check user access permission
		CheckUserAccessPermission(1);
		$this->UserLevelId = GetUserLevel();
		$this->load->model('manageusers_model','mumodel');
	}
	/* Method used to display home page with quick search details - created by Karthik K on 17 Oct, 2014 */
	public function index()
	{
		// Get page count
		$PageCount					= (int)$this->uri->segment(2,0);
		$GetStateList				= $this->common_model->GetStateList();
		$Result['ScriptPage']		= 'PageScripts';
		$Result['StateList']		= $GetStateList;
		$Result['DisplayTitle']		= '';
		$Result['RemoveAboutUs']	= "Remove";
		/*$Result['RemoveGoToTop']	= "Remove";*/
		$Result['DisplayPage']		= "ViewCreatedUsers";
		$Result['RemoveQuickSearch']= "Remove";
		$TotalList					= $this->mumodel->GetDisplayUsers();
		$TotalListCount				= count($TotalList);
		$Result['UserList']			= $this->mumodel->GetDisplayUsers($PageCount,GetLangLabel('DisplayUsersLimit'));
		$Result['UserLevels']		= $this->common_model->GetUserLevel();
		// Generate pagination
		$Result['Pagination']		= GeneratePagination(GetLangLabel('DisplayUsersLimit'),base_url('viewusers'),$TotalListCount);
		// Unset session filter data
		$FilterSessionData['FilTxtFullName']	= '';
		$FilterSessionData['FilTxtUserName']	= '';
		$FilterSessionData['FilTxtUserEmail']	= '';
		$FilterSessionData['FilSltUserLevel']	= '';
		$FilterSessionData['FilSltUserStatus']	= '';
		$this->session->set_userdata($FilterSessionData);
		$this->load->view('index',$Result);
	}
	/* Method used to display  edit user form and update user details - created by Karthik K on 06 Nov, 2014 */
	public function edituserdetails()
	{
		$UserId = $this->uri->segment(2,0);
		if($UserId != '')
		{
			$DecodeUserId				= DecodeValue($UserId);
			// Get view user details
			$GetUserDetails				= $this->mumodel->GetViewUsersDetails($DecodeUserId);
			if(count($GetUserDetails)>0)
			{
				if($this->input->post('updateuserdetails') != '')
				{
					// Do form validation
					$this->load->library('form_validation');
					$this->form_validation->set_rules('TxtUserName','lang:ErrUserName','required|xss_clean|alpha');
					$this->form_validation->set_rules('TxtUserEmail','lang:ErrUserEmail','required|xss_clean|valid_email|callback_checkuseremail');
					$this->form_validation->set_rules('SltUserLevel','lang:ErrUserLevel','required');
					$this->form_validation->set_rules('TxtUserExpiry','','xss_clean');
					if($this->input->post('NewPassword') != '' || $this->input->post('CofirmNewPassword') != '')
					{
						$this->form_validation->set_rules('NewPassword','lang:ErrNewPassword','required|xss_clean');
						$this->form_validation->set_rules('CofirmNewPassword','lang:ErrNewPasswordAgain','required|xss_clean|matches[NewPassword]');
					}
					if($this->form_validation->run($this))
					{
						// Update user details
						$this->mumodel->UpdateUserDetails($DecodeUserId);
						$this->session->set_flashdata('SuccessMsg',GetLangLabel('MsgUserDetailsUpdated'));
						redirect('viewusers');
					}
			
				}
				// Get state list
				$GetStateList				= $this->common_model->GetStateList();
				$Result['ScriptPage']		= 'PageScripts';
				$Result['StateList']		= $GetStateList;
				$Result['DisplayTitle']		= '';
				$Result['RemoveAboutUs']	= "Remove";
				$Result['RemoveGoToTop']	= "Remove";
				$Result['DisplayPage']		= "FormEditUserDetails";
				$Result['RemoveQuickSearch']	= "Remove";
				// Get userleve list
				$Result['UserLevels']		= $this->common_model->GetUserLevel();
				$Result['UserDetails']		= $GetUserDetails;
				$this->load->view('index',$Result);
			}
			else
			{
				$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgUserEditFailed'));
				redirect('viewusers');
			}
		}
	}
	/* Method used to check unique useremail or not - created by Karthik K on 07 Nov, 2014 */
	public function checkuseremail($UserEmail='')
	{
		if($UserEmail != '')
		{
			$UserId = DecodeValue($this->uri->segment(2,0));
			$GetDetails	= $this->common_model->SelectData(GetLangLabel('Tbl_LoginUsers'),array('id'),array('id !='=>$UserId,'email'=>$UserEmail));
			if(count($GetDetails)>0)
			{
				$this->form_validation->set_message('checkuseremail',GetLangLabel('ErrEmailAlreadyInUse'));
				return false;
			}
			else
			{
				return true;
			}
		}
	}
	/* Method used to add new user  - created by Karthik K on 07 Nov, 2014 */
	public function registernewuser()
	{
		$GetStateList				= $this->common_model->GetStateList();
		$Result['ScriptPage']		= 'PageScripts';
		$Result['StateList']		= $GetStateList;
		$Result['DisplayTitle']		= '';
		$Result['RemoveAboutUs']	= "Remove";
		$Result['RemoveGoToTop']	= "Remove";
		$Result['DisplayPage']		= "FormAddNewUser";
		$Result['RemoveQuickSearch']	= "Remove";
		// Get registered users list
		$Result['UserList']			= $this->mumodel->GetDisplayUsers();
		// Get user level list
		$Result['UserLevels']		= $this->common_model->GetUserLevel(true);
		if($this->input->post('BtnAddNewUser') != '')
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('TxtUserName','lang:ErrUserName','requried|xss_clean|alpha');
			$this->form_validation->set_rules('TxtLoginName','lang:ErrLoginName','requried|xss_clean|callback_checkloginname');
			$this->form_validation->set_rules('TxtUserEmail','lang:ErrUserEmail','required|xss_clean|valid_email|callback_checknewuseremail');
			$this->form_validation->set_rules('SltUserLevel','lang:ErrUserLevel','requried');
			$this->form_validation->set_rules('NewPassword','lang:ErrUserPassword','requried|xss_clean');
			$this->form_validation->set_rules('CofirmNewPassword','lang:ErrUserConfirmPassword','requried|xss_clean|matches[NewPassword]');
			$this->form_validation->set_rules('TxtUserSendEmail');
			if($this->form_validation->run($this))
			{
				$this->mumodel->InsertNewUserDetails();
				redirect("viewusers");
			}
		}
		$this->load->view('index',$Result);
	}
	/* Method used to check unique login or not - created by Karthik K on 07 Nov, 2014 */
	public function checkloginname($LoginName)
	{
		if($LoginName != '')
		{
			$GetDetails	= $this->common_model->SelectData(GetLangLabel('Tbl_LoginUsers'),array('id'),array('username'=>$LoginName));
			if(count($GetDetails)>0)
			{
				$this->form_validation->set_message('checkloginname',GetLangLabel('ErrLoginNameExists'));
				return false;
			}
			else
			{
				return true;
			}
		}
	}
	/* Method used to check unique login or not - created by Karthik K on 07 Nov, 2014 */
	public function checknewuseremail($NewEmail='')
	{
		if($NewEmail != '')
		{
			$GetDetails	= $this->common_model->SelectData(GetLangLabel('Tbl_LoginUsers'),array('id'),array('email'=>$NewEmail));
			if(count($GetDetails)>0)
			{
				$this->form_validation->set_message('checknewuseremail',GetLangLabel('ErrEmailAlreadyInUse'));
				return false;
			}
			else
			{
				return true;
			}
		}
	}
	/* Method used to delete user details - created by Karthik K on 06 Nov, 2014 */
	public function deleteuserdetails()
	{
		$UserId = $this->uri->segment(2,0);
		if($UserId != '')
		{
			$DecodeUserId	= DecodeValue($UserId);
			$this->mumodel->DeleteAddedUser($DecodeUserId);
			redirect('viewusers');
		}
	}
	/* Method used to filter user details - created by Karthik K on 15 Nov, 2014 */
	public function filteruserlist()
	{
		$PageCount					= $this->uri->segment(2,0);
		$GetStateList				= $this->common_model->GetStateList();
		$Result['ScriptPage']		= 'PageScripts';
		$Result['StateList']		= $GetStateList;
		$Result['DisplayTitle']		= '';
		$Result['RemoveAboutUs']	= "Remove";
		$Result['RemoveGoToTop']	= "Remove";
		$Result['DisplayPage']		= "ViewCreatedUsers";
		$Result['RemoveQuickSearch']	= "Remove";
		if($this->input->post('BtnFilterUser'))
		{
			// Store session values from form posting values
			$FilterSessionData['FilTxtFullName']	= $this->input->post('FilTxtFullName');
			$FilterSessionData['FilTxtUserName']	= $this->input->post('FilTxtUserName');
			$FilterSessionData['FilTxtUserEmail']	= $this->input->post('FilTxtUserEmail');
			$FilterSessionData['FilSltUserLevel']	= $this->input->post('FilSltUserLevel');
			$FilterSessionData['FilSltUserStatus']	= $this->input->post('FilSltUserStatus');
			$this->session->set_userdata($FilterSessionData);
		}
		$TotalList 					= $this->mumodel->FilterDisplayUsersList();
		$Result['UserList']			= $this->mumodel->FilterDisplayUsersList($PageCount,GetLangLabel('DisplayUsersLimit'));
		$TotalListCount				= count($TotalList);
		$Result['UserLevels']		= $this->common_model->GetUserLevel();
		// Generate pagination
		$Result['Pagination']		= GeneratePagination(GetLangLabel('DisplayUsersLimit'),base_url('filterusers'),$TotalListCount);
		$this->load->view('index',$Result);
	}
	/* Method used to get user access log details - created by Karthik K on 01 Dec, 2014 */
	public function getuseraccesslog()
	{
		$UserId = $this->uri->segment(2,0);
		if($UserId != '')
		{
			$UserId = DecodeValue($UserId);
			$GetUserAccessLog 				= $this->common_model->GetUserAccessLogDetails($UserId);
			$Result['AccessLog']			= $GetUserAccessLog;
			$Result['RemoveAboutUs']		= "Remove";
			$Result['RemoveQuickSearch']	= "Remove";
			$Result['DisplayPage']			= "ViewUserAccessLog";
			$this->load->view('index',$Result);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */