<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 05 Nov, 2014
* Modified date : 05 Nov, 2014
* Description 	: Page contains display added user level list
*/ 
$GetMethodName	= $this->router->fetch_method(); 
/*$ShowAddNewForm = '';
if(isset($AddLevelForm) && $AddLevelForm != '')
{
	$ShowAddNewForm = 'style="display:block"';
}*/?>
<div class="nav_tab_content">
	<div class='row row_content_header'>
		<div class='large-12 columns'>
			<div class='fright'>
				<a href='<?php echo base_url('addgroup'); ?>' class='BtnAddUserLevel BtnGraySmall' id='BtnAddUserLevel'>
					<?php echo GetLangLabel('BtnAddUserLevel'); ?>
				</a><?php 
			// echo form_button(array('class'=>'BtnAddUserLevel BtnGraySmall','id'=>'BtnAddUserLevel'),GetLangLabel('BtnAddUserLevel'));
			/*if(isset($UserLevelList) && count($UserLevelList)>0)
			{
				echo form_input(array('class'=>'TxtSearchUserLevel','id'=>'TxtSearchUserLevel','name'=>'TxtSearchUserLevel','placeholder'=>GetLangLabel('PhSearchUserLevel'))); ?>
				<a class='SearchAddedLevel'href="javascript:;"><i class="monocle_icons img_search"></i></a><?php 
			}*/?>
			</div>
		</div>
	</div><?php 
	if(($GetMethodName == 'index' && count($UserLevelList) > 0) || ($GetMethodName == 'filterlevellist' ))
	{?>
		<div class='row'>
			<div class='large-12 columns'>
				<div class='nav_row_head_filter'>
					<h5><?php echo GetLangLabel('TabSearchUserLevels'); ?></h5>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='large-12 columns'><?php 
				echo form_open(base_url('filtergroups'),array('class'=>'FrmFilterLevelList','id'=>'FrmFilterLevelList','autocomplete'=>'off')); ?>
					<div class='row'>
						<div class='large-3 columns'>&nbsp;</div>
						<div class='large-3 columns'><?php 
							echo form_input(array('class'=>'FilTxtLevelName','id'=>'FilTxtLevelName','name'=>'FilTxtLevelName','placeholder'=>GetLangLabel('PhFilterLevelName')),set_value('FilTxtLevelName',GetSessionVal('FilTxtLevelName'))); ?>
						</div>					
						<div class='large-3 columns'><?php 
							echo form_dropdown('FilSltLevelStatus',array(''=>GetLangLabel('TxtSelectStatus'),'1'=>GetLangLabel('StatusActive'),'0'=>GetLangLabel('StatusInActive')),set_value('FilSltLevelStatus',GetSessionVal('FilTxtLevelName'))," class='FilSltLevelStatus' id='FilSltLevelStatus' "); ?>
						</div>
						<div class='large-3 columns'></div>
					</div>
					<div class='row'>
						<div class='large-12 columns'>
							<div class='txt_center'>
								<a href='<?php echo base_url('viewgroups'); ?>' class='btn btn-default MarginRgt10px'><?php echo GetLangLabel('BtnFilterReset'); ?></a><?php 
								echo form_button(array('type'=>'submit','class'=>'btn BtnFilterLevel','id'=>'BtnFilterLevel','name'=>'BtnFilterLevel','value'=>GetLangLabel('BtnFilterSearch')),GetLangLabel('BtnFilterSearch')); ?>
							</div>
						</div>
					</div><?php 
				echo form_close(); ?>
			</div>
		</div><?php 
	}/*<div class='DynamicShowContent' <?php echo $ShowAddNewForm; ?>><?php $this->load->view('FormAddNewLevel'); ?></div>*/ ?>
	<div class='DynamicRowContent'>
		<div class='tab_row_container'>
			<div class='row nav_row_head'>
				<div class='large-3 columns row_field small-3'><?php echo GetLangLabel('TxtTitleGroupName'); ?></div>
				<div class='large-2 columns row_field hide-for-small'><?php echo GetLangLabel('TxtTitleTotalUsers'); ?></div>
				<div class='large-2 columns row_field small-3'><?php echo GetLangLabel('TxtTitleActiveUsers'); ?></div>
				<div class='large-2 columns row_field small-3'><?php echo GetLangLabel('TxtTitleStatus'); ?></div>
				<div class='large-3 columns row_field small-3'><?php echo GetLangLabel('TxtTitleActions'); ?></div>
			</div>
			<div class='DisplayAllContentList'><?php 
				if(isset($UserLevelList) && count($UserLevelList)>0)
				{
					foreach($UserLevelList as $DisplayValues)
					{?>
						<div class='row nav_row_content'>
							<div class='large-3 columns row_field small-3'><?php echo $DisplayValues->group_name; ?></div>
							<div class='large-2 columns row_field hide-for-small'><?php echo $DisplayValues->total_users; ?></div>
							<div class='large-2 columns row_field small-3'><?php echo $DisplayValues->active_users; ?></div>
							<div class='large-2 columns row_field small-3'><?php
							if($DisplayValues->group_status == 1)
							{
								echo "<span class='ClrActive'>".GetLangLabel('StatusActive')."</span>";
							}
							else
							{
								echo "<span class='ClrInActive'>".GetLangLabel('StatusInActive')."</span>";
							}?>
							</div>
							<div class='large-3 columns row_field small-3'>
								<a href='<?php echo base_url("editgroup/".EncodeValue($DisplayValues->id)); ?>' class='monocle_icons img_edit McToolTip' show-tip='<?php echo GetLangLabel('TipEditGroup'); ?>'></a><?php 
								if($DisplayValues->total_users == 0)
								{?>
									<a href='<?php echo base_url("deletegroup/".EncodeValue($DisplayValues->id)); ?>' class='monocle_icons img_delete DeleteAddedLevel McToolTip' show-tip='<?php echo GetLangLabel('TipDeleteGroup'); ?>'></a><?php 
								}?>
							</div>
						</div>
						<?php
					}
				}
				else
				{?>
					<div class='row nav_row_content'>
						<div class='large-12 columns NoRecordsMsg'>
							<?php echo GetLangLabel('MsgNoCreatedLevelsFound'); ?>
						</div>
					</div>
					<?php
				}?>
			</div>
		</div><?php 
		if(isset($Pagination) && $Pagination != '')
		{?>
			<div class='row ShowPagination'>
				<div class='large-12 columns txt_center'>
					<?php echo $Pagination; ?>
				</div>
			</div>
			<?php
		}?>
	</div>		
</div>