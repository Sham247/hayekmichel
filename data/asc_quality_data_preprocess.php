<?php

  $dsn = 'mysql:dbname=monocledev;host=127.0.0.1:8889';
  $user = 'root';
  $pass = 'root';

//  $options = array(PDO::ATTR_AUTOCOMMIT=>FALSE);

  $pdo = new PDO($dsn,$user,$pass);

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


// SELECT THe table with the min, max, avg and count for each state, for each quality measure.

//  $data = $pdo->query("SELECT _hospital_quality_min_max_count.*, _hospital_quality_cats_weight.category_weight_pct from _hospital_quality_min_max_count, _hospital_quality_cats_weight, _hospital_quality_measures  where _hospital_quality_min_max_count.location_state = 'GA' AND _hospital_quality_cats_weight.id = _hospital_quality_min_max_count.quality_category AND _hospital_quality_measures.measure_name = _hospital_quality_min_max_count.measure_name group by _hospital_quality_min_max_count.location_state, _hospital_quality_min_max_count.quality_category, _hospital_quality_min_max_count.measure_name")->fetchAll();
  $items_array = array(1,2,3,4,8,9,10,11,12);
  foreach($items_array as $i) {
    if ($i < 12) {
      $data = $pdo->query("SELECT state, min(`ASC_" . $i . "_Rate`) as statemin, max(`ASC_" . $i . "_Rate`) as statemax, avg(`ASC_" . $i . "_Rate`) as stateavg, count(npi) as statecount, 'ASC-" . $i . "' as measure_id from _amb_surgical_quality_medicare_raw_april2019 where `ASC_" . $i . "_Rate` != 'N/A' AND `ASC_" . $i . "_Rate`  > 0 group by state")
        ->fetchAll();
      foreach ($data as $d) {
        $datac = [
          'state' => $d['state'],
          'statemin' => $d['statemin'],
          'statemax' => $d['statemax'],
          'stateavg' => $d['stateavg'],
          'statecount' => $d['statecount'],
          'measure_id' => $d['measure_id'],
        ];
        $sqlc = "INSERT into _amb_surg_aggregates(state, statemin, statemax, stateavg, statecount, measure_id) VALUES (:state, :statemin, :statemax, :stateavg, :statecount, :measure_id)";
        $stmt = $pdo->prepare($sqlc);
        $stmt->execute($datac);
      }
    } else {
  $data = $pdo->query("SELECT state, min(`ASC_12_Total_Cases`) as asc12_totalcases_min, max(`ASC_12_Total_Cases`) as asc12_totalcases_max, avg(`ASC_12_Total_Cases`) as asc12_totalcases_avg, count(`ASC_12_Total_Cases`) as asc12_totalcases_count from _amb_surgical_quality_medicare_raw_april2019 where `ASC_12_Total_Cases` != 'N/A' AND `ASC_12_Total_Cases` > 0 group by state")
    ->fetchAll();
  foreach ($data as $d) {
    $datac = [
      'state' => $d['state'],
      'asc12_totalcases_count' => $d['asc12_totalcases_count'],
      'asc12_totalcases_min' => $d['asc12_totalcases_min'],
      'asc12_totalcases_max' => $d['asc12_totalcases_max'],
      'asc12_totalcases_avg' => $d['asc12_totalcases_avg'],
    ];
    $sqlc = "INSERT into _amb_surg_aggregates(state, asc_12_total_cases, asc_12_total_cases_min, asc_12_total_cases_max, asc_12_total_cases_avg) VALUES (:state, :asc12_totalcases_count, :asc12_totalcases_min, :asc12_totalcases_max, :asc12_totalcases_avg)";
    $stmt = $pdo->prepare($sqlc);
    $stmt->execute($datac);
  }
}
  }


