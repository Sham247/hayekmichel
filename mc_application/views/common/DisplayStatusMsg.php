<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 15 Oct, 2014
* Modified date : 15 Oct, 2014
* Description 	: Page contains display form success,error message details
*/
$SuccessMsg	= $this->session->flashdata('SuccessMsg');
$ErrorMsg	= $this->session->flashdata('ErrorMsg');
if($SuccessMsg != '')
{
	echo "<div class='alert alert-success'>".$SuccessMsg."</div>";
}
if($ErrorMsg != '')
{
	echo "<div class='alert alert-error'>".$ErrorMsg."</div>";
}
?>