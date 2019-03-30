<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common extends MX_Controller 
{
	public function __construct()
	{
		parent::__construct();
		// Check login session 
		RedirectToLogin();
		// Check user access permission
		CheckUserAccessPermission(1);
	}
	/* Method used to display reports - created by Karthik K on 15 Nov, 2014 */
	public function viewreports()
	{
		$GetStateList				= $this->common_model->GetStateList();
		/*$Result['ScriptPage']		= 'PageScripts';*/
		$Result['StateList']		= $GetStateList;
		$Result['DisplayTitle']		= '';
		$Result['RemoveAboutUs']	= "Remove";
		$Result['RemoveGoToTop']	= "Remove";
		$Result['RemoveQuickSearch']= "Remove";
		$Result['DisplayPage']		= "DisplayRequestedReports";
		$GetFromDate				= $this->input->post('TxtReportFrom');
		$GetFromDate				= ($GetFromDate != '')?strtotime($GetFromDate):strtotime("01-".date('M-Y'));
		$GetToDate					= $this->input->post('TxtReportTo');
		$GetToDate					= ($GetToDate != '')?strtotime($GetToDate):time();
		$this->load->model('manageusers/manageusers_model');
		$TotalUsers					= $this->manageusers_model->GetDisplayUsers();
		$Result['TotalUsers']		= count($TotalUsers);
		// Get user registered user reports
		$GetReports					= $this->common_model->GetAccountCreatedReports($GetFromDate,$GetToDate);
		$Result['UserReports']		= $GetReports;
		// Get most login users list
		$Result['MostLoginList']	= $this->common_model->GetMostLoginUserList();
		$this->load->view('index',$Result);
	}
	/* Method used to display user reports - created by Karthik K on 06 Nov, 2014 */
	public function sendmailtouserlevels()
	{
		// Get state list
		$GetStateList				= $this->common_model->GetStateList();
		$Result['ScriptPage']		= 'PageScripts';
		$Result['StateList']		= $GetStateList;
		$Result['DisplayTitle']		= '';
		$Result['RemoveAboutUs']	= "Remove";
		$Result['RemoveGoToTop']	= "Remove";
		$Result['DisplayPage']		= "SendMailToUsers";
		$Result['RemoveQuickSearch']= "Remove";
		// Get user level list
		$Result['UserLevels']		= $this->common_model->GetUserLevel(true);
		if($this->input->post('BtnSendMailTo') != '')
		{
			// Do form validation
			$this->load->library('form_validation');
			$this->form_validation->set_rules('SltMailToLevel','lang:ErrMailToLevel','required');
			$this->form_validation->set_rules('TxtMailSubject','lang:ErrMailSubject','required|xss_clean|strip_php_tags');
			$this->form_validation->set_rules('TxtMailMessage','lang:ErrMailMessage','required|xss_clean|strip_php_tags');
			if($this->form_validation->run($this))
			{
				// Send mail to selected user levels
				$this->common_model->SendMailToSelectedLevels();
				redirect('sendmailto');
			}
		}
		$this->load->view('index',$Result);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */