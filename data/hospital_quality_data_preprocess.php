<?php

  $dsn = 'mysql:dbname=monocledev;host=127.0.0.1:8889';
  $user = 'root';
  $pass = 'root';

  //  $options = array(PDO::ATTR_AUTOCOMMIT=>FALSE);

  $pdo = new PDO($dsn, $user, $pass);

  try {
    $dbh = new PDO($dsn, $user, $pass);
  } catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }


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
    'WY',
  ];
  foreach ($states_array as $s) {
    $data = $pdo->query("SELECT _hospital_quality_min_max_count.*, _hospital_quality_cats_weight.category_weight_pct, _hospital_quality_measures.lower_is_better as lib from _hospital_quality_min_max_count, _hospital_quality_cats_weight, _hospital_quality_measures  where  _hospital_quality_cats_weight.id = _hospital_quality_min_max_count.quality_category AND _hospital_quality_measures.measure_name = _hospital_quality_min_max_count.measure_name AND  _hospital_quality_min_max_count.location_state = '".$s."'")
      ->fetchAll();
    foreach ($data as $d) {
      $factor = 0;
      $counter = 0;
      //   $item_array = array();
      //    $item_array = get_percentiles($d['state_measure_count'], $d['state_measure_max']);
      echo '<h3>' . $d['measure_name'] . '</h3>';
      echo $d['location_state'] . ' - ' . $d['condition_name'] . ' - ' . $d['measure_name'] . ' MIN: ' . $d['state_measure_min'] . ' MAX: ' . $d['state_measure_max'] . ' COUNT: ' . $d['state_measure_count'] . ' WEIGHT: ' . $d['category_weight_pct'] . ' LOWER IS BETTER: ' . $d['lib'];
      if (intval($d['state_measure_max']) > 0) {
        $index = 100 / $d['state_measure_count'];

        // echo '<br> THE INDEX IS: ' . $index;
        $data2 = $pdo->query("SELECT score, hospital_name, provider_id, lower_is_better from _hospital_quality_raw_scores where location_state = '" . $d['location_state'] . "' AND measure_name = '" . addslashes($d['measure_name']) . "' and score_text != 'Not Available' order by score")
          ->fetchAll();
        if ($data2) {
          $array_of_scores = [];
          echo '<table border=1><tr><th>ITEM#</th><th>Hospital Name</th><th>Provider ID</th><th>Raw Score</th><th>Percentile</th><th>Weighted Percentile</th></tr>';
          foreach ($data2 as $d2) {
            array_push($array_of_scores, intval($d2['score']));
          }
          //  var_dump($array_of_scores);
          reset($data2);
          foreach ($data2 as $d2) {
            if ($d['lib'] == 1) {
              $smaller_is_better = 1;
            }
            else {
              $smaller_is_better = 0;
            }
            $counter++;
            $theItem = number_smaller($array_of_scores, $d2['score'], $smaller_is_better);
            echo '<tr>';
            echo '<td>' . $counter . '</td><td>' . $d2['hospital_name'] . '</td><td>' . $d2['provider_id'] . '</td><td>' . $d2['score'] . '</td><td>' . (intval($theItem * $index)) . '</td><td>' . ((intval($theItem * $index)) * (intval($d['category_weight_pct']) / 100)) . '</td></tr>';

            $datac = [
              'provider_id' => $d2['provider_id'],
              'hospital_name' => $d2['hospital_name'],
              'measure_name' => $d['measure_name'],
              'score' => $d2['score'],
              'percentile' => (intval($theItem * $index)),
              'quality_category' => $d['quality_category'],
              'weighted_score' => ((intval($theItem * $index)) * (intval($d['category_weight_pct']) / 100)),
              'lower_is_better' => $d['lower_is_better'],
              'location_state' => $d['location_state'],
            ];
            $sqlc = "INSERT INTO _hospital_quality_results (provider_id, hospital_name, measure_name, score, percentile, quality_category, weighted_score, lower_is_better, location_state) VALUES (:provider_id, :hospital_name, :measure_name, :score, :percentile, :quality_category, :weighted_score, :lower_is_better, :location_state)";
            $stmt = $pdo->prepare($sqlc);
            $stmt->execute($datac);
            //        //    echo 'INSERT SHOULD OCCUR HERE... ';
          }
          echo '</table>';

        }
      }
    }
  }


    function number_smaller($array, $number, $smaller_is_better = 0) {
      $counter = 0;
      foreach ($array as $a) {
        if ($smaller_is_better != 1) {
          if ($a <= $number) {
            $counter++;
          }
        }
        else {
          if ($a >= $number) {
            $counter++;
          }
        }
      }
      return $counter;
    }