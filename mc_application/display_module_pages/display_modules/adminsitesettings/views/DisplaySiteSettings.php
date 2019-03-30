<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 27 Nov, 2014
* Modified date : 27 Nov, 2014
* Description 	: Page contains display site settings details
*/ ?>
<script src="<?php echo JsUrl('jquery_ui.js'); ?>"></script>
<div class="nav_tab_content">
	<div class='row'>
		<div class='large-12 columns'>
			<div id="AdmSiteSettings">
				<h3 class='SectionStateMetrics'>State Metrics</h3>
				<div class='SectionStateMetricsContent'><?php 
				echo form_open(base_url('statemetrics'),array('class'=>'BorderNone form-horizontal FrmStateMetrics','id'=>'FrmStateMetrics','validation'=>'true','autocomplete'=>'off')); ?>
				<div class='row'>
					<div class='large-8 columns large-centered'>
						<fieldset>
							<div class="row">
								<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblMetricState'),'SltStateMetric',array('class'=>'control-label')); ?></div>
								<div class="large-5 columns"><?php 
								$DisplayStateList['']= 'Select State';
								if(isset($StateList) && count($StateList)>0)
								{
									foreach($StateList as $DisplayVal)
									{
										$DisplayStateList[EncodeValue($DisplayVal->id)] = $DisplayVal->state_name;
									}
								}
								echo form_dropdown('SltStateMetric',$DisplayStateList,set_value('SltStateMetric'),' class="SltStateMetric" id="SltStateMetric" jvalidation="Required:true|ShowErrMsg:'.GetLangLabel('ErrMetricState').'|NewErrMsg:'.form_error('SltStateMetric').'"'); ?>
								</div>
								<div class="large-3 columns"></div>
							</div>
							<div class="row">
								<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblMetricPhysicianRanked'),'TxtPhysicianRanked',array('class'=>'control-label')); ?></div>
								<div class="large-5 columns"><?php 
								echo form_textarea(array('class'=>'TxtPhysicianRanked','id'=>'TxtPhysicianRanked','name'=>'TxtPhysicianRanked','jvalidation'=>'Required:true|ShowErrMsg:'.GetLangLabel('ErrPhysicianRanked').'|NewErrMsg:'.form_error('TxtPhysicianRanked')),set_value('TxtPhysicianRanked'));?>
								</div>
								<div class="large-3 columns"></div>
							</div>
							<div class="row">
								<div class='large-4 columns'><?php echo form_label(GetLangLabel('LblMetricStateCovered'),'TxtStateCovered',array('class'=>'control-label')); ?></div>
								<div class="large-5 columns"><?php 
								echo form_textarea(array('class'=>'TxtStateCovered','id'=>'TxtStateCovered','name'=>'TxtStateCovered','jvalidation'=>'Required:true|ShowErrMsg:'.GetLangLabel('ErrStateCovered').'|NewErrMsg:'.form_error('TxtStateCovered')),set_value('TxtStateCovered'));?>
								</div>
								<div class="large-3 columns"></div>
							</div>
							<div class="row">
								<div class='large-12 columns txt_center'><?php 
									echo form_button(array('type'=>'submit','class'=>'BtnUpdateStateMetric btn','id'=>'BtnUpdateStateMetric','name'=>'BtnUpdateStateMetric','value'=>GetLangLabel('BtnUpdate')),GetLangLabel('BtnUpdate')); ?>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
				<?php
				echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function()
{
		/* Enable accordian menu */
	var icons = {header: "ui-icon-circle-arrow-e",activeHeader: "ui-icon-circle-arrow-s"};
	$("#AdmSiteSettings").accordion({heightStyle: "content",icons: icons});
});
</script>