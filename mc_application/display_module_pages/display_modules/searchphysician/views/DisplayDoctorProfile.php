<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : o4 Nov, 2014
* Modified date : 04 Nov, 2014
* Description 	: Page contains display doctor profile details
*/
if(isset($DoctorProfile) && count($DoctorProfile)>0)
{
	$DisplayEducation	= ($DoctorProfile[0]['Education'] != '')?' | '.$DoctorProfile[0]['Education']:'';
	$DoctorName 		= $DoctorProfile[0]['ProvName'];
	$DoctorNpi 			= $DoctorProfile[0]['NPI'];
	$Quality 			= $DoctorProfile[0]['Quality'];
	$Efficiency 		= $DoctorProfile[0]['Efficiency'];
	$QualityImage		= "0.png";
	$EfficiencyImage	= "0.png";
	if($Quality >= 0)
	{
		$QualityImage = $Quality.".png";	
	}
	if($Efficiency >= 0)
	{
		$EfficiencyImage = $Efficiency.".png";	
	}
	$DoctorGender 		= $DoctorProfile[0]['Gender'];
	$DoctorGenderText	= "Not Specified";
	if($DoctorGender == 'M')
	{
		$DoctorGenderText = "Male";
	}
	else if($DoctorGender == 'F')
	{
		$DoctorGenderText = "Female";
	}
	$Languages 			= $DoctorProfile[0]['Languages'];
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
	} ?>
	<div class='title-block'>
		<h3><?php echo $DoctorName; ?></h3>
		<div class='divider'><span></span></div>  
	</div>
	<div class='row'>
		<div class='large-8 large-centered columns'>
			<table width='100%'>
				<tbody>
					<tr>
						<td>Name:</td>
						<td><?php echo $DoctorName.$DisplayEducation; ?></td>
					</tr>
					<tr>
						<td>Practice:</td>
						<td><?php 
						if(trim($DoctorProfile[0]['Organizationlegalname']) != '')
						{
							echo $DoctorProfile[0]['Organizationlegalname'];
						}
						else
						{
							echo " - ";
						}?>
						</td>
					</tr><?php 
					$DoctorAddress	= $this->common_model->GetDoctorAddress($DoctorNpi);
					if(isset($DoctorAddress) && count($DoctorAddress)>0)
					{
						$AddressCount = 0;
						$SecondaryAddress = "";
						$GetListCount = count($DoctorAddress);
						foreach($DoctorAddress as $DisplayAddress)
						{
							$AddressCount++;
							$DisplayFoundAddress = $DisplayAddress['Address'];
							if($AddressCount == 1 && $DisplayAddress['BoardAddress'] != '' && $DisplayAddress['BoardAddress'] != $DisplayFoundAddress)
							{?>
								<tr>
									<td>Address <?php echo $AddressCount; ?>:</td>
									<td><?php echo $DisplayAddress['BoardAddress']; ?></td>
								</tr><?php 
								$AddressCount++;
								$GetListCount = $GetListCount+1;
							}?>
							<tr>
								<td>Address <?php echo ($GetListCount>1)?$AddressCount:''; ?>:</td>
								<td><?php echo $DisplayFoundAddress; ?></td>
							</tr><?php

						}
					}
					else
					{?>
						<tr>
							<td>Address :</td>
							<td> - </td>
						</tr><?php
					}?>
					<tr>
						<td>Phone:</td>
						<td><?php
						if(trim($DoctorProfile[0]['Phone']) != '')
						{
							echo $DoctorProfile[0]['Phone'];
						}
						else
						{
							echo " - ";
						}?>
						</td>
					</tr>
					<tr>
						<td>Specialty:</td>
						<td><?php echo $DoctorProfile[0]['PrimarySpecialty']; ?></td>
					</tr>	
					<tr>
						<td>Gender:</td>
						<td><?php echo $DoctorGenderText; ?></td>
					</tr>	
					<tr>
						<td>Medical School:</td>
						<td><?php 
						$SchoolPresent	= false;
						if($DoctorProfile[0]['Medical school name'] == 'OTHER' || $DoctorProfile[0]['Medical school name'] == null || $DoctorProfile[0]['Medical school name'] == '')
						{
							$ExplodeMedSchool	= explode('@@@',$DoctorProfile[0]['MedicalSchool']);
							$MedSchoolCount   = 0;
							if(count($ExplodeMedSchool)>0)
							{
								foreach($ExplodeMedSchool as $DisplaySchoolVal)
								{
									if($DisplaySchoolVal != '')
									{
										$ExplodeSchlName	= explode('|',$DisplaySchoolVal);
										if(count($ExplodeSchlName)>0)
										{
											$MedSchoolCount++;
											$SchoolPresent = true;
											if(count($ExplodeMedSchool)>1)
											{?>
												<div><?php echo $MedSchoolCount.". ".$ExplodeSchlName[0]; ?></div><?php 
											}
											else
											{?>
												<div><?php echo $ExplodeSchlName[0]; ?></div><?php 
											}
										}
									}
								}
							}
						}
						else
						{
							if(trim($DoctorProfile[0]['Medical school name']) != '')
							{
								$ExplodeMedSchool	= explode('|',$DoctorProfile[0]['Medical school name']);
								if(count($ExplodeMedSchool)>0)
								{
									$SchoolPresent = true; ?>
									<div><?php echo $ExplodeMedSchool[0]; ?></div><?php 
								}
							}
						}
						if($SchoolPresent == false)
						{?>
							<div> - </div><?php 
						}?>
						</td>
					</tr>		
					<tr>
						<td>Graduation year:</td>
						<td><?php 
						if(trim($DoctorProfile[0]['Graduation year']) !='')
						{
							echo $DoctorProfile[0]['Graduation year'];
						}
						else
						{
							echo " - ";
						}?>
						</td>
					</tr>
					<tr>
						<td>Board Certifications:</td>
						<td><?php
						$DoctorCertificate		= $DoctorProfile[0]['Doctor_certificate'] ;
						$SplitDoctorCertificate	= explode('|@@|',$DoctorCertificate);
						$SplitDoctorCertificate	= array_filter($SplitDoctorCertificate);
						$CertificateCount		= 0;
						if(count($SplitDoctorCertificate)>0)
						{
							foreach($SplitDoctorCertificate as $DisplayCertificate)
							{
								if($DisplayCertificate != '')
								{
									$CertificateCount++;
									$DisplayCertificateCount = (count($SplitDoctorCertificate)>1)?$CertificateCount.". ":''; ?>
									<div><?php echo $DisplayCertificateCount.$DisplayCertificate; ?></div><?php 
								}
							}
						}
						else
						{
							echo " - ";
						}?>
						</td>
					</tr>
					<tr>
						<td>Residency:</td>
						<td><?php
						if($DoctorProfile[0]['residency'] != '')
						{
							echo $DoctorProfile[0]['residency'];
						}
						else
						{
							echo ' - ';
						}?>
						</td>
					</tr>
					<tr>
						<td>Accepts Medicare:</td>
						<td><?php
						if($DoctorProfile[0]['accepts_medicare_assignment'] != '')
						{
							if($DoctorProfile[0]['accepts_medicare_assignment'] != '')
							{
								if($DoctorProfile[0]['accepts_medicare_assignment'] == 'Y')
								{
									echo 'Accepts';
								}
								else if($DoctorProfile[0]['accepts_medicare_assignment'] == 'M')
								{
									echo 'May Accept';
								}
								else
								{
									echo 'No';
								}
							}
						}
						else
						{
							echo ' - ';
						}?>
						</td>
					</tr>
					<tr>
						<td>Participates in eRx:</td>
						<td><?php 
						if($DoctorProfile[0]['erx'] != '')
						{
							if($DoctorProfile[0]['erx'] == 'Y')
							{
								echo 'Yes';
							}
							else
							{
								echo 'No';
							}
						}
						else
						{
							echo ' - ';
						}?>
						</td>
					</tr>
					<tr>
						<td>Participates in PQRS:</td>
						<td><?php
						if($DoctorProfile[0]['pqrs'] != '')
						{
							if($DoctorProfile[0]['pqrs'] == 'Y')
							{
								echo 'Yes';
							}
							else
							{
								echo 'No';
							}
						}
						else
						{
							echo ' - ';
						}?>
						</td>
					</tr>
					<tr>
						<td>Participates in EHR:</td>
						<td><?php
						if($DoctorProfile[0]['ehr'] != '')
						{
							if($DoctorProfile[0]['ehr'] == 'Y')
							{
								echo 'Yes';
							}
							else
							{
								echo 'No';
							}
						}
						else
						{
							echo ' - ';
						}?>
						</td>
					</tr>
					<tr>
						<td>NCQA Recognition:</td>
						<td><?php 
						if(($DoctorProfile[0]['NCQA_Recognization'] != '' && $DoctorProfile[0]['NCQA_Recognization'] != '0'))
						{
							$SplitRecognization	= explode('@',$DoctorProfile[0]['NCQA_Recognization']);
							$SplitRecognization	= array_filter($SplitRecognization);
							$RecognitionCount	= 0;
							if(count($SplitRecognization)>0)
							{
								foreach($SplitRecognization as $DisplayRecognization)
								{
									$RecognitionCount++;
									$AddRecongCount	= (count($SplitRecognization)>1)?$RecognitionCount.'. ':'';
									echo "<div>".$AddRecongCount.$DisplayRecognization."</div>";
								}
							}
							else
							{
								echo " - ";
							}
						}
						else
						{
							echo " - ";
						}?>
						</td>
					</tr>
					<tr>
						<td>Disciplinary action:</td>
						<td><?php 
						if(($DoctorProfile[0]['FinalDisAction'] != '' && $DoctorProfile[0]['FinalDisAction'] != '0'))
						{
							$SplitDisAction	= explode('@',$DoctorProfile[0]['FinalDisAction']);
							$SplitDisAction	= array_filter($SplitDisAction);
							$DisActionCount	= 0;
							if(count($SplitDisAction)>0)
							{
								foreach($SplitDisAction as $DisplayDisAction)
								{
									$DisActionCount++;
									$AddDisActionCount	= (count($SplitDisAction)>1)?$DisActionCount.'. ':'';
									echo "<div>".$AddDisActionCount.$DisplayDisAction."</div>";
								}
							}
							else
							{
								echo " - ";
							}
						}
						else
						{
							echo " - ";
						}?>
						</td>
					</tr>
					<tr>
						<td>Criminal Offences:</td>
						<td><?php 
						if(($DoctorProfile[0]['CriminalOffences'] != '' && $DoctorProfile[0]['CriminalOffences'] != '0'))
						{
							$SplitCrimeOffences	= explode('@',$DoctorProfile[0]['CriminalOffences']);
							$SplitCrimeOffences	= array_filter($SplitCrimeOffences);
							$CrimeOffCount		= 0;
							if(count($SplitCrimeOffences)>0)
							{
								foreach($SplitCrimeOffences as $DisplayCrimeOffences)
								{
									$CrimeOffCount++;
									$AddCrimeActCount	= (count($SplitCrimeOffences)>1)?$CrimeOffCount.'. ':'';
									echo "<div>".$AddCrimeActCount.$DisplayCrimeOffences."</div>";
								}
							}
							else
							{
								echo " - ";
							}
						}
						else
						{
							echo " - ";
						}?>
						</td>
					</tr>
					<tr>
						<td>Publications:</td>
						<td><?php
						if(($DoctorProfile[0]['O_Publications'] != '' && $DoctorProfile[0]['O_Publications'] != '0'))
						{
							$SplitPublications	= explode('@',$DoctorProfile[0]['O_Publications']);
							$SplitPublications	= array_filter($SplitPublications);
							$PublicationCount	= 0;
							if(count($SplitPublications)>0)
							{
								foreach($SplitPublications as $DisplayPublications)
								{
									$PublicationCount++;
									$AddPublicationCount	= (count($SplitPublications)>1)?$PublicationCount.'. ':'';
									echo "<div>".$AddPublicationCount.str_replace('|',' ',$DisplayPublications)."</div>";
								}
							}
							else
							{
								echo " - ";
							}
						}
						else
						{
							echo " - ";
						}?>
						</td>
					</tr>	
					<tr>
						<td>Languages:</td>
						<td><?php echo $Languages ?></td>
					</tr>
					<tr>
						<td>Organizations:</td>
						<td><?php
						if(trim($DoctorProfile[0]['O_Organizations']) != '' && $DoctorProfile[0]['O_Organizations'] != '0')
						{
							$SplitOrganization	= explode('|',$DoctorProfile[0]['O_Organizations']);
							$SplitOrganization	= array_filter($SplitOrganization);
							$OrgCount	= 0;
							if(count($SplitOrganization)>0)
							{
								foreach($SplitOrganization as $DisplayOrganization)
								{
									$OrgCount++;
									$AddOrgCount	= (count($SplitOrganization)>1)?$OrgCount.'. ':'';
									echo "<div>".$AddOrgCount.str_replace('|',' ',$DisplayOrganization)."</div>";
								}
							}
							else
							{
								echo " - ";
							}
						}
						else
						{
							echo " - ";
						}?>
						</td>
					</tr>		
					<tr>
						<td>Appointments:</td>
						<td><?php 
						if(($DoctorProfile[0]['O_Appointments'] != '' && $DoctorProfile[0]['O_Appointments'] != '0'))
						{
							$SplitAppoinments	= explode('@',$DoctorProfile[0]['O_Appointments']);
							$SplitAppoinments	= array_filter($SplitAppoinments);
							$AppoinmentCount	= 0;
							if(count($SplitAppoinments)>0)
							{
								foreach($SplitAppoinments as $DisplayAppoinments)
								{
									$AppoinmentCount++;
									$AddAppoinmentCount	= (count($SplitAppoinments)>1)?$AppoinmentCount.'. ':'';
									echo "<div>".$AddAppoinmentCount.str_replace('|',' ',$DisplayAppoinments)."</div>";
								}
							}
							else
							{
								echo " - ";
							}
						}
						else
						{
							echo " - ";
						}?>
						</td>
					</tr>
					<tr>
						<td>Quality:</td>
						<td>
							<img src='<?php echo ImageUrl("ratings/".$QualityImage); ?>' alt='Quality' />
						</td>
					</tr>
					<tr>
						<td>Efficiency:</td>
						<td>
							<img src='<?php echo ImageUrl("ratings/".$EfficiencyImage); ?>' alt='Efficiency' />
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<br><?php
	if($GetPageType == '')
	{?>
		<button class='<?php echo $GetType.$DoctorProfile[0]['NPI']; ?>_close btn btn-default pull-right'>Close</button><?php
	}
	else
	{?>
		<button class='go_back_to_compare btn btn-default pull-right'>Go Back</button>";
		<button class='fadeandscale1_close btn btn-default pull-right marginRight10px'>Close</button><?php
	}
}?>