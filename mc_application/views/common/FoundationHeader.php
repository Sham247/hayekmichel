<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 13 Oct, 2014
* Modified date : 13 Oct, 2014
* Description 	: Page contains foundation HTML framework header section
*/
$GetUserLevel		= GetUserLevel();
$GetControllerName 	= $this->router->fetch_class();
$GetSessionId 		= UserSessionId(); ?>
<!DOCTYPE html>
	<!--[if IE 8]> 	<html class="no-js lt-ie9" lang="en"> <![endif]-->
	<!--[if gt IE 8]><!--> <html class="no-jss" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8" /> 
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Cache-control" content="no-cache" >
		<meta http-equiv="expires" content="0" >
		<title>Monocle</title>  
		<link rel="stylesheet" href="<?php echo CssUrl('normalize.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('foundation.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('fgx-foundation.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('bootstrap.min.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('style.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('jquery_ui.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('custom_style.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('common_css.css'); ?>" />	
		<link rel="stylesheet" href="<?php echo CssUrl('font_awesome.min.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('slider.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('default.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('tooltip.css'); ?>" />
		<link rel="shortcut icon" href="<?php echo ImageUrl('favicon_monocle.png'); ?>">
		<script src="<?php echo JsUrl('jquery.js'); ?>"></script> 
		<script src="<?php echo JsUrl('jquery_common_function.js'); ?>"></script> 	
	</head><?php 
	if($GetControllerName == 'dashboard')
	{
		echo '<body data-ng-app="app" id="app" data-custom-background="" data-off-canvas-nav="">';
	}
	else
	{
		echo '<body>';
	}?>
	<!-- Begin Main Wrapper -->
	<div class="header-wrapper">
		<div class="main-wrapper">
			<!-- Main Navigation -->  
			<header class="row main-navigappointment-block grey-bgation">
				<div class="large-3 columns">	
					<a href="<?php echo base_url(); ?>" id="logo">
						<img src="<?php echo ImageUrl('monocle_logo.png'); ?>" alt="Monocle Logo" />
					</a>
				</div>
				<div class="large-9 columns">	
					<div class='row'>
						<div class='large-9 columns'><?php 
						if(isset($RemoveQuickSearch) == false && $GetSessionId != '')
						{
							$this->load->view('common/FormQuickSearch');
						} ?>		
						</div>
						<div class='large-3 columns'><?php 
						if($GetSessionId != '')
						{?>
							<nav class="top-bar">
								<section class="top-bar-section">
									<ul class="right">
									<li class="has-dropdown">
											<a href="javascript:;" class="active"><?php echo UserDisplayName(); ?></a>
											<ul class="dropdown">
												<li>
													<a href="<?php echo base_url('home'); ?>">
														<!-- <i class="icon-home"></i> --> Home
													</a>
												</li><?php 
												$GetModuleMenus	= $this->common_model->GetModulesMenus();
												if(isset($GetModuleMenus) && count($GetModuleMenus)>0)
												{
													foreach ($GetModuleMenus as $DisplayMenu) 
													{?>
														<li>
															<a href="<?php echo base_url($DisplayMenu->module_url); ?>">
																<i></i> <?php echo $DisplayMenu->module_name; ?>
															</a>
														</li><?php													
													}
												}
												/*if(CheckUserAccessPermission(3,false) == 1)
												{?>
													<li>
														<a href="<?php echo base_url('dashboard'); ?>">
															<i class="icon-home"></i> Dashboard
														</a>
													</li><?php 
												}
												if($GetUserLevel == 3) 
												{?>		
													<li><a href="<?php echo base_url('dashboard'); ?>">
														<i class="icon-home"></i>Dashboard</a>
													</li><?php 
												}*/?>	
												<li>
													<a href="<?php echo base_url('profile'); ?>">
														<!-- <i class="icon-user"></i> -->My Account
													</a>
												</li>
												<li><a href="<?php echo base_url('logout'); ?>">Sign out</a></li>
											</ul>
										</li>
									</ul>
								</section>
							</nav><?php 
						}?>
						</div>
					</div>
				</div>
			</header>
			<!-- Start Main Slider -->
		</div>
	</div>