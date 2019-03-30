<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Monoclepageerror extends MX_Controller
{
	/* Method used to display added user levels - created by Karthik K on 06 Nov, 2014 */
	function Monoclepageerror()
	{
		parent::__Construct();
	}
	public function index()
	{
		$Result['RemoveQuickSearch']	= "Remove";
		$Result['RemoveAboutUs']		= "Remove";
		$this->load->view('index',$Result);
	}
} ?>