<script type="text/javascript" src="<?php echo JsUrl('jquery_form_validation_plugin.js'); ?>"></script>
<script type="text/javascript">
$(function()
{
	/* Function used to form validation -  creatd by Karthik K on 07 Nov, 2014 */
	$.FormRule({FormSelector:'#FrmEditLevel,#FrmNewLevel',FormSubmit:true});
	// Add new userlevel form validation
	/*$('body').on('submit','form#FrmNewLevel',function()
	{
		var FormObj		= $(this);
		var GetStatus 	= $.FormRule({FormSelector:'#FrmNewLevel',FormSubmit:false});
		if(GetStatus)
		{
			$('.BtnAddNewLevel',FormObj).attr('disabled','disabled');
			var FormData	= FormObj.serialize()+'&RequestType=<?php echo EncodeValue("Ajax"); ?>';
			var SendRequest	= $.fn.SendServerRequest({SendUrl:'<?php echo base_url("addlevel"); ?>',SentData:FormData,DataType:'JSON'});
	       	SendRequest.success(function(GetResponse) 
	       	{
	       		$('.BtnAddNewLevel',FormObj).removeAttr('disabled');
	       		if(GetResponse.Status == 'error')
	       		{
	       			if(GetResponse.TxtLevelName != '')
	       			{
	       				$.FormRule.ShowFieldErrMsg({ErrField:'TxtLevelName',ShowMsg:GetResponse.TxtLevelName});
	       			}
	       		}
	       		else
	       		{
	       			$('.display_page_content .nav-tabs').before('<div class="alert alert-success">'+GetResponse.Msg+'</div>');
	       			var LevelHtml = '';
	       			var GetLevelList	= GetResponse.LevelList;
	       			$.each(GetLevelList,function(LevelKey,DisplayLevelVal)
	       			{
	       				LevelHtml	+= "<div class='row nav_row_content'>";
	       				LevelHtml	+= "<div class='large-3 columns row_field small-3'>"+DisplayLevelVal.LevelName+"</div>";
						LevelHtml   += "<div class='large-2 columns row_field hide-for-small'>"+DisplayLevelVal.TotalUsers+"</div>";
						LevelHtml 	+= "<div class='large-2 columns row_field small-3'>"+DisplayLevelVal.ActiveUsers+"</div>";
						LevelHtml 	+= "<div class='large-2 columns row_field small-3'>"+DisplayLevelVal.LevelStatus+"</div>";
						LevelHtml   += "<div class='large-3 columns row_field small-3'>";
						LevelHtml   += "<a class='monocle_icons img_edit' href='<?php echo base_url('editlevel'); ?>/"+DisplayLevelVal.LevelId+"'></a>";
						LevelHtml   += "<a class='monocle_icons img_delete' href='<?php echo base_url('deletelevel'); ?>/"+DisplayLevelVal.LevelId+"'></a>";
						LevelHtml   += "</div></div>";
	       			});
	       			$('.DynamicRowContent .DisplayAllContentList').html(LevelHtml);
	       			$.fn.HideAlertMsg();
	       			$(FormObj)[0].reset();
	       		}
	       	});
		}
	});*/
	/* Script used to display addnew user,addnew level form details - created by Karthi K on 07 Nov, 2014 */
	/*$('body').on('click','.BtnAddUserLevel ',function()
	{
		var DynamiObj = $('.DynamicShowContent');
		if(DynamiObj.is(':visible'))
		{
			$('form input:first',DynamiObj).focus();
		}
		else
		{
			DynamiObj.slideDown('slow',function()
			{
				$('form input:first',$(this)).focus();
			});
		}
	});*/
	/* Script used to hide addnew user,addnew level form details - created by Karthi K on 08 Nov, 2014 */
	/*$('body').on('click','.CloseDynamicContent',function()
	{
		$('.DynamicShowContent form')[0].reset();
		$('.DynamicShowContent').slideUp('slow',function()
		{
			$('form input,form textarea,form select',$(this)).removeClass('fieldError').val('');
			$('form .JValidationErr',$(this)).remove();
		});		
	});*/
	/* Script used to display confirm box for delete user level - created by Karthi K on 08 Nov, 2014 */
	$('body').on('click','.DeleteAddedLevel',function()
	{
		if(confirm('<?php echo GetLangLabel("Confirm_DeleteUserLevel"); ?>'))
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