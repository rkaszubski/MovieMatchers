<?php


class Dbh {
  private $host = 'localhost';
  private $dbName = 'MMDatabase';
  private $user = 'root';
  private $pwd = 'Pauahi808';
  // private $host = '107.180.21.70';
  // private $dbName = 'ElonsLilBackup';
  // private	$user = 'nhester';
  // private	$pwd = 'XaeA-123';

  protected function connect() {
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;
    try {
      $pdo = new PDO($dsn, $this->user, $this->pwd);
      $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      return $pdo;
    } catch (PDOException $e) {
      $error_message = $e->getMessage();
  		// include('error.php');
      echo $e;
  		exit();
    }
  }


}
