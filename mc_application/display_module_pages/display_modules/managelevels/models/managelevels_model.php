<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 17 Oct, 2014
* Modified date : 17 Oct, 2014
* Description 	: Page contains manage user level page model functions
*/ 
class Managelevels_model  extends CI_Model
{
	var $UserLevelId;
	var $LoggedUserId;
	var $SuperAdmin;
	function __construct()
	{
		parent::__construct();
		$this->UserLevelId 	= GetUserLevel();
		$this->LoggedUserId = UserSessionId();
		$this->SuperAdmin 	= IsSuperAdmin();
	}
	/* Method used to get display user levels - created by Karthik K on 07 Nov, 2014 */
	public function GetDisplayUserLevels($StartLimit='',$EndLimit='')
	{
		if($this->SuperAdmin == 1)
		{
			$UserLevelQuery 	= "SELECT ul.id,ul.group_name,ul.description,ul.redirect,ul.group_status,
								   (SELECT COUNT(id) FROM ".GetLangLabel('Tbl_LoginUsers')." WHERE group_id=ul.id) as total_users,
								   (SELECT COUNT(id) FROM ".GetLangLabel('Tbl_LoginUsers')." WHERE group_id=ul.id AND user_status=1) as active_users
								   FROM ".GetLangLabel('Tbl_UserLevel')." ul 
								   WHERE ul.id!='".$this->UserLevelId."'  ORDER BY ul.updated_on DESC";
		}
		else
		{
			$UserLevelQuery 	= "SELECT ul.id,ul.group_name,ul.description,ul.redirect,ul.group_status,
								   (SELECT COUNT(id) FROM ".GetLangLabel('Tbl_LoginUsers')." WHERE group_id=ul.id) as total_users,
								   (SELECT COUNT(id) FROM ".GetLangLabel('Tbl_LoginUsers')." WHERE group_id=ul.id AND user_status=1) as active_users
								   FROM ".GetLangLabel('Tbl_UserLevel')." ul,".GetLangLabel('Tbl_CreatedLevelList')." cll
								   WHERE cll.created_by='".$this->LoggedUserId."' AND cll.group_id=ul.id AND 
								   ul.id!='".$this->UserLevelId."'  ORDER BY ul.updated_on DESC";
		}
		if($StartLimit >= 0 && $EndLimit > 0 )
		{
			$UserLevelQuery	.= " LIMIT ".$StartLimit.','.$EndLimit;
		}
		$UserLevelDetails	= $this->db->query($UserLevelQuery);
		$UserLevelResult	= $UserLevelDetails->result();
		if(isset($UserLevelResult) && count($UserLevelResult)>0)
		{
			return $UserLevelResult;
		}
	}
	/* Method used to get selected user level details - created by Karthik K on 07 Nov, 2014 */
	public function GetViewLevelDetails($LevelId='')
	{
		if($LevelId != '')
		{
			if($this->SuperAdmin == 1)
			{
				$UserLevelQuery 	= "SELECT ul.id,ul.group_name,ul.description,ul.redirect,ul.group_status 
								   	   FROM ".GetLangLabel('Tbl_UserLevel')." ul 
								   	   WHERE ul.id='".$LevelId."'";
			}
			else
			{
				$UserLevelQuery 	= "SELECT ul.id,ul.group_name,ul.description,ul.redirect,ul.group_status 
								   	   FROM ".GetLangLabel('Tbl_UserLevel')." ul,".GetLangLabel('Tbl_CreatedLevelList')." cll 
								   	   WHERE cll.created_by='".$this->LoggedUserId."' AND cll.group_id=ul.id 
								   	   AND ul.id='".$LevelId."'";
			}
			$UserLevelDetails	= $this->db->query($UserLevelQuery);
			$UserLevelResult	= $UserLevelDetails->result();
			if(isset($UserLevelResult) && count($UserLevelResult)>0)
			{
				return $UserLevelResult;
			}
		}
	}
	/* Method used to insert new userlevel details - created by Karthik K on 08 Nov, 2014 */
	public function InsertNewUserLevelDetails()
	{
		$InsertData['group_name']	= $this->input->post('TxtLevelName');
		$InsertData['description']	= $this->input->post('TxtLevelDesc');
		$InsertData['group_status']	= 1;
		$InsertData['created_on']	= GetTime();
		$InsertData['updated_on']	= GetTime();
		$this->common_model->InsertData(GetLangLabel('Tbl_UserLevel'),$InsertData);
		$InsertLevelId = $this->db->insert_id();
		// Insert module access details
		if(isset($_REQUEST['ChkLevelModules']) && count($_REQUEST['ChkLevelModules']) > 0)
		{
			$GetModuleAccess	= $_REQUEST['ChkLevelModules'];
			foreach ($GetModuleAccess as $ModuleId) 
			{
				$InsertAccessData['group_id']	= $InsertLevelId;
				$InsertAccessData['module_id']		= DecodeValue($ModuleId);
				$this->common_model->InsertData(GetLangLabel('Tbl_UserLevelAccessModules'),$InsertAccessData);
			}
		}
		// Insert user created level list
		$InsertListData['created_by']	= $this->LoggedUserId;
		$InsertListData['group_id']		= $InsertLevelId;
		$this->common_model->InsertData(GetLangLabel('Tbl_CreatedLevelList'),$InsertListData);
		// Insert group mapped state data
		if(isset($_REQUEST['ChkGroupState']) && count($_REQUEST['ChkGroupState'])>0)
		{
			foreach($_REQUEST['ChkGroupState'] as $GetStateVal)
			{
				$InsertStateData['group_id']	= $InsertLevelId;
				$InsertStateData['state_id']	= DecodeValue($GetStateVal);
				$this->common_model->InsertData(GetLangLabel('Tbl_GroupStateMap'),$InsertStateData);
			}
		}
	}
	/* Method used to update user level details - created by Karthik K on 07 Nov, 2014 */
	public function UpdateLevelDetails($UpdateLevelId='')
	{
		if($UpdateLevelId != '')
		{
			// Get form posting values
			$LevelName 		= $this->input->post('TxtLevelName');
			$LevelDesc		= $this->input->post('TxtLevelDesc');
			$LevelStatus 	= $this->input->post('ChkLevelStatus');
			if($LevelStatus != '')
			{
				$LevelStatus  = 0;
			}
			else
			{
				$LevelStatus  = 1;
			}
			// Update user data
			$UpdateData['group_name']	= $LevelName;
			$UpdateData['description']	= $LevelDesc;
			$UpdateData['group_status'] = $LevelStatus;
			$UpdateData['updated_on'] 	= GetTime();
			$this->common_model->UpdateData(GetLangLabel('Tbl_UserLevel'),$UpdateData,array('id'=>$UpdateLevelId));
			// Get access module details
			$GetAccessModules			= $this->common_model->GetUserAccessModules($UpdateLevelId);
			$AccessModuleId				= array();
			if(isset($GetAccessModules) && count($GetAccessModules)>0)
			{
				foreach($GetAccessModules as $CheckMdlId)
				{
					if($CheckMdlId->is_level_view > 0)
					{
						$AccessModuleId[]	= $CheckMdlId->id;
					}
				}
			}
			// Insert module access details
			if(isset($_REQUEST['ChkLevelModules']) && count($_REQUEST['ChkLevelModules']) > 0)
			{
				$GetModuleAccess	= $_REQUEST['ChkLevelModules'];
				foreach ($GetModuleAccess as $ModuleId) 
				{
					if(in_array(DecodeValue($ModuleId),$AccessModuleId) == false)
					{
						$InsertAccessData['group_id']	= $UpdateLevelId;
						$InsertAccessData['module_id']		= DecodeValue($ModuleId);
						$this->common_model->InsertData(GetLangLabel('Tbl_UserLevelAccessModules'),$InsertAccessData);
					}
					else
					{
						$RemoveId	= array_search(DecodeValue($ModuleId),$AccessModuleId);
						unset($AccessModuleId[$RemoveId]);
					}
				}
			}
			if(count($AccessModuleId))
			{
				foreach($AccessModuleId as $DeleteMdl)
				{
					$DeleteMdlQuery	= "SELECT id FROM ".GetLangLabel('Tbl_UserLevelAccessModules')." WHERE group_id='".$UpdateLevelId."' AND module_id='".$DeleteMdl."'";
					$DeleteMdlDetails	= $this->db->query($DeleteMdlQuery);
					$DeleteMdlResult	= $DeleteMdlDetails->result();
					if(isset($DeleteMdlResult) && count($DeleteMdlResult)>0)
					{
						$this->common_model->DeleteData(GetLangLabel('Tbl_UserLevelAccessModules'),array('id'=>$DeleteMdlResult[0]->id));
					}
				}
			}
			// Get added group state list
			$GroupStateList 	= $this->GetGroupAddedStateList($UpdateLevelId,true);
			$GroupAddedState 	= array();
			if(isset($GroupStateList) && count($GroupStateList)>0)
			{
				foreach($GroupStateList as $GroupState)
				{
					$GroupAddedState[]	= $GroupState->id;
				}
			}
			// Insert group state details
			if(isset($_REQUEST['ChkGroupState']) && count($_REQUEST['ChkGroupState']) > 0)
			{
				$UpdateGroupState	= $_REQUEST['ChkGroupState'];
				foreach ($UpdateGroupState as $StateId) 
				{
					if($StateId != '')
					{
						if(in_array(DecodeValue($StateId),$GroupAddedState) == false)
						{
							$InsertStateData['group_id']	= $UpdateLevelId;
							$InsertStateData['state_id']	= DecodeValue($StateId);
							$this->common_model->InsertData(GetLangLabel('Tbl_GroupStateMap'),$InsertStateData);
						}
						else
						{
							$RemoveStateId	= array_search(DecodeValue($StateId),$GroupAddedState);
							unset($GroupAddedState[$RemoveStateId]);
						}
					}
				}
			}
			// Delete existing added group state details
			if(count($GroupAddedState))
			{
				foreach($GroupAddedState as $DeleteState)
				{
					$DeleteStateQuery	= "SELECT id FROM ".GetLangLabel('Tbl_GroupStateMap')." WHERE group_id='".$UpdateLevelId."' AND state_id='".$DeleteState."'";
					$DeleteStateDetails	= $this->db->query($DeleteStateQuery);
					$DeleteStateResult	= $DeleteStateDetails->result();
					if(isset($DeleteStateResult) && count($DeleteStateResult)>0)
					{
						$this->common_model->DeleteData(GetLangLabel('Tbl_GroupStateMap'),array('id'=>$DeleteStateResult[0]->id));
					}
				}
			}
		}
	}
	/* Method used to delete added userlevel details - created by Karthik K on 08 Nov, 2014 */
	public function DeleteAddedUserLevel($LevelId='')
	{
		if($this->SuperAdmin == 1)
		{
			$Query 		= "SELECT ul.id,(SELECT count(id) FROM ".GetLangLabel('Tbl_LoginUsers')." WHERE group_id=ul.id) as user_count 
						  FROM ".GetLangLabel('Tbl_UserLevel')." ul WHERE ul.id='".$LevelId."' AND ul.id!='".$this->UserLevelId."'";
		}
		else
		{
			$Query 		= "SELECT ul.id,(SELECT count(id) FROM ".GetLangLabel('Tbl_LoginUsers')." WHERE group_id=ul.id) as user_count 
						  FROM ".GetLangLabel('Tbl_UserLevel')." ul,".GetLangLabel('Tbl_CreatedLevelList')." cll 
						  WHERE cll.created_by='".$this->LoggedUserId."' AND cll.group_id=ul.id AND ul.id='".$LevelId."'";
		}
		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			if($Result[0]->user_count > 0)
			{
				$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgLevelDeleteUserFound'));
			}
			else
			{
				$this->common_model->DeleteData(GetLangLabel('Tbl_UserLevel'),array('id'=>$Result[0]->id));
				$this->session->set_flashdata('SuccessMsg',GetLangLabel('MsgLevelDelete'));
			}
		}
		else
		{
			$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgLevelDeleteFailed'));
		}
	}
	/* Method used to filter display user level list - created by Karthik K on 17 Nov, 2014 */
	public function FilterDisplayLevelList($StartLimit='',$EndLimit='')
	{
		$FilTxtLevelName	= GetSessionVal('FilTxtLevelName');
		$FilSltLevelStatus	= GetSessionVal('FilSltLevelStatus');
		$WhereCond			= '';
		if(trim($FilTxtLevelName) != '')
		{
			$WhereCond	= " ul.group_name LIKE '%".$FilTxtLevelName."%' ";
		}
		if($FilSltLevelStatus != '')
		{
			if($WhereCond != '')
			{
				$WhereCond	.= " AND ul.group_status='".$FilSltLevelStatus."' ";
			}
			else
			{
				$WhereCond	.= " ul.group_status='".$FilSltLevelStatus."' ";
			}
		}
		if($WhereCond != '')
		{
			$WhereCond	= " AND ".$WhereCond;
		}
		if($this->SuperAdmin == 1)
		{
			$UserLevelQuery 	= "SELECT ul.id,ul.group_name,ul.description,ul.redirect,ul.group_status,
								   (SELECT COUNT(id) FROM ".GetLangLabel('Tbl_LoginUsers')." WHERE group_id=ul.id) as total_users,
								   (SELECT COUNT(id) FROM ".GetLangLabel('Tbl_LoginUsers')." WHERE group_id=ul.id AND user_status=1) as active_users
								   FROM ".GetLangLabel('Tbl_UserLevel')." ul 
								   WHERE ul.id!='".$this->UserLevelId."' ".$WhereCond." ORDER BY ul.updated_on DESC";
		}
		else
		{
			$UserLevelQuery 	= "SELECT ul.id,ul.group_name,ul.description,ul.redirect,ul.group_status,
								   (SELECT COUNT(id) FROM ".GetLangLabel('Tbl_LoginUsers')." WHERE group_id=ul.id) as total_users,
								   (SELECT COUNT(id) FROM ".GetLangLabel('Tbl_LoginUsers')." WHERE group_id=ul.id AND user_status=1) as active_users
								   FROM ".GetLangLabel('Tbl_UserLevel')." ul,".GetLangLabel('Tbl_CreatedLevelList')." cll
								   WHERE cll.created_by='".$this->LoggedUserId."' AND cll.group_id=ul.id AND 
								   ul.id!='".$this->UserLevelId."' ".$WhereCond."  ORDER BY ul.updated_on DESC";
		}
		if($StartLimit >= 0 && $EndLimit > 0 )
		{
			$UserLevelQuery	.= " LIMIT ".$StartLimit.','.$EndLimit;
		}
		$UserLevelDetails	= $this->db->query($UserLevelQuery);
		$ReturnResult		= $UserLevelDetails->result();
		return $ReturnResult;
	}
	/* Method used to get selected state list for created groups - created by Karthik K on 24 Nov, 2014 */
	public function GetGroupAddedStateList($GroupId='',$ActualResult=false)
	{
		if($GroupId != '')
		{
			if($ActualResult == true)
			{
				$Query 	= "SELECT sl.id,sl.state_name,gsm.id as group_state_id FROM ".GetLangLabel('Tbl_StateList')." sl 
						  JOIN ".GetLangLabel('Tbl_GroupStateMap')." gsm ON (gsm.state_id=sl.id AND gsm.group_id='".$GroupId."') 
						  WHERE sl.status=1";
			}
			else
			{
				$Query 	= "SELECT sl.id,sl.state_name,gsm.id as group_state_id FROM ".GetLangLabel('Tbl_StateList')." sl 
						  LEFT JOIN ".GetLangLabel('Tbl_GroupStateMap')." gsm ON (gsm.state_id=sl.id AND gsm.group_id='".$GroupId."') 
						  WHERE sl.status=1";
			}
			$Details 	= $this->db->query($Query);
			$Result 	= $Details->result();
			if(isset($Result) && count($Result)>0)
			{
				return $Result;
			}
		}
	}
}
?>