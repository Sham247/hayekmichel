<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 15 Oct, 2014
* Modified date : 15 Oct, 2014
* Description 	: Page contains profile form field details
*/ 
$this->load->view('common/FoundationHeader');  ?>
	<!-- Main Content -->
	<div class="main-wrapper">
   		<div class="row main-content display_page_content">
   			<?php $this->load->view('common/DisplayStatusMsg'); ?>
			<div class="tabs-left">
				<ul class="nav nav-tabs ProfileMenuList">
					<li class="active">
						<a href="javascript:;" class='ProfileListLinks' show-id='frm_profile'>
							<i class="monocle_icons img_settings"></i>Profile
						</a>
					</li>
					<li>
						<a href="javascript:;" class='ProfileListLinks' show-id='ViewUserAccessLog'>
							<i class="monocle_icons img_list"></i>Access Log
						</a>
					</li>
				</ul><?php 
				if(isset($ProfileDetails) && count($ProfileDetails)>0)
				{
					echo form_open(base_url('profile'),array('id'=>'frm_profile','class'=>'ProfileListContent form-horizontal frm_profile','method'=>'post','validation'=>'true','autocomplete'=>'off')); ?>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="usr-control">
								<div class='row'>
									<div class='large-8 columns large-centered'>
										<fieldset>
											<div class="row">
												<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblCurrentPassword'),'CurrentPass',array('class'=>'control-label')); ?></div>
												<div class="large-5 columns"><?php 
												echo form_password(array('class'=>'CurrentPass','id'=>'CurrentPass','name'=>'CurrentPass','jvalidation'=>'Required:true|ShowErrMsg:'.GetLangLabel('ErrCurrentPassword').'|NewErrMsg:'.form_error('CurrentPass')));?>
												</div>
												<div class="large-3 columns"></div>
											</div>
											<div class="row">
												<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblName'),'UserFullName',array('class'=>'control-label')); ?></div>
												<div class="large-5 columns"><?php 
													echo form_input(array('class'=>'UserFullName','id'=>'UserFullName','name'=>'UserFullName','jvalidation'=>'Required:true|IsAlpha:true|ShowErrMsg:'.GetLangLabel('ErrName').'|NewErrMsg:'.form_error('UserFullName')),set_value('UserFullName',$ProfileDetails[0]->name)); ?>
												</div>
												<div class="large-3 columns"></div>
											</div>
											<div class="row">
												<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblEmail'),'UserEmail',array('class'=>'control-label')); ?></div>
												<div class="large-5 columns"><?php 
													echo form_input(array('class'=>'UserEmail','id'=>'UserEmail','name'=>'UserEmail','jvalidation'=>'Required:true|IsMail:true|ShowErrMsg:'.GetLangLabel('ErrEmail').'|NewErrMsg:'.form_error('UserEmail')),set_value('UserEmail',$ProfileDetails[0]->email)); ?>
												</div>
												<div class="large-3 columns"></div>
											</div>
											<div class="row">
												<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblNewPassword'),'NewPassword',array('class'=>'control-label')); ?></div>
												<div class="large-5 columns"><?php 
													$PasswordRule	= "NoMatch:CurrentPass|PassMatch:CofirmNewPassword|NewErrMsg:".form_error('NewPassword');
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
										</fieldset>
									</div>
								</div>
							</div>
							<div class="row">
								<div class='large-12 columns txt_center'><?php 
									echo form_button(array('type'=>'submit','class'=>'btn','name'=>'save_profile','value'=>'Save Changes'),GetLangLabel('BtnSaveProfile')); ?>
								</div>
							</div>
						</div><?php 
					echo form_close();
				}?>
				<div class="nav_tab_content ProfileListContent" id='ViewUserAccessLog'><?php 
					if(isset($AccessLog) && count($AccessLog)>0)
					{?>
						<div class='row'>
							<div class='large-8 columns large-centered'>
								<div class='DynamicRowContent'>
									<div class='tab_row_container'>
										<div class='row nav_row_head'>
											<div class='large-1 columns row_field small-1'>S.No</div>
											<div class='large-6 columns row_field small-6'><?php echo GetLangLabel('TxtTitleLastLogin'); ?></div>
											<div class='large-5 columns row_field small-5'><?php echo GetLangLabel('TxtTitleLocation'); ?></div>
										</div>
										<div class='DisplayAllContentList'><?php 
											$LogCount = 0;
											foreach($AccessLog as $DisplayLog)
											{
												$LogCount++; ?>
												<div class='row nav_row_content'>
													<div class='large-1 columns row_field small-1'><?php echo $LogCount.'. '; ?></div>
													<div class='large-6 columns row_field small-6'>
														<?php echo date('M d, Y',$DisplayLog->logged_on).' at '.date('h:m a',$DisplayLog->logged_on); ?>
													</div>
													<div class='large-5 columns row_field small-5'><?php echo $DisplayLog->ip_address; ?></div>
												</div><?php
											}?>
										</div>
									</div>
								</div>
							</div>
						</div><?php 
					}	
					else
					{?>
						<div class='row nav_row_content'>
							<div class='large-12 columns NoRecordsMsg'>
								<?php echo GetLangLabel('MsgNoAccessLogFound'); ?>
							</div>
						</div>
						<?php
					}?>
				</div>
			</div>
		</div>
	</div><?php
$this->load->view('common/FoundationFooter'); ?>