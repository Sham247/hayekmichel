<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 15 Oct, 2014
* Modified date : 15 Oct, 2014
* Description 	: Page contains quick search form section
*/ 
$GetStateId		= set_value('state_id');
$GetZipcode		= set_value('zipcode');
$AddDisabled	= '';
if($GetStateId == '')
{
	$AddDisabled 	= "disabled='disabled'"; 
	$GetZipcode		= '';
}
if(isset($StateList) && count($StateList)>0)
{
	echo form_open(base_url('home'),array('method'=>'get','autocomplete'=>'off','id'=>'frm_quick_search','name'=>'frm_quick_search','class'=>'ShowSearchPageForm')); ?>
		<div class="row">
			<div class="large-3 columns"><?php 
		    	$StateOptions['']	= 'Select State';
	    		foreach($StateList as $StateValues)
	    		{
	    			$StateOptions[$StateValues->id]	= $StateValues->state_name;
	    		}
		    	echo form_dropdown('state_id',$StateOptions,$GetStateId," class='state_id' id='state_id' "); ?>
		    </div>
			<div class="large-2 columns"><?php 
		    echo form_input(array('placeholder'=>GetLangLabel('PhZipcode'),'maxlength'=>'5','name'=>'zipcode','class'=>'zipcode','id'=>'zipcode'),$GetZipcode,$AddDisabled); ?>
		    </div>
		    <div class="large-5 columns"><?php 
		    	echo form_label(GetLangLabel('LblFindByDoctor'),'',array('class'=>'quick_search_lbl'));
		    	echo form_input(array('placeholder'=>GetLangLabel('PhFindByDoctor'),'name'=>'search_name','class'=>'search_name','id'=>'search_name'),set_value('search_name')); ?>
		    </div>
		    <div class="large-2 columns quick_search_crtl"><?php 
		    echo form_button(array('type'=>'submit','class'=>'btn_quick_search','id'=>'btn_quick_search','name'=>'search','value'=>'search'),GetLangLabel('BtnQuickSearch')); ?>
		    </div>
		</div><?php 
	echo form_close();
} ?>