<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 17 Oct, 2014
* Modified date : 17 Oct, 2014
* Description 	: Page contains display doctor list details
*/ 
$HideSortBy 		= "style='display:none;'";
$ShowDocList		= '';
$DoctorListCount	= 0;
$DisplaySearchTitle	= '';
if(isset($DisplayTitle) && $DisplayTitle != '')
{
	$DisplaySearchTitle	= $DisplayTitle;
}
if(isset($DoctorList) && count($DoctorList)>0)
{
	$ShowDocList		= "style='display:block'";
	$DoctorListCount	= count($DoctorList);
	if(count($DoctorList) > 2)
	{
		$HideSortBy = "style='display:block;'";
	}
}
else if($DisplaySearchTitle != '')
{
	$ShowDocList		= "style='display:block'";
} ?>
<div class="large-12 columns" id="total_doc_list" <?php echo $ShowDocList; ?>>
	<div class="title-block" id ="doctor_title">
		<h3 class='search_doc_cond_title'>Doctor List<?php echo $DisplaySearchTitle; ?></h3>
		<select class='list_sort_by' id='list_sort_by' <?php echo $HideSortBy; ?>>
			<option value='desc'>Sort by</option>
			<option value='quality'>Quality</option>
			<option value='efficiency'>Efficiency</option>
		</select>
		<div class="divider clear_both"><span></span></div>  
	</div>	
	<div class="row" id="doctor_list"><?php 
	if(isset($DoctorList) && count($DoctorList)>0)
	{
		$DisplayCount	= 0;
		foreach($DoctorList as $DisplayValues)
		{
			$DisplayCount++;
			if($DisplayCount <= 8)
			{
				$QualityImage	= '0.png';
				$EfficiencyImage= '0.png';
				$Quality 		= $DisplayValues->Quality;
				$Efficiency 	= $DisplayValues->Efficiency;
				// Get quality rating image
				if($Quality < '0' || $Quality == '0' || $Quality == '' || $Quality == NULL)
				{
					$QualityImage 	= "0.png";	
					$Quality 		= 0;
				}
				else
				{
					$QualityImage = $Quality.".png";
				}
				// Get efficiency rating image
				if($Efficiency < '0' || $Efficiency == '0' || $Efficiency == '' || $Efficiency == NULL)
				{
					$EfficiencyImage 	= "0.png";	
					$Efficiency 		= 0;
				}
				else
				{
					$EfficiencyImage = $Efficiency.".png";
				}
				// Get doctor gender image
				$DoctorGender	= $DisplayValues->Gender;
				if($DoctorGender== 'M')
				{
					$DoctorImage = "MaleImageBig.png";
				}
				else if($DoctorGender == 'F')
				{
					$DoctorImage = "FemaleImageBig.png";
				}
				else
				{
					$DoctorImage = "NoImageBig.png";
				}
				$DisplayLanguages = $DisplayValues->Languages;
				if($DisplayLanguages == '' || $DisplayLanguages == '0')
				{
					$DisplayLanguages = 'English';
				}
				else
				{
					// Explode doctor known languages
					$ExplodeLanguage	= explode('@',$DisplayLanguages);
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
					$DisplayLanguages 	= implode(' | ',$ReturnLanguage);
					$DisplayLanguages 	= 'English | '.$DisplayLanguages;
				}
				$DoctorNpi 			= $DisplayValues->NPI;
				$DoctorId 			= $DisplayValues->id;
				$DoctorName 		= $DisplayValues->ProvName;
				/*$Prov_rename1 = str_replace(",","_",$DoctorName);
				$Prov_rename2 = str_replace(" ","_",$DoctorName);*/
				$DoctorAddress 		= $DisplayValues->Address;
				/*$ReplacedAddress 	= str_replace("#","",$DoctorAddress);*/
				$ReplacedAddress 	= str_replace("'","",$DoctorAddress);
				$ReplacedAddress 	= str_replace("<br>",",",$ReplacedAddress);
				$DoctorEducation	= (trim($DisplayValues->Education) != '')?' | '.$DisplayValues->Education:''; ?>
				<div class='large-3 columns doctor_display_list' data-quality='<?php echo $Quality; ?>' data-efficiency='<?php echo $Efficiency; ?>'>
					<article class='post col-2'>
							<div class='post_img'>
								<img src='<?php echo ImageUrl($DoctorImage); ?>' alt='<?php echo $DoctorName; ?>'/>
							</div>
							<ul class='pricing-table'>
								<li class='title'>
									<a href='javascript:void(0);' class='view_doc_details' doc-id='<?php echo $DoctorNpi; ?>' id='drill_<?php echo $DoctorNpi; ?>'>
										<?php echo $DoctorName.$DoctorEducation ?>
									</a>
								</li>
							<li class='price'><?php echo $DisplayValues->PrimarySpecialty; ?></li>
							<li class='bullet-item address'><?php 
								if($DoctorAddress != '')
								{?>
									<i class='icon-home'></i><span><?php echo $DoctorAddress; ?></span><?php 
								}
								else
								{
									echo " - ";
								}?>
							</li>
							<li class='bullet-item phone'><?php 
							if($DisplayValues->Phone!= '')
							{?>
								<i class='icon-phone'></i><span><?php echo $DisplayValues->Phone; ?></span><?php
							}
							else
							{
								echo " - ";
							}?>
							</li>
							<li class='bullet-item languages'>Languages <br> <?php echo $DisplayLanguages; ?></li>
							<li class='bullet-item'>
								Quality <br>
								<img src='<?php echo ImageUrl("ratings/".$QualityImage); ?>' alt='Doctor Quality' />
							</li>
							<li class='bullet-item'>
								Efficiency <br> 
								<img src='<?php echo ImageUrl("ratings/".$EfficiencyImage); ?>' alt='Doctor Quality' />
							</li>
							<li class='compare_map'>
								<iframe width='235' height='100' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://maps.google.it/maps?q=<?php echo $ReplacedAddress; ?>&output=embed'></iframe>
							</li>
							<input type='hidden' name='docname' id='get_name_<?php echo $DoctorId; ?>' value='<?php echo $DoctorName; ?>' />
							<input type='hidden' name='docgender' id='get_image_<?php echo $DoctorId; ?>' value='<?php echo $DoctorGender; ?>' /><?php
							if($DoctorListCount > 1)
							{?>
								<li class='cta-button'>
									<button id='<?php echo $DoctorId; ?>' class='button add_doc_compare'>Add to Compare</button>
								</li><?php
							}?>
						</ul>
					</article>
					<div id='fadeandscale<?php echo $DoctorNpi; ?>' class='well large-9 columns' style='display:none;'></div>
				</div><?php
			}
		}
	}
	else
	{?>
		<center class='color_red'>
			<h3><?php echo GetLangLabel('MsgDoctorNoResult'); ?></h3>
		</center><?php
	}?>
	</div><?php 
	if($DoctorListCount > 8)
	{?>
		<div class='row' id='more_list_div'>
			<div class='large-12 columns'>
				<button id='more_doc' class='button more_doc' type='button'>More List &gt;&gt;</button>
			</div>
		</div><?php 
	}?>
</div> 