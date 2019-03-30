<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 10 Nov, 2014
* Modified date : 10 Nov, 2014
* Description 	: Page contains to display no permission page
*/  
$this->load->view('common/FoundationHeader'); ?>
<!-- Main Content -->
<div class="main-wrapper">
   	<div class="row main-content">
   		<div class='UserViewRestricted'>
   			<h3>
   				Sorry, You don't have access to view this page.
   			</h3>
   			<img src='<?php echo ImageUrl('restricted.jpg'); ?>' />
   		</div>
   	</div>
</div>
<?php 
$this->load->view('common/FoundationFooter'); ?>