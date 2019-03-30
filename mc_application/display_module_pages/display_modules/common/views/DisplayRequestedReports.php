<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 10 Nov, 2014
* Modified date : 10 Nov, 2014
* Description 	: Page contains display generate reports formd details
*/ 
$RangeUserCount	= 0;
$RangeData 		= '';
$TopUserList	= '';
$TopUserDetails = '';
if(isset($UserReports) && count($UserReports)>0)
{
	$RangeUserCount	= count($UserReports);
	$RangeArray = array();
	foreach ($UserReports as $ShowValues)
	{
		$CreateDate		= $ShowValues->created_on."000";
		$RangeArray[] 	= "[$CreateDate,$ShowValues->RangeCount]";
	}
	$RangeData = implode(',', $RangeArray);
}?>
<div class='nav_tab_content'><?php 
	echo form_open(base_url('reports'),array('id'=>'FrmUserReports','class'=>'BorderNone form-horizontal FrmUserReports','method'=>'post','validation'=>'true','autocomplete'=>'off')); ?>
		<div class='row'>
			<div class='large-8 columns large-centered'>
				<fieldset>
					<div class="row">
						<div class='large-2 columns'><?php echo form_label(GetLangLabel('LblReportFromDate'),'TxtReportFrom',array('class'=>'control-label')); ?></div>
						<div class="large-3 columns"><?php 
						echo form_input(array('class'=>'TxtReportFrom','id'=>'TxtReportFrom','name'=>'TxtReportFrom','readonly'=>'true','jvalidation'=>'Required:true'),set_value('TxtReportFrom'));?>
						</div>
						<div class='large-1 columns'><?php echo form_label(GetLangLabel('LblReportToDate'),'TxtReportTo',array('class'=>'control-label')); ?></div>
						<div class="large-3 columns"><?php 
						echo form_input(array('class'=>'TxtReportTo','id'=>'TxtReportTo','name'=>'TxtReportTo','readonly'=>'true','jvalidation'=>'Required:true'),set_value('TxtReportTo'));?>
						</div>
						<div class='large-2 columns ReportBtnCtrls'><?php 
							echo form_button(array('type'=>'submit','class'=>'BtnUserReports btn','name'=>'BtnUserReports','value'=>'Reports'),GetLangLabel('BtnShowUserReport')); ?>
						</div>
						<div class='large-1 columns'></div>
					</div>
				</fieldset>
			</div>
		</div><?php 
	echo form_close(); ?>
	<div class='row'>
		<div class='large-3 columns'>
			<div class='row'>
				<div class='large-12 small-6 columns'>
					<div class='ReportsDetails PcMarginBtm10px'>
						<h2 class='ReportTitle'>Total</h2>
						<h4 class='ReportCount'><?php echo $TotalUsers; ?> <small class='ReportText'>Registered</small></h4>
					</div>
				</div>
				<div class='large-12 small-6 columns'>
					<div class='ReportsDetails'>
						<h2 class='ReportTitle'>Range</h2>
						<h4 class='ReportCount'><?php echo $RangeUserCount; ?> <small class='ReportText'>Registered</small></h4>
					</div>
				</div>
			</div>
		</div>
		<div class='large-9 columns'>
			<div id="RangeGraph">			 
			    <div class="RangeGraphContainer">
			        <div id="ShowRangeGraph"></div>
			    </div>
			</div>
		</div>
	</div><?php 
	if(isset($MostLoginList) && count($MostLoginList)>0)
	{?>
		<div class='row'>
			<div class='large-12 columns'>
				<h3 class='BarGraphTitle'>Most Frequent Users</h3>
			</div>
		</div>
		<div class='row'>
			<div class='large-3 columns'>
				<div class='row'>
					<div class='large-12 columns'>
						<div class='BarChartDetails'>
							<h2 class='BarChartTopTitle'>Top 10</h2>
							<div class='row'>
								<div class='large-8 small-8 columns'><h5 class='BarChartTitle'>Name</h5></div>
								<div class='large-4 small-4 columns'><h5 class='BarChartTitle'>Logins</h5></div>
							</div>
							<ul class='DisplayUsrList'><?php 
							$TopUserListArray		= array();
							$TopUserDetailsArray	 = array();
							foreach($MostLoginList as $KeyIndex=>$DisplayValues)
							{
								$TopUserListArray[]		= "[$KeyIndex,$DisplayValues->LoginCount]";
								$TopUserDetailsArray[]	= "[$KeyIndex,'$DisplayValues->name']"; ?>
								<li class='DisplayUsrListLi'>
									<div class='row'>
										<div class='large-8 small-8 columns'><?php echo $DisplayValues->name; ?></div>
										<div class='large-4 small-4 columns'><?php echo $DisplayValues->LoginCount; ?></div>
									</div>
								</li><?php 
							}
							$TopUserList 	= implode(',',$TopUserListArray);
							$TopUserDetails = implode(',',$TopUserDetailsArray); ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class='large-9 columns'>
				<div id="RangeGraph">			 
			    <div class="RangeGraphContainer">
			        <div id="MostLoginGraph"></div>
			    </div>
			</div>
		</div><?php 
	}?>
