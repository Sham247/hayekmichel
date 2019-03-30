<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 05 Nov, 2014
* Modified date : 05 Nov, 2014
* Description 	: Page contains display edit user form details
*/ 
echo form_open(base_url('edituser/'.EncodeValue($UserDetails[0]['id'])),array('id'=>'FrmEditUser','class'=>'form-horizontal FrmEditUser','method'=>'post','validation'=>'true','autocomplete'=>'off')); ?>
	<div class='row'>
		<div class='large-8 columns large-centered'>
			<fieldset>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblUserName'),'TxtUserName',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					echo form_input(array('class'=>'TxtUserName','id'=>'TxtUserName','name'=>'TxtUserName','jvalidation'=>'Required:true|IsAlpha:true|ShowErrMsg:'.GetLangLabel('ErrUserName').'|NewErrMsg:'.form_error('TxtUserName')),set_value('TxtUserName',$UserDetails[0]['name']));?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblUserEmail'),'TxtUserEmail',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					echo form_input(array('class'=>'TxtUserEmail','id'=>'TxtUserEmail','name'=>'TxtUserEmail','jvalidation'=>'Required:true|IsMail:true|ShowErrMsg:'.GetLangLabel('ErrUserEmail').'|NewErrMsg:'.form_error('TxtUserEmail')),set_value('TxtUserEmail',$UserDetails[0]['email']));?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblNewPassword'),'NewPassword',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
						$PasswordRule	= "NewErrMsg:".form_error('NewPassword');
						echo form_password(array('class'=>'NewPassword','id'=>'NewPassword','name'=>'NewPassword','placeholder'=>GetLangLabel('PhNewPassword'),'jvalidation'=>$PasswordRule));?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblNewPasswordAgain'),'CofirmNewPassword',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
						$ConfirmPassRule	= "PassMatch:NewPassword|NewErrMsg:".form_error('CofirmNewPassword');
						echo form_password(array('class'=>'CofirmNewPassword','id'=>'CofirmNewPassword','name'=>'CofirmNewPassword','jvalidation'=>$ConfirmPassRule)); ?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblUserLevel'),'SltUserLevel',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					/*$UserLevelList['']= 'Select';*/
					if(isset($UserLevels) && count($UserLevels)>0)
					{
						foreach($UserLevels as $DisplayLevel)
						{
							$UserLevelList[EncodeValue($DisplayLevel->id)] = $DisplayLevel->group_name;
						}
					}
					echo form_dropdown('SltUserLevel',$UserLevelList,EncodeValue($UserDetails[0]['group_id']),' class="SltUserLevel" id="SltUserLevel" jvalidation="Required:true|ShowErrMsg:'.GetLangLabel('ErrUserLevel').'|NewErrMsg:'.form_error('SltUserLevel').'"');?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblUserExpiryDate'),'TxtUserExpiry',array('class'=>'control-label')); ?></div>
					<div class="large-2 small-5 columns"><?php 
					$ExpireDate = '';
					if($UserDetails[0]['expire_date'] != '' && $UserDetails[0]['expire_date'] != null)
					{
						$ExpireDate	= date('d-M-Y',$UserDetails[0]['expire_date']);
					}
					echo form_input(array('class'=>'TxtUserExpiry','id'=>'TxtUserExpiry','name'=>'TxtUserExpiry','readonly'=>'true'),set_value('TxtUserExpiry',$ExpireDate));?>
					</div>
					<div class="large-6 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns hide-for-small'>&nbsp;</div>
					<div class="large-5 columns"><?php 
					$AddChecked = '';
					if($UserDetails[0]['user_status'] == 0)
					{
						$AddChecked = true;
					}
					echo form_checkbox(array('class'=>'ChkUserStatus fleft','id'=>'ChkUserStatus','name'=>'ChkUserStatus','value'=>'0'),'',$AddChecked);
					echo form_label(GetLangLabel('LblUserDeactive'),'ChkUserStatus',array('class'=>'control-label LblChkUserStatus')); ?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-12 columns txt_center'>
						<a href='<?php echo base_url('viewusers'); ?>' class='btn btn-default MarginRgt10px'>Cancel</a><?php 
						echo form_button(array('type'=>'submit','class'=>'updateuserdetails btn','name'=>'updateuserdetails','value'=>'Save Changes'),GetLangLabel('BtnSaveProfile')); ?>
					</div>
				</div>
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