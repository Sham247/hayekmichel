<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Adminsitesettings extends MX_Controller
{
	var $UserLevelId;
	public function __construct()
	{
		parent::__construct();
		// Check login session 
		RedirectToLogin();
		// Check user access permission
		CheckUserAccessPermission(2);
		$this->UserLevelId = GetUserLevel();
		$this->load->model('adminsitesettings_model','assm');
	}
	/* Method used to display admin site settings - created by Karthik K on 27 Nov, 2014 */
	public function index()
	{
		$GetStateList				= $this->common_model->GetStateList();
		/*$Result['ScriptPage']		= 'PageScripts';*/
		$Result['StateList']		= $GetStateList;
		$EncodeStateData			= array();
		if(isset($GetStateList) && count($GetStateList)>0)
		{
			foreach($GetStateList as $GetStateVal)
			{
				$InnerStateData['StateId']	= EncodeValue($GetStateVal->id);
				$InnerStateData['PhyRanked']= $GetStateVal->physicians_ranked;
				$InnerStateData['StateCovered']	= $GetStateVal->state_covered;
				$EncodeStateData[]				= $InnerStateData;
			}
		}
		$Result['EncodedStateData']	= json_encode($EncodeStateData);
		$Result['DisplayTitle']		= '';
		$Result['RemoveAboutUs']	= "Remove";
		$Result['RemoveGoToTop']	= "Remove";
		$Result['RemoveQuickSearch']= "Remove";
		$Result['DisplayPage']		= "DisplaySiteSettings";
		$Result['ScriptPage']		= 'PageScripts';
		$this->load->view('index',$Result);
	}	
	/* Method used to update state metrics - created by Karthik K on 27 Nov, 2014 */
	public function updatestatemetrics()
	{
		$this->assm->UpdateStateMetrics();
	}
}
?>