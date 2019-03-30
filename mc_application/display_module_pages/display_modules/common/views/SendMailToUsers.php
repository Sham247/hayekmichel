<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 10 Nov, 2014
* Modified date : 10 Nov, 2014
* Description 	: Page contains display send mail form details
*/ ?>
<div class="nav_tab_content"><?php 
echo form_open(base_url('sendmailto'),array('id'=>'FrmSendMailTo','class'=>'BorderNone form-horizontal FrmSendMailTo','method'=>'post','validation'=>'true','autocomplete'=>'off')); ?>
	<div class='row'>
		<div class='large-8 columns large-centered'>
			<fieldset>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblMailToLevel'),'SltMailToLevel',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					$UserLevelList['']= GetLangLabel('TxtSelectGroup');
					if(isset($UserLevels) && count($UserLevels)>0)
					{
						foreach($UserLevels as $DisplayLevel)
						{
							$UserLevelList[EncodeValue($DisplayLevel->id)] = $DisplayLevel->group_name;
						}
					}
					echo form_multiselect('SltMailToLevel[]',$UserLevelList,set_value('SltMailToLevel'),' class="SltMailToLevel" id="SltMailToLevel" jvalidation="Required:true|ShowErrMsg:'.GetLangLabel('ErrMailToLevel').'|NewErrMsg:'.form_error('SltMailToLevel').'"'); ?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblMailSubject'),'TxtMailSubject',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					echo form_input(array('class'=>'TxtMailSubject','id'=>'TxtMailSubject','name'=>'TxtMailSubject','jvalidation'=>'Required:true|ShowErrMsg:'.GetLangLabel('ErrMailSubject').'|NewErrMsg:'.form_error('TxtMailSubject')),set_value('TxtMailSubject'));?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblMailMessage'),'TxtMailMessage',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					echo form_textarea(array('class'=>'TxtMailMessage','id'=>'TxtMailMessage','name'=>'TxtMailMessage','jvalidation'=>'Required:true|ShowErrMsg:'.GetLangLabel('ErrMailMessage').'|NewErrMsg:'.form_error('TxtMailMessage')),set_value('TxtMailMessage'));?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-12 columns txt_center'><?php 
						echo form_button(array('type'=>'submit','class'=>'BtnAddNewLevel btn','name'=>'BtnSendMailTo','value'=>'Send Mail'),GetLangLabel('BtnSendMailTo')); ?>
					</div>
				</div>
			</fieldset>
		</div>
	</div><?php 
echo form_close(); ?>
</div>