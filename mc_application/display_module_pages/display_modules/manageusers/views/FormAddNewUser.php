<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 05 Nov, 2014
* Modified date : 05 Nov, 2014
* Description 	: Page contains display edit user form details
*/ 
echo form_open(base_url('adduser'),array('id'=>'FrmNewUser','class'=>'form-horizontal FrmNewUser','method'=>'post','validation'=>'true','autocomplete'=>'off')); ?>
	<div class='row'>
		<div class='large-8 columns large-centered'>
			<fieldset>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblUserName'),'TxtUserName',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					echo form_input(array('class'=>'TxtUserName','id'=>'TxtUserName','name'=>'TxtUserName','jvalidation'=>'Required:true|IsAlpha:true|ShowErrMsg:'.GetLangLabel('ErrUserName').'|NewErrMsg:'.form_error('TxtUserName')),set_value('TxtUserName'));?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblLoginName'),'TxtLoginName',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					echo form_input(array('class'=>'TxtLoginName','id'=>'TxtLoginName','name'=>'TxtLoginName','jvalidation'=>'Required:true|ShowErrMsg:'.GetLangLabel('ErrLoginName').'|NewErrMsg:'.form_error('TxtLoginName')),set_value('TxtLoginName'));?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<?php // TODO add user password ?>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblNewPassword'),'NewPassword',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
						$PasswordRule	= "Required:true|ShowErrMsg:".GetLangLabel('ErrUserPassword')."|NewErrMsg:".form_error('NewPassword');
						echo form_password(array('class'=>'NewPassword','id'=>'NewPassword','name'=>'NewPassword','jvalidation'=>$PasswordRule));?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblNewPasswordAgain'),'CofirmNewPassword',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
						$ConfirmPassRule	= "Required:true|ShowErrMsg:".GetLangLabel('ErrUserConfirmPassword')."|PassMatch:NewPassword|NewErrMsg:".form_error('CofirmNewPassword');
						echo form_password(array('class'=>'CofirmNewPassword','id'=>'CofirmNewPassword','name'=>'CofirmNewPassword','jvalidation'=>$ConfirmPassRule)); ?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblUserEmail'),'TxtUserEmail',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					echo form_input(array('class'=>'TxtUserEmail','id'=>'TxtUserEmail','name'=>'TxtUserEmail','jvalidation'=>'Required:true|IsMail:true|ShowErrMsg:'.GetLangLabel('ErrUserEmail').'|NewErrMsg:'.form_error('TxtUserEmail')),set_value('TxtUserEmail'));?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblUserLevel'),'SltUserLevel',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					$UserLevelList['']= 'Select';
					if(isset($UserLevels) && count($UserLevels)>0)
					{
						foreach($UserLevels as $DisplayLevel)
						{
							$UserLevelList[EncodeValue($DisplayLevel->id)] = $DisplayLevel->group_name;
						}
					}
					echo form_dropdown('SltUserLevel',$UserLevelList,set_value('SltUserLevel'),' class="SltUserLevel" id="SltUserLevel" jvalidation="Required:true|ShowErrMsg:'.GetLangLabel('ErrUserLevel').'|NewErrMsg:'.form_error('SltUserLevel').'"');?>
					</div> 
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblUserExpiryDate'),'TxtUserExpiry',array('class'=>'control-label')); ?></div>
					<div class="large-2 small-4 columns"><?php 
					echo form_input(array('class'=>'TxtUserExpiry','id'=>'TxtUserExpiry','name'=>'TxtUserExpiry','readonly'=>'true'),set_value('TxtUserExpiry'));?>
					</div>
					<div class="large-6 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblSendEmailAuthentication'),'TxtUserSendEmail',array('class'=>'control-label')); ?></div>
					<div class="large-2 small-4 columns"><?php 
					echo form_checkbox(array('class'=>'TxtUserSendEmail','id'=>'TxtUserSendEmail','name'=>'TxtUserSendEmail','value'=>'SendEmail','checked'=>set_checkbox('TxtUserSendEmail','SendEmail')));?>
					</div>
					<div class="large-6 columns"></div>
				</div>
				<div class="row">
					<div class='large-12 columns txt_center'>
						<a href='<?php echo base_url('viewusers'); ?>' class='btn btn-default MarginRgt10px'><?php echo GetLangLabel('BtnCancel'); ?></a><?php 
						echo form_button(array('type'=>'submit','class'=>'BtnAddNewUser btn','name'=>'BtnAddNewUser','value'=>'Add User'),GetLangLabel('BtnAddNewUser')); ?>
					</div>
				</div><?php /*
				<div class='row'>
					<div class='large-12 columns txt_center'><?php echo GetLangLabel('MsgNoteNewUserPasswordSent'); ?></div>
				</div> */ ?>
			</fieldset>
		</div>
	</div><?php 
echo form_close(); ?>
<script type="text/javascript" src='<?php echo JsUrl('jquery_ui.js') ?>'></script>
<script type="text/javascript">
$(function()
{
	$("#TxtUserExpiry").datepicker({showAnim:'slide',changeMonth: true,changeYear: true,minDate:1,dateFormat: "dd-M-yy"});
	$('body').on("focus","#TxtUserExpiry",function()
	{
		var DateValue	= $(this).val();
		setTimeout(function()
		{
			if($('div#ui-datepicker-div .ui-datepicker-buttonpane').length == 0 && DateValue != '')
			{
				var CreateClearBtn	= "<div class='ui-datepicker-buttonpane'><button class='ui-datepicker-close ui-clear-expiredate ui-state-default ui-priority-primary ui-corner-all' type='button'>Clear</button></div>";
				$('table.ui-datepicker-calendar').after(CreateClearBtn);
			}
		},2);
	});
});
</script>