<script type="text/javascript" src="<?php echo JsUrl('jquery_form_validation_plugin.js'); ?>"></script>
<script type="text/javascript">
$(function()
{
	/* Fucntion used to form validation -  creatd by Karthik K on 07 Nov, 2014 */
	$.FormRule({FormSelector:'#FrmEditUser,#FrmNewUser',FormSubmit:true});
	// Add new user form validation
	/*$('body').on('submit','form#FrmNewUser',function()
	{
		var FormObj		= $(this);
		var GetStatus 	= $.FormRule({FormSelector:'#FrmNewUser',FormSubmit:false});
		if(GetStatus)
		{
			$('.BtnAddNewUser',FormObj).attr('disabled','disabled');
			var FormData	= FormObj.serialize()+'&RequestType=<?php echo EncodeValue("Ajax"); ?>';
			var SendRequest	= $.fn.SendServerRequest({SendUrl:'<?php echo base_url("adduser"); ?>',SentData:FormData,DataType:'JSON'});
	       	SendRequest.success(function(GetResponse) 
	       	{
	       		$('.BtnAddNewUser',FormObj).removeAttr('disabled');
	       		if(GetResponse.Status == 'error')
	       		{
	       			if(GetResponse.TxtUserName != '')
	       			{
	       				$.FormRule.ShowFieldErrMsg({ErrField:'TxtUserName',ShowMsg:GetResponse.TxtUserName});
	       			}
	       			if(GetResponse.TxtLoginName != '')
	       			{
	       				$.FormRule.ShowFieldErrMsg({ErrField:'TxtLoginName',ShowMsg:GetResponse.TxtLoginName});
	       			}
	       			if(GetResponse.TxtUserEmail != '')
	       			{
	       				$.FormRule.ShowFieldErrMsg({ErrField:'TxtUserEmail',ShowMsg:GetResponse.TxtUserEmail});
	       			}
	       			if(GetResponse.SltUserLevel != '')
	       			{
	       				$.FormRule.ShowFieldErrMsg({ErrField:'SltUserLevel',ShowMsg:GetResponse.SltUserLevel});
	       			}
	       		}
	       		else
	       		{
	       			$('.display_page_content .nav-tabs').before('<div class="alert alert-success">'+GetResponse.Msg+'</div>');
	       			var UserHtml 	= '';
	       			var GetUserList	= GetResponse.UserList;
	       			$.each(GetUserList,function(UserKey,DisplayUserVal)
	       			{
	       				UserHtml	+= "<div class='row nav_row_content'>";
	       				UserHtml	+= "<div class='large-2 columns row_field  hide-for-small'>";
	       				UserHtml	+= DisplayUserVal.FullName+"</div><div class='large-2 columns row_field hide-for-small'>";
	       				UserHtml	+= DisplayUserVal.Username+"</div><div class='large-2 columns small-5 row_field'>";
	       				UserHtml	+= "<a href='mailto:"+DisplayUserVal.Email+"'>"+DisplayUserVal.Email+"</a>";
	       				UserHtml	+= "</div><div class='large-2 columns small-2 row_field'>"+DisplayUserVal.LevelName+"</div>";
	       				UserHtml	+= "<div class='large-2 columns row_field  hide-for-small'>"+DisplayUserVal.UserStatus+"</div>";
	       				UserHtml	+= "<div class='large-2 columns small-3 row_field'>";
	       				UserHtml	+= "<a class='monocle_icons img_edit' href='<?php echo base_url('edituser'); ?>/"+DisplayUserVal.UserId+"'></a>";
	       				UserHtml	+= "<a class='monocle_icons img_delete' href='<?php echo base_url('deleteuser'); ?>/"+DisplayUserVal.UserId+"'></a>";
	       				UserHtml	+= "</div></div>";
	       			});
	       			$('.DynamicRowContent .DisplayAllContentList').html(UserHtml);
	       			$.fn.HideAlertMsg();
	       			$(FormObj)[0].reset();
	       		}
	       	});
		}
	});*/
	/* Script used to clear expire user date details - created by Karthik K on 25 Nov, 2014 */
	$('body').on('click','.ui-clear-expiredate',function()
	{
		$('#TxtUserExpiry').val('');
		$("#TxtUserExpiry").datepicker("hide");
	});	/* Script used to display confirm box for delete user - created by Karthi K on 08 Nov, 2014 */
	$('body').on('click','.DeleteAddedUser',function()
	{
		if(confirm('<?php echo GetLangLabel("Confirm_DeleteUser"); ?>'))
		{
			return true;
		}
		else
		{
			return false;
		}
	});
});
</script>