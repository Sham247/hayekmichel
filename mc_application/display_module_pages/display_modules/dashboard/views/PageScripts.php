<script src="<?php echo JsUrl('dashboard/jquery_angular_js.js'); ?>"></script>
<script src="<?php echo JsUrl('dashboard/jquery_angular_ui.js'); ?>"></script>
<script src="<?php echo JsUrl('dashboard/jquery_dashboard_app.js'); ?>"></script>
<script src="<?php echo JsUrl('jquery_ui.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo CssUrl('dashboard_ui.css'); ?>" />
<script type="text/javascript">
$(function()
{
	/* Enable/ Disable zipcode field */
	$("#state_id" ).change(function()
	{
		var selected_state_id 	= $(this).val();
		if(selected_state_id == '')
		{
			$( "#zipcode" ).attr('disabled','disabled').val('');
		}
		else
		{
			$( "#zipcode" ).removeAttr('disabled').val('');
		}
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
	$("#frm_quick_search #search_name").category_autocomplete(
	{
		delay: 0,autoFocus: true,highlightClass:'matchedWord',
		source: function(data_request,data_response) 
		{
			var getStateId 	= jQuery( "#state_id" ).val();
			var zipcode 	= jQuery( "#zipcode" ).val();
			$.ajax({method:'POST',url:"<?php echo base_url('searchlist'); ?>",dataType: "json",data:{search_name:data_request.term,search_state:getStateId,search_zipcode:zipcode},
	 		success: function(get_output) 
	     	{
	        	data_response(get_output);
	     	}});
		},
		minLength:1
	});
});
</script>