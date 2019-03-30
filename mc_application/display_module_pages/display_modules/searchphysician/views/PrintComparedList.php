<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 06 Nov, 2014
* Modified date : 06 Nov, 2014
* Description 	: Page contains display compared doctor list details in print view
*/  ?>
 <!DOCTYPE html>
	<!--[if IE 8]> 	<html class="no-js lt-ie9" lang="en"> <![endif]-->
	<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width" />
		<title>Monocle</title>  
		<!-- Default CSS -->	
		<link rel="stylesheet" href="<?php echo CssUrl('foundation.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('fgx-foundation.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('font_awesome.min.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('bootstrap.min.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('style.css'); ?>" />
		<link rel="stylesheet" href="<?php echo CssUrl('custom_style.css'); ?>" />
		<link rel="shortcut icon" href="<?php echo ImageUrl('favicon_monocle.png'); ?>">
		<script type="text/javascript">
		function printfunc()
		{
			window.print();
			window.close();
		}
		</script>
	</head>
	<body onload="printfunc()">
		<div class="main-wrapper" id="compare_doc_list">
			<div class="large-12 columns">
			    <div class="row">
					<div class="well large-12 columns">
						<div class="title-block">
							<h3>Compare List</h3>
							<div class="divider"><span></span></div>  
						</div>
						<div class="row" id="final_compare_report"><?php
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
								<div class='large-3 columns' id='final_report_doc'>
									<article class='post col-2'>
										<div class='post_img'><img src='<?php echo ImageUrl($DoctorImage); ?>' /></div>
										<ul class='pricing-table'>
											<li class='title'><?php echo $DoctorName.$DisplayEducation; ?></li>
											<li class='price'><?php echo $DisplayValues['PrimarySpecialty']; ?></li>
											<li class='bullet-item address'>
												<i class='icon-home'></i>
												<span><?php echo $DoctorAddress; ?></span>
											</li>
											<li class='bullet-item'><?php
											if($DisplayValues['Phone'] != '')
											{?>
												<i class='icon-phone-sign'><span> <?php echo $DisplayValues['Phone']; ?></span></i><?php
											}
											else
											{
												echo " - ";
											}?>
											</li>
											<li class='bullet-item languages'>Languages <br> <?php echo $Languages; ?></li><?php
											$GetHospitalList		= $this->common_model->GetDoctorWorkedHospitals($DoctorNpi);
											$TotalDisplayHospital	= 3;
											$RemainingHospitalCount	= $TotalDisplayHospital-count($GetHospitalList);
											if(count($GetHospitalList) > 0)
											{
												foreach($GetHospitalList as $DisplayHospital)
												{
													if($DisplayHospital['HospitalName'] != '')
													{?>
														<li class='bullet-item hos_star'>
															<i class='colorred icon-hospital'></i>
															<?php echo $DisplayHospital['HospitalName']; ?>
															<br>
															<center><img src='<?php echo ImageUrl("ratings/".$DisplayHospital['Star'].".png"); ?>' alt='Hospital rating' /></center>
														</li><?php
													}
													else
													{?>
														<li class='bullet-item hos_star'>
															<i class='colorred icon-hospital'></i>
															<?php echo $DisplayHospital['NewHospitalName']; ?>
															<br>
															<center><img src='<?php echo ImageUrl("ratings/0.png"); ?>' alt='Hospital rating' /></center>
														</li><?php 
													}
												}
											}
											if($RemainingHospitalCount > 0)
											{
												for($StartLoop=0;$StartLoop<$RemainingHospitalCount;$StartLoop++) 
												{?>
													<li class='bullet-item hos_star'> - </li><?php
												}
											}?>
											<li class='bullet-item'>Quality <br> <img src='<?php echo ImageUrl("ratings/".$QualityImage); ?>' /></li>
											<li class='bullet-item'>Efficiency <br> <img src='<?php echo ImageUrl("ratings/".$EfficiencyImage); ?>' /></li>
										</ul>
									</article>
								</div><?php
							}?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>