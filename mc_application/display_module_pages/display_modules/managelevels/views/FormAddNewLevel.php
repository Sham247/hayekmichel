<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 08 Nov, 2014
* Modified date : 08 Nov, 2014
* Description 	: Page contains display add userlevel form details
*/ 
echo form_open(base_url('addgroup'),array('id'=>'FrmNewLevel','class'=>'form-horizontal FrmNewLevel','method'=>'post','validation'=>'true','autocomplete'=>'off')); ?>
	<div class='row'>
		<div class='large-8 columns large-centered'>
			<fieldset>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblLevelName'),'TxtLevelName',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					echo form_input(array('class'=>'TxtLevelName','id'=>'TxtLevelName','name'=>'TxtLevelName','jvalidation'=>'Required:true|ShowErrMsg:'.GetLangLabel('ErrLevelName').'|NewErrMsg:'.form_error('TxtLevelName')),set_value('TxtLevelName'));?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblLevelDesc'),'TxtLevelDesc',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					echo form_textarea(array('class'=>'TxtLevelDesc','id'=>'TxtLevelDesc','name'=>'TxtLevelDesc'),set_value('TxtLevelDesc'));?>
					</div>
					<div class="large-3 columns"></div>
				</div><?php 
				if($this->SuperAdmin == 1)
				{?>
					<div class="row">
						<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblState'),'SltGroupState',array('class'=>'control-label')); ?></div>
						<div class="large-5 columns">
							<div class='MultipleStateList'><?php
							if(isset($StateList) && count($StateList)>0)
							{
								foreach($StateList as $DisplayState)
								{
									$EncodedStateId = EncodeValue($DisplayState->id); ?>
									<div class='InnerStateList'><?php
									echo form_checkbox(array('name'=>'ChkGroupState[]','class'=>'ChkGroupState','id'=>'ChkGroupState'.$EncodedStateId,'value'=>$EncodedStateId));
									echo form_label($DisplayState->state_name,'ChkGroupState'.$EncodedStateId); ?>
									</div><?php 
								}
							}?>
							</div><?php 
						/*$GroupStateList['']= GetLangLabel('TxtSelectState');
						if(isset($StateList) && count($StateList)>0)
						{
							foreach($StateList as $DisplayState)
							{
								$GroupStateList[EncodeValue($DisplayState->id)] = $DisplayState->state_name;
							}
						}
						echo form_multiselect('SltGroupState[]',$GroupStateList,set_value('SltGroupState'),' class="SltGroupState" id="SltGroupState" ');*/ ?>
						</div>
						<div class="large-3 columns"></div>
					</div>
					<?php 
				}
				if(isset($LevelAccessModules) && count($LevelAccessModules)>0)
				{?>
					<div class="row">
						<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblModulePermission'),'',array('class'=>'control-label')); ?></div>
						<div class="large-5 columns"><?php 
							foreach($LevelAccessModules as $DisplayModules)
							{
								$EncodedModuleId	= EncodeValue($DisplayModules->id); ?>
								<div class='row UserMdlList'>
									<div class='large-12 columns'><?php 
										echo form_checkbox(array('class'=>'ChkLevelModules fleft','id'=>'ChkLevelModules'.$EncodedModuleId,'name'=>'ChkLevelModules[]','value'=>$EncodedModuleId));
										echo form_label($DisplayModules->module_name,'ChkLevelModules'.$EncodedModuleId,array('class'=>'control-label LblChkLevelStatus')); ?>
									</div>
								</div><?php 
							}?>
						</div>
						<div class="large-3 columns"></div>
					</div><?php 
				} ?>
				<div class="row">
					<div class='large-12 columns txt_center'>
						<a href='<?php echo base_url("viewgroups"); ?>' class='btn btn-default MarginRgt10px'><?php echo GetLangLabel('BtnCancel'); ?></a><?php 
						echo form_button(array('type'=>'submit','class'=>'BtnAddNewLevel btn','name'=>'BtnAddNewLevel','value'=>'Add Level'),GetLangLabel('BtnAddNewLevel')); ?>
					</div>
				</div>
			</fieldset>
		</div>
	</div><?php 
echo form_close(); ?>