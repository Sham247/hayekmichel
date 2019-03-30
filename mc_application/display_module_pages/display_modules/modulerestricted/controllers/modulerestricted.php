<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Modulerestricted extends MX_Controller
{
	function index()
	{
		$Result['DisplayTitle']		= 'Page Restricted';
		$Result['RemoveGoToTop']	= "Remove";
		$Result['RemoveQuickSearch']	= "Remove";
		$this->load->view('ModuleRestricted',$Result);
	}
}
?>