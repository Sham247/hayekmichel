<script type="text/javascript" src="<?php echo JsUrl('jquery_form_validation_plugin.js'); ?>"></script>
<script type="text/javascript">
$(function()
{
	$.FormRule({FormSelector:'#frm_profile',FormSubmit:true});
	/* Script used to show hide menu content - created by Karthik K on 01 Dec, 2014 */
	$('body').on('click','ul.ProfileMenuList li a.ProfileListLinks',function()
	{
		var ActiveMenu	= $(this).attr('show-id');
		if(ActiveMenu != '')
		{
			$('ul.ProfileMenuList li').removeClass('active');
			$(this).parent().addClass('active');
			$('.ProfileListContent').each(function()
			{
				if($(this).attr('id') == ActiveMenu)
				{
					$('#'+ActiveMenu).show();
				}
				else
				{
					$(this).hide();
				}
			});
		}
	});
});
</script>