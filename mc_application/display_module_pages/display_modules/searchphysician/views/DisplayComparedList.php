<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 04 Nov, 2014
* Modified date : 04 Nov, 2014
* Description 	: Page contains display compared doctor list details
*/
if(isset($ComparedList) && count($ComparedList)>0)
{
	foreach($ComparedList as $DisplayComparedValues)
	{
		$DisplayEducation	= ($DisplayComparedValues['Education'] != '')?' | '.$DisplayComparedValues['Education']:'';
		$DoctorNpi 			= $DisplayComparedValues['NPI'];
		$DoctorId 			= $DisplayComparedValues['id'];
		$DoctorName 		= $DisplayComparedValues['ProvName'];
		$ReplaceAddress		= str_replace("#","",$DisplayComparedValues['Address']);
		$ReplaceAddress 	= str_replace("'","",$ReplaceAddress);
		$ReplaceAddress 	= str_replace("<br>","",$ReplaceAddress);
		$Languages 			= $DisplayComparedValues['Languages'];
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
		// Efficient rating end here
		$DoctorGender 	= $DisplayComparedValues['Gender'];
		$DoctorImage 	= "NoImageSmall.png";
		if($DoctorGender == 'M')
		{
			$DoctorImage = "MaleImageSmall.png";
		}
		else if($DoctorGender == 'F')
		{
			$DoctorImage = "FemaleImageSmall.png";
		}
		$Quality 	= $DisplayComparedValues['Quality'];
		$Efficiency = $DisplayComparedValues['Efficiency'];
		$QualityImage	= "0.png";
		$EfficiencyImage= "0.png";
		if($Quality >= 0)
		{
			$QualityImage = $Quality.".png";	
		}
		if($Efficiency >= 0)
		{
			$EfficiencyImage = $Efficiency.".png";	
		}?>
		<div class='large-3 columns' id='final_report_doc'>
			<article class='post col-2'>
				<div class='post_img'>
					<img src='<?php echo ImageUrl($DoctorImage); ?>' alt='<?php echo $DoctorName; ?>'/>
				</div>
				<ul class='pricing-table'>
					<li class='title'>
						<a href='javascript:;' class='view_compare_doc_details' doc-id='<?php echo $DoctorNpi; ?>'>
						<?php echo $DoctorName.$DisplayEducation; ?></a>
					</li>
					<li class='price'><?php echo $DisplayComparedValues['PrimarySpecialty']; ?></li>
					<li class='bullet-item address'>
						<i class='icon-home'></i><span><?php echo $DisplayComparedValues['Address']; ?></span>
					</li>
					<li class='bullet-item'><?php 
					if($DisplayComparedValues['Phone'] != '')
					{?>
						<i class='icon-phone-sign'><span><?php echo $DisplayComparedValues['Phone']; ?></span></i><?php
					}
					else
					{
						echo " - ";
					}?>
					</li>
					<li class='bullet-item languages'>Languages <br><?php echo $Languages ?></li><?php  
					$GetHospitalList		= $this->common_model->GetDoctorWorkedHospitals($DoctorNpi);
					$TotalDisplayHospital	= 3;
					if(isset($GetHospitalList)) {
                      $RemainingHospitalCount = $TotalDisplayHospital - count($GetHospitalList);
                      if (count($GetHospitalList) > 0)
                        {
						foreach ($GetHospitalList as $DisplayHospital) {
                          ?>
                            <li class='bullet-item hos_star'>
                            <i class='colorred icon-hospital'></i><?php
                          if (trim($DisplayHospital['HospitalName']) != '') {
                            ?>
                              <a href='javascript:;' class='display_hos_pro'
                                 hos-id='<?php echo $DisplayHospital['ProviderNumber']; ?>'>
                                <?php echo $DisplayHospital['HospitalName']; ?>
                              </a>
                              <br>
                              <center><img
                                      src='<?php echo ImageUrl("ratings/" . $DisplayHospital['Star'] . ".png"); ?>'
                                      alt='Hospital rating'/></center><?php
                          }
                          else {
                            echo $DisplayHospital['NewHospitalName']; ?>
                              <br>
                              <center><img
                                          src='<?php echo ImageUrl("ratings/0.png"); ?>'
                                          alt='Hospital rating'/></center> <?php
                          } ?>
                            </li><?php
                        }
					}
					if ($RemainingHospitalCount > 0) {
                      for ($StartLoop = 0; $StartLoop < $RemainingHospitalCount; $StartLoop++) {
                        ?>
                          <li class='bullet-item hos_star'> -</li><?php
                      }

                    }
					}
					?>
					<li class='bullet-item'>
						Quality <br> 
						<img src='<?php echo ImageUrl("ratings/".$QualityImage); ?>' alt='Doctor Quality' />
					</li>
					<li class='bullet-item'>
						Efficiency <br> 
						<img src='<?php echo ImageUrl("ratings/".$EfficiencyImage); ?>' alt='Doctor Quality' />
					</li>
					<li class='compare_map'>
						<iframe width='235' height='100' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://maps.google.it/maps?q=<?php echo $ReplaceAddress; ?>&output=embed'></iframe>
					</li>
					<input type='hidden' name='hid_doctor_id[]' value='<?php echo $DoctorId; ?>' />
				</ul>
			</article>
		</div><?php
	}
}
 ?>