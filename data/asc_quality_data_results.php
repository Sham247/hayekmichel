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

  $items_array = array(1,2,3,4,8,9,10,11,12);
  foreach($items_array as $i) {
    $laststate = '';
    if ($i < 12) {
      $states_array = [
        'AK',
        'AL',
        'AR',
        'AZ',
        'CA',
        'CO',
        'CT',
        'DE',
        'FL',
        'GA',
        'HI',
        'IA',
        'ID',
        'IL',
        'IN',
        'KS',
        'KY',
        'LA',
        'MA',
        'MD',
        'ME',
        'MI',
        'MN',
        'MO',
        'MS',
        'MT',
        'NC',
        'ND',
        'NE',
        'NH',
        'NJ',
        'NM',
        'NV',
        'NY',
        'OH',
        'OK',
        'OR',
        'PA',
        'RI',
        'SC',
        'SD',
        'TN',
        'TX',
        'UT',
        'VA',
        'VT',
        'WA',
        'WI',
        'WV',
        'WY'
      ];
      foreach ($states_array as $s) {
        $array_of_scores = [];
        $data = $pdo->query("select id, npi, provider_id, ASC_Name, state, ASC_" . $i . "_Rate as thescore from _amb_surgical_quality_medicare_raw_april2019 where ASC_" . $i . "_Rate > 0 AND state = '" . $s . "' order by state, ASC_" . $i . "_Rate")
          ->fetchAll();
        $index = 0;
        $counter = 0;
//        $array_of_scores = [];
        foreach ($data as $d) {
          array_push($array_of_scores, $d['thescore']);
        }
        reset($data);
        foreach ($data as $d) {
          $counter++;
          if ($counter == 1) {
            echo '<h3>' . $d['state'] . ' - ASC_' . $i . '_Rate</h3>';
          }
          echo 'The counter is: ' . $counter . '<hr>';

          if (count($array_of_scores) > 0) {
            $index = 100 / count($array_of_scores);
            echo '<h4>THe index is: ' . $index . '</h4>';
            $theItem = number_smaller($array_of_scores, $d['thescore']);
            $percentile = (intval($theItem * $index));
            if ($percentile == 100) {
              $percentile = 99;
            }
            echo '<br>the percentile is: ' . $percentile;
            $datac = [
              'npi' => $d['npi'],
              'provider_id' => $d['provider_id'],
              'asc_name' => $d['ASC_Name'],
              'state' => $d['state'],
              'measure_id' => 'ASC_' . $i . '_Rate',
              'score' => $d['thescore'],
              'percentile' => $percentile,
              'state_measure_count' => count($array_of_scores),
              'original_id' => $d['id'],
            ];
            $sqlc = "INSERT INTO _amb_surgical_results (npi, provider_id, asc_name, state, measure_id, score, percentile, state_measure_count, original_id) VALUES (:npi, :provider_id, :asc_name, :state, :measure_id, :score, :percentile, :state_measure_count, :original_id)";
            //  echo $sqlc. '<hr>';
            $stmt = $pdo->prepare($sqlc);
            $stmt->execute($datac);
          }
        }
      }
    }
  }

  function number_smaller($array, $number)
  {
    $counter = 0;
    foreach($array as $a) {
      if($a <= $number) {
        $counter++;
      }
    }
    return $counter;
  }