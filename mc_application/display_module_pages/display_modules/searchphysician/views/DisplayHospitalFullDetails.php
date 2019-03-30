<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 04 Dec, 2014
* Modified date : 04 Dec, 2014
* Description 	: Page used to display hospital full details
*/ 
if(isset($HospitalFinalResult) && count($HospitalFinalResult)>0)
{?>
	<div class='title-block'><h3><?php echo $HospitalFinalResult[0]['hospital_name']; ?></h3>
		<div class='divider'><span></span></div>  
	</div>
	<div class='row'>
		<div class='large-12 columns'>
			<div class='fright'>
				<button class='fadeandscale1_close btn btn-default fleft MarginRgt5px'>Close</button>
				<button class='BackToHospital btn btn-default fleft'>Go Back</button>
			</div>
		</div>
	</div>
	<div class='row'>
		<div class='large-12 columns HospitalFullList'>
			<table class="table table-bordered">
				<tbody>
					
					<?php
					$i='0';
					$group_heading_name='';
					foreach($HospitalFinalResult as $DisplayResult)
			 		{
			 			$measure_percentile = $DisplayResult['measure_percentile'];
			 			$stateid = $DisplayResult['stateid'];
			 			if($i=='0')
			 			{
			 				
			 			 if(strlen($measure_percentile) && strlen($measure_percentile)>='1') { 
			 			 	
			 			?>
			 			
			 			<tr>
					
					   	<th style="width:40%;" >Measure Name</th>
					    <th style="width:15%;">Score</th>
						<th style="width:10%;">Band %</th>
						
					</tr>
			 			
			 			<?php 
			 			}else{
			 				 if(strlen($measure_percentile) && strlen($measure_percentile)>='1' && $measure_percentile!='NULL') { 
			 				 	
			 				 }else{
			 				?>
			 				<tr>
					
					   	<th>Measure Name</th>
					    <th>Score</th>
						
						<th>Comparison to Avg</th>
					</tr>
					<?php 
			 			}
			 			}
			 			}
					
			 			$DisplayStatusText = "Below Average";
			 			if($DisplayResult['ranking_score'] == 0)
			 			{
			 				$DisplayStatusText	= "Average";
			 			}
			 			else if($DisplayResult['ranking_score'] == 1)
			 			{
			 				$DisplayStatusText	= "Above Average";
			 			}
			 			$HospitalPoints = $DisplayResult['hospital_points'];
			 			if($DisplayResult['hospital_points'] == null)
			 			{
			 				$HospitalPoints = "-";
			 				$DisplayStatusText	= "-";
			 			}
			 			$groupname = $DisplayResult['groupname'];
			 			
			 			?>
			 			<?php  if(strlen($measure_percentile) && strlen($measure_percentile)>='1' && $measure_percentile!='NULL') { 
			 				
			 				
			 			if($DisplayResult['star_value'] > 0)
		 			{
		 				$HospitalRating = "<img src='".ImageUrl('ratings/'.$DisplayResult['star_value']).".png' />";
		 			}
		 			else
		 			{
		 				$HospitalRating	= "N/A";
		 			}
		 			
			 				?>
			 				<?php if($DisplayResult['groupname']!=$group_heading_name) {?>
			 			 <tr>
						<td colspan='3' style="background-color:#DDDDDD; ">
						<div style="text-align: left; width: 75%;float: left; margin-bottom: 0px !important;"><font style="font-size:15px;font-weight: 600;"><?php echo $DisplayResult['groupname']; ?></font></div>
						<div style="text-align: right; width: 25%;float: right; margin-bottom: 0px !important;"><font style="font-size:15px;font-weight: 600;"><?php echo $HospitalRating; ?></font></div>
						</td>
							
						</tr> 
						<?php } ?>
			 									<tr>
						
							<td  style="width:55%;"><?php echo $DisplayResult['measure_name']; ?>:</td>
							<td style="width:15%;"><?php echo $HospitalPoints; ?></td>
							<td style="width:15%;"><?php  echo $measure_percentile;?></td>														
							
						</tr>
			 			<?php }else{
			 			
			 				 if(strlen($measure_percentile) && strlen($measure_percentile)>='1' && $measure_percentile!='NULL') { 
			 				 	
			 				 }else{
			 				 	
			 				 
			 				?>
						<tr>
							<td><?php echo $DisplayResult['measure_name']; ?>:</td>
							<td><?php echo $HospitalPoints; ?></td>
							<td><?php echo $DisplayStatusText;?></td>
						</tr><?php
			 			}
			 			}
			 			
			 			$i ='1';
			 			$group_heading_name = $DisplayResult['groupname'];
					}?>
				</tbody>
			</table>
		</div>
	</div>
	<div class='row'>
		<div class='large-12 columns'>
			<div class='pull-right'>
				<button class='fadeandscale1_close btn btn-default MarginRgt5px'>Close</button>
				<button class='BackToHospital btn btn-default'>Go Back</button>
			</div>
		</div>
	</div><?php
} ?>