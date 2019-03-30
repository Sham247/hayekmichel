<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MX_Controller 
{
	/* Method used to check valid login or not - created by Karthik K on 26 Sep, 2014*/
	public function index()
	{
		$GetSessionId = UserSessionId();
		if(isset($GetSessionId) && $GetSessionId != '')
		{
			redirect('home');
		}
		else
		{
			$this->load->library(array('form_validation'));
			$this->form_validation->set_rules('username','Username is required','required|xss_clean');
			$this->form_validation->set_rules('password','Password is required','required');
			if($this->form_validation->run())
			{
				$RedirectUrl	= "login";
				$GetUserName 	= $this->input->post('username');
				$GetPassword 	= $this->input->post('password');
				$LoginResult	= $this->common_model->CheckUserLogin($GetUserName,md5($GetPassword));
				if(isset($LoginResult) && count($LoginResult)>0)
				{
					$GetDateTime	= time();
					if(($LoginResult[0]->expire_date > $GetDateTime) || $LoginResult[0]->expire_date == null)
					{
						if($LoginResult[0]->is_confirmed == 1)
						{
							if($LoginResult[0]->group_status == 1)
							{
								if($LoginResult[0]->user_status == 1)
								{
									// Update login history details
									$HistoryData['user_id']		= $LoginResult[0]->id;
									$HistoryData['ip_address']	= GetIp();
									$HistoryData['logged_on']	= GetTime();
									$this->common_model->InsertData(GetLangLabel('Tbl_LoginHistory'),$HistoryData);
									// Store session vlaue
									$SesssionData['UserSessionId']	= $LoginResult[0]->id;
									$SesssionData['UserGroupId']	= $LoginResult[0]->group_id;
									$SesssionData['DisplayName']	= $LoginResult[0]->name;
									$SesssionData['IsSuperAdmin']	= $LoginResult[0]->is_super_admin;
									$this->session->set_userdata('MonocleUserSession',$SesssionData);
									$GetViewedUrl 	= $this->session->userdata('ViewedUrl');
									if($GetViewedUrl != '')
									{
										$RedirectUrl = $GetViewedUrl;
									}
									else
									{
										$RedirectUrl	= 'home';
									}
								}
								else
								{
									$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgAccountInActive'));
								}
							}
							else
							{
								$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgUserGroupInActive'));
							}
						}
						else
						{
							$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgConfirmEmail'));
						}
					}
					else
					{
						$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgAccountExpired'));
					}
				}
				else
				{
					$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgInvalidLoginDetails'));
				}
				redirect($RedirectUrl);
			}
			else
			{
				$Result['RemoveAboutUs']	= "Remove";
				$Result['RemoveGoToTop']	= "Remove";
				$Result['ScriptPage']		= "PageScripts";
				$this->load->view('Login',$Result);
			}
		}
	}
	/* Method used to confirm user login details - created by Karthik K on 08 Nov, 2014*/
	public function userconfirmaccount()
	{
		$GetSessionId = UserSessionId();
		if(isset($GetSessionId) && $GetSessionId != '')
		{
			redirect('home');
		}
		else
		{
			$GetKey	= DecodeValue($this->uri->segment(2,0));
			$this->common_model->CofirmUserAccount($GetKey);
			redirect('login');
		}
	}
	/* Method used to send reset password link - created by Karthik K on 28 Nov, 2014 */
	public function forgotpassword()
	{
		$JsonOutput = array();
		$Status 	= "Error";
		$DisplayMsg	= "Username/ Email does not exists";
		$GetResult 	= $this->common_model->SendResetPasswordDetails();
		if(isset($GetResult) && count($GetResult)>0)
		{
			$Status 	= "Success";
			$DisplayMsg	= "Reset password link has been sent to registered email.";
		}
		$JsonOutput['Status']	= $Status;
		$JsonOutput['Msg']		= $DisplayMsg;
		echo json_encode($JsonOutput);
	}
	/* Method used to changed user login password using reset password link - created by Karthik K on 01 Dec, 2014 */
	public function resetuserpass()
	{
		$GetSessionId = UserSessionId();
		if(isset($GetSessionId) && $GetSessionId != '')
		{
			redirect('home');
		}
		else
		{
			$GetKey			= DecodeValue($this->uri->segment(2,0));
			$CheckDetails 	= $this->common_model->SelectData(GetLangLabel('Tbl_UserResetPassword'),array('id','user_id'),array('reset_key'=>$GetKey,'changed_on'=>null));
			if(isset($CheckDetails) && count($CheckDetails)>0)
			{
				$Result['RemoveAboutUs']	= "Remove";
				$Result['ScriptPage']		= "PageScripts";
				if($this->input->post('BtnResetPass') != '')
				{
					$this->load->library('form_validation');
					$this->form_validation->set_rules('TxtNewPassword','lang:ErrResetNewPassword','required|xss_clean');
					$this->form_validation->set_rules('TxtRetypeNewPassword','lang:ErrResetRetypeNewPassword','required|xss_clean|matches[TxtNewPassword]');
					if($this->form_validation->run())
					{
						$this->common_model->ChangeUserPassword($CheckDetails[0]->id,$CheckDetails[0]->user_id);
						$this->session->set_flashdata('SuccessMsg',GetLangLabel('MsgResetPasswordSuccess'));
						redirect('login');
					}
				}
				$this->load->view('FormResetPassword',$Result);
			}
			else
			{
				$this->session->set_flashdata('ErrorMsg',GetLangLabel('MsgInvalidResetPassword'));
				redirect('login');
			}
		}
	}
	/* Method used to unset logged user session value - created by Karthik K on 26 Sep, 2014*/
	public function logout()
	{
		$UnsetSessionData	= array('ViewedUrl'=>'','MonocleUserSession'=>'');
		$this->session->unset_userdata($UnsetSessionData);
		$this->session->set_flashdata('SuccessMsg','Logout Success');
		redirect('login');
	}
	function test()
	{
		/*$this->load->library('parser');
		$data = array(
		              'blog_title'   => 'My Blog Title',
		              'blog_heading' => 'My Blog Heading',
		              'blog_entries' => array(
		                                      array('title' => 'Title 1', 'body' => 'Body 1'),
		                                      array('title' => 'Title 2', 'body' => 'Body 2'),
		                                      array('title' => 'Title 3', 'body' => 'Body 3'),
		                                      array('title' => 'Title 4', 'body' => 'Body 4'),
		                                      array('title' => 'Title 5', 'body' => 'Body 5')
		                                      )
		            );

		$this->parser->parse('blog_template', $data);*/
		/*$query = "update mc_hospital_group_details SET 	hospital_points_old=hospital_points";
		$details = $this->db->query($query);*/
		/*$query = "SELECT a.CCN1 AS provider_number, a.LBN1 AS hospital_name, b.HospitalName
FROM  `providermasterv5` a
JOIN hospitalstar b ON ( b.HospitalName LIKE CONCAT('%',a.`LBN1`,'%')) 
WHERE ifnull(a.`CCN1`,'')=''
AND IFNULL( a.`LBN1` ,  '' ) !=  '' AND ifnull(b.HospitalName,'') != ''
UNION
SELECT a.CCN2 AS provider_number, a.LBN2 AS hospital_name, b.HospitalName
FROM  `providermasterv5` a
JOIN hospitalstar b ON ( b.HospitalName LIKE CONCAT('%',a.`LBN2`,'%')) 
WHERE ifnull(a.`CCN2`,'')=''
AND IFNULL( a.`LBN2` ,  '' ) !=  '' AND ifnull(b.HospitalName,'') != ''
UNION
SELECT a.CCN3 AS provider_number, a.LBN3 AS hospital_name, b.HospitalName
FROM  `providermasterv5` a
JOIN hospitalstar b ON ( b.HospitalName LIKE CONCAT('%',a.`LBN3`,'%')) 
WHERE ifnull(a.`CCN3`,'')=''
AND IFNULL( a.`LBN3` ,  '' ) !=  '' AND ifnull(b.HospitalName,'') != ''
UNION
SELECT a.CCN4 AS provider_number, a.LBN4 AS hospital_name, b.HospitalName
FROM  `providermasterv5` a
JOIN hospitalstar b ON ( b.HospitalName LIKE CONCAT('%',a.`LBN4`,'%')) 
WHERE ifnull(a.`CCN4`,'')=''
AND IFNULL( a.`LBN4` ,  '' ) !=  '' AND ifnull(b.HospitalName,'') != ''
UNION
SELECT a.CCN5 AS provider_number, a.LBN5 AS hospital_name, b.HospitalName
FROM  `providermasterv5` a
JOIN hospitalstar b ON ( b.HospitalName LIKE CONCAT('%',a.`LBN5`,'%')) 
WHERE ifnull(a.`CCN5`,'')=''
AND IFNULL( a.`LBN5` ,  '' ) !=  '' AND ifnull(b.HospitalName,'') != ''";
		$details = $this->db->query($query);
		$result  = $details->result();
		if(isset($result) && count($result)>0)
		{
			$AddValue='';
			foreach($result as $GetVal)
			{
				$AddValue	.= $GetVal->hospital_name.'`'.$GetVal->HospitalName."\n";
			}
			$this->load->helper('file');
			if(!write_file('hospital.txt',$AddValue))
			{
				echo "fail";
			}
			else
			{
				echo "Success";
			}
		}
		Invalid Provider Number = 100079,800037*/
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */