<?php 
$MethodName	= $this->router->fetch_method();
 ?>
<script type="text/javascript" src="<?php echo JsUrl('jquery_form_validation_plugin.js'); ?>"></script>
<script type="text/javascript">
$(function()
{
	<?php 
	if($MethodName == 'resetuserpass')
	{?>
		/* Function used to form validation -  creatd by Karthik K on 28 Nov, 2014 */
		$.FormRule({FormSelector:'#FrmResetPassword',FormSubmit:true});<?php 
	}
	else 
	{?>
		/* Function used to form validation -  creatd by Karthik K on 28 Nov, 2014 */
		$.FormRule({FormSelector:'#frm_login',FormSubmit:true,ShowErrMsg:false});
		/* Script to display forgot password form section - created by Karthik K on 28 Nov, 2014 */
		$('body').on('click','#frm_login a#forgotlink',function()
		{
			$('#frm_login').hide();
			$('#FrmForgotPass').show();
		});
		/* Script to validation forgot password form - created by Karthik K on 01 Dec, 2014 */
		$('body').on('submit','form#FrmForgotPass',function()
		{
			var FormObj			= $(this);
			var FrmForgotStatus = $.FormRule({FormSelector:'#FrmForgotPass',FormSubmit:false,ShowErrMsg:false});
			if(FrmForgotStatus)
			{
				$('button',FormObj).attr('disabled','disabled');
				var FormData 	= FormObj.serialize();
				var SendRequest	= $.fn.SendServerRequest({SendUrl:'<?php echo base_url("forgotpass"); ?>',SentData:FormData,DataType:'JSON'});
				SendRequest.success(function(GetResponse)
				{
					if(GetResponse.Status == 'Success')
					{
						$('div.alert-error').remove();
						if($('div.alert-success').length == 0)
						{
							$('div.display_page_content div.main').before("<div class='alert alert-success'>"+GetResponse.Msg+"</div>");
						}
						else
						{
							$('div.alert-success').text(GetResponse.Msg);
						}
					}
					else
					{
						$('div.alert-success').remove();
						if($('div.alert-error').length == 0)
						{
							$('div.display_page_content div.main').before("<div class='alert alert-error'>"+GetResponse.Msg+"</div>");
						}
						else
						{
							$('div.alert-error').text(GetResponse.Msg);
						}
					}
					$.fn.HideAlertMsg();
					$('button',FormObj).removeAttr('disabled');
					FormObj[0].reset();
				});
			}
		});
		/* Script to display forgot password form section - created by Karthik K on 28 Nov, 2014 */
		$('body').on('click','#FrmForgotPass button#BtnGoToLogin',function()
		{
			$('#frm_login').show();
			$('#FrmForgotPass').hide();
		});<?php 
	}?>
});
</script>