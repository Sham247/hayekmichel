<?php
/*mysql_connect("db557033446.db.1and1.com", "dbo557033446", "AltnTech@123") or die(mysql_error());
 mysql_select_db("db557033446") or die(mysql_error());*/

mysql_connect("localhost","root","");
mysql_select_db("monocledev");

ini_set('max_execution_time', 0);

$handle = fopen("Hospital_Rank_20160728.csv",'r');
if(!$handle) die('Cannot open uploaded file.');

$id ='5565';
//Read the file as csv
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//`Id`, `state_id`, `NPI`, `LicenceNo`, `License Issuance Date`,
//`License Expiration Date`, `First Name`, `Last Name`, `Middle Name`,
//`Gender`, `Credential`, `DateOfBirth`, `RaceEthnicity`, `PlaceOfBirth`,
//`Medical school name`, `BoardAddress`, `Address`, `Graduation year`,
//`Primary specialty`, `Secondaryspecialty1`, `Secondaryspecialty2`,
//`Secondaryspecialty3`, `Secondaryspecialty4`, `Allsecondaryspecialties`,
//`Organizationlegalname`, `Line 1 Street Address`, `Line 2 Street Address`,
//`City`, `State`, `secondary_zipcode`, `Zip Code`, `CCN1`, `LBN1`, `CCN2`,
//`LBN2`, `CCN3`, `LBN3`, `CCN4`, `LBN4`, `CCN5`, `LBN5`, `erx`, `pqrs`,
//`ehr`, `accepts_medicare_assignment`, `MedicalSchool`, `Internship`,
//`Residency`, `OtherGraduateEducation`, `Disciplinary_Action_Other_States`,
//`Forced_Resignations`, `Education`, `MedicaidPatient`, `Training`, `Specialty1`,
//`Specialty2`, `Specialty3`, `Specialty4`, `Specialty5`, `BCertificate1`,
//`BCertificate2`, `FinalDisAction`, `PrivilegeRevocation`, `CriminalOffences`,
//`Malpractice`, `Status`, `HospitalStaffPrevillege`, `Award`, `O_Publications`,
//`O_Organizations`, `O_Languages`, `O_Appointments`, `NCQA_Recognization`,
//`NCQAp`, `NCQAd`, `NCQAh`, `NCQAb`, `Sp_Exp`, `RK_Q1Avg`, `RK_Q2Avg`,
//`RK_Q3Avg`, `RK_Q1SAvg`, `RK_Q2SAvg`, `RK_Q3SAvg`, `RK_Quartile1`,
//`RK_Quartile2`, `RK_Quartile3`, `RK_TotalEfficiency`, `RK_EffTopScore`,
//`EffStar`, `QualStar`, `StarProduct`, `Phone`
	
	
		
	$ProviderNumber =   sanitiseData($data['0']);
	$HospitalName =   sanitiseData($data['1']);
	$Star = sanitiseData($data['2']);
	
	$id = $id+1;
		
	echo $sql = "select count(ProviderNumber) as providercount from hospitalstar where ProviderNumber='$ProviderNumber' ";
	$query = mysql_query($sql);
	$res = mysql_fetch_array($query);
echo " provider count ".$providercount = $res['providercount'];
	if($providercount=='0')
	{
	$hospitalstar_insert_query = "INSERT INTO `hospitalstar`(`id`,`ProviderNumber`, `HospitalName`, `Star`) 
	VALUES ('$id','$ProviderNumber','$HospitalName','$Star')";
	
	echo " Newrecord  ".$hospitalstar_insert_query;//exit;
	$result = mysql_query($hospitalstar_insert_query)or die(mysql_error());
//	$last_insert_id = mysql_insert_id();
	echo $id.'<br>';//exit;
	$id=$id++;
	
	}else{
		$hospitalstar_update_query = "UPDATE `hospitalstar` set HospitalName='$HospitalName',Star='$Star' WHERE ProviderNumber='$ProviderNumber' ";
	
	echo " Updaterecord ".$hospitalstar_update_query;//exit; 
	echo "<br>";
	$result = mysql_query($hospitalstar_update_query)or die(mysql_error());
	
	}
}


function sanitiseData($data)
{
	//$data = trim($data);
	$data = str_replace("##",",",$data);
	$data = str_replace("&amp;#146;","'",$data);
	$data = str_replace("&amp;#150;","-",$data);
	$data = str_replace("&amp;#039;","'",$data);
	$data = str_replace("&amp;amp;#146;","'",$data);
	$data = str_replace("&amp;#145;","'",$data);
	$data = str_replace("&amp;amp;","&",$data);
	$data = str_replace("&amp;","&",$data);
	$data = str_replace("&#149;","•",$data);
	$data = str_replace("&#146;","'",$data);
	$data = str_replace("&#150;","-",$data);
	$data = str_replace("&#039;","'",$data);
	$data = str_replace("&#146;","'",$data);
	$data = str_replace("&#145;","'",$data);
	$data = str_replace("?s","'s",$data);
	$data = str_replace("<br />","\r\n",$data);
	$data = str_replace("<br/>","\r\n",$data);
	$data = stripslashes($data);
	//$data = str_replace("\r\n","<br/>",$data);
	//$data = htmlentities($data);
	//$data = nl2br($data);
	$data =  mysql_escape_string($data);
	return $data;
}