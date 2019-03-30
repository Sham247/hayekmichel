<?php
/*mysql_connect("db557033446.db.1and1.com", "dbo557033446", "AltnTech@123") or die(mysql_error());
 mysql_select_db("db557033446") or die(mysql_error());*/

mysql_connect("localhost","monocleme","41#x&_Pbp<($]D");
mysql_select_db("monocledev");
echo "testing ";exit;
ini_set('max_execution_time', 0);

$handle = fopen("Provider_Master_20160727.csv",'r');
if(!$handle) die('Cannot open uploaded file.');


//Read the file as csv
while (($data = fgetcsv($handle, 1000, "|")) !== FALSE) {
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
	
	
		$id = $data['0'];
	$state_id = $data['1'];
	$NPI =  $data['2'];
	$LicenceNo = $data['3'];
	$License_Issuance_Date =  $data['4'];
	$License_Expiration_Date =  $data['5'];
	$First_Name = sanitiseData($data['6']);
	$Last_Name = sanitiseData($data['7']);
	$Middle_Name = sanitiseData($data['8']);
	$Gender =  $data['9'];
	
	$Credential =  $data['10'];
	$DateOfBirth =  $data['11'];
	$RaceEthnicity =  $data['12'];
	$PlaceOfBirth = sanitiseData($data['13']);
	$Medical_school_name = sanitiseData($data['14']);	
	$Address = sanitiseData($data['15']);
        $BoardAddress = sanitiseData($data['16']);
	$Graduation_year = $data['17'];
	$Primary_specialty = sanitiseData($data['18']);
	$Secondaryspecialty1 = sanitiseData($data['19']);
	$Secondaryspecialty2 = sanitiseData($data['20']);
	$Secondaryspecialty3 = sanitiseData($data['21']);	
	$Secondaryspecialty4 = sanitiseData($data['22']);
	$Allsecondaryspecialties = sanitiseData($data['23']);
	$Organizationlegalname = sanitiseData($data['24']);	
	$Line_1_Street_Address = sanitiseData($data['25']);
	$Line_2_Street_Address = sanitiseData($data['26']);
	$City = sanitiseData($data['27']);
	$State = sanitiseData($data['28']);	
	//$secondary_zipcode = sanitiseData($data['29']);
	$Zip_Code= sanitiseData($data['29']);
	$CCN1 = sanitiseData($data['30']);	
	$LBN1 = sanitiseData($data['31']);	
	$CCN2 = sanitiseData($data['32']);
	$LBN2 = sanitiseData($data['33']);
	$CCN3 = sanitiseData($data['34']);	
	$LBN3 = sanitiseData($data['35']);
	$CCN4= sanitiseData($data['36']);
	$LBN4 = sanitiseData($data['37']);
        $CCN5 = sanitiseData($data['38']);
	$LBN5 = sanitiseData($data['39']);
	$erx = sanitiseData($data['40']);	
	$pqrs = sanitiseData($data['41']);
	$ehr= sanitiseData($data['42']);
	$accepts_medicare_assignment = sanitiseData($data['43']);
	$MedicalSchool = sanitiseData($data['44']);
	$Internship = sanitiseData($data['45']);
	$Residency = sanitiseData($data['46']);
	$OtherGraduateEducation = sanitiseData($data['47']);	
	$Disciplinary_Action_Other_States = sanitiseData($data['48']);
	$Forced_Resignations= sanitiseData($data['49']);
	$Education = sanitiseData($data['50']);
        $MedicaidPatient = sanitiseData($data['51']);
	$Training = sanitiseData($data['52']);
	$Specialty1 = sanitiseData($data['53']);	
	$Specialty2 = sanitiseData($data['54']);
	$Specialty3= sanitiseData($data['55']);
	$Specialty4 = sanitiseData($data['56']);
        $Specialty5 = sanitiseData($data['57']); 
        $BCertificate1 = sanitiseData($data['58']);
	$BCertificate2 = sanitiseData($data['59']);
        $FinalDisAction = sanitiseData($data['60']);
	$PrivilegeRevocation = sanitiseData($data['61']);	
	$CriminalOffences = sanitiseData($data['62']);
	$Malpractice= sanitiseData($data['63']);
	$Status = sanitiseData($data['64']);
        $HospitalStaffPrevillege = sanitiseData($data['65']);
	$Award = sanitiseData($data['66']);
	$O_Publications = sanitiseData($data['67']);	
	$O_Organizations = sanitiseData($data['68']);
	$O_Languages= sanitiseData($data['69']);
	$O_Appointments = sanitiseData($data['70']);
        $NCQA_Recognization = sanitiseData($data['71']); 
        $NCQAp = sanitiseData($data['72']);
	$NCQAd = sanitiseData($data['73']);
	$NCQAh = sanitiseData($data['74']);
	$NCQAb = sanitiseData($data['75']);	
	//$Sp_Exp = sanitiseData($data['76']);
	$RK_Q1Avg= sanitiseData($data['76']);
	$RK_Q2Avg = sanitiseData($data['77']);
        $RK_Q3Avg = sanitiseData($data['78']);
	$RK_Q1SAvg = sanitiseData($data['79']);
	$RK_Q2SAvg = sanitiseData($data['80']);	
	$RK_Q3SAvg = sanitiseData($data['81']);
	$RK_Quartile1= sanitiseData($data['82']);
	$RK_Quartile2 = sanitiseData($data['83']);
        $RK_Quartile3 = sanitiseData($data['84']);
       $RK_TotalEfficiency = sanitiseData($data['85']);
        $RK_EffTopScore = sanitiseData($data['86']);
	$EffStar = sanitiseData($data['87']);
	$QualStar = sanitiseData($data['88']);	
	$StarProduct = sanitiseData($data['89']);
	$Phone= sanitiseData($data['90']);
	$is_ehc= sanitiseData($data['91']);
	
	

	$providermaster_insert_query = "INSERT INTO `providermaster_state`(`Id`, `state_id`, `NPI`, `LicenceNo`, `License Issuance Date`, `License Expiration Date`, `First Name`, `Last Name`, `Middle Name`, `Gender`, `Credential`, `DateOfBirth`, `RaceEthnicity`, `PlaceOfBirth`, `Medical school name`, `BoardAddress`, `Address`, `Graduation year`, `Primary specialty`, `Secondaryspecialty1`, `Secondaryspecialty2`, `Secondaryspecialty3`, `Secondaryspecialty4`, `Allsecondaryspecialties`, `Organizationlegalname`, `Line 1 Street Address`, `Line 2 Street Address`, `City`, `State`, `Zip Code`, `CCN1`, `LBN1`, `CCN2`, `LBN2`, `CCN3`, `LBN3`, `CCN4`, `LBN4`, `CCN5`, `LBN5`, `erx`, `pqrs`, `ehr`, `accepts_medicare_assignment`, `MedicalSchool`, `Internship`, `Residency`, `OtherGraduateEducation`, `Disciplinary_Action_Other_States`, `Forced_Resignations`, `Education`, `MedicaidPatient`, `Training`, `Specialty1`, `Specialty2`, `Specialty3`, `Specialty4`, `Specialty5`, `BCertificate1`, `BCertificate2`, `FinalDisAction`, `PrivilegeRevocation`, `CriminalOffences`, `Malpractice`, `Status`, `HospitalStaffPrevillege`, `Award`, `O_Publications`, `O_Organizations`, `O_Languages`, `O_Appointments`, `NCQA_Recognization`, `NCQAp`, `NCQAd`, `NCQAh`, `NCQAb`, `RK_Q1Avg`, `RK_Q2Avg`, `RK_Q3Avg`, `RK_Q1SAvg`, `RK_Q2SAvg`, `RK_Q3SAvg`, `RK_Quartile1`, `RK_Quartile2`, `RK_Quartile3`, `RK_TotalEfficiency`, `RK_EffTopScore`, `EffStar`, `QualStar`, `StarProduct`, `Phone`,`is_ehc`) 
	VALUES ('','$state_id','$NPI','$LicenceNo','$License_Issuance_Date','$License_Expiration_Date','$First_Name','$Last_Name','$Middle_Name','$Gender','$Credential','$DateOfBirth','$RaceEthnicity','$PlaceOfBirth','$Medical_school_name','$BoardAddress','$Address','$Graduation_year','$Primary_specialty','$Secondaryspecialty1','$Secondaryspecialty2','$Secondaryspecialty3','$Secondaryspecialty4','$Allsecondaryspecialties','$Organizationlegalname','$Line_1_Street_Address','$Line_2_Street_Address','$City','$State','$Zip_Code','$CCN1','$LBN1','$CCN2','$LBN2','$CCN3','$LBN3','$CCN4','$LBN4','$CCN5','$LBN5','$erx','$pqrs','$ehr','$accepts_medicare_assignment','$MedicalSchool','$Internship','$Residency','$OtherGraduateEducation','$Disciplinary_Action_Other_States','$Forced_Resignations','$Education','$MedicaidPatient','$Training','$Specialty1','$Specialty2','$Specialty3','$Specialty4','$Specialty5','$BCertificate1','$BCertificate2','$FinalDisAction','$PrivilegeRevocation','$CriminalOffences','$Malpractice','$Status','$HospitalStaffPrevillege','$Award','$O_Publications','$O_Organizations','$O_Languages','$O_Appointments','$NCQA_Recognization','$NCQAp','$NCQAd','$NCQAh','$NCQAb','$RK_Q1Avg','$RK_Q2Avg','$RK_Q3Avg','$RK_Q1SAvg','$RK_Q2SAvg','$RK_Q3SAvg','$RK_Quartile1','$RK_Quartile2','$RK_Quartile3','$RK_TotalEfficiency','$RK_EffTopScore','$EffStar','$QualStar','$StarProduct','$Phone','$is_ehc')";
	
	echo $providermaster_insert_query;exit;
	$result = mysql_query($providermaster_insert_query)or die(mysql_error());
	$last_insert_id = mysql_insert_id();
	echo $last_insert_id.'<br>';//exit;
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