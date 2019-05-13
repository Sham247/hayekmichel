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

  $data = $pdo->query("select avg(raw_total) as state_avg from _phys_measures_precalc where st = '".$s."'")->fetchAll();
    foreach($data as $d) {

            $datac = [
              'st' => $s,
              'state_avg' => $d['state_avg'],
            ];
            $sqlc = "UPDATE _phys_measures_precalc set state_avg = :state_avg where st = :st";
            $stmt = $pdo->prepare($sqlc);
           $stmt->execute($datac);
//        //    echo 'INSERT SHOULD OCCUR HERE... ';
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