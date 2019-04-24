<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 15 Oct, 2014
* Modified date : 15 Oct, 2014
* Description 	: Page contains foundation framework home page
*/ 
$this->load->view('common/FoundationHeader');  ?>
<script type="text/javascript">
// this code is to show the select box for the state arkansas only 
$(document).ready(function(){
	$('.hospital').hide();
	$(".ad_search_state_id").change(function(){
		var value = $('.ad_search_state_id').val();
		if(value=='4')
		{
			$('.insurance').hide();
			$('.hospital').show();
		}else
		{
			$('.insurance').show();
			$('.hospital').hide();
		}
	});
});
$(document).ready(function(){
	$("#chooseby").change(function(){
		var value = $('#chooseby').val();
		if(value=='condition')
		{
			$('.second_category_bg').hide();
			$('#sub_condtion_result').hide();
		}else
		{
			$('.second_category_bg').show();
		}
	});
});

</script>
<div class="slider-wrapper theme-default">
    <div id="slider" class="nivoSlider">
		<img src="<?php echo ImageUrl('slider/11.jpg'); ?>" data-thumb="<?php echo ImageUrl('slider/1.jpg'); ?>" alt="" title="#caption1" />
		<img src="<?php echo ImageUrl('slider/22.jpg'); ?>" data-thumb="<?php echo ImageUrl('slider/2.jpg'); ?>" alt="" title="#caption2"  />
		<img src="<?php echo ImageUrl('slider/33.jpg'); ?>" data-thumb="<?php echo ImageUrl('slider/3.jpg'); ?>" alt="" title="#caption3" />
	</div>
	<div id="caption1" class="nivo-html-caption">
	</div>
	<div id="caption2" class="nivo-html-caption">
	</div>
	<div id="caption3" class="nivo-html-caption">
	</div>
    <div class="main-wrapper app-wrapper">
		<div class="appointment-block grey-bg search_page_section">        
			<div class='row'>
				<div class="large-12 columns red">
                    <p>A Little About You ></p>&nbsp;<!-- Put appointemnt label here -->
                </div><?php /*
				<div class="large-4 columns SearchBoxBg">
					<a href='javascript:;' class='SearchMenuLink ActiveLink' show-page='appointment-contact-form'>Quick Search</a>
					<a href='javascript:;' class='SearchMenuLink' show-page='frm_quick_search'>Advanced Search</a>
                </div>	*/ ?>
			</div>    
            <div class="row">
                <div class="large-12 columns">
                    <form name="getfindcare" style='display:block' class='ShowSearchPageForm' id="appointment-contact-form" autocomplete='off'>
                        <div class="row">
                            <div class="large-3 columns PcPaddingRgt0Px">
                            	<!-- <input class="datepicker" type="text" placeholder="Date Of Birth"  name="dob" /> -->
                            	<input type="text" placeholder="Your Age *"
                                       class="required number" maxlength="3" size="4" min="5" max="50" name="age" id="age" />
                                <select name="gender" id="gender" class="required placeholder">
									<option value="">Your Gender *</option>
									<option value="M">Male</option>
									<option value="F">Female</option>
								</select>
                            </div>
                             <div class="large-3 columns PcPaddingRgt0Px"><?php
                             	$StateOptions['']	= 'Choose State';
                             	if(isset($StateList) && count($StateList)>0)
						    	{
						    		foreach($StateList as $StateValues)
						    		{
						    			$StateOptions[$StateValues->id]	= $StateValues->state_name;
						    		}
						    	}
						    	echo form_dropdown('ad_search_state_id',$StateOptions,''," class='ad_search_state_id placeholder required' id='ad_search_state_id' "); ?>
						    	<input type="text" placeholder="Preferred ZIP *" class="required number" pattern="[0-9]{5}" maxlength="5" name="preferred_zip" id="preferred_zip" disabled='disabled' />
                            </div>
                            <div class="large-3 columns PcPaddingRgt0Px">
                            	<input type="text" placeholder="Range (Miles)" id="miles" maxlength="3" class="number"  name="Range" />
                                <select name="chooseby" id="chooseby" class="placeholder">
									<option value="">Choose By *</option>
									<option value="condition">Conditions</option>
									<option value="specialty">Specialty</option>
								</select>
                            </div>
                            <div class="large-3 columns insurance"><?php 
                                $CarrierOptions['']	= 'Choose Insurance';
                                if(isset($CarrierList) && count($CarrierList)>0)
                                {
                                	foreach($CarrierList as $DisplayCarrier)
                                	{
                                		$CarrierOptions[EncodeValue($DisplayCarrier->id)] = $DisplayCarrier->state_name." : ".$DisplayCarrier->carrier_name;
                                	}
                                }
                                echo form_dropdown('carrier_id',$CarrierOptions,''," class='carrier_id' id='carrier_id' "); ?>
                            </div>
                            <div class="large-3 columns hospital">
                            <select name="is_ehc" id="is_ehc" class="placeholder ehc">
									<option value="">Choose Provider *</option>
									<option value="yes">EHC Doctors</option>
									<option value="ALL">All Doctors</option>
								</select>
							</div>
                        </div>
                    </form><?php 
                    //$this->load->view('common/FormQuickSearch'); ?>
                </div>		
            </div>
		</div> <!-- temp design -->
		<div class="clearfix"></div>
	</div>			
</div>	
<!-- End Main Slider -->
<!-- Main Content -->
<div class="main-wrapper">
   	<div class="row main-content">
		<div class="clearfix"></div>
		<!-- Fade & scale popup div start here -->
		<div class="large-12 columns">
		    <div class="row margin0px">
				<div id="search_doc_cond" class="well large-12 columns">
					<form method='post' id='frm_filter_doc_cond' class='frm_filter_doc_cond'>
					    <div id="accordion">
							  <h3 class='first_category_bg'>Condition</h3>
							  <div id="condtion_result" class='first_category_content'></div>
							   <h3 class='second_category_bg'>Primary Diagnosis</h3>
							  <div id="sub_condtion_result" class='second_category_content'></div>
							 <h3 class='third_category_bg'>Secondary Diagnosis</h3>
							  <div id="subsub_condtion_result" class='third_category_content'></div>
                            <h3 class=fourth_category_bg'>Tertiary Diagnosis</h3>
                            <div id="subsubsub_condtion_result" class='fourth_category_content'></div>

						</div>
						<div id="spinner" class="spinner" style="display:none;">
						    <img id="img-spinner" src="<?php echo ImageUrl('loading_spinner.gif'); ?>" alt="Loading"/>
						</div>
						<br>
						<input type='hidden' name='is_ehc_hidden' value="" id ='is_ehc_hidden'/>
						<input type='hidden' name='state_id_hidden' value="" id ='state_id_hidden'  />
					    <button id="disable_doc_reports" class="final_report btn" type='button' disabled='disabled'>Submit</button> 
					    <button class="search_doc_close btn btn-default" type='button'>Close</button>
					</form>
				</div>
		 	</div>
		</div>
		<!-- Compare list -->
   		<div class="large-12 columns" id='show_doc_comp_list'>
   			<div class="title-block" id ="compare_title">
				<h3>Compare List</h3>
				<div class="divider"><span></span></div>  
			</div>		
			<div class="row" id="compare_list"></div>
			<div class="row" id="compare_button">	
				<input type='hidden' name='compareids' value='' id ='compareids' autocomplete='off' />
				<div class='large-12 columns'>
					<center>
						<button class="button" id="getcompare_ids">Compare</button> 
						<button class="button" id="compare_clear">Clear</button>
					</center>
				</div>
			</div>
   		</div><?php 
   		// Display doctor list
   		$this->load->view('DisplayDoctorList'); ?>
		<!-- Doctor list end here -->
        <div class="large-12 columns HideSection">	
			<div class="title-block">
				<h3>Metrics</h3>
				<div class="divider"><span></span></div>  
			</div>		
			<div class="row" id="metrics">
				<div class="large-4 columns">
					<div class="panel callout radius ">
						<span class="float_left">
							<img src='<?php echo ImageUrl('medkit.png'); ?>' >
						</span>
						<div class="panel_right physicians_ranked">
							<?php echo $RankedText; ?>
						</div>
					</div>
				</div>       
				<div class="large-4 columns">
					<div class="panel callout radius">
						<span class="float_left">
							<img src='<?php echo ImageUrl('physicians.png'); ?>' >
						</span>
						<div class="panel_right">
							<h2>64</h2>
							<h4>Specialties Offered</h4>
						</div>
					</div>
				</div>          
				<div class="large-4 columns">               	
					<div class="panel callout radius">
						<span class="float_left">
							<img class='state_img' src='<?php echo ImageUrl("state_images/".$StateImage); ?>' >
						</span>
						<div class="panel_right state_covered">
							<?php echo $CoveredText; ?>
						</div>
					</div>                                             
				</div>                             
			</div>
		</div>
		<div class="clearfix"></div>
		<!-- Compare resule start here -->
		<div class="large-12 columns">
			<div class="row">
				<div id="fadeandscale1" class="well large-11 columns compare_list_container">
					<div class='compare_doc_content'></div>
					<div class='HosProfContainer'></div>
					<div class='compare_list_doc_content'>
						<div class="title-block">
							<h3>Compare List</h3>
							<div class="divider"><span></span></div>  
						</div>
						<form action='<?php echo base_url('generatedetails'); ?>' method='get' target='_blank' id='frm_create_doclist_pdf'>
							<div class="row" id="final_compare_report"></div>
						</form>
						<div class="row">
							<center>
								<div class="large-9 columns" id="email_onclick">
									<form id="compare_email_form">
										<div class="large-8 columns" id="email_com_des">
											<div class="large-7 columns">
												<input type="text" class="email required" placeholder="Enter Your Email ID" name="compare_email" >
											</div>
											<div class="large-4 columns email_btn_container">
												 <input type="submit" class="button" id="compare_email_submit" value="Send Email">
											</div>
										</div>
										
									</form>
								</div>
							</center>
						</div>
						<div class="row" id="compare_list_btns">
							<div class='large-12 columns'>
								<center><div id="email_status"></div></center>
								<center>
									<button id="printfunc" class="button printsavefunc">Print</button> 
									<button id="savepdf" class="button created_pdf_list">Save</button> 
									<button id="sent_as_email" class="button">Email</button>
								</center>
							</div>
						</div>
						<br>
						<button class="fadeandscale1_close btn btn-default pull-right">Close</button>
					</div>
				</div>
			 </div>
		</div>
		<div class="clearfix"></div>
	</div><!-- row main content end -->
</div><!-- main content end -->
<?php
$this->load->view('common/FoundationFooter'); ?>
