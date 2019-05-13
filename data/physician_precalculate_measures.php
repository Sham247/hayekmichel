<?php
  /**
   * This page merges existing physician data into these new tables...
   */
  ini_set('memory_limit', -1);
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

  $data = $pdo->query("SELECT id, NPI, `Primary specialty`, `FIRST NAME` as fname, `LAST NAME` as lname, State, Award, O_Publications, O_Organizations, O_Appointments, `Reported Quality Measures`, `Used electronic health records`, `Hospital affiliation CCN 1` as h1,  `Hospital affiliation CCN 2` as h2,  `Hospital affiliation CCN 3` as h3, `Hospital affiliation CCN 4` as h4, `Hospital affiliation CCN 5` as h5 from _phys_compare_medicare_raw_april2019 order by id")->fetchAll();
    foreach($data as $d) {
      if (trim($d['Reported Quality Measures']) == 'Y'){
        $quality = 3;
      } else {
        $quality = 0;
      }

      if (trim($d['Used electronic health records']) == 'Y'){
        $ehr = 2;
      } else {
        $ehr = 0;
      }

      if(trim($d['O_Publications']) != '') {
        $pub_array = explode('@', $d['O_Publications']);
        $pubcount = 1 + count($pub_array);
      } else {
        $pubcount = 0;
      }
      $pubtotal = (3 * $pubcount);

      if(trim($d['Award']) != '') {
        $award_array = explode('@', $d['Award']);
        $awardcount = 1 + count($award_array);
      } else {
        $awardcount = 0;
      }
      $awardtotal= (3 * $awardcount);


      if(trim($d['O_Appointments']) != '') {
        $appt_array = explode('@', $d['O_Appointments']);
        $apptcount = 1 + count($appt_array);
      } else {
        $apptcount = 0;
      }
      $appttotal= (3 * $apptcount);

 echo '<h3>' . $d['id'] . '-'. $d['lname'].' ' . $d['fname'] . '</h3>';

      $raw_total = $quality + $ehr + $awardtotal + $appttotal + $pubtotal;
      $datac = [
        'npi' => $d['NPI']. ' ',
        'specialty' => $d['Primary specialty']. ' ',
        'fname' => $d['fname']. ' ',
        'lname' => $d['lname']. ' ',
        'pqrs' => $quality,
        'ehr' => $ehr,
        'awards' =>  $awardtotal,
        'appts' => $appttotal,
        'pubs' => $pubtotal,
        'raw_total' => $raw_total,
        'hospital_affiliation1' => $d['h1'],
        'hospital_affiliation2' => $d['h2'],
        'hospital_affiliation3' => $d['h3'],
        'hospital_affiliation4' => $d['h4'],
        'hospital_affiliation5' => $d['h5'],
        'st' => $d['State'],

      ];
      $sqlc = "INSERT into _phys_measures_precalc (npi, specialty, fname, lname, pqrs, ehr, awards, faculty_appointments, publications,raw_total, st, hospital_affiliation1, hospital_affiliation2, hospital_affiliation3, hospital_affiliation4, hospital_affiliation5) VALUES (:npi, :specialty, :fname, :lname, :pqrs, :ehr, :awards, :appts, :pubs, :raw_total, :st, :hospital_affiliation1, :hospital_affiliation2, :hospital_affiliation3, :hospital_affiliation4, :hospital_affiliation5)";
      echo $sqlc.'<hr>';
      $stmt = $pdo->prepare($sqlc);
      $stmt->execute($datac);

    }