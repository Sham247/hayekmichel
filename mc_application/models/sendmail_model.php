<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 07 Nov, 2014
* Modified date : 07 Nov, 2014
* Description 	: Page contains sendmail model functions
*/ 
class Sendmail_model extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
	}
	/* Method for send mail common template - created by Karthik K on 07 Nov, 2014 */
	public function SendMailTo($ReturnConfig=array())
	{
		$MailConfig['FromEmail']	= FROM_EMAIL;
		$MailConfig['ToEmail']		= '';
		$MailConfig['MailSubject']	= SITE_NAME;	
		$MailConfig['MailContent']	= '';	
		$MailConfig['AddCc']		= '';
		foreach($ReturnConfig as $GetKey=>$GetValue)
		{
			if(isset($MailConfig[$GetKey]) && $GetValue != '')
			{
				$MailConfig[$GetKey]	= $GetValue;
			}
		}
		if($MailConfig['ToEmail'] != '')
		{
			$this->load->library('email');
			$this->email->from($MailConfig['FromEmail']);
			$this->email->to($MailConfig['ToEmail']);
			$this->email->subject($MailConfig['MailSubject']);
			$this->email->message($MailConfig['MailContent']);
			$this->email->send();
		}
	}
	/* Email template for new user - created by Karthik K on 07 Nov, 2014 */
	public function SendMailToNewUser($ReturnConfig=array())
	{
		$MailSubject	= "Welcome to ".SITE_NAME;
		$DisplayContent	= "<p style='margin-top:0'>Welcome to ".SITE_NAME.". Here are your account details</p>";
		$DisplayContent	.= "<table><tr><td>Name : </td><td>".$ReturnConfig['UserFullName']."</td></tr>";
		$DisplayContent .= "<tr><td>Username : </td><td>".$ReturnConfig['LoginName']."</td></tr>";
		$DisplayContent .= "<tr><td>Email : </td><td>".$ReturnConfig['Email']."</td></tr>";
		$DisplayContent .= "<tr><td>Password : </td><td>".$ReturnConfig['Password']."</td></tr></table>";
		$DisplayContent	.= "<p>Please confirm your email to activate your account : ".$ReturnConfig['ConfirmUrl']."</p>";
		$DisplayName	= "Dear ".$ReturnConfig['UserFullName'].', ';
		$ContentFind	= array('{{NAME}}','{{TIME}}','{{CONTENT}}');
		$ContentReplace	= array($DisplayName,date('d M, Y'),$DisplayContent);
		$GetTemplate 	= $this->load->view('email_templates/CommonEmailTemplate','',true);
		$MailContent	= str_ireplace($ContentFind,$ContentReplace,$GetTemplate);
		$MailConfig['ToEmail'] 		= $ReturnConfig['Email'];
		$MailConfig['MailSubject'] 	= $MailSubject;
		$MailConfig['MailContent'] 	= $MailContent;
		$this->SendMailTo($MailConfig);
	}
	/* Email template for reset password details - created by Karthik K on 01 Dec, 2014 */
	public function SendMailToResetPassword($ReturnConfig=array())
	{
		$MailSubject	= SITE_NAME." - Forgot Password";
		$DisplayContent	= "<p>Please click following link to reset your password : ".$ReturnConfig['ResetLink']."</p>";
		$ContentFind	= array('{{NAME}}','{{TIME}}','{{CONTENT}}');
		$DisplayName	= "Dear ".$ReturnConfig['UserFullName'].', ';
		$ContentReplace	= array($DisplayName,date('d M, Y'),$DisplayContent);
		$GetTemplate 	= $this->load->view('email_templates/CommonEmailTemplate','',true);
		$MailContent	= str_ireplace($ContentFind,$ContentReplace,$GetTemplate);
		$MailConfig['ToEmail'] 		= $ReturnConfig['Email'];
		$MailConfig['MailSubject'] 	= $MailSubject;
		$MailConfig['MailContent'] 	= $MailContent;
		$this->SendMailTo($MailConfig);
	}
	/* Email template for reset password details - created by Karthik K on 01 Dec, 2014 */
	public function SendMailToPasswordChanged($ReturnConfig=array())
	{
		$MailSubject	= SITE_NAME." - Password Changed";
		$DisplayContent	= "<p>Your password has been changed successfully</p>";
		$ContentFind	= array('{{NAME}}','{{TIME}}','{{CONTENT}}');
		$DisplayName	= "Dear ".$ReturnConfig['UserFullName'].', ';
		$ContentReplace	= array($DisplayName,date('d M, Y'),$DisplayContent);
		$GetTemplate 	= $this->load->view('email_templates/CommonEmailTemplate','',true);
		$MailContent	= str_ireplace($ContentFind,$ContentReplace,$GetTemplate);
		$MailConfig['ToEmail'] 		= $ReturnConfig['Email'];
		$MailConfig['MailSubject'] 	= $MailSubject;
		$MailConfig['MailContent'] 	= $MailContent;
		$this->SendMailTo($MailConfig);
	}
	/* Email template for send email to group members - created by Karthik K on 08 Dec, 2014 */
	public function SendMailToGroupMembers($ReturnConfig=array())
	{
		$MailSubject	= SITE_NAME.$ReturnConfig['MailSubject'];
		$DisplayContent	= $ReturnConfig['MailContent'];
		$ContentFind	= array('{{NAME}}','{{TIME}}','{{CONTENT}}');
		$DisplayName	= "Dear ".$ReturnConfig['UserFullName'].', ';
		$ContentReplace	= array($DisplayName,date('d M, Y'),$DisplayContent);
		$GetTemplate 	= $this->load->view('email_templates/CommonEmailTemplate','',true);
		$MailContent	= str_ireplace($ContentFind,$ContentReplace,$GetTemplate);
		$MailConfig['ToEmail'] 		= $ReturnConfig['Email'];
		$MailConfig['MailSubject'] 	= $MailSubject;
		$MailConfig['MailContent'] 	= $MailContent;
		$this->SendMailTo($MailConfig);
	}
	/* Email template for common content - created by Karthik K on 09 Dec, 2014 */
	public function CommonEmailTemplate($ReturnConfig=array())
	{
		$MailSubject	= SITE_NAME.$ReturnConfig['MailSubject'];
		$DisplayContent	= $ReturnConfig['MailContent'];
		$ContentFind	= array('{{NAME}}','{{TIME}}','{{CONTENT}}');
		$DisplayName	= $ReturnConfig['DisplayName'];
		$ContentReplace	= array($DisplayName,date('d M, Y'),$DisplayContent);
		$GetTemplate 	= $this->load->view('email_templates/CommonEmailTemplate','',true);
		$MailContent	= str_ireplace($ContentFind,$ContentReplace,$GetTemplate);
		$MailConfig['ToEmail'] 		= $ReturnConfig['Email'];
		$MailConfig['MailSubject'] 	= $MailSubject;
		$MailConfig['MailContent'] 	= $MailContent;
		$this->SendMailTo($MailConfig);
	}
}?>