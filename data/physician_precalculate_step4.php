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


  $states_array = [
    'AK',
    'AL',
    'AR',
    'AZ',
    'CA',
    'CO',
    'CT',
    'DC',
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
    $data = $pdo->query("select distinct specialty  from _phys_measures_precalc where st = '" . $s . "' order by specialty")
      ->fetchAll();
    foreach ($data as $d) {

      $data2 = $pdo->query("select id, raw_total  from _phys_measures_precalc where st = '" . $s . "' AND specialty = '" . $d['specialty'] . "' order by raw_total")
        ->fetchAll();

      $array_of_scores = [];
      $index = 0;
      $counter = 0;
      $theItem = 0;
      $percentile = 0;

      foreach ($data2 as $d2) {
        array_push($array_of_scores, $d2['raw_total']);
      }
      var_dump($array_of_scores);
      reset($data2);
      foreach ($data2 as $d2) {
        $counter++;
        $theItem = number_smaller($array_of_scores, $d2['raw_total']);

        if (count($array_of_scores) > 0) {
          $index = 100 / count($array_of_scores);
          echo 'the index is: ' . $index . '<hr>';
          $theItem = number_smaller($array_of_scores, $d2['raw_total']);
          echo 'the item is: ' . $theItem . '<hr>';
          $percentile = (intval($theItem * $index));
          if ($percentile == 100) {
            $percentile = 99;
          }

          echo '<br>the percentile is: ' . $percentile . ' where the raw score is: ' . $d2['raw_total'] . ' at id = ' . $d2['id'] . ' for specialty: '.$d['specialty'] . ' in state: '. $s . '<hr>';
          $datac = [
            'specialty' => $d['specialty'],
            'st' => $s,
            'percentile' => $percentile,
            'id' => $d2['id'],
          ];
          $sqlc = "UPDATE _phys_measures_precalc set speciality_percentile_by_state = :percentile where st = :st AND specialty = :specialty AND id = :id";
          echo $sqlc . '<hr>';
          $stmt = $pdo->prepare($sqlc);
          $stmt->execute($datac);
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