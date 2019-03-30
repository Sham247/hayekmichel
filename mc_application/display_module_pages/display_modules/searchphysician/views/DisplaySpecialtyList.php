<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 31 Oct, 2014
* Modified date : 31 Oct, 2014
* Description 	: Page contains display specialty list details
*/
// Display primary specialty details
if(isset($FirstSpecialty) && count($FirstSpecialty)>0)
{
	foreach($FirstSpecialty as $DisplayFirstSpecialty) 
	{
		$DisplayName = $DisplayFirstSpecialty->Specialty;
		$DisplayName = ucfirst($DisplayName);
		$DisplayName = ucfirst(strtolower($DisplayName)); ?>
		<div class='large-6 columns'>
			<div>
	      		<span><?php
	      		echo form_radio(array('id'=>'specialty_chkCond_'.$DisplayFirstSpecialty->SpecialtyNo,'name'=>'specialtyname','class'=>'specnum'),$DisplayFirstSpecialty->SpecialtyNo); ?>
	      		</span><?php 
	      		echo form_label($DisplayName,'specialty_chkCond_'.$DisplayFirstSpecialty->SpecialtyNo); ?>
			</div>      
	    </div><?php
	}
}
// Display sub specialty details
if(isset($SecondSpecialty) && count($SecondSpecialty)>0)
{
	foreach($SecondSpecialty as $DisplaySecondSpecialty) 
	{
		$DisplayName = $DisplaySecondSpecialty->SubSpecialty;
		$DisplayName = ucfirst($DisplayName);
		$DisplayName = ucfirst(strtolower($DisplayName)); ?>
		<div class='large-6 columns'>
			<div>
	      		<span><?php
	      		echo form_radio(array('id'=>'subspecial_chkCond_'.$DisplaySecondSpecialty->SubSpecialtyNo,'name'=>'subspecialtyname','class'=>'subspecial_chkCond'),$DisplaySecondSpecialty->SubSpecialtyNo); ?>
	      		</span><?php 
	      		echo form_label($DisplayName,'subspecial_chkCond_'.$DisplaySecondSpecialty->SubSpecialtyNo); ?>
			</div>      
	    </div><?php
	}
}
// Display specialty based procedure group details
if(isset($ThirdSpecialty) && count($ThirdSpecialty)>0)
{
	foreach($ThirdSpecialty as $DisplayThirdSpecialty) 
	{
		$DisplayName = $DisplayThirdSpecialty->Procedure_Group;
		$DisplayName = ucfirst($DisplayName);
		$DisplayName = ucfirst(strtolower($DisplayName)); ?>
		<div class='large-6 columns'>
			<div>
	      		<span><?php
	      		echo form_radio(array('id'=>'proce_group_chkCond_'.$DisplayThirdSpecialty->Procedure_Group_Control_No,'name'=>'proceduregroup'),$DisplayThirdSpecialty->Procedure_Group_Control_No); ?>
	      		</span><?php 
	      		echo form_label($DisplayName,'proce_group_chkCond_'.$DisplayThirdSpecialty->Procedure_Group_Control_No); ?>
			</div>      
	    </div><?php
	}
}?>