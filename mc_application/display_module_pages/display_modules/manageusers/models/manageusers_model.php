<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 17 Oct, 2014
* Modified date : 17 Oct, 2014
* Description 	: Page contains manage users page model functions
*/ 
class Manageusers_model  extends CI_Model
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
	/* Method used to get display user list - created by Karthik K on 06 Nov, 2014 */
	public function GetDisplayUsers($StartLimit='',$EndLimit='')
	{
		if($this->SuperAdmin == 1)
		{
			$UserQuery = "SELECT lu.id,lu.group_id,ll.group_name,lu.username,lu.name,lu.email,
						  lu.created_on,lu.updated_on,lu.user_status,lu.expire_date FROM ".GetLangLabel('Tbl_LoginUsers')." lu,
						  ".GetLangLabel('Tbl_UserLevel')." ll
						  WHERE lu.group_id=ll.id AND lu.id!='".$this->LoggedUserId."' ORDER BY lu.updated_on DESC";
		}
		else
		{
			$UserQuery = "SELECT lu.id,lu.group_id,ll.group_name,lu.username,lu.name,lu.email,
						  lu.created_on,lu.updated_on,lu.user_status,lu.expire_date FROM ".GetLangLabel('Tbl_LoginUsers')." lu,
						  ".GetLangLabel('Tbl_UserLevel')." ll,".GetLangLabel('Tbl_CreatedUserList')." cul
						  WHERE cul.created_by='".$this->LoggedUserId."' AND cul.created_to=lu.id AND 
						  lu.group_id=ll.id  ORDER BY lu.updated_on DESC";
		}
		if($StartLimit >= 0 && $EndLimit > 0 )
		{
			$UserQuery	.= " LIMIT ".$StartLimit.','.$EndLimit;
		}
		$UserDetails	= $this->db->query($UserQuery);
		$UserResult		= $UserDetails->result_array();
		if(isset($UserResult) && count($UserResult)>0)
		{
			return $UserResult;
		}
	}
	/* Method used to get selected user details - created by Karthik K on 07 Nov, 2014 */
	public function GetViewUsersDetails($UserId='')
	{
		if($UserId != '')
		{
			if($this->SuperAdmin == 1)
			{
				$UserQuery = "SELECT lu.id,lu.group_id,ll.group_name,lu.username,lu.name,lu.email,
							  lu.user_status,lu.expire_date FROM ".GetLangLabel('Tbl_LoginUsers')." lu,
							  ".GetLangLabel('Tbl_UserLevel')." ll
							  WHERE lu.group_id=ll.id AND lu.id='".$UserId."'";
			}
			else
			{
				$UserQuery = "SELECT lu.id,lu.group_id,ll.group_name,lu.username,lu.name,lu.email,
							  lu.user_status,lu.expire_date FROM ".GetLangLabel('Tbl_LoginUsers')." lu,
							  ".GetLangLabel('Tbl_UserLevel')." ll,".GetLangLabel('Tbl_CreatedUserList')." cul
							  WHERE cul.created_by='".$this->LoggedUserId."' AND cul.created_to=lu.id AND
							  lu.group_id=ll.id AND lu.id='".$UserId."'";
			}
			$UserDetails	= $this->db->query($UserQuery);
			$UserResult		= $UserDetails->result_array();
			if(isset($UserResult) && count($UserResult)>0)
			{
				return $UserResult;
			}
		}
	}
	/* Method used to insert new user details - created by Karthik K on 07 Nov, 2014 */
	public function InsertNewUserDetails()
	{
		$SendEmail		= $this->input->post('TxtUserSendEmail');
		// Get form posting values
		$UserFullName 	= $this->input->post('TxtUserName');
		$UserEmail		= $this->input->post('TxtUserEmail');
		$UserLoginName 	= $this->input->post('TxtLoginName');
		$UserLevelId 	= $this->input->post('SltUserLevel');
		$ExpiryDate		= $this->input->post('TxtUserExpiry');
		$UserPassword	= $this->input->post('NewPassword');
		// Insert new user details
		$GetPassword	= random_string();
		$InsertUserData['group_id']		= DecodeValue($UserLevelId);
		$InsertUserData['username']		= $UserLoginName;
		$InsertUserData['name']			= $UserFullName;
		$InsertUserData['email']		= $UserEmail;
		$InsertUserData['password']		= md5($UserPassword);
		$InsertUserData['user_status']	= 1;
		if($SendEmail == true)
		{
			$InsertUserData['is_confirmed']	= 0;
		}
		else
		{
			$InsertUserData['is_confirmed']	= 1;
		}
		$InsertUserData['created_on']	= GetTime();
		$InsertUserData['updated_on']	= GetTime();
		if($ExpiryDate != '')
		{
			$DateToTimestamp	= strtotime($ExpiryDate);
			$InsertUserData['expire_date']		= $DateToTimestamp;
		}
		$this->common_model->InsertData(GetLangLabel('Tbl_LoginUsers'),$InsertUserData);
		// Get inserted user id
		$InsertUserId	= $this->db->insert_id();
		// Insert user created list
		$InsertListData['created_by']	= $this->LoggedUserId;
		$InsertListData['created_to']	= $InsertUserId;
		$this->common_model->InsertData(GetLangLabel('Tbl_CreatedUserList'),$InsertListData);
		// Insert user confirm details
		$ConfirmKey 	= random_string('alnum',25);
		$InsertConfirmData['user_id']		= $InsertUserId;
		$InsertConfirmData['confirm_key']	= $ConfirmKey;
		$InsertConfirmData['sent_on']		= GetTime();
		if($SendEmail == true)
		{
			$InsertConfirmData['confirmed_on']	= GetTime();
		}
		$this->common_model->InsertData(GetLangLabel('Tbl_UserConfirm'),$InsertConfirmData);
		if($SendEmail == true)
		{
			// Send mail to new user
			$this->load->model('sendmail_model');
			$MailData['Email']			= $UserEmail;
			$MailData['UserFullName']	= $UserFullName;
			$MailData['LoginName']		= $UserLoginName;
			$MailData['Password']		= $UserPassword;
			$MailData['ConfirmUrl']		= "<a href='".base_url('confirmuser/'.EncodeValue($ConfirmKey))."'>".base_url('confirmuser/'.EncodeValue($ConfirmKey))."</a>";
			$this->sendmail_model->SendMailToNewUser($MailData);
			$this->session->set_flashdata('SuccessMsg',GetLangLabel('MsgNewUserAdded'));
		}
		else
		{
			$this->session->set_flashdata('SuccessMsg',GetLangLabel('MsgNewUserAddedWithConfirm'));
		}
	}
	/* Method used to update user details - created by Karthik K on 07 Nov, 2014 */
	public function UpdateUserDetails($UpdateUserId='')
	{
		if($UpdateUserId != '')
		{
			// Get form posting values
			$UserName 	= $this->input->post('TxtUserName');
			$UserEmail	= $this->input->post('TxtUserEmail');
			$UserLevel 	= $this->input->post('SltUserLevel');
			$NewPassword= $this->input->post('NewPassword');
			$UserStatus = $this->input->post('ChkUserStatus');
			$ExpiryDate	= $this->input->post('TxtUserExpiry');
			if($UserStatus != '')
			{
				$UserStatus  = 0;
			}
			else
			{
				$UserStatus  = 1;
			}
			// Update user data
			$UpdateData['name']		= $UserName;
			$UpdateData['email']	= $UserEmail;
			$UpdateData['group_id']	= DecodeValue($UserLevel);
			if($ExpiryDate != '')
			{
				$DateToTimestamp			= strtotime($ExpiryDate);
				$UpdateData['expire_date']	= $DateToTimestamp;
			}
			else
			{
				$UpdateData['expire_date']	= null;
			}
			if($NewPassword != '')
			{
				$UpdateData['password']= md5($NewPassword);
			}
			$UpdateData['user_status'] 	= $UserStatus;
			$UpdateData['updated_on'] 	= GetTime();
			$this->common_model->UpdateData(GetLangLabel('Tbl_LoginUsers'),$UpdateData,array('id'=>$UpdateUserId));

		}
	}
	/* Method used to delete added user - created by Karthik K on 08 Nov, 2014 */
	public function DeleteAddedUser($UserId='')
	{
		if($this->SuperAdmin == 1)
		{
			$Query 		= "SELECT id FROM ".GetLangLabel('Tbl_LoginUsers')." WHERE id='".$UserId."' AND id != '".$this->LoggedUserId."'";
		}
		else
		{
			$Query 		= "SELECT lu.id FROM ".GetLangLabel('Tbl_LoginUsers')." lu,
						   ".GetLangLabel('Tbl_CreatedUserList')." cul
						   WHERE cul.created_by='".$this->LoggedUserId."' AND cul.created_to=lu.id 
						   AND lu.id='".$UserId."'";
		}
		$Details 	= $this->db->query($Query);
		$Result 	= $Details->result();
		if(isset($Result) && count($Result)>0)
		{
			$this->common_model->DeleteData(GetLangLabel('Tbl_LoginUsers'),array('id'=>$Result[0]->id));
			$this->session->set_flashdata('SuccessMsg',GetLangLabel('MsgUserDelete'));
		}
		else
		{
			$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgUserDeleteFailed'));
		}
	}
	/* Method used to filter user list - created by Karthik K on 15 Nov, 2014 */
	public function FilterDisplayUsersList($StartLimit='',$EndLimit='')
	{
		$FilTxtFullName		= GetSessionVal('FilTxtFullName');
		$FilTxtUserName		= GetSessionVal('FilTxtUserName');
		$FilTxtUserEmail	= GetSessionVal('FilTxtUserEmail');
		$FilSltUserLevel	= GetSessionVal('FilSltUserLevel');
		$FilSltStatus		= GetSessionVal('FilSltUserStatus');
		$WhereCond			= '';
		if(trim($FilTxtFullName) != '')
		{
			$WhereCond	= " lu.name LIKE '%".$FilTxtFullName."%' ";
			// $FilterCond['lu.name']	= $FilTxtFullName;
		}
		if(trim($FilTxtUserName) != '')
		{
			if($WhereCond != '')
			{
				$WhereCond	.= " AND lu.username LIKE '%".$FilTxtUserName."%' ";
			}
			else
			{
				$WhereCond	.= " lu.username LIKE '%".$FilTxtUserName."%' ";
			}
			// $FilterCond['lu.username']	= $FilTxtUserName;
		}
		if(trim($FilTxtUserEmail) != '')
		{
			if($WhereCond != '')
			{
				$WhereCond	.= " AND lu.email LIKE '%".$FilTxtUserEmail."%' ";
			}
			else
			{
				$WhereCond	.= " lu.email LIKE '%".$FilTxtUserEmail."%' ";
			}
			// $FilterCond['lu.email']	= $FilTxtUserEmail;
		}
		if($FilSltUserLevel != '')
		{
			if($WhereCond != '')
			{
				$WhereCond	.= " AND lu.group_id='".DecodeValue($FilSltUserLevel)."'";
			}
			else
			{
				$WhereCond	.= " lu.group_id='".DecodeValue($FilSltUserLevel)."'";
			}
			// $FilterCond['lu.group_id']	= DecodeValue($FilSltUserLevel);
		}
		$GetCurrentTime	= time();
		if($FilSltStatus != '')
		{
			if($FilSltStatus == 2)
			{
				if($WhereCond != '')
				{
					$WhereCond	.= " AND  ifnull(lu.expire_date,'')!= '' AND lu.expire_date < '".$GetCurrentTime."' ";
				}
				else
				{
					$WhereCond	.= "  ifnull(lu.expire_date,'')!= '' AND lu.expire_date < '".$GetCurrentTime."' ";
				}
			}
			else
			{
				if($WhereCond != '')
				{
					$WhereCond	.= " AND lu.user_status='".$FilSltStatus."' AND (ifnull(lu.expire_date,'') = '' OR lu.expire_date > '".$GetCurrentTime."')";
				}
				else
				{
					$WhereCond	.= " lu.user_status='".$FilSltStatus."'  AND (ifnull(lu.expire_date,'') = '' OR lu.expire_date > '".$GetCurrentTime."')";
				}
			}
			// $FilterCond['lu.user_status']	= $FilSltStatus;
		}
		if($WhereCond != '')
		{
			$WhereCond	= " AND ".$WhereCond;
		}
			/*$WhereCond	= '';
			$CondCount	= 0;
			foreach($FilterCond as $GetKey=>$GetValue)
			{
				$CondCount++;
				$AddSymbol	= ' AND ';
				if($CondCount == count($FilterCond))
				{
					$AddSymbol	= '';
				}if(($LoginResult[0]->expire_date > $GetDateTime) || $LoginResult[0]->expire_date == null)
				$WhereCond	.= $GetKey.'="'.$GetValue.'"'.$AddSymbol;
			}*/
		if($this->SuperAdmin == 1)
		{
			$UserQuery = "SELECT lu.id,lu.group_id,ll.group_name,lu.username,lu.name,lu.email,
						  lu.created_on,lu.updated_on,lu.user_status,lu.expire_date FROM ".GetLangLabel('Tbl_LoginUsers')." lu,
						  ".GetLangLabel('Tbl_UserLevel')." ll
						  WHERE lu.group_id=ll.id AND lu.id!='".$this->LoggedUserId."' ".$WhereCond."
						  ORDER BY lu.updated_on DESC";
		}
		else
		{
			$UserQuery = "SELECT lu.id,lu.group_id,ll.group_name,lu.username,lu.name,lu.email,
						  lu.created_on,lu.updated_on,lu.user_status,lu.expire_date FROM ".GetLangLabel('Tbl_LoginUsers')." lu,
						  ".GetLangLabel('Tbl_UserLevel')." ll,".GetLangLabel('Tbl_CreatedUserList')." cul
						  WHERE cul.created_by='".$this->LoggedUserId."' AND cul.created_to=lu.id AND 
						  lu.group_id=ll.id ".$WhereCond."  ORDER BY lu.updated_on DESC";
		}
		if($StartLimit >= 0 && $EndLimit > 0 )
		{
			$UserQuery	.= " LIMIT ".$StartLimit.','.$EndLimit;
		}
		$UserDetails	= $this->db->query($UserQuery);
		$ReturnResult	= $UserDetails->result_array();
		return $ReturnResult;
	}
}
?>