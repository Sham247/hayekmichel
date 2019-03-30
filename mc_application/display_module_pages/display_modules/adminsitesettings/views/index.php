<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 27 Nov, 2014
* Modified date : 27 Nov, 2014
* Description 	: Page contains display site settings details
*/ 
$this->load->view('common/FoundationHeader');  ?>
<!-- Main Content -->
<div class="main-wrapper">
   	<div class="row main-content display_page_content"><?php 
   	$this->load->view('common/DisplayStatusMsg');
   	$this->load->view('common/UserManagementMenu');
   	$this->load->view($DisplayPage); ?>
	</div><!-- row main content end -->
</div><?php
$this->load->view('common/FoundationFooter'); ?>