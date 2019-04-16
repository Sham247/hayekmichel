<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 19 Oct, 2014
* Modified date : 19 Oct, 2014
* Description 	: Page contains home page script details
*/
$EncodedStateData	= '';
if(isset($JsonStateData) && count($JsonStateData)>0)
{
	$EncodedStateData	= json_encode($JsonStateData);
}
$EncodedCarrierData	= '';
if(isset($JsonCarrierList) && $JsonCarrierList != '')
{
	$EncodedCarrierData	= $JsonCarrierList;
}?>
<script src="<?php echo JsUrl('jquery_slider.js'); ?>"></script>
<script src="<?php echo JsUrl('jquery_form_validation.js'); ?>"></script>
<script src="<?php echo JsUrl('jquery.popup.js'); ?>"></script>
<script src="<?php echo JsUrl('jquery_ui.js'); ?>"></script>
<script type='text/javascript'>
$(window).load(function() 
{
	$('#fadeandscale1').popup({pagecontainer: '.container',transition: 'all 0.3s'});
	$('#slider').nivoSlider({ controlNav: false});	
});
var EncodedStateData	= <?php echo $EncodedStateData; ?>;
var EncodedCarrierData	= <?php echo $EncodedCarrierData; ?>;
$(function()
{
	/* Script used to restrict only numbers for zipcode field  - created by Karthik K on 08 Dec, 2014 */
    $.fn.AllowOnlyNumbers({RestrictObj:'#zipcode'});
	// Temp script
	$('body').on('click','.SearchMenuLink',function()
	{
		var VisibleFormId	= $(this).attr('show-page');
		if(VisibleFormId != '')
		{
			$('.SearchMenuLink').removeClass('ActiveLink');
			$(this).addClass('ActiveLink');
			$('.ShowSearchPageForm').each(function()
			{
				if(VisibleFormId == $(this).attr('id'))
				{
					if(VisibleFormId == 'appointment-contact-form')
					{
						$('#search_doc_cond').hide();
					}
					else if(VisibleFormId == 'frm_quick_search')
					{
						if($('input[type="radio"].condnum').length > 0)
						{
							$('#search_doc_cond').show();
						}
					}
					$('#'+$(this).attr('id')).slideUp();
				}
				else
				{
					$(this).slideDown();
				}
			});
		}	
	});
	$('button.add_doc_compare').each(function()
	{
		$(this).removeAttr('disabled');
	});
	/* Search form enable form validation */
	$( "#appointment-contact-form" ).validVal();
	/* Enable accordian menu */
	var icons = {header: "ui-icon-circle-arrow-e",activeHeader: "ui-icon-circle-arrow-s"};
	$("#accordion").accordion({heightStyle: "content",icons: icons});
	/* Enable/ Disable zipcode field */
	$("#state_id" ).change(function()
	{
		var selected_state_id 	= $(this).val();
		var ranked_text			= "<h2>18,000</h2><h4>Georgia Physicians Ranked</h4>";
		var covered_text		= "<h2>100 %</h2><h4>Of Georgia Covered</h4>";
		var state_img			= "<?php echo ImageUrl('state_images/georgia.png'); ?>";
		if(selected_state_id == '')
		{
			$( "#zipcode" ).attr('disabled','disabled').val('');
		}
		else
		{
			$( "#zipcode" ).removeAttr('disabled').val('');
			$.each(EncodedStateData,function(get_key,get_val)
			{
				if(get_val.StateId == selected_state_id && get_val.RankedText != '' && get_val.CoveredText != '')
				{
					ranked_text		= get_val.RankedText;
					covered_text	= get_val.CoveredText;
					state_img		= get_val.StateImg;
				}
			});
		}
		$('.physicians_ranked').html(ranked_text);
		$('img.state_img').attr('src',state_img);
		$('.state_covered').html(covered_text);
	});
	/* Enable autocomplete for zipcode - created by Karthik K on 17 Oct, 2014 */
	$( "#zipcode" ).autocomplete(
	{
		delay: 0,
		source:function(zip_request,zip_response)
		{
			var getStateId = $( "#state_id" ).val();
			if(getStateId != '')
			{
				$.ajax({url: "<?php echo base_url('getzipcode'); ?>",dataType: "json",method:'post',data:{state_id:getStateId,zipcode:zip_request.term},
		     	success: function(get_output) 
		     	{
		        	zip_response(get_output);
		     	}});
			}
		}
	});
	/* Enable/ Disable zipcode field */
	$("#ad_search_state_id" ).change(function()
	{
		var AdvStateId 		= $(this).val();
		var DisplayCarrierList	= "<option value=''>Choose Insurance</option>";
		if(AdvStateId == '')
		{
			$( "#preferred_zip" ).attr('disabled','disabled').val('');
			$.each(EncodedCarrierData,function(CarrierVey,CarrierVal)
			{
				DisplayCarrierList	+= "<option value='"+CarrierVal.CarrierId+"'>"+CarrierVal.StateName+" : "+CarrierVal.CarrierName+"</option>";
			});
		}
		else
		{
			$( "#preferred_zip" ).removeAttr('disabled').val('');
			$.each(EncodedCarrierData,function(CarrierVey,CarrierVal)
			{
				if(CarrierVal.StateId == AdvStateId)
				{
					DisplayCarrierList	+= "<option value='"+CarrierVal.CarrierId+"'>"+CarrierVal.CarrierName+"</option>";
				}
			});
		}
		$('select#carrier_id').html(DisplayCarrierList);
	});
	/* Enable autocomplete for advanced search zipcode - created by Kartik K on 19 Nov, 2014 */
	$("#preferred_zip").autocomplete(
	{
		delay: 0,
		source:function(zip_request,zip_response)
		{
			var getStateId = $("#ad_search_state_id" ).val();
			if(getStateId != '')
			{
				$.ajax({url: "<?php echo base_url('getzipcode'); ?>",dataType: "json",method:'post',data:{state_id:getStateId,zipcode:zip_request.term},
		     	success: function(get_output) 
		     	{
		        	zip_response(get_output);
		     	}});
			}
		}
	});
	/* Script used to restrict form submittion - created by Karthik K on 04 Nov, 2014 */
	$('#age,#miles,#preferred_zip').keypress(function(e) 
	{
 		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) 
 		{ 
			return false;
		}
	});
	/* Script tp display choosed list - modified on 19 Oct, 2014 */
	var sent_request = false;
	$('body').on('change','#chooseby',function(e) 
	{
	    e.preventDefault();
	    var form_data = $( "#appointment-contact-form" ).triggerHandler( "submitForm" );
	   	var chooseby = $("#chooseby").val();
	    if (chooseby != '' && form_data) 
	    {
	    	$('#disable_doc_reports').attr('disabled','disabled');
	    	$('#search_doc_cond').slideDown();
	    	var send_url	= "<?php echo base_url('firstcondition'); ?>";
	    	if(chooseby == "condition")
            {
            	$('#search_doc_cond').addClass('condition_category_list');
    			conditionname = "<span class='ui-accordion-header-icon ui-icon ui-icon-circle-arrow-s'></span><span>Condition</span>";
            	subconditionname = "<span class='ui-accordion-header-icon ui-icon ui-icon-circle-arrow-s'></span><span>Sub Condition</span>";
            	subsubconditionname = "<span class='ui-accordion-header-icon ui-icon ui-icon-circle-arrow-s'></span><span>Sub Sub Condition</span>";
                $('h3#ui-id-3').html(subsubconditionname);
                $('h3#ui-id-1').html(conditionname);
                $('h3#ui-id-2').html(subconditionname);
            }
            else
            {
            	$('#search_doc_cond').removeClass('condition_category_list');
            	specialtyname = "<span class='ui-accordion-header-icon ui-icon ui-icon-circle-arrow-s'></span><span>Specialty</span>";
            	subspecialtyname = "<span class='ui-accordion-header-icon ui-icon ui-icon-circle-arrow-s'></span><span>Sub Specialty</span>";
            	proceduregroup = "<span class='ui-accordion-header-icon ui-icon ui-icon-circle-arrow-s'></span><span>Procedure Group</span>";
            	$('h3#ui-id-3').html(proceduregroup);
            	$('h3#ui-id-1').html(specialtyname);
            	$('h3#ui-id-2').html(subspecialtyname);
            	send_url	= "<?php echo base_url('firstspecialty'); ?>";
            }
	    	$('#condtion_result').append("<div class='txt_center'><img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/></div>");
	        $.ajax({type: "POST",url:send_url,data: form_data,
	    	success: function( msg ) 
	        {
	        	sent_request	= true;
            	if(msg == 'fail')
            	{
            		alert("Please Try Again...!");
            	}
            	else
            	{
            		var get_div_top	= $('div.main-wrapper.app-wrapper').offset().top;
            		$("html, body").animate({ scrollTop:get_div_top}, 750);
            		$('button#disable_doc_reports').attr('disabled','disabled');
	            	$('#condtion_result,#sub_condtion_result,#subsub_condtion_result').empty();
	                $('#condtion_result').html(msg);
	                $("#accordion").accordion("option", "active", 0);
					var disable_count = 0;
		  		    $('#accordion h3').each(function()
		  		   	{
		  		   		disable_count++;
		  		   		if(disable_count > 1)
		  		   		{
		  		   			$(this).addClass('ui-state-disabled');
		  		   		}
		  		   	});
	            }
	      	}});
	    }
	    else
	    {
	    	$( "#appointment-contact-form select#chooseby").val('');
	    	$('#search_doc_cond').slideUp();
	    	$('#condtion_result,#sub_condtion_result,#subsub_condtion_result').empty();
	    	$('button#disable_doc_reports').attr('disabled','disabled');
	    	sent_request = false;
	    }
	});
	/* Script to display user gender based condition - created by Karthik on 12 Aug 2014 */
	$('body').on('change','select#gender',function()
	{
		var form_data 	= $("#appointment-contact-form" ).serialize();
	   	var chooseby 	= $("#chooseby").val();
		if($(this).val() != '' && chooseby != '' && sent_request)
		{
			var send_url	= "<?php echo base_url('firstcondition'); ?>";
			if(chooseby == "condition")
            {
    			conditionname = "<span class='ui-accordion-header-icon ui-icon ui-icon-circle-arrow-s'></span><span>Condition</span>";
            	subconditionname = "<span class='ui-accordion-header-icon ui-icon ui-icon-circle-arrow-s'></span><span>Sub Condition</span>";
            	subsubconditionname = "<span class='ui-accordion-header-icon ui-icon ui-icon-circle-arrow-s'></span><span>Sub Sub Condition</span>";
                $('h3#ui-id-3').html(subsubconditionname);
                $('h3#ui-id-1').html(conditionname);
            	$('h3#ui-id-2').html(subconditionname);
            }
            else
            {
            	specialtyname = "<span class='ui-accordion-header-icon ui-icon ui-icon-circle-arrow-s'></span><span>Specialty</span>";
            	subspecialtyname = "<span class='ui-accordion-header-icon ui-icon ui-icon-circle-arrow-s'></span><span>Sub Specialty</span>";
            	proceduregroup = "<span class='ui-accordion-header-icon ui-icon ui-icon-circle-arrow-s'></span><span>Procedure Group</span>";
            	$('h3#ui-id-3').html(proceduregroup);
            	$('h3#ui-id-1').html(specialtyname);
            	$('h3#ui-id-2').html(subspecialtyname);
            	send_url	= "<?php echo base_url('firstspecialty'); ?>";
            }
			$('#condtion_result').html("<div class='txt_center'><img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/></div>");
	   		$('#disable_doc_reports').attr('disabled','disabled');
	    	$('#search_doc_cond').slideDown();
	        $.ajax({type: "POST",url:send_url,data: form_data,
	    	success: function( msg ) 
	        {
            	if(msg == 'fail')
            	{
            		alert("Please Try Again...!");
            	}
            	else
            	{
            		var get_div_top	= $('div.main-wrapper.app-wrapper').offset().top;
            		$("html, body").animate({ scrollTop:get_div_top}, 750);
            		$('button#disable_doc_reports').attr('disabled','disabled');
	            	$('#condtion_result,#sub_condtion_result,#subsub_condtion_result').empty();
	                $('#condtion_result').html(msg);
	                $("#accordion").accordion("option", "active", 0);
					var disable_count = 0;
		  		    $('#accordion h3').each(function()
		  		   	{
		  		   		disable_count++;
		  		   		if(disable_count > 1)
		  		   		{
		  		   			$(this).addClass('ui-state-disabled');
		  		   		}
		  		   	});
	            }
	      	}});
		}
		else
		{
			$("#appointment-contact-form select#chooseby").val('');
	    	$('#search_doc_cond').slideUp();
	    	$('#condtion_result,#sub_condtion_result,#subsub_condtion_result').empty();
	    	$('button#disable_doc_reports').attr('disabled','disabled');
	    	sent_request = false;
		}
	});
	/* Script to close display search condition box - created by Karthik on 19 Oct, 2014 */
	$('body').on('click','button.search_doc_close',function()
	{
		$("#chooseby").val('');
		$('#search_doc_cond').slideUp('slow');
	    $('#condtion_result,#sub_condtion_result,#subsub_condtion_result').empty();
	    $('button#disable_doc_reports').attr('disabled','disabled');
	    sent_request	= false;
	});
	/* Script used to get sub condition list - modified on 19 Oct, 2014 */
	$('body').on('click', '.condnum', function()
	{
	    console.log('condnum = ' + $(this).val());
		var getcondno 	= $(this).val();
		var gender 		= $('#gender').val();
		$('#disable_doc_reports').removeAttr('disabled');
        $('#sub_condtion_result').html("<div class='txt_center'><img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/></div>");
        $.ajax({type: "POST",url: "<?php echo base_url('secondcondition'); ?>",data: { getcondno: getcondno,gender:gender },
            success: function( subconds )
            {
                $('#sub_condtion_result').prev().removeClass('ui-state-disabled');
                $('#sub_condtion_result').html(subconds);
                $("#accordion").accordion("option", "active", 2);
            }});
		
	});


    /* Script used to get sub condition list - was missing... 4/16/19 */
    $('body').on('click','.sub_cond_num',function()
    {
        var getcondno 	= $(this).val();
        var gender 			= $('#gender').val();
        $('#disable_doc_reports').removeAttr('disabled');
        $('#sub_condtion_result').html("<div class='txt_center'><img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/></div>");
        $.ajax({type: "POST",url: "<?php echo base_url('secondcondition'); ?>",data: { getcondno: getcondno,gender:gender },
            success: function( subconds )
            {
                $('#sub_condtion_result').prev().removeClass('ui-state-disabled');
                $('#sub_condtion_result').html(subconds);
                $("#accordion").accordion("option", "active", 2);
            }});
    });



	/* Script used to get sub sub condition list - modified on 19 Oct, 2014 */
	$('body').on('click','.subsub_cond_num',function()
	{
		var getsubcondno 	= $(this).val();
		var gender 			= $('#gender').val();
		$('#disable_doc_reports').removeAttr('disabled');
		$('#subsub_condtion_result').html("<div class='txt_center'><img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/></div>");
		$.ajax({type: "POST",url: "<?php echo base_url('thirdcondition'); ?>",data: { getsubcondno: getsubcondno,gender:gender },
	    success: function( subsubconds )
	    {
	    	$('#subsub_condtion_result').prev().removeClass('ui-state-disabled');
            $('#subsub_condtion_result').html(subsubconds);
			$("#accordion").accordion("option", "active", 2);						                    
	    }});
	});
	/* Script used to get specialty list - modified on 19 Oct, 2014 */
	$('body').on('click', '.specnum', function()
	{
		var getspecial =  $(this).val();
		$('#sub_condtion_result').html("<div class='txt_center'><img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/></div>");
		$.ajax({type: "POST",url: "<?php echo base_url('secondspecialty'); ?>",data: { getspecial: getspecial },
	 	success: function(special)
	 	{
	 		$('#disable_doc_reports').removeAttr('disabled');
	 		$('#sub_condtion_result').prev().removeClass('ui-state-disabled');
	 		$('#subsub_condtion_result').empty();
	    	$('#sub_condtion_result').html(special);
			$("#accordion").accordion("option", "active", 1);		
			var disable_count = 0;
  		    $('#accordion h3').each(function()
  		   	{
  		   		disable_count++;
  		   		if(disable_count > 2)
  		   		{
  		   			$(this).addClass('ui-state-disabled');
  		   		}
  		   	});		                
     	}});
	});
	/* Script used to get sub sub specialty list - modified on 19 Oct 2014*/
	$('body').on('click','.subspecial_chkCond',function()
	{
		var getsubspecial =  $(this).val();
		$('#disable_doc_reports').removeAttr('disabled');
		$('#subsub_condtion_result').html("<div class='txt_center'><img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/></div>");
		$.ajax({type: "POST",url: "<?php echo base_url('thirdspecialty'); ?>",data: { getsubspecial: getsubspecial },
	  	success: function( progroup )
	  	{
	  		$('#subsub_condtion_result').prev().removeClass('ui-state-disabled');
	    	$('#subsub_condtion_result').html(progroup);
			$("#accordion").accordion("option", "active", 2);	
	   	}});
	});
	/* Script used to display more list for doctors - created by Karthik K on 05 Nov, 2014 */
	$('body').on('click','button#more_doc', function()
	{
		var btn_more_obj	= $(this);
		btn_more_obj.attr('disabled','disabled').text('Loading...');
		var is_ehc_hidden = $("#is_ehc_hidden").val();
		var state_id_hidden = $("#state_id_hidden").val();
		var list_sort_by		= $('#list_sort_by').val();
		/*var get_form_data	= $('#appointment-contact-form').serialize();
		get_form_data		+= '&'+$('#frm_filter_doc_cond').serialize()+'&list_count='+$('div.doctor_display_list').length+'&list_sort_by='+$('select#list_sort_by').val();*/
		var get_form_data	= 'list_count='+$('div.doctor_display_list').length+'&is_ech_hidden='+is_ehc_hidden+'&state_id_hidden='+state_id_hidden+'&list_sort_by='+list_sort_by;
		var SendRequest		= $.fn.SendServerRequest({SendUrl:'<?php echo base_url("getlist"); ?>',SentData:get_form_data});
		SendRequest.success(function(GetResponse)
		{
			btn_more_obj.removeAttr('disabled').html('More List &gt;&gt;');
			$.fn.append_doc_list({doctor_list:GetResponse});
		});
	});
	/* Script to get searched doctor list - created by Karthik K on 05 Nov, 2014 */
	var display_order_id	= '';
	$('.final_report').click(function()
	{
		var final_report_obj		= $(this);
		var check_form_validation 	= $( "#appointment-contact-form" ).triggerHandler( "submitForm" );
		if(check_form_validation)
		{
			$('#doctor_list').empty();
			$('#show_doc_comp_list,#total_doc_list').hide();
			$('#compareids').val('');
			$('#spinner').show();
			var conditionname 		= $('input[name=conditionname]:checked').val();
			var subconditionname 	= $('input[name=subconditionname]:checked').val();
			var subsubconditionname = $('input[name=subsubconditionname]:checked').val();
			var specialno 			= $('input[name=specialtyname]:checked').val();
			var getsubspecial 		= $('input[name=subspecialtyname]:checked').val();
			var controlnum 			= $('input[name=proceduregroup]:checked').val();
			var get_zipcode			= $('input[name=preferred_zip]').val();
		    var get_isehc			= $('select.ehc').val();
			var get_miles			= $('input[name=Range]').val();
			var carrierid			= $('select.carrier_id').val();
			var getStateId          = $("#ad_search_state_id" ).val();
			var display_title		= 'Doctor List - ';


			$('input[name="is_ehc_hidden"]').val(get_isehc);
			$('input[name="state_id_hidden"]').val(getStateId);

			
			if($('input[name=conditionname]:checked').length > 0)
			{
				if($('input[name=subsubconditionname]:checked').length > 0)
				{
					display_title += $('label[for="subsubcond_chkCond_'+subsubconditionname+'"]').text();
				}
				else if($('input[name=subsubconditionname]:checked').length > 0)
				{
					display_title += $('label[for="sub_chkCond_'+subconditionname+'"]').text();
				}
				else
				{
					display_title += $('label[for="phBreadCrumb_chkCond_'+conditionname+'"]').text();
				}
			}
			else
			{
				if($('input[name=proceduregroup]:checked').length > 0)
				{
					display_title += $('label[for="proce_group_chkCond_'+controlnum+'"]').text();
				}
				else if($('input[name=subspecialtyname]:checked').length > 0)
				
				{
					display_title += $('label[for="subspecial_chkCond_'+getsubspecial+'"]').text();
				}
				else
				{
					display_title += $('label[for="specialty_chkCond_'+specialno+'"]').text();

				}
			}
			var get_posting_data	= '';
			var selected_type		= '';
			if ( typeof(conditionname) !== "undefined" && conditionname !== null ) 
			{
				if(typeof(subsubconditionname) !== "undefined")
				{
					subsubconditionname	= subsubconditionname;
				}
				else
				{
					subsubconditionname	= '';
				}
				if(typeof(subconditionname) !== "undefined")
				{
					subconditionname	= subconditionname;
				}
				else
				{
					subconditionname	= '';
				}
				get_posting_data	= "conditionname="+conditionname+"&subconditionname="+subconditionname+"&subsubconditionname="+subsubconditionname+"&Range="+get_miles+"&preferred_zip="+get_zipcode+"&state_id="+getStateId+"&is_ehc="+get_isehc+'&carrier_id='+carrierid;
				selected_type		= 1;
			}
			else if ( typeof(specialno) !== "undefined" && specialno !== null ) 
			{
				if(typeof(getsubspecial) !== "undefined")
				{
					getsubspecial	= getsubspecial;
				}
				else
				{
					getsubspecial	= '';
				}
				if(typeof(controlnum) !== "undefined")
				{
					controlnum	= controlnum;
				}
				else
				{
					controlnum	= '';
				}
				get_posting_data	= "specialtyname="+specialno+"&subspecialtyname="+getsubspecial+"&proceduregroup="+controlnum+"&Range="+get_miles+"&preferred_zip="+get_zipcode+"&state_id="+getStateId+"&is_ehc="+get_isehc+'&carrier_id='+carrierid;
				selected_type		= 0;
			}
			display_order_id	= '';
			final_report_obj.attr('disabled','disabled');
			$('div#show_doc_comp_list #compare_list').empty();
			$('#doctor_title h3.search_doc_cond_title').text(display_title);
			var send_request		= $.fn.SendServerRequest({SendUrl:'<?php echo base_url("getlist"); ?>',SentData:get_posting_data});
			send_request.success(function(get_doc_list)
			{
				$('#total_doc_list').show();
				final_report_obj.removeAttr('disabled');
				$('#doctor_title').css('display','block');
				if(get_doc_list == '' || get_doc_list == 'fail' || get_doc_list == 'error')
				{
					$('#more_doc').css('display','none');
				}
				if(selected_type == 1)
				{
					$.fn.append_doc_list({doctor_list:get_doc_list,get_report:true});
				}
				else
				{
					$.fn.append_doc_list({doctor_list:get_doc_list,get_report:true,category:''});
				}
				$("html, body").animate({ scrollTop: $('#total_doc_list').offset().top }, 750);
	            $('#fadeandscale').popup('hide');
	            $('#spinner').hide();
	            $('select#list_sort_by').val('desc');
			});
		}
	});
	/* Script used to sort doctor list using quality or efficiency - created by Karthik on 26 Aug, 2014 */
	var SortBySend = false;
	$('select#list_sort_by').on('change',function()
	{
		var get_sort_by = $(this).val();
		if($('#more_list_div button.more_doc').length == 0 && $('h3.inner_doc_category').length == 0)
		{
			var sort_by_obj = $("#doctor_list div.doctor_display_list");
			$('#doctor_list').append('<div class="doc_sort_loading"><img id="img-spinner" src="<?php echo ImageUrl("loading_spinner.gif"); ?>" alt="Loading"/>');	
			if(get_sort_by == 'quality')
			{
				sort_by_obj.sort(function(a,b)
				{
					var first_val = parseInt($(a).attr('data-quality'),10);
					var second_val = parseInt($(b).attr('data-quality'),10);
					if(first_val < second_val)
					{
						return 1;
					}
					else if(first_val > second_val)
					{
						return -1;
					}
					else
					{
						return 0;
					}
				}).appendTo('#doctor_list');
			}
			else if(get_sort_by == 'efficiency')
			{
				sort_by_obj.sort(function(a,b)
				{
					var first_val = parseInt($(a).attr('data-efficiency'),10);
					var second_val = parseInt($(b).attr('data-efficiency'),10);
					if(first_val < second_val)
					{
						return 1;
					}
					else if(first_val > second_val)
					{
						return -1;
					}
					else
					{
						return 0;
					}
				}).appendTo('#doctor_list');
			}
			else
			{
				sort_by_obj.sort(function(a,b)
				{
					var first_val = parseInt($(a).attr('data-quality')*$(a).attr('data-efficiency'),10);
					var second_val = parseInt($(b).attr('data-quality')*$(b).attr('data-efficiency'),10);
					if(first_val < second_val)
					{
						return 1;
					}
					else if(first_val > second_val)
					{
						return -1;
					}
					else
					{
						return 0;
					}
				}).appendTo('#doctor_list');
			}
			$('.doc_sort_loading').remove();
		}
		else
		{
			if(SortBySend == false)
			{
				SortBySend = true;
				display_order_id	= '';
				$('#doctor_list').append('<div class="doc_sort_loading"><img id="img-spinner" src="<?php echo ImageUrl("loading_spinner.gif"); ?>" alt="Loading"/>');
				/*var get_form_data	= $('#appointment-contact-form').serialize();
				get_form_data		+= '&'+$('#frm_filter_doc_cond').serialize()+'&list_count=0&list_sort_by='+$('select#list_sort_by').val();*/
				var get_form_data	= 'list_sort_by='+$('select#list_sort_by').val();
				var send_request	= $.fn.SendServerRequest({SendUrl:'<?php echo base_url("getlist"); ?>',SentData:get_form_data});
				send_request.success(function(get_sort_list)
				{
					$('div#doctor_list').empty();
					$.fn.append_doc_list({doctor_list:get_sort_list});
					SortBySend = false;
				});
			}
		}
	});
	/* Script used add doctor to compare list - created by Karthik K on 19 Oct 2014 */
	$('body').on('click', '.add_doc_compare',function()
	{
		var doc_obj		= $(this);
		var get_doc_id	= doc_obj.attr('id');
		var GetTopVal   = doc_obj.offset().top;
		GetTopVal   	= parseInt(GetTopVal)-540;
		var GetLeftVal  = doc_obj.closest('ul.pricing-table').offset().left;
		GetLeftVal		= parseInt(GetLeftVal)-147;
		var doctor_name = $('#get_name_'+get_doc_id).val();
		var doctor_gender= $('#get_image_'+get_doc_id).val();
		var compareids 	 = $('#compareids').val();
		if($('div.added_doc_compare').length < 4)
		{
			var doctor_image	= "<?php echo ImageUrl('NoImageSmall.png'); ?>";
			if(doctor_gender == 'M')
			{
				doctor_image	= "<?php echo ImageUrl('MaleImageSmall.png'); ?>";
			}
			else if(doctor_gender == 'F')
			{
				doctor_image	= "<?php echo ImageUrl('FemaleImageSmall.png'); ?>";
			}
			doc_obj.text('Added').attr("disabled", "disabled");
			var AddStyle 		= "style='top:"+GetTopVal+"px;position:absolute !important;z-index:3;left:"+GetLeftVal+"px'";
			var compare_html	= "<div "+AddStyle+" class='large-3 columns clear_list added_doc_compare ShowCompAnimation' id='added_doc_compare"+get_doc_id+"'><div class='panel callout radius compare'>";
			compare_html		+= "<span class='float_left'><img src='"+doctor_image+"' alt='Doctor' /></span><div class='panel_right pos_rel'>";
			compare_html		+= "<a href='javascript:;' class='icon-remove remove_added_list' doctor-id='"+get_doc_id+"' title='Remove'></a>";
			compare_html		+= "<h4>"+doctor_name+"</h4></div></div></div>";
			AnimateLeftVal	= 0;
			if($('.added_doc_compare').length == 0)
			{
				$('#show_doc_comp_list').show();
			}
			if($('.added_doc_compare').length > 0)
			{
				AnimateLeftVal	= parseInt($('.added_doc_compare:last').offset().left)+163;
			}
            $('#compare_list').append(compare_html);
            $('.ShowCompAnimation').animate({'top':'50px','left':AnimateLeftVal+'px'},1000,function()
            {
            	$(this).removeAttr('style').removeClass('ShowCompAnimation');
            });
            var add_compared_id	= '';
            if(compareids != '' )
            {
            	var split_compared_id	= compareids.split(',');
            	for(var i=0;i<split_compared_id.length;i++)
            	{
            		add_compared_id	+= split_compared_id[i]+',';
				}
				add_compared_id += get_doc_id;
            }
            else
            {
				add_compared_id = get_doc_id;
            }
            $('#compareids').val(add_compared_id);
		}
        else
        {
        	alert('Sorry you have reached maximum limit...!');
        }
	});
	/* Script used to remove added doctor from compare list - created on 19 Oct 2014 */
	$('body').on('click', 'a.remove_added_list',function()
	{
		var remove_comp_obj	= $(this);
		var remove_id		= remove_comp_obj.attr('doctor-id');
		if(remove_id != '')
		{
			$('#added_doc_compare'+remove_id).remove();
			$('.add_doc_compare#'+remove_id).removeAttr('disabled').text('Add to Compare');
			if($('div.added_doc_compare').length == 0)
			{
				$('#show_doc_comp_list').hide();
				$('div#show_doc_comp_list #compare_list').empty();
				$('#compareids').val('');
			}
			else
			{
				var get_added_id	= $('#compareids').val();
				var split_added_id	= get_added_id.split(',');
				var add_remaining_id = $.map(split_added_id, function(val,index) 
				{
					if(val != remove_id)
					{
						var str = val;
					}
				     return str;
				}).join(",");
				$('#compareids').val(add_remaining_id);
			}
		}
	});
	/* Script used to clear compare list - created on 19 Oct 2014 */
	$('#compare_clear').on('click', function()
	{
		$('#show_doc_comp_list').hide();
		$('li.cta-button button.add_doc_compare').removeAttr("disabled").text('Add to Compare');
		$('div#show_doc_comp_list #compare_list').empty();
		$('#compareids').val('');
	});
	/* Script to display added compare list doctor list - modified on 11 Aug, 2014 */
	$('#getcompare_ids').on('click', function()
	{
		if ($('div.added_doc_compare').length != 0)
		{
			compareids = $('#compareids').val();
			$('.compare_doc_content,.HosProfContainer').hide().html('');
			$('.compare_list_doc_content').show();
			$('#email_onclick').hide();
			$('#fadeandscale1').popup('show');
			$('#compare_list_btns').hide();
			$('#final_compare_report').html("<div class='txt_center'><img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/></div>");
			var posting_data 	= "compareids="+compareids;
			var send_request	= $.fn.SendServerRequest({SendUrl:'<?php echo base_url("viewcomparelist"); ?>',SentData:posting_data});
			send_request.success(function(msg) 
			{
				if(msg == 'fail')
				{
					alert(msg);
				}
				else
				{
					$('#final_compare_report').html(msg);
					$('#fadeandscale1').parent().scrollTop(0);
					$('#compare_list_btns').show();
				}
			});
		}
	});
	$('body').on('click','button.fadeandscale1_close',function()
	{
		var ParentObj	= $('#fadeandscale1');
		$('.compare_doc_content,.HosProfContainer,.final_compare_report',ParentObj).html('');
	});
	/* Script to display compared doctor details in compare popup - created by Karthik K on 30 Sep, 2014 */
	$('body').on('click','.view_compare_doc_details',function()
	{
		var get_doctor_id	= $(this).attr('doc-id');
		var html_selector	= $('#fadeandscale1');
		$('.compare_doc_content',html_selector).show().html("<div class='txt_center'><img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/></div>");
		$('.compare_list_doc_content,.HosProfContainer',html_selector).hide();
		$.ajax({type: "POST",url: "<?php echo base_url('doctorprofile'); ?>",data: {get_id: get_doctor_id,page_type:'popup'},
	  	success: function(get_details)
	  	{
	  		$('.compare_doc_content',html_selector).html(get_details);
	  	}});
	});
	/* Script to display compared doctor worked hospital details in compare popup - created by Karthik K on 30 Oct, 2014 */
	$('body').on('click','.display_hos_pro',function()
	{
		var get_hos_id		= $(this).attr('hos-id');
		var html_selector	= $('#fadeandscale1');
		$('.compare_doc_content',html_selector).show().html("<div class='txt_center'><img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/></div>");
		$('.compare_list_doc_content,.HosProfContainer',html_selector).hide();
		$.ajax({type: "POST",url: "<?php echo base_url('hospitalprofile'); ?>",data: {hos_prof_id: get_hos_id,page_type:'popup'},
	  	success: function(get_details)
	  	{
	  		$('.compare_doc_content',html_selector).html(get_details);
	  	}});
	});
	/* Script to display hospital final list details - created by Karthik K on 04 Dec, 2014*/
	$('body').on('click','button.BtnViewHosFinal',function()
	{
		var HosProId = $(this).attr('hos-pro-id');
		var HtmlSelector	= $('#fadeandscale1');
		$('.HosProfContainer',HtmlSelector).show().html("<div class='txt_center'><img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/></div>");
		$('.compare_list_doc_content,.compare_doc_content',HtmlSelector).hide();
		var PostingData 	= "HosProId="+HosProId;
		var SendHosRequest	= $.fn.SendServerRequest({SendUrl:'<?php echo base_url("hospitalfinallist"); ?>',SentData:PostingData});
		SendHosRequest.success(function(GetOutput) 
		{
			$('.HosProfContainer',HtmlSelector).html(GetOutput);
		});
	});
	/* Display doctor compare list - created by Karthik K on 30 Sep, 2014 */
	$('body').on('click','.go_back_to_compare',function()
	{
		$('#fadeandscale1 .compare_list_doc_content').show();
		$('#fadeandscale1 .compare_list_doc_content iframe').each(function()
		{
			$(this).attr('src',$(this).attr('src'));
		});
		$('#fadeandscale1 .compare_doc_content,#fadeandscale1 .HosProfContainer').hide();
		$('#fadeandscale1_wrapper').scrollTop(0);
		setTimeout(function()
		{
			$('#fadeandscale1 .compare_doc_content,#fadeandscale1 .HosProfContainer').html('');
		},5);		
	});
	/* Script used to display hospital details - created by Karthik K on 05 Dec, 2014 */
	$('body').on('click','button.BackToHospital',function()
	{
		var HtmlSelector	= $('#fadeandscale1');
		$('.compare_doc_content',HtmlSelector).show();
		$('.HosProfContainer',HtmlSelector).hide();
		$('#fadeandscale1_wrapper').scrollTop(0);
	});
	/* Script function used to validate send email form - created by Karthik K on 06 Nov, 2014 */
	$('#compare_email_form').validVal();
	/* Script used to show send email form - created by Karthik K on 06 Nov, 2014   */
	$('#sent_as_email').on('click',function()
	{
		$('#email_onclick').show();
	});
	/* Script used to send compared doctor details via mail - created by Karthik K on 06 Nov, 2014 */
	$('#compare_email_submit').on('click', function(es) 
	{
	    es.preventDefault();
	    var email_obj = $(this);
		if ($('div.added_doc_compare').length != 0)
		{
			compareids = $('#compareids').val();
		}
	    var email_id = $( "#compare_email_form" ).triggerHandler( "submitForm" );
	    if (email_id) 
	    {
	    	email_obj.attr('disabled','disabled');
	    	var get_email_id 	= $('#compare_email_form input[name="compare_email"]').val();
	    	var posting_data 	= "email_id="+get_email_id+"&compareids="+compareids;
	    	var send_request	= $.fn.SendServerRequest({SendUrl:'<?php echo base_url("sendcomparelist"); ?>',SentData:posting_data,DataType:'JSON'});
	       	send_request.success(function(get_data) 
	       	{
	       		email_obj.removeAttr('disabled');
	       		if(get_data.status == 'error')
	       		{
	       			$('#email_status').show().html('<span class="ClsError"><b>'+get_data.msg+'</b></span>')
	       		}
	       		else
	       		{
	       			$('#email_status').html('<span class="ClsSuccess"><b>'+get_data.msg+'</b></span>').show();
	       			$('#email_com_des input[name="compare_email"]').val('');
	       			$('#email_onclick').css('display','none');
	       		}
            	$('#email_status').delay('3000').slideUp();
            });
	    }
	});
	/* Script to created pdf for compare doctor list - created by Karthik on 12 Aug, 2014 */
	$('body').on('click','button.created_pdf_list',function()
	{
		$('form#frm_create_doclist_pdf').submit();
	});
	/* Script to display print compared list list - create by Karthik on 06 Nov, 2014 */
	$('.printsavefunc').on('click', function()
	{
    	if ($('div.added_doc_compare').length != 0)
		{
			var posting_data 	= $('form#frm_create_doclist_pdf').serialize();
			window.open('<?php echo base_url("printcomparelist"); ?>?'+posting_data,'_blank');
		}
		else
		{
			alert('Please Add any other physician');
		}
	});
	/* Display Category based doctorlist script starts here - created by Karthik on 25 Aug, 2014 */
	$.widget( "custom.category_autocomplete", jQuery.ui.autocomplete, 
	{
		_create: function()
		{
			this._super();
			this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
		},
		_renderMenu: function(ul, items) 
		{
	        var self = this,
	       	categories = { };
	        $.each(items, function (index, item) 
	        {
	            if (!categories.hasOwnProperty(item.category)) 
	            {
	                 categories[item.category] = [item];
	            } 
	            else 
	            {
	                categories[item.category] = categories[item.category].concat([item]);
	            }
	        });
	        $.each(categories, function(category, items) 
	        {
	            if(category) 
	            {
	                ul.append("<li class='ui-autocomplete-category' tabindex='-1' >"+category+"</li>").data("item.autocomplete",items);
	            }
	            $.each(items, function (index, item) 
	            {
	            	self._renderItemData(ul, item);
	            });
	        });
	    }
	});
	/* Autosuggestion script used to display quick search details - created by Karthik on 04 Nov, 2014 */
	$("#frm_quick_search #search_name").category_autocomplete(
	{
		delay: 0,autoFocus: true,highlightClass:'matchedWord',
		source: function(data_request,data_response) 
		{
			var getStateId 	= $( "#state_id" ).val();
			var zipcode 	= $( "#zipcode" ).val();
			$.ajax({method:'POST',url:"<?php echo base_url('searchlist'); ?>",dataType: "json",data:{search_name:data_request.term,search_state:getStateId,search_zipcode:zipcode},
	 		success: function(get_output) 
	     	{
	        	data_response(get_output);
	     	}});
		},
		minLength:1
	});
	/* Script to display doctor details - created by Karthik on 14 Aug, 2014  */
	$('body').on('click','.view_doc_details,.view_spl_doc_details',function()
	{
		var active_obj		= $(this);
		var get_doctor_id	= active_obj.attr('doc-id');
		var get_popup_selector = $('div#fadeandscale'+get_doctor_id);
		var get_type = 'condition';
		if(active_obj.hasClass('view_spl_doc_details'))
		{
			var get_popup_selector = $('div#fadeandscale_special'+get_doctor_id);
			get_type = 'specialty';
		}
		var check_html		= $.trim(get_popup_selector.html());
		get_popup_selector.popup('show');
		get_popup_selector.popup({pagecontainer: '.container',transition: 'all 0.3s'});
		if(check_html == '')
		{
			get_popup_selector.html("<div class='txt_center'><img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/></div>");
			$.ajax({type: "POST",url: "<?php echo base_url('doctorprofile'); ?>",data: {get_id: get_doctor_id,get_type:get_type },
		  	success: function(get_details)
		  	{
		  		get_popup_selector.html(get_details);
		  		get_popup_selector.parent().scrollTop(0);
		  	}});
		}
		else
		{
			get_popup_selector.parent().scrollTop(0);
		}
	});
	/* Function used to generate doctor list details using JSON output - created by Karthik on 05 Nov, 2014 */
	$.fn.append_doc_list = function(options)
	{
		var defaults = $.extend({doctor_list:'',get_report:false,category:'condition'},options);
		var doc_list_html   = '';
		var btn_more_html 	= '';
		if(defaults.doctor_list != '')
		{
			var json_obj = $.parseJSON(defaults.doctor_list);
			if(json_obj.record_exist == 'yes')
			{
				if($('div#more_list_div button.more_doc').length > 0)
				{
					$('div#more_list_div button.more_doc').show();
				}
				else
				{
					btn_more_html += '<div class="row" id="more_list_div"><div class="large-12 columns">';
					btn_more_html += '<button id="more_doc" class="button more_doc" type="button">More List &gt;&gt;</button></div></div>';
				}
			}
			else
			{
				$('div#more_list_div').remove();
			}
			if(json_obj.empty != '' && json_obj.empty == 'empty')
			{
				$('select#list_sort_by').hide();
				doc_list_html += '<center class="color_red"><h3><?php echo GetLangLabel("MsgDoctorNoResult"); ?></h3></center>';
				$('div#more_list_div').remove();
			}
			else
			{
				var json_doc_list	= json_obj.list;
				$.each(json_doc_list,function(doc_key,doc_val)
				{
					var display_bg_color	= '';
					if(defaults.category == 'condition' && doc_val.order_id != '')
					{
						if(doc_val.order_id == '3')
						{
							display_bg_color	= 'cond_bg';
						}
						else if(doc_val.order_id == '2')
						{
							display_bg_color	= 'scond_bg';
						}
						else
						{
							display_bg_color	= 'sscond_bg';
						}
					}
					if(doc_val.order_id != '' && display_order_id != '' && display_order_id != doc_val.order_id)
					{
						var show_category_title	= '';
						if(doc_val.order_id == 3)
						{
							if(defaults.category == 'condition')
							{
								show_category_title = $('label[for="phBreadCrumb_chkCond_'+$('input[name=conditionname]:checked').val()+'"]').text();
							}
							else
							{
								show_category_title = $('label[for="specialty_chkCond_'+$('input[name=specialtyname]:checked').val()+'"]').text();
							}
						}
						else if(doc_val.order_id == 2)
						{
							if(defaults.category == 'condition')
							{
								show_category_title = $('label[for="sub_chkCond_'+$('input[name=subconditionname]:checked').val()+'"]').text();
							}
							else
							{
								show_category_title = $('label[for="subspecial_chkCond_'+$('input[name=subspecialtyname]:checked').val()+'"]').text();
							}
						}
						doc_list_html	+= "<div class='large-12 columns clear_both'><div class='title-block'><h3 class='inner_doc_category'>Doctor List - "+show_category_title+"</h3>";
						doc_list_html	+= "<div class='divider clear_both'><span></span></div></div></div>";
					}
					display_order_id	= doc_val.order_id;
					doc_list_html += '<div class="large-3 columns doctor_display_list" data-quality="'+doc_val.quality+'"  data-efficiency="'+doc_val.efficiency+'"><article class="post col-2 '+display_bg_color+'">';
					doc_list_html += '<div class="post_img"><img src="'+doc_val.image+'" alt="'+doc_val.name+'"></div>';
					doc_list_html += '<ul class="pricing-table"><li class="title">';
					doc_list_html += '<a href="javascript:void(0);" class="view_doc_details" doc-id="'+doc_val.doc_npi+'" id="drill_'+doc_val.doc_npi+'">'+doc_val.name+doc_val.education+'</a></li>';
					doc_list_html += '<li class="price">'+doc_val.primary+'</li>';
					if(doc_val.address != '')
					{
						doc_list_html += '<li class="bullet-item address"><i class="icon-home"></i><span>'+doc_val.address+'</span></li>';
					}
					else
					{
						doc_list_html += '<li class="bullet-item address"> - </li>';
					}
					if(doc_val.phone != '')
					{
						doc_list_html += '<li class="bullet-item phone"><i class="icon-phone"></i><span>'+doc_val.phone+'</span></li>';
					}
					else
					{
						doc_list_html += '<li class="bullet-item phone"> - </li>';
					}
					doc_list_html += '<li class="bullet-item languages">Languages <br> '+doc_val.language+'</li>';
					doc_list_html += '<li class="bullet-item">Quality <br> <img src="'+doc_val.quality_img+'"></li>';
					doc_list_html += '<li class="bullet-item">Efficiency <br> <img src="'+doc_val.efficiency_img+'"></li>';
					doc_list_html += '<li class="compare_map"><iframe width="235" height="100" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.it/maps?q='+doc_val.map_address+'&amp;output=embed"></iframe></li>';
					doc_list_html += '<input type="hidden" name="docno" id="get_'+doc_val.doc_id+'" value="'+doc_val.doc_id+'">';
					doc_list_html += '<input type="hidden" name="docname" id="get_name_'+doc_val.doc_id+'" value="'+doc_val.name+'">';
					doc_list_html += '<input type="hidden" name="docgender" id="get_image_'+doc_val.doc_id+'" value="'+doc_val.gender+'"><li class="cta-button">';
					doc_list_html += '<button id="'+doc_val.doc_id+'" class="button add_doc_compare">Add to Compare</button>';
					doc_list_html += '</li></ul></article>';
					if($('div#fadeandscale'+doc_val.doc_npi).length == 0)
					{
						doc_list_html += '<div id="fadeandscale'+doc_val.doc_npi+'" class="well large-9 columns" style="display:none;"></div>';
					}
					doc_list_html += '</div>';
				});
				if(defaults.get_report == true && json_obj.list_count < 2)
				{
					$('select#list_sort_by').hide();
				}
				else
				{
					$('select#list_sort_by').show();
				}
			}
		}
		else
		{
			$('select#list_sort_by').hide();
			doc_list_html += '<center class="color_red"><h3><?php echo GetLangLabel("MsgDoctorNoResult"); ?></h3></center>';
			$('div#more_list_div').remove();
		}
		$('div#doctor_list').append(doc_list_html);
		if(btn_more_html != '')
		{
			$('div#doctor_list').after(btn_more_html);
		}
	};
});
</script> 