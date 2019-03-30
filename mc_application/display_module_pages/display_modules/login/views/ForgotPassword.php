<?php 
echo form_open('forgotpass',array('method'=>'post','class'=>'form normal-label FrmForgotPass','id'=>'FrmForgotPass','validation'=>'true','autocomplete'=>'off')); ?>
<fieldset>
	<h4 class='txt_center'>Forgot Password</h4>
	<div class="row login_fields">
		<div class='large-12 columns'><?php 
			echo form_label(GetLangLabel('LblLoginUsernameEmail'),'TxtForgotUser',array('class'=>'login-label')); ?>
		</div>
		<div class="large-12 columns"><?php 
			echo form_input(array('class'=>'TxtForgotUser','id'=>'TxtForgotUser','name'=>'TxtForgotUser','jvalidation'=>'Required:true'),set_value('TxtForgotUser')); ?>
		</div>
	</div>
</fieldset>
<div class='row'>
	<div class='large-12 columns'>
		<div class='fright'><?php 
			echo form_button(array('class'=>'btn BtnGoToLogin btn-default MarginRgt10px','name'=>'BtnGoToLogin','id'=>'BtnGoToLogin','value'=>GetLangLabel('BtnGoToLogin')),GetLangLabel('BtnGoToLogin'));
			echo form_button(array('type'=>'submit','class'=>'btn BtnForgotPass btn-primary','name'=>'BtnForgotPass','id'=>'BtnForgotPass','value'=>GetLangLabel('BtnForgotPass')),GetLangLabel('BtnForgotPass')); ?>
		</div>
	</div>
</div><?php 
echo form_close(); ?>