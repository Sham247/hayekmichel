<?php 

$dsn = 'mysql:dbname=monocledev;host=127.0.0.1:8889';
$user = 'root';
$pass = 'root';

$options = array(PDO::ATTR_AUTOCOMMIT=>FALSE);

$pdo = new PDO($dsn,$user,$pass,$options);

try {
    $dbh = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}


// get the general info

// $sql = 'SELECT procedure_name, url from subcategories where catid = :id  and active = 1 and url != "" order by procedure_name';
// $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
// $sth->execute(array(':id' => $id));
// $results = $sth->fetchAll();
// foreach($results as $r){ 

    


    $data = $pdo->query("SELECT provider_id, hospital_name, location_address, location_city, location_state, location_zip from medicare_hospital_compare_general WHERE location_state = 'GA' order by hospital_name")->fetchAll();
    echo '<table border = 1><tr><th>Provider ID</th><th>Hospital Name</th><th>Address</th></tr>';
    foreach($data as $d){
        echo '<tr><td>'. $d['provider_id'].'</td><td>' . $d['hospital_name'] . '</td><td>' . $d['location_address'] . ' - ' . $d['location_city'] . ',  ' .$d['location_state'] . '  ' . $d['location_zip'] . '</td></tr>';
    }
echo '</table>';
// get the patient experiences
echo '<hr>';
echo '<h3>Patient Experiences (only AU Medical Associates)</h3>';

$data = $pdo->query("SELECT organization_legal_name_or_doing_business_as_name as hospname, measure_identifier, measure_performance_rate, measure_title, denominator_count from medicare_hospital_compare_patient_experiences WHERE state = 'GA' AND organization_legal_name_or_doing_business_as_name = 'AU MEDICAL ASSOCIATES' order by organization_legal_name_or_doing_business_as_name")->fetchAll();
echo '<table border="1"><tr><th>Hospital Name</th><th>Meaure Identifer</th><th>Measure Title</th><th>Measure Performance</th><th>Denominator</th></tr>';

foreach($data as $d){
echo '<tr><td>' .  $d['hospname'] . '</td><td>' . $d['measure_identifier'] . '</td><td>' . $d['measure_title'] .'</td><td>' . $d['measure_performance_rate'] . '</td><td>'. $d['denominator_count'] . '</td></tr>';
}
echo '</table>';
echo '<hr>';
// get timely and effective care
echo '<h3>Timely and Effective Care (only AU Medical Center)</h3>';

$data = $pdo->query("SELECT hospital_name as hospname, measure_id, measure_name, sample, score  from medicare_hosp_compare_timely_effective_by_hospital WHERE hospital_name = 'AU MEDICAL CENTER' order by measure_name")->fetchAll();
echo '<table border="1"><tr><th>Hospital Name</th><th>Meaure Identifer</th><th>Measure Title</th><th>Measure Performance</th><th>Sample</th></tr>';

foreach($data as $d){
echo '<tr><td>' .  $d['hospname'] . '</td><td>' . $d['measure_id'] . '</td><td>' . $d['measure_name'] .'</td><td>' . $d['score'] . '</td><td>'. $d['sample'] . '</td></tr>';
}
echo '</table>';
echo '<hr>';

// get complications and death
echo '<h3>Complications & Death (only AU Medical Center)</h3>';

$table = 'medicare_hosp_complications_death';

$data = $pdo->query("SELECT hospital_name as hospname, measure_id, measure_name, lower_estimate, higher_estimate, denominator, score  from medicare_hosp_complications_death WHERE hospital_name = 'AU MEDICAL CENTER' order by measure_name")->fetchAll();
echo '<table border="1"><tr><th>Hospital Name</th><th>Meaure Identifer</th><th>Measure Title</th><th>Score</th><th>Higher Estimate</th><th>Lower Estimate</th><th>Denominator</th></tr>';

foreach($data as $d){
echo '<tr><td>' .  $d['hospname'] . '</td><td>' . $d['measure_id'] . '</td><td>' . $d['measure_name'] .'</td><td>' . $d['score'] . '</td><td>' . $d['higher_estimate'] . '</td><td>' . $d['lower_estimate'] . '</td><td>'. $d['denominator'] . '</td></tr>';
}
echo '</table>';
echo '<hr>';
// get unplanned hospital visits
echo '<h3>Unplanned Hospital Visits (only AU Medical Center)</h3>';


$data = $pdo->query("SELECT hospital_name as hospname, measure_id, measure_name, lower_estimate, higher_estimate, denominator, score  from medicare_hosp_unplanned_by_hospital WHERE hospital_name = 'AU MEDICAL CENTER' order by measure_name")->fetchAll();
echo '<table border="1"><tr><th>Hospital Name</th><th>Meaure Identifer</th><th>Measure Title</th><th>Score</th><th>Higher Estimate</th><th>Lower Estimate</th><th>Denominator</th></tr>';

foreach($data as $d){
echo '<tr><td>' .  $d['hospname'] . '</td><td>' . $d['measure_id'] . '</td><td>' . $d['measure_name'] .'</td><td>' . $d['score'] . '</td><td>' . $d['higher_estimate'] . '</td><td>' . $d['lower_estimate'] . '</td><td>'. $d['denominator'] . '</td></tr>';
}
echo '</table>';
echo '<hr>';

// get use of medical imaging
echo '<h3>Use of Medical Imaging (only AU Medical Center)</h3>';

$data = $pdo->query("SELECT hospital_name as hospname, measure_id, measure_name, score  from medicare_hosp_comp_imaging_by_hosp WHERE hospital_name = 'AU MEDICAL CENTER' order by measure_name")->fetchAll();
echo '<table border="1"><tr><th>Hospital Name</th><th>Meaure Identifer</th><th>Measure Title</th><th>Score</th></tr>';

foreach($data as $d){
echo '<tr><td>' .  $d['hospname'] . '</td><td>' . $d['measure_id'] . '</td><td>' . $d['measure_name'] .'</td><td>' . $d['score'] . '</td></tr>';
}
echo '</table>';
echo '<hr>';


// get payment & value of care....
echo '<h3>Payment & Value of Care (only AU Medical Center)</h3>';

$data = $pdo->query("SELECT hospital_name as hospname, measure_id, measure_name, payment, value_of_care_category, value_of_care_display_name, lower_estimate, higher_estimate, denominator  from medicare_hosp_comp_payment_value_by_hosp WHERE hospital_name = 'AU MEDICAL CENTER' order by value_of_care_category, value_of_care_display_name")->fetchAll();
echo '<table border="1"><tr><th>Hospital Name</th><th>Meaure Identifer</th><th>Measure Name</th><th>Payment</th><th>Value Category</th><th>Value Name</th><th>Lower Estimate</th><th>Higher Estimate</th><th>Denominator</th></tr>';

foreach($data as $d){
echo '<tr><td>' .  $d['hospname'] . '</td><td>' . $d['measure_id'] . '</td><td>' . $d['measure_name'] .'</td><td>' . $d['payment'] .'</td><td>' . $d['value_of_care_category'] .'</td><td>' . $d['value_of_care_display_name'] .'</td><td>' . $d['lower_estimate'] .'</td><td>' . $d['higher_estimate'] .'</td><td>' . $d['denominator'] . '</td></tr>';
}
echo '</table>';
echo '<hr>';

