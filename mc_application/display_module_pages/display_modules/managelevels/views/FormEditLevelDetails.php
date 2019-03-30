<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 05 Nov, 2014
* Modified date : 05 Nov, 2014
* Description 	: Page contains display edit userlevel form details
*/ 
echo form_open(base_url('editgroup/'.EncodeValue($LevelDetails[0]->id)),array('id'=>'FrmEditLevel','class'=>'form-horizontal FrmEditLevel','method'=>'post','validation'=>'true','autocomplete'=>'off')); ?>
	<div class='row'>
		<div class='large-8 columns large-centered'>
			<fieldset>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblLevelName'),'TxtLevelName',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					echo form_input(array('class'=>'TxtLevelName','id'=>'TxtLevelName','name'=>'TxtLevelName','jvalidation'=>'Required:true|ShowErrMsg:'.GetLangLabel('ErrLevelName').'|NewErrMsg:'.form_error('TxtLevelName')),set_value('TxtLevelName',$LevelDetails[0]->group_name));?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblLevelDesc'),'TxtLevelDesc',array('class'=>'control-label')); ?></div>
					<div class="large-5 columns"><?php 
					echo form_textarea(array('class'=>'TxtLevelDesc','id'=>'TxtLevelDesc','name'=>'TxtLevelDesc'),set_value('TxtLevelDesc',$LevelDetails[0]->description));?>
					</div>
					<div class="large-3 columns"></div>
				</div>
				<div class="row">
					<div class='large-4 columns hide-for-small'>&nbsp;</div>
					<div class="large-5 columns"><?php 
					$AddChecked = '';
					if($LevelDetails[0]->group_status == 0)
					{
						$AddChecked = true;
					}
					echo form_checkbox(array('class'=>'ChkLevelStatus fleft','id'=>'ChkLevelStatus','name'=>'ChkLevelStatus','value'=>'0'),'',$AddChecked);
					echo form_label(GetLangLabel('LblLevelDeactive'),'ChkLevelStatus',array('class'=>'control-label LblChkLevelStatus')); ?>
					</div>
					<div class="large-3 columns"></div>
				</div><?php 
				if($this->SuperAdmin == 1)
				{?>
					<div class="row">
						<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblState'),'SltGroupState',array('class'=>'control-label')); ?></div>
						<div class="large-5 columns">
							<div class='MultipleStateList'><?php
							if(isset($GroupStateList) && count($GroupStateList)>0)
							{
								foreach($GroupStateList as $DisplayState)
								{
									$EncodedStateId = EncodeValue($DisplayState->id); ?>
									<div class='InnerStateList'><?php 
									$SetSelected	= false;
									if($DisplayState->group_state_id != '')
									{
										$SetSelected	= true;
									}
									echo form_checkbox(array('name'=>'ChkGroupState[]','class'=>'ChkGroupState','id'=>'ChkGroupState'.$EncodedStateId,'value'=>$EncodedStateId),'',$SetSelected);
									echo form_label($DisplayState->state_name,'ChkGroupState'.$EncodedStateId); ?>
									</div><?php 
								}
							}?>
							</div><?php 
						/*$DisplayGroupStateList['']	= GetLangLabel('TxtSelectState');
						$SetSelected				= array();
						if(isset($GroupStateList) && count($GroupStateList)>0)
						{
							foreach($GroupStateList as $DisplayState)
							{
								$DisplayGroupStateList[EncodeValue($DisplayState->id)] = $DisplayState->state_name;
								if($DisplayState->group_state_id != '')
								{
									$SetSelected[]	= EncodeValue($DisplayState->id);
								}
							}
						}
						echo form_multiselect('SltGroupState[]',$DisplayGroupStateList,set_value('SltGroupState',$SetSelected),' class="SltGroupState" id="SltGroupState" ');*/ ?>
						</div>
						<div class="large-3 columns"></div>
					</div><?php 
				}
				if(isset($LevelAccessModules) && count($LevelAccessModules)>0)
				{?>
					<div class="row">
						<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblModulePermission'),'',array('class'=>'control-label')); ?></div>
						<div class="large-5 columns"><?php 
							foreach($LevelAccessModules as $DisplayModules)
							{
								$EncodedModuleId	= EncodeValue($DisplayModules->id);
								$AddMdlChecked		= '';
								if($DisplayModules->is_level_view > 0)
								{
									$AddMdlChecked	= true;
								} ?>
								<div class='row UserMdlList'>
									<div class='large-12 columns'><?php 
										echo form_checkbox(array('class'=>'ChkLevelModules fleft','id'=>'ChkLevelModules'.$EncodedModuleId,'name'=>'ChkLevelModules[]','value'=>$EncodedModuleId),'',$AddMdlChecked);
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
						<a href='<?php echo base_url('viewgroups'); ?>' class='btn btn-default MarginRgt10px'>Cancel</a><?php 
						echo form_button(array('type'=>'submit','class'=>'updateleveldetails btn','name'=>'updateleveldetails','value'=>'Save Changes'),GetLangLabel('BtnSaveProfile')); ?>
					</div>
				</div>
			</fieldset>
		</div>
	</div><?php 
echo form_close(); ?>