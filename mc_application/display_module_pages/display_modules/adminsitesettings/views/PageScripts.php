<?php
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 27 Nov, 2014
* Modified date : 27 Nov, 2014
* Description 	: Page contains admin site settings page script details
*/
$JsonStateData	= '';
if(isset($EncodedStateData) && $EncodedStateData != '')
{
	$JsonStateData	= $EncodedStateData;
} ?>
<script type="text/javascript" src="<?php echo JsUrl('jquery_form_validation_plugin.js'); ?>"></script>
<script type="text/javascript">
$(function()
{
	var JsonStateData	= <?php echo $JsonStateData; ?>;
	/* Fucntion used to form validation -  creatd by Karthik K on 07 Nov, 2014 */
	$('body').on('submit','form#FrmStateMetrics',function()
	{
		var MetricFormObj = $(this);
		var GetFormStatus = $.FormRule({FormSelector:MetricFormObj,FormSubmit:false});
		if(GetFormStatus)
		{
			var BtnObj	= $('button#BtnUpdateStateMetric',MetricFormObj);
			BtnObj.attr('disabled','disabled');
			GetFormData	= MetricFormObj.serialize();
			var SendRequest		= $.fn.SendServerRequest({SendUrl:'<?php echo base_url("statemetrics"); ?>',SentData:GetFormData});
			SendRequest.success(function(GetResponse)
			{
				
			});
		}
	});
	/* Script to change state metrics - created by Karthik K on 27 Nov, 2014 */
	$('body').on('change','form#FrmStateMetrics #SltStateMetric',function()
	{
		var StateId 	= $(this).val();
		if(StateId != '')
		{
			$.each(JsonStateData,function(GetKey,GetVal)
			{
				if(GetVal.StateId == StateId)
				{
					$('form#FrmStateMetrics #TxtPhysicianRanked').val(GetVal.PhyRanked).removeClass('fieldError');
					$('form#FrmStateMetrics #TxtStateCovered').val(GetVal.StateCovered).removeClass('fieldError');
					$('form#FrmStateMetrics .JValidationErr').remove();
					/*ranked_text		= get_val.RankedText;
					covered_text	= get_val.CoveredText;
					state_img		= get_val.StateImg;*/
				}
			});
		}
		else
		{
			$('form#FrmStateMetrics #TxtPhysicianRanked').val('');
			$('form#FrmStateMetrics #TxtStateCovered').val('');
		}
	});
});
</script>