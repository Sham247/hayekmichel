<?php
  error_reporting(E_ALL);
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

  $data = $pdo->query("SELECT id, `First Name`, `Last Name`, State from _phys_compare_medicare_raw_april2019 where isNull(board_certification)" )->fetchAll();
    foreach($data as $d) {

      $fname = $d['First Name'];
      $lname = $d['Last Name'];
      $state = $d['State'];
      $id = $d['id'];
      echo $fname . ' ' . $lname . ' '. $state . ' ' . $d['id'] .'<hr>';
      $curl = curl_init('https://www.certificationmatters.org/find-my-doctor/?dsearch=1&fname='.$fname.'&lname='.$lname.'&state='.$state.'&specialty=');
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

      $page = curl_exec($curl);

      if (curl_errno($curl)) // check for execution errors
      {
        echo 'Scraper error: ' . curl_error($curl);
        exit;
      }

      curl_close($curl);
        $subpage= explode('<li class="board">', $page);
        $subpage_end = explode('</li>', $subpage[1]);
        if(is_array($subpage_end[0])) {
        echo $subpage_end[0];
          $datac = [
            'board_certification' => trim($subpage_end[0]),
            'id' => $d['id'],
          ];
        } else {
          echo 'there was no data for this person...<hr>';
          $datac = [
            'board_certification' => '_',
            'id' => $d['id'],
          ];
        }
          $sqlc = "UPDATE _phys_compare_medicare_raw_april2019 set board_certification = :board_certification where id = :id";
          echo $sqlc.'<hr>';
          $stmt = $pdo->prepare($sqlc);
          $stmt->execute($datac);
   sleep(5);
        }
   