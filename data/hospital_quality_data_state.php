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

  $data = $pdo->query("select mea.measure_name, st.state, st.hcahps_answer_percent from _hospital_quality_measures as mea, __medicare_hosp_comp_hcaps_by_state as st where mea.`measure_name` = st.`hcahps_question` AND hcahps_answer_percent != '' order by measure_name, state")->fetchAll();
    foreach($data as $d) {
      $factor = 0;
      $counter = 0;
      //   $item_array = array();
      //    $item_array = get_percentiles($d['state_measure_count'], $d['state_measure_max']);
      echo '<h3>' . $d['measure_name'] . '</h3>';
      echo $d['state'] . ' - ' . $d['hcahps_answer_percent'];

      $datac = [
              'state_average' => $d['hcahps_answer_percent'],
              'state' => $d['state'],
              'measure_name' => $d['measure_name'],
            ];
            $sqlc = "UPDATE _hospital_quality_results set state_average = :state_average where location_state = :state AND measure_name = :measure_name";
            echo $sqlc.'<hr>';
            $stmt = $pdo->prepare($sqlc);
            $stmt->execute($datac);

         }