<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 22 Dec, 2014
* Modified date : 22 Dec, 2014
* Description 	: Page contains report details
*/ 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Getreports extends MX_Controller 
{
	public function __Construct()
	{
		parent::__Construct();
		$this->load->model('getreports_model','rmodel');
		// Check login session 
		RedirectToLogin();
		// Check user access permission
		CheckUserAccessPermission(3);
	}
	/* Method used to check valid login or not - created by Karthik K on 26 Sep, 2014*/
	public function index()
	{
		$Result['RemoveAboutUs']	= "Remove";
		$Result['ScriptPage']		= "PageScripts";
		$Result['CategoryList']		= $this->rmodel->GetReportsCategoryList();
		$Result['VisibleReport']	= "1";
		$Result['DisplayPage']		= "DisplayReports";
		// pr($GetFolderList);
		/*$GetPath = "../Reports/";
		if($Handle = @opendir ($GetPath))
		{
			while ($File = readdir($Handle))
			{
				if($File != '.' && $File != '..')
				{
					// echo "Directory : ".$File."<br/>";
					$Query		= "SELECT id FROM mc_dashboard_category WHERE category_name='".$File."'";
					$GetDetails	= $this->db->query($Query);
					$GetResult	= $GetDetails->result();
					if(isset($GetResult) && count($GetResult)>0)
					{
						if($HandleOne = @opendir ($GetPath.$File))
						{
							while ($FileOne = readdir($HandleOne))
							{
								if($FileOne != '.' && $FileOne != '..')
								{
									// echo "		Sub Directory : ".$FileOne."<br/>";
									$InsertQuery['category_id'] = $GetResult[0]->id;
									$InsertQuery['sub_category_name'] = $FileOne;
									$this->common_model->InsertData('mc_dashboard_sub_category',$InsertQuery);
								}
							}
						}
					}
				}
			}
		}*/
		$this->load->view('index',$Result);
	}
	/* Method used to view dashboard selected reports - created by Karthik K on 23 Dec, 2014 */
	public function viewreports()
	{
		$Result['VisibleReport']	= DecodeValue($this->input->post('SubCategoryId'));
		$GetOutput					= $this->load->view('DisplayReports',$Result,true);
		echo json_encode(array('ShowReports'=>$GetOutput));
	}
}