<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MX_Controller 
{
	public function __construct()
	{
		parent::__construct();
		// Check login session 
		RedirectToLogin();
		// Check user access permission
		CheckUserAccessPermission(3);
	}
	/* Method used to display home page with quick search details - created by Karthik K on 17 Oct, 2014 */
	public function index()
	{
		$GetStateList			= $this->common_model->GetStateList();
		$Result['ScriptPage']	= 'PageScripts';
		$Result['StateList']	= $GetStateList;
		$Result['DisplayTitle']	= '';
		$Result['RemoveAboutUs']	= "Remove";
		$Result['RemoveQuickSearch']	= "Remove";
		$this->load->view('Dashboard',$Result);
	}
	/* Method used to get dashboard result - created by Karthik K on 28 Oct, 2014 */
	public function getdashboardresult()
	{
		$this->load->view('DisplayDashboardResult');
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */