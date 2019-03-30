<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userprofile extends MX_Controller 
{
	var $LoggedUserId;
	public function __construct()
	{
		parent::__construct();
		// Check login session 
		$this->LoggedUserId = UserSessionId();
		RedirectToLogin();
	}
	/* Method used to display home page with quick search details - created by Karthik K on 17 Oct, 2014 */
	public function index()
	{
		// Get state list
		$GetStateList				= $this->common_model->GetStateList();
		$Result['StateList']		= $GetStateList;
		// Get logged user profile details
		$ProfileDetails				= $this->common_model->SelectData(GetLangLabel('Tbl_LoginUsers'),array('name','email'),array('id'=>UserSessionId()));
		$Result['ProfileDetails']	= $ProfileDetails;
		$Result['ScriptPage']		= 'PageScripts';
		$Result['DisplayTitle']		= '';
		$Result['RemoveAboutUs']	= "Remove";
		$Result['RemoveGoToTop']	= "Remove";
		$Result['RemoveQuickSearch']	= "Remove";
		$GetUserAccessLog 				= $this->common_model->GetUserAccessLogDetails();
		$Result['AccessLog']			= $GetUserAccessLog;
		if($this->input->post('save_profile') != '')
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('CurrentPass','lang:ErrCurrentPassword','required|xss_clean|callback_CheckCurrentPassword');
			$this->form_validation->set_rules('UserFullName','lang:ErrName','required|xss_clean|alpha');
			$this->form_validation->set_rules('UserEmail','lang:ErrEmail','required|xss_clean|valid_email|callback_checkuseremail');
			if($this->input->post('NewPassword') != '' || $this->input->post('CofirmNewPassword') != '')
			{
				$this->form_validation->set_rules('NewPassword','lang:ErrNewPassword','required|xss_clean');
				$this->form_validation->set_rules('CofirmNewPassword','lang:ErrNewPasswordAgain','required|xss_clean|matches[NewPassword]');
			}
			if($this->form_validation->run($this))
			{
				$this->common_model->UpdateProfileDetails();
				$this->session->set_flashdata('SuccessMsg',GetLangLabel('MsgProfileUpdated'));
				redirect('profile');
			}
		}
		$this->load->view('Profile',$Result);
	}
	/* Method used to check unique useremail or not - created by Karthik K on 07 Nov, 2014 */
	public function checkuseremail($UserEmail='')
	{
		if($UserEmail != '')
		{
			$GetDetails	= $this->common_model->SelectData(GetLangLabel('Tbl_LoginUsers'),array('id'),array('id !='=>$this->LoggedUserId,'email'=>$UserEmail));
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
	/* Method used to check valid current password or not - created by Karthik K on 28 Oct, 2014 */
	public function CheckCurrentPassword($CurrentPassword='')
	{
		$GetDetails	= $this->common_model->SelectData(GetLangLabel('Tbl_LoginUsers'),array('password'),array('id'=>UserSessionId()));
		if(isset($GetDetails) && count($GetDetails)>0)
		{
			if($GetDetails[0]->password == md5($CurrentPassword))
			{
				return true;
			}
			else
			{
				$this->form_validation->set_message('CheckCurrentPassword',GetLangLabel('ErrIncorrectCurrentPassword'));
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */