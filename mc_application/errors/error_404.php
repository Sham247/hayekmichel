<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 10 Nov, 2014
* Modified date : 10 Nov, 2014
* Description 	: Page contains to display no permission page
*/  
$CiObj	=& get_instance();
$CiObj->load->view('common/FoundationHeader'); ?>
<!-- Main Content -->
<div class="main-wrapper">
   	<div class="row main-content">
   		<div class='UserViewRestricted'>
   			<h3>404 Page Not Found</h3>
   			<img src='<?php echo ImageUrl('error_404.jpg'); ?>' />
   		</div>
   	</div>
</div><?php 
$CiObj->load->view('common/FoundationFooter'); ?>