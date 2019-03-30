<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 19 Oct, 2014
* Modified date : 19 Oct, 2014
* Description 	: Page contains display condition list details
*/
if(isset($FirstCondition) && count($FirstCondition)>0)
{
	foreach($FirstCondition as $DisplayFirstCondition) 
	{
		$DisplayName = $DisplayFirstCondition->Condition;
		if(strtolower($DisplayName) != 'hiv')
		{
			$DisplayName = ucfirst($DisplayName);
			$DisplayName = ucfirst(strtolower($DisplayName));
		}?>
		<div class='large-6 columns'>
			<div>
	      		<span><?php
	      		echo form_radio(array('id'=>'phBreadCrumb_chkCond_'.$DisplayFirstCondition->CondNum,'name'=>'conditionname','class'=>'condnum'),$DisplayFirstCondition->CondNum); ?>
	      		</span><?php 
	      		echo form_label($DisplayName,'phBreadCrumb_chkCond_'.$DisplayFirstCondition->CondNum); ?>
			</div>      
	    </div><?php
	}
}
if(isset($SecondCondition) && count($SecondCondition)>0)
{
	foreach($SecondCondition as $DisplaySecondCondition) 
	{
		$DisplayName = $DisplaySecondCondition->SubCondition;
		if(strtolower($DisplayName) != 'human immunodeficiency virus [hiv] infection')
		{
			$DisplayName = ucfirst($DisplayName);
			$DisplayName = ucfirst(strtolower($DisplayName));
		}?>
		<div class='large-6 columns'>
			<div>
	      		<span><?php
	      		echo form_radio(array('id'=>'sub_chkCond_'.$DisplaySecondCondition->SubConditionNo,'name'=>'subconditionname','class'=>'subsub_cond_num'),$DisplaySecondCondition->SubConditionNo); ?>
	      		</span><?php 
	      		echo form_label($DisplayName,'sub_chkCond_'.$DisplaySecondCondition->SubConditionNo,array('class'=>'McToolTip','show-tip'=>$DisplaySecondCondition->description)); ?>
			</div>      
	    </div><?php
	}
}
if(isset($ThirdCondition) && count($ThirdCondition)>0)
{
	foreach($ThirdCondition as $DisplayThirdCondition) 
	{
		$DisplayName = $DisplayThirdCondition['Sub sub condition'];
		if(strtolower($DisplayName) != 'hiv')
		{
			$DisplayName = ucfirst($DisplayName);
			$DisplayName = ucfirst(strtolower($DisplayName));
		}?>
		<div class='large-6 columns'>
			<div>
	      		<span><?php
	      		echo form_radio(array('id'=>'subsubcond_chkCond_'.$DisplayThirdCondition['SSCondNum'],'name'=>'subsubconditionname'),$DisplayThirdCondition['SSCondNum']); ?>
	      		</span><?php 
	      		echo form_label($DisplayName,'subsubcond_chkCond_'.$DisplayThirdCondition['SSCondNum'],array('class'=>'McToolTip','show-tip'=>$DisplayThirdCondition['Description'])); ?>
			</div>      
	    </div><?php
	}
}
?>