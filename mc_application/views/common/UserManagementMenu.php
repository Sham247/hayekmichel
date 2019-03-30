<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 06 Nov, 2014
* Modified date : 06 Nov, 2014
* Description 	: Page contains display added users list
*/
$ControllerName		= $this->router->fetch_class();
$GetMethodName		= $this->router->fetch_method();
$UserMenuActive 	= '';
$LevelMenuActive	= '';
$ReportsMenuActive 	= '';
$SendMailMenuActive	= '';
$SettingsMenuActive	= '';
$ActiveClassName	= "class='active'";
if($ControllerName == 'manageusers')
{
	$UserMenuActive = $ActiveClassName;
}
else if($ControllerName == 'managelevels')
{
	$LevelMenuActive = $ActiveClassName;
}
else if($ControllerName == 'common')
{
	if($GetMethodName == 'viewreports')
	{
		$ReportsMenuActive = $ActiveClassName;
	}
	else if($GetMethodName == 'sendmailtouserlevels')
	{
		$SendMailMenuActive = $ActiveClassName;
	}
}
else if($ControllerName == 'adminsitesettings')
{
	$SettingsMenuActive = $ActiveClassName;
}
 ?>
<ul class="nav nav-tabs">
	<li <?php echo $UserMenuActive; ?>>
		<a href="<?php echo base_url('viewusers'); ?>"><span class='monocle_icons img_list'></span><?php echo GetLangLabel('MenuUsers'); ?></a>
	</li>
	<li <?php echo $LevelMenuActive; ?>>
		<a href="<?php echo base_url('viewgroups'); ?>"><span class='monocle_icons img_list'></span><?php echo GetLangLabel('MenuGroups'); ?></a>
	</li><?php /*
	<li <?php echo $ReportsMenuActive; ?>>
		<a href="<?php echo base_url('reports'); ?>"><span class='monocle_icons img_report'></span>Reports</a>
	</li> */ ?>
	<li <?php echo $SendMailMenuActive; ?>>
		<a href="<?php echo base_url('sendmailto'); ?>"><span class='monocle_icons img_mail'></span><?php echo GetLangLabel('MenuSendMail'); ?></a>
	</li><?php 
	/*if(CheckUserAccessPermission(2,false) == 1)
	{?>
		<li <?php echo $SettingsMenuActive; ?>>
			<a href="<?php echo base_url('sitesettings'); ?>"><span class='monocle_icons img_settings'></span><?php echo GetLangLabel('MenuSiteSettings'); ?></a>
		</li><?php 
	}*/?>
</ul>