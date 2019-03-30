<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 06 Nov, 2014
* Modified date : 06 Nov, 2014
* Description 	: Page used to display hospital profile details
*/ 
if(isset($HospitalProfile) && count($HospitalProfile)>0)
{?>
	<div class='title-block'><h3><?php echo $HospitalProfile[0]['hospital_name']; ?></h3>
		<div class='divider'><span></span></div>  
	</div>
	<div class='row'>
		<div class='large-12 columns'>
			<div class='fright'>
				<button class='btn btn-default fleft BtnViewHosFinal MarginRgt5px' hos-pro-id="<?php echo EncodeValue($HospitalProfile[0]['provider_number']); ?>">Full Details</button>
				<button class='fadeandscale1_close btn btn-default fleft MarginRgt5px'>Close</button>
				<button class='go_back_to_compare btn btn-default fleft'>Go Back</button>
			</div>
		</div>
	</div>
	<div class='row'>
		<div class='large-8 large-centered columns hospital_profile_list'>
			<table width='100%'>
				<tbody><?php
				foreach($HospitalProfile as $DisplayDetails)
		 		{
		 			if($DisplayDetails['star_value'] > 0)
		 			{
		 				$HospitalRating = "<img src='".ImageUrl('ratings/'.$DisplayDetails['star_value']).".png' />";
		 			}
		 			else
		 			{
		 				$HospitalRating	= "N/A";
		 			}?>
					<tr>
						<td><?php echo $DisplayDetails['group_name']; ?>:</td>
						<td><?php echo $HospitalRating; ?></td>
					</tr><?php
				}?>
				</tbody>
			</table>
		</div>
	</div>
	<div class='row'>
		<div class='large-12 columns'>
			<div class='pull-right'>
				<button class='fadeandscale1_close btn btn-default MarginRgt5px'>Close</button>
				<button class='go_back_to_compare btn btn-default'>Go Back</button>
			</div>
		</div>
	</div><?php
} ?>