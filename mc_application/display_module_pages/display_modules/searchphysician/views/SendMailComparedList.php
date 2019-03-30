<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 04 Nov, 2014
* Modified date : 04 Nov, 2014
* Description 	: Page contains send compared doctor list details via email page design
*/
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><p style='text-indent:20px'>Below are the compared doctor list. Please check</p></td>
	</tr>
	<tr  style='font-size:12px'>
		<td>
			<table>
				<tr><?php
					foreach($DoctorList as $DisplayValues)
					{
						$DisplayEducation	= ($DisplayValues['Education'] != '')?' | '.$DisplayValues['Education']:'';
						$DoctorNpi 			= $DisplayValues['NPI'];
						$DoctorName 		= $DisplayValues['ProvName'];
						$DoctorAddress		= $DisplayValues['Address'];
						$Languages 			= $DisplayValues['Languages'];
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
						$DoctorGender 	= $DisplayValues['Gender'];
						$DoctorImage 	= "NoImageSmall.png";
						if($DoctorGender == 'M')
						{
							$DoctorImage = "MaleImageSmall.png";
						}
						else if($DoctorGender == 'F')
						{
							$DoctorImage = "FemaleImageSmall.png";
						}
						$Quality 	= $DisplayValues['Quality'];
						$Efficiency = $DisplayValues['Efficiency'];
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
						<td align="center" valign="top" width="25%">
							<table border="1" cellpadding="10" cellspacing="0" width="100%" style="text-align:center">
								<tr>
				                	<td class="leftColumnContent">
				                		<img src='<?php echo ImageUrl($DoctorImage); ?>' />
				                 	</td>
								</tr>
				                <tr>
				                	<td valign="middle" style='height:50px'>
				                    	<h1 style='font-size:16px'><?php echo $DoctorName.$DisplayEducation; ?></h1>
				                    </td>
				                </tr>
				                <tr>
				                	<td valign="middle" style='height:80px'><?php echo $DisplayValues["PrimarySpecialty"]; ?></td>
								</tr>
				                <tr>
				                	<td valign="middle" style='height:50px'><?php echo $DoctorAddress; ?></td>
				                </tr>
				                <tr><?php
				                if($DisplayValues['Phone'] != '')
				                {?>
				                    <td valign="middle" ><?php echo $DisplayValues['Phone']; ?></td><?php
				                }
				                else
				                {?>
				                    <td valign="middle"> - </td><?php
				                }?>
				                </tr>
				                <tr>
				                	<td valign="middle"  style='height:50px'> Languages <br>
				                		<center><?php echo $Languages; ?></center>
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
						                        <td valign="middle" style='height:70px'><?php echo $DisplayHospital['HospitalName']; ?><br>
						                            <center>
						                                <img src="<?php echo ImageUrl('ratings/'.$DisplayHospital['Star'].'.png'); ?>" >
						                            </center>
						                        </td>
						                    </tr><?php
						                }
						                else
						                {?>
											<tr>
						                        <td valign="middle" style='height:70px'><?php echo $DisplayHospital['NewHospitalName']; ?><br>
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
										<tr><td valign="middle" style='height:70px'> - </td></tr><?php
									}
								}?>
								<tr>
				                	<td valign="top">
				                    	Quality <br> 
				                		<img src='<?php echo ImageUrl("ratings/".$QualityImage); ?>' />
				                	</td>
				               	</tr>   
				                <tr>
				                	<td valign="top">
				                    	Efficiency <br>
				                		<img src='<?php echo ImageUrl("ratings/".$EfficiencyImage); ?>' />
				                	</td>
				                </tr>
							</table>
						</td><?php
					}?>
				</tr>
			</table>
		</td>
	</tr>
</table>