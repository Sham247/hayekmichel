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


// SELECT THe table with the min, max, avg and count for each state, for each quality measure.

  $data = $pdo->query("")->fetchAll();
    foreach($data as $d) {
