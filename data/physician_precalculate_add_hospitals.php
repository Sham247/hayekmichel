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
    for($i=1;$i<=5;$i++) {
      $data = $pdo->query("select distinct `hospital_affiliation" . $i . "`  from _phys_measures_precalc")
        ->fetchAll();
      foreach ($data as $d) {
        if ($d['hospital_affiliation' . $i]) {
          echo '<h2>HOSPITAL ID: ' . $d['hospital_affiliation' . $i] . '</h2>';
          for ($j = 1; $j <= 13; $j++) {
            $dataweight = $pdo->query("SELECT category_weight_pct from _hospital_quality_cats_weight where id = " . $j)
              ->fetchAll();
            foreach ($dataweight as $w) {
              $weight = ($w['category_weight_pct'] / 100);
            }
            if ($j == 1) {
              $total = 0;
            }

            $data2 = $pdo->query("select count(percentile) as count, sum(percentile) as tot from _hospital_quality_results where provider_id = " . $d['hospital_affiliation' . $i] . " and quality_category = " . $j)
              ->fetchAll();
            foreach ($data2 as $d2) {
              echo '<br>CATEGORY ' . $j . ' - SUM = ' . $d2['tot'] . 'WEIGHTED: ' . ($d2['tot'] * $weight) . '<hr>';
              $total = $total + ($d2['tot'] * $weight);
            }
          }

          $datac = [
            'total' => intval($total),
            'hospital_affiliation' . $i => $d['hospital_affiliation' . $i],
          ];
          $sqlc = "UPDATE _phys_measures_precalc set hospital_percentile" . $i . " = :total where hospital_affiliation" . $i . " = :hospital_affiliation" . $i;
          echo $sqlc . '<hr>';
          $stmt = $pdo->prepare($sqlc);
          $stmt->execute($datac);
          echo '<h1>TOTAL VALUE = ' . $total . '</h1>';
        }
      }
    }