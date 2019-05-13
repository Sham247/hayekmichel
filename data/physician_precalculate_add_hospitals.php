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

    $data = $pdo->query("select distinct hospital_affiliation1  from _phys_measures_precalc")->fetchAll();
    foreach ($data as $d) {
      if($d['hospital_affiliation1']) {
        echo '<h2>HOSPITAL ID: ' . $d['hospital_affiliation1'] . '</h2>';
        for ($i = 1; $i <= 13; $i++) {
          $dataweight = $pdo->query("SELECT category_weight_pct from _hospital_quality_cats_weight where id = " . $i)
            ->fetchAll();
          foreach ($dataweight as $w) {
            $weight = ($w['category_weight_pct'] / 100);
          }
          if ($i == 1) {
            $total = 0;
          }

          $data2 = $pdo->query("select count(percentile) as count, sum(percentile) as tot, (sum(percentile) / count(percentile)) as avg from _hospital_quality_results where provider_id = " . $d['hospital_affiliation1'] . " and quality_category = " . $i)
            ->fetchAll();
          foreach ($data2 as $d2) {
            echo '<br>CATEGORY ' . $i . ' - AVG = ' . $d2['avg'] . 'WEIGHTED: ' . ($d2['avg'] * $weight) . '<hr>';
            $total = $total + ($d2['avg'] * $weight);
          }
        }

        $datac = [
          'total' => intval($total),
          'hospital_affiliation1' => $d['hospital_affiliation1'],
        ];
        $sqlc = "UPDATE _phys_measures_precalc set hospital_percentile1 = :total where hospital_affiliation1 = :hospital_affiliation1";
        echo $sqlc . '<hr>';
        $stmt = $pdo->prepare($sqlc);
        $stmt->execute($datac);
        echo '<h1>TOTAL VALUE = ' . $total . '</h1>';
      }
    }