</div>
<script type="text/javascript" src='<?php echo JsUrl('jquery_ui.js') ?>'></script>
<script type="text/javascript" src='<?php echo JsUrl('jquery_graphchart.js') ?>'></script>
<script type="text/javascript" src='<?php echo JsUrl('jquery_graphresize.js') ?>'></script>
<script type="text/javascript">
function viewGraph()
{
	/*$('.GraphChart').css('height','0');
	$('.GraphChart').each(function(index) 
	{
		var SetHeight = $(this).attr('graph-val');
		$(this).animate({height:SetHeight}, 1500).html("<div>"+SetHeight+"</div>");
	});*/
}
function weekendAreas(axes) 
{
	var markings = [];
	var d = new Date(axes.xaxis.min);
	// go to the first Saturday
	d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
	d.setUTCSeconds(0);
	d.setUTCMinutes(0);
	d.setUTCHours(0);
	var i = d.getTime();
	do 
	{
		// when we don't set yaxis, the rectangle automatically
		// extends to infinity upwards and downwards
		markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
		i += 7 * 24 * 60 * 60 * 1000;
	} while (i < axes.xaxis.max);
	return markings;
}
function showTooltip(x, y, contents) 
{
	$mainDiv = $('<div class="tooltip fade top in" id="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + contents + '</div></div>');
	$mainDiv.css({position: 'absolute',display: 'none',top: y - 45,left: x - 65});
	$($mainDiv).appendTo('body').fadeIn(100);
}
$(function()
{
	var RangeGraphData = [{data:[<?php echo $RangeData; ?>],color: '#71c73e'}];
	// Lines
	$.plot($('#ShowRangeGraph'), RangeGraphData, 
	{
		series: {points: {show: true,radius:4},lines: {show: true},shadowSize: 0,},
	    xaxis: {mode: "time",timeformat: "%d %b",tickLength: 1,minTickSize: [1, "day"]},
		yaxes: [ { min: 0, tickSize: 1, tickDecimals: 0 }],
		yaxes: [ { min: 0, tickSize: 0, tickDecimals: 0 }],
		grid: {show: true,aboveData: false,backgroundColor: '#fff',borderWidth: 1,borderColor: '#ccc',clickable: false,hoverable: true,markings: weekendAreas}
	});
	$("#ShowRangeGraph").bind("plothover", function (event, pos, item) 
	{
		var GraphObj = $(this)
		if(item) 
		{
			var parent = GraphObj.offset();
			previousPoint = item.dataIndex;
			$('#tooltip').remove();
			var DateFormat = new Date(item.datapoint[0]);
			var FormattedDate = $.plot.formatDate(DateFormat, "%d %b, %y");
			showTooltip(item.pageX, item.pageY, item.datapoint[1] + " users on " + FormattedDate);
		}
		else 
		{
			$('#tooltip').remove();
			//previousPoint = null;
		}
	});
	$("#ShowRangeGraph").resize();
	<?php 
	if(isset($MostLoginList) && count($MostLoginList)>0)
	{?>
		var BarGraphData = [{data: [<?php echo $TopUserList; ?>],color: '#71c73e'}];
		$.plot($('#MostLoginGraph'), BarGraphData, 
		{
			series: {bars: { show: true },points: { show: false }},
			grid: {show: true,aboveData: false,color: '#ccc',backgroundColor: '#fff',borderWidth: 1,borderColor: '#ccc',clickable: false,hoverable: true},
			xaxis: {ticks: [<?php echo $TopUserDetails; ?>],tickLength: 0},
			yaxes: [ { min: 0, tickSize: 1, tickDecimals:0}],
		});<?php 
	}?>
	// viewGraph();
	$("#TxtReportFrom").datepicker({showAnim:'slide',changeMonth: true,changeYear: true,dateFormat: "dd-M-yy",
		onClose: function( selectedDate ) 
		{
			$("#TxtReportTo").datepicker( "option", "minDate", selectedDate );
			$("#TxtReportTo").datepicker( "option", "defaultDate", selectedDate );
			
			$("#TxtReportTo").focus();
		}
	});
	$("#TxtReportTo").datepicker({showAnim:'slide',changeMonth: true,changeYear: true,dateFormat: "dd-M-yy",
		onClose: function( selectedDate ) 
		{
			$("#TxtReportFrom").datepicker( "option", "maxDate", selectedDate );
		}
	});
});
</script>
