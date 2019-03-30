<?php
$this->load->view('common/FoundationHeader'); ?>
<div class="main-wrapper">
   	<div class="row main-content display_page_content"><?php 
   		$this->load->view('common/DisplayStatusMsg'); ?>
		<div class="main large-3 columns large-centered small-8 small-centered"><?php 
		echo form_open('',array('method'=>'post','class'=>'form normal-label FrmResetPassword','id'=>'FrmResetPassword','validation'=>'true'));?>
				<fieldset>
					<h4 class='txt_center'>Reset Password</h4>
					<div class="row login_fields">
						<div class='large-12 columns'><?php 
							echo form_label(GetLangLabel('LblResetNewPassword'),'TxtNewPassword',array('class'=>'login-label')); ?>
						</div>
						<div class="large-12 columns"><?php 
						echo form_password(array('class'=>'TxtNewPassword','id'=>'TxtNewPassword','name'=>'TxtNewPassword','jvalidation'=>'Required:true')); ?>
						</div>
					</div>
					<div class="row login_fields">
						<div class='large-12 columns'><?php 
							echo form_label(GetLangLabel('LblResetRetypePassword'),'TxtRetypeNewPassword',array('class'=>'login-label')); ?>
						</div>
						<div class="large-12 columns"><?php 
							echo form_password(array('class'=>'TxtRetypeNewPassword','id'=>'TxtRetypeNewPassword','name'=>'TxtRetypeNewPassword','jvalidation'=>'Required:true|PassMatch:TxtNewPassword')); ?>
						</div>
					</div>
				</fieldset>
				<div class='row'>
					<div class='large-12 columns'>
						<input type="submit" value="Reset" class="btn BtnResetPass float_right btn-primary" id="BtnResetPass" name="BtnResetPass"/>
					</div>
				</div><?php 
			echo form_close();?>
		</div>
	</div>
</div>
<?php $this->load->view('common/FoundationFooter'); ?>