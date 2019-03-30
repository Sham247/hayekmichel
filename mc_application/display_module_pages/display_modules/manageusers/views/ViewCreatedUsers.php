<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 05 Nov, 2014
* Modified date : 05 Nov, 2014
* Description 	: Page contains display added users list
*/ 
$GetMethodName	= $this->router->fetch_method(); ?>
<div class="nav_tab_content">
	<div class='row row_content_header'>
		<div class='large-12 columns'>
			<div class='fright'>
				<a href='<?php echo base_url('adduser'); ?>' class='BtnAddUser BtnGraySmall' id='BtnAddUser'>
					<?php echo GetLangLabel('BtnAddUser'); ?>
				</a>
			</div>
		</div>
	</div><?php 
	if(($GetMethodName == 'index' && count($UserList) > 0) || ($GetMethodName == 'filteruserlist' ))
	{?>
		<div class='row'>
			<div class='large-12 columns'>
				<div class='nav_row_head_filter'>
					<h5><?php echo GetLangLabel('TabSearchUsers'); ?></h5>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='large-12 columns'><?php 
				echo form_open(base_url('filterusers'),array('class'=>'FrmFilterUserList','id'=>'FrmFilterUserList','autocomplete'=>'off')); ?>
					<div class='row'>
						<div class='large-3 columns'><?php 
							echo form_input(array('class'=>'FilTxtFullName','id'=>'FilTxtFullName','name'=>'FilTxtFullName','placeholder'=>GetLangLabel('PhFilterFullName')),set_value('FilTxtFullName',GetSessionVal('FilTxtFullName'))); ?>
						</div>
						<div class='large-3 columns'><?php 
							echo form_input(array('class'=>'FilTxtUserName','id'=>'FilTxtUserName','name'=>'FilTxtUserName','placeholder'=>GetLangLabel('PhFilterUserName')),set_value('FilTxtUserName',GetSessionVal('FilTxtUserName'))); ?>
						</div>
						<div class='large-3 columns'><?php 
							echo form_input(array('class'=>'FilTxtUserEmail','id'=>'FilTxtUserEmail','name'=>'FilTxtUserEmail','placeholder'=>GetLangLabel('PhFilterUserEmail')),set_value('FilTxtUserEmail',GetSessionVal('FilTxtUserEmail'))); ?>
						</div>
						<div class='large-3 columns'><?php 
							$UserLevelList['']	= GetLangLabel('TxtSelectGroup');
							if(isset($UserLevels) && count($UserLevels)>0)
							{
								foreach($UserLevels as $DisplayLevel)
								{
									$UserLevelList[EncodeValue($DisplayLevel->id)] = $DisplayLevel->group_name;
								}
							}
							echo form_dropdown('FilSltUserLevel',$UserLevelList,set_value('FilSltUserLevel',GetSessionVal('FilSltUserLevel')),' class="FilSltUserLevel" id="FilSltUserLevel" '); ?>
						</div>
						<div class='large-3 columns'><?php 
							echo form_dropdown('FilSltUserStatus',array(''=>GetLangLabel('TxtSelectStatus'),'1'=>GetLangLabel('StatusActive'),'0'=>GetLangLabel('StatusInActive'),'2'=>GetLangLabel('StatusExpired')),set_value('FilSltUserStatus',GetSessionVal('FilSltUserStatus'))," class='FilSltUserStatus' id='FilSltUserStatus' "); ?>
						</div>
						<div class='large-3 columns'></div>
					</div>
					<div class='row'>
						<div class='large-12 columns'>
							<div class='txt_center'>
								<a href='<?php echo base_url('viewusers'); ?>' class='btn btn-default MarginRgt10px'><?php echo GetLangLabel('BtnFilterReset'); ?></a><?php 
								echo form_button(array('type'=>'submit','class'=>'btn BtnFilterUser','id'=>'BtnFilterUser','name'=>'BtnFilterUser','value'=>GetLangLabel('BtnFilterSearch')),GetLangLabel('BtnFilterSearch')); ?>
							</div>
						</div>
					</div><?php 
				echo form_close(); ?>
			</div>
		</div><?php 
	} ?>
	<div class='DynamicRowContent'>
		<div class='tab_row_container'>
			<div class='row nav_row_head'>
				<div class='large-2 columns row_field hide-for-small'><?php echo GetLangLabel('TxtTitleName'); ?></div>
				<div class='large-2 columns row_field hide-for-small'><?php echo GetLangLabel('TxtTitleUsername'); ?></div>
				<div class='large-2 columns small-5 row_field'><?php echo GetLangLabel('TxtTitleEmail'); ?></div>
				<div class='large-2 columns small-2 row_field'><?php echo GetLangLabel('TxtTitleGroup'); ?></div>
				<div class='large-2 columns row_field  hide-for-small'><?php echo GetLangLabel('TxtTitleStatus'); ?></div>
				<div class='large-2 columns small-3 row_field'><?php echo GetLangLabel('TxtTitleActions'); ?></div>
			</div>
			<div class='DisplayAllContentList'><?php 
				if(isset($UserList) && count($UserList)>0)
				{
					foreach($UserList as $DisplayUser)
					{?>
						<div class='row nav_row_content'>
							<div class='large-2 columns row_field  hide-for-small'>
								<?php echo $DisplayUser['name']; ?>
							</div>
							<div class='large-2 columns row_field hide-for-small'>
								<?php echo $DisplayUser['username']; ?>
							</div>
							<div class='large-2 columns small-5 row_field'>
								<a href='mailto:<?php echo $DisplayUser['email']; ?>'><?php echo $DisplayUser['email']; ?></a>
							</div>
							<div class='large-2 columns small-2 row_field'><?php echo $DisplayUser['group_name']; ?></div>
							<div class='large-2 columns row_field  hide-for-small'><?php 
							$GetDateTime	= time();
							if($DisplayUser['expire_date'] != null && $DisplayUser['expire_date'] < $GetDateTime)
							{
								echo "<span class='ClrExpired'>".GetLangLabel('StatusExpired')."</span>";
							}
							else
							{
								if($DisplayUser['user_status'] == 1)
								{
									echo "<span class='ClrActive'>".GetLangLabel('StatusActive')."</span>";
								}
								else
								{
									echo "<span class='ClrInActive'>".GetLangLabel('StatusInActive')."</span>";
								}
							}?>
							</div>
							<div class='large-2 columns small-3 row_field'>
								<a href="<?php echo base_url('edituser').'/'.EncodeValue($DisplayUser['id']); ?>" class='monocle_icons img_edit McToolTip' show-tip='<?php echo GetLangLabel('TipEditUser'); ?>'></a>
								<a href="<?php echo base_url('deleteuser').'/'.EncodeValue($DisplayUser['id']); ?>" class='monocle_icons img_delete DeleteAddedUser McToolTip' show-tip='<?php echo GetLangLabel('TipDeleteUser'); ?>'></a>
								<a href="<?php echo base_url('viewlog').'/'.EncodeValue($DisplayUser['id']); ?>" class='monocle_icons img_list McToolTip' show-tip='<?php echo GetLangLabel('TipViewUserLog'); ?>'></a>
							</div>
						</div><?php
					}
				}
				else
				{?>
					<div class='row nav_row_content'>
						<div class='large-12 columns NoRecordsMsg'>
							<?php echo GetLangLabel('MsgNoCreatedUsersFound'); ?>
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