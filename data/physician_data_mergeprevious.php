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

  $data = $pdo->query("SELECT prov.id, prov.NPI,  prov.Internship, prov.Residency, prov.Disciplinary_Action_Other_States, prov.Forced_Resignations, prov.CriminalOffences, prov.Malpractice, prov.Award, prov.O_Publications, prov.O_Organizations, prov.O_Languages, prov.O_Appointments, prov.NCQA_Recognization, prov.is_ehc, phy.npi, phy.`Last Name`, phy.`First Name` from providermaster_state prov, _phys_compare_medicare_raw_april2019 phy where prov.npi = phy.npi")->fetchAll();
    foreach($data as $d) {
      $factor = 0;
      $counter = 0;
      echo '<h3>' . $d['id'] . '-'. $d['Last Name'].' ' . $d['First Name'] . '</h3>';

      $datac = [
        'internship' => $d['internship']. ' ',
        'residency' => $d['residency']. ' ',
        'disc' => $d['Disciplinary_Action_Other_States']. ' ',
        'forced' => $d['Forced_Resignations']. ' ',
        'crim' => $d['CriminalOffences'].' ',
        'malp' => $d['Malpractice'].' ',
        'award' => $d['Award'].' ',
        'pubs' => $d['O_Publications'].' ',
        'orgs' => $d['O_Organizations'].' ',
        'lang' => $d['O_Languages'].' ',
        'appt' => $d['O_Appointments'].' ',
        'ncqa' => $d['NCQA_Recognization'].' ',
        'npi' => $d['NPI'],
      ];
      $sqlc = "UPDATE _phys_compare_medicare_raw_april2019 set Internship = :internship, Residency = :residency,  Disciplinary_Action_Other_States = :disc,  Forced_Resignations = :forced, CriminalOffences = :crim,  Malpractice = :malp, Award = :award, O_Publications = :pubs, O_Organizations = :orgs, O_Languages = :lang, O_Appointments = :appt, NCQA_Recognization = :ncqa, mergedone = 1  where NPI = :npi";
      echo $sqlc.'<hr>';
      $stmt = $pdo->prepare($sqlc);
      $stmt->execute($datac);

    }