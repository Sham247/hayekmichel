<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends MX_Controller 
{
	public function index()
	{
		$GetSessionId = UserSessionId();
		if(isset($GetSessionId) && $GetSessionId != '')
		{
			redirect('home');
		}
		else
		{
			$this->load->view('SignUp');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */