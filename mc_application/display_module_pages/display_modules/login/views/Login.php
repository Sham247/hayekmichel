<?php
$this->load->view('common/FoundationHeader');
$UsernameErr	= (form_error('username') != '')?'RedBorder':'';
$PasswordErr	= (form_error('password') != '')?'RedBorder':'';  ?>
<div class="main-wrapper">
   	<div class="row main-content display_page_content"><?php 
   		$this->load->view('common/DisplayStatusMsg'); ?>
		<div class="main large-3 columns large-centered small-8 small-centered"><?php 
		echo form_open('',array('method'=>'post','class'=>'form normal-label frm_login','id'=>'frm_login','validation'=>'true'));?>
				<fieldset>
					<h4 class='txt_center'>Sign in to Monocle</h4>
					<div class="row login_fields">
						<div class='large-12 columns'><?php 
							echo form_label(GetLangLabel('LblLoginUsername'),'username',array('class'=>'login-label')); ?>
							<span class="forgot"><a data-toggle="modal" href="javascript:;" id="forgotlink" tabindex=-1>Trouble signing in</a></span>
						</div>
						<div class="large-12 columns">
							<input class="xlarge <?php echo $UsernameErr; ?>" jvalidation="Required:true" id="username" name="username" type="text" value="<?php echo set_value('username'); ?>"/>
						</div>
					</div>
					<div class="row login_fields">
						<div class='large-12 columns'><?php 
							echo form_label(GetLangLabel('LblLoginPassword'),'password',array('class'=>'login-label')); ?>
						</div>
						<div class="large-12 columns">
							<input class="xlarge <?php echo $PasswordErr; ?>" jvalidation="Required:true" id="password" name="password" size="30" type="password"/>
						</div>
					</div>
				</fieldset>
				<div class='row'>
					<div class='large-12 columns'>
						<label class="remember float_left" for="remember">
							<input type="checkbox" id="remember" name="remember"/>
							<span class='remember_span'>Stay signed in</span>
						</label>
						<input type="submit" value="Sign in" class="btn login-submit float_right btn-primary" id="login-submit" name="login"/>
					</div>
				</div><?php 
			echo form_close();
			$this->load->view('ForgotPassword');?>
		</div>
	</div>
</div>
<?php $this->load->view('common/FoundationFooter'); ?>