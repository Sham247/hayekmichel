<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 15 Oct, 2014
* Modified date : 15 Oct, 2014
* Description 	: Page contains user login form details
*/ 
$this->load->view('common/bootstrap_header'); ?>
<div class="alert alert-block alert-error no-hide register_disable">
	<h4 class="alert-heading">Registrations disabled.</h4>
	<p>Already have an account? <a href="<?php echo base_url('login'); ?>">Sign in here</a>!</p>
</div>
<?php 
$this->load->view('common/bootstrap_footer'); ?>