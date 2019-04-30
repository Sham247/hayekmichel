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

  $data = $pdo->query("SELECT measure_name, number_of_hospitals_better, number_of_hospitals_same, number_of_hospitals_worse, number_of_hospitals_too_few, state from __medicare_hosp_comp_complications_by_state  order by state, measure_name")->fetchAll();
    foreach($data as $d) {
//      $factor = 0;
//      $counter = 0;
      //   $item_array = array();
      //    $item_array = get_percentiles($d['state_measure_count'], $d['state_measure_max']);
      echo '<h3>' . $d['state'] . ' - ' . $d['measure_name'] . '</h3>';
      echo   ' BETTER: ' . $d['number_of_hospitals_better'] . ' SAME: ' . $d['number_of_hospitals_same'] . ' WORSE: ' . $d['number_of_hospitals_worse'] . ' TOO FEW: ' . $d['number_of_hospitals_too_few'];
      echo '<hr>';
//      if (intval($d['state_measure_max']) > 0) {
//        $index = 100 / $d['state_measure_count'];
//
//       // echo '<br> THE INDEX IS: ' . $index;
//        $data2 = $pdo->query("SELECT score, hospital_name, provider_id from _hospital_quality_raw_scores where location_state = '". $d['location_state'] ."' AND measure_name = '" . addslashes($d['measure_name']) . "' and score_text != 'Not Available' order by score")
//          ->fetchAll();
//        if ($data2) {
//          $array_of_scores = array();
//          echo '<table border=1><tr><th>ITEM#</th><th>Hospital Name</th><th>Provider ID</th><th>Raw Score</th><th>Percentile</th><th>Weighted Percentile</th></tr>';
//          foreach ($data2 as $d2) {
//            array_push($array_of_scores, intval($d2['score']));
//          }
//         //  var_dump($array_of_scores);
//          reset($data2);
//          foreach ($data2 as $d2) {
//            $counter++;
//            $theItem = number_smaller($array_of_scores, $d2['score']);
//           // echo 'there are '.$theItem . 'values smaller than this' . (intval($theItem * $index)  + 1) .'<br>';
////            array_filter($array_of_scores, function($n){
////              $theItem =  $n <= $d2['score'];
////            });
//
//            echo '<tr>';
//            echo '<td>' . $counter . '</td><td>' . $d2['hospital_name'] . '</td><td>' . $d2['provider_id'] . '</td><td>' . $d2['score'] . '</td><td>' . (intval($theItem * $index)) . '</td><td>' . ((intval($theItem * $index)) * (intval($d['category_weight_pct'])/100)) . '</td></tr>';
//
//            $datac = [
//              'provider_id' => $d2['provider_id'],
//              'hospital_name' => $d2['hospital_name'],
//              'measure_name' => $d['measure_name'],
//              'score' => $d2['score'],
//              'percentile' => (intval($theItem * $index)),
//              'quality_category' => $d['quality_category'],
//              'weighted_score' => ((intval($theItem * $index)) * (intval($d['category_weight_pct'])/100)),
//              'lower_is_better' => $d['lower_is_better'],
//              'location_state' => $d['location_state'],
//            ];
//            $sqlc = "INSERT INTO _hospital_quality_results (provider_id, hospital_name, measure_name, score, percentile, quality_category, weighted_score, lower_is_better, location_state) VALUES (:provider_id, :hospital_name, :measure_name, :score, :percentile, :quality_category, :weighted_score, :lower_is_better, :location_state)";
//            $stmt = $pdo->prepare($sqlc);
//           $stmt->execute($datac);
////        //    echo 'INSERT SHOULD OCCUR HERE... ';
//         }
//          echo '</table>';
//
//        }
//      }
//    }
//
//
//  function number_smaller($array, $number)
//  {
//    $counter = 0;
//    foreach($array as $a) {
//      if($a <= $number) {
//        $counter++;
//      }
//    }
//    return $counter;
  }