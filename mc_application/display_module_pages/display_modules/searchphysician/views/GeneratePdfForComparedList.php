<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 06 Nov, 2014
* Modified date : 06 Nov, 2014
* Description 	: Page contains display compared doctor list details in PDF format
*/
 ?>
<style type="text/css">
.name_bg{color: #333333;font-size: 1em;font-weight: bold;height:30px;overflow:hidden}
.spl_bg{color: #333333;font-size:11x;height:100px;overflow:hidden;}
.tbl_content{text-align: center;margin:0;border-collapse:collapse;}
.display_address{height: 50px;line-height: 19px;font-size:10px;}
.display_lang{height:75px;line-height: 19px;font-size:10px;}
.hosp_addr{height:65px;vertical-align: middle;font-size:10px;}
.display_qual,.display_effe{font-size:10px;}
.fl{float:left;}
</style>
<h1>Compare List</h1>
<table width="100%">
	<tr><?php 
	if(isset($DoctorList) && count($DoctorList)>0)
	{
		foreach($DoctorList as $DisplayValues)
		{
			$DoctorNpi 			= $DisplayValues['NPI'];
			$DoctorName 		= $DisplayValues['ProvName'];
			$QualityImage 		= '0.png';
			$EfficiencyImage 	= '0.png';
			if($DisplayValues['Quality'] >= 0)
			{
				$QualityImage	= $DisplayValues['Quality'].'.png';
			}
			if($DisplayValues['Efficiency'] >= 0)
			{
				$EfficiencyImage	= $DisplayValues['Efficiency'].'.png';
			}
			$DoctorGender	= $DisplayValues['Gender'];
			$DoctorImage 	= "NoImageSmall.png";
			if($DoctorGender == 'M')
			{
				$DoctorImage = "MaleImageSmall.png";
			}
			else if($DoctorGender == 'F')
			{
				$DoctorImage = "FemaleImageSmall.png";
			}
			$Languages = $DisplayValues['Languages'];
			if($Languages == '' || $Languages == '0')
			{
				$Languages = 'English';
			}
			else
			{
				// Explode doctor known languages
				$ExplodeLanguage	= explode('@',$Languages);
				if(count($ExplodeLanguage)>0)
				{
					$ReturnLanguage	= array();
					foreach($ExplodeLanguage as $GetLanguage)
					{
						// Change display language format
						$ReturnLanguage[]	= ucfirst(strtolower($GetLanguage));
					}
				} 
				// Implode known language
				$Languages = implode(' | ',$ReturnLanguage);
				$Languages = 'English | '.$Languages;
			}
			$DoctorAddress	= $DisplayValues['Address']; 
			$DoctorAddress 	= str_replace("'","",$DoctorAddress);
			/*$DoctorAddress 	= str_replace("<br>","",$DoctorAddress);*/
			$DoctorEducation	= ($DisplayValues['Education'] != '')?' | '.$DisplayValues['Education']:'';
			$SetTblWidth = '';
			if(count($DoctorList) == 1)
			{
				$SetTblWidth = "width='50%'";?>
					<td width="25%">&nbsp;</td>
				<?php
			}?>
			<td <?php echo $SetTblWidth; ?>>
	         	<table cellpadding="0" cellspacing="0">
	         		<tr>
	         			<td align="center">
	         				<img src="<?php echo ImageUrl($DoctorImage); ?>" />
	         			</td>
	         		</tr>
	         	</table>
	         	<table border="1" class="tbl_content" cellpadding="5" align="center">
	         		<tr>
	         			<td class="name_bg" valign="middle"><?php echo $DoctorName.$DoctorEducation; ?></td>
	         		</tr>
	         		<tr>
	         			<td class="spl_bg" valign="middle"><?php echo $DisplayValues['PrimarySpecialty']; ?></td>
	         		</tr>
	         		<tr>
	         			<td valign="middle" class="display_address">
	         				<img src="<?php echo ImageUrl('home.png'); ?>"/> 
	         				<?php echo $DoctorAddress; ?>
	         			</td>
	         		</tr>
	         		<tr><?php
         			if($DisplayValues['Phone'] != '')
         			{?>
         				<td valign="middle">
         					<img src="<?php echo ImageUrl('phone.png'); ?>"/> 
         					<?php echo $DisplayValues['Phone']; ?>
         				</td><?php
         			}
         			else
         			{?>
         				<td valign="middle"> - </td><?php
         			}?>
	         		</tr>
	         		<tr>
	         			<td valign="middle" class="display_lang">
	         				Languages <br><?php echo $Languages; ?>
	         			</td>
	         		</tr><?php
	         		$GetHospitalList		= $this->common_model->GetDoctorWorkedHospitals($DoctorNpi);
					$TotalDisplayHospital	= 3;
					$RemainingHospitalCount	= $TotalDisplayHospital-count($GetHospitalList);
					if(count($GetHospitalList) > 0)
					{
						foreach($GetHospitalList as $DisplayHospital)
						{
							if($DisplayHospital['HospitalName'] != '')
							{?>
								<tr>
	     							<td valign="middle" class="hosp_addr"><?php echo $DisplayHospital['HospitalName']; ?><br>
	         							<center>
	         								<img src="<?php echo ImageUrl('ratings/'.$DisplayHospital['Star'].".png"); ?>" >
	         							</center>
	     							</td>
	     						</tr><?php
	     					}
	     					else if($DisplayHospital['NewHospitalName'] != '')
	     					{?>
	     						<tr>
	     							<td valign="middle" class="hosp_addr"><?php echo $DisplayHospital['NewHospitalName']; ?><br>
	         							<center>
	         								<img src="<?php echo ImageUrl('ratings/0.png'); ?>" >
	         							</center>
	     							</td>
	     						</tr><?php 
	     					}
						}
					}
					if($RemainingHospitalCount > 0)
					{
						for($StartLoop=0;$StartLoop<$RemainingHospitalCount;$StartLoop++) 
						{?>
							<tr><td valign="middle" class="hosp_addr"> - </td></tr><?php
						}
					}?>
	         		<tr>
	         			<td valign="middle" class="display_qual">
	         				Quality <br>
	         				<img src="<?php echo ImageUrl('ratings/'.$QualityImage); ?>" />
	         			</td>
	         		</tr>
	         		<tr>
	         			<td valign="middle" class="display_effe">
	         				Efficiency <br> 
	         				<img src="<?php echo ImageUrl('ratings/'.$EfficiencyImage); ?>" />
	         			</td>
	         		</tr>
	         	</table>
			</td><?php
		}
	}
	else
	{?>
		<td align='center' style='color:#ff0000;padding:80px 0'>Invalid values</td><?php
	}?>
	</tr>
</table>