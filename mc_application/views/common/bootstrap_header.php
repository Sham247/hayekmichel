<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 13 Oct, 2014
* Modified date : 13 Oct, 2014
* Description 	: Page contains bootstrap HTML framework header section
*/?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Monocle</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="<?php echo CssUrl('bootstrap.css'); ?>" rel="stylesheet">
		<link href="<?php echo CssUrl('main_page.css'); ?>" rel="stylesheet">
		<link href="<?php echo CssUrl('common_css.css'); ?>" rel="stylesheet">
		<link rel="shortcut icon" href="images/demo/logos/favicon-Monocle.png">
		<style type="text/css">
		.usernav
		{
			margin: 40px 10px 0 0 !important;
		}
		</style>
	</head>
	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar">
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
						<a class="brand" href="<?php echo base_url(); ?>">
							<img src="<?php echo ImageUrl('monocle_logo.png'); ?>" alt="Monocle Logo" />
						</a>
						<div class="nav-collapse">
							<ul class="nav pull-right usernav">
								<li>
									<a href="<?php echo base_url('login'); ?>" class="signup-link">
										<em>Have an account?</em> 
										<strong>Sign in!</strong>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container" id="maincontainer">
			<div class="row">
				<div class="span12"> 
				<?php $this->load->view('common/DisplayStatusMsg'); ?>