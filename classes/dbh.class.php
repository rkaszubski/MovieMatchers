<?php


class Dbh {
  private $host = 'localhost';
  private $user = 'root';
  private $pwd = 'Pauahi808';
  private $dbName = 'MMDatabase';

  // private $host = 'localhost';
  // private $user = 'elbuser';
  // private $pwd = 'Xlp9K^8thp7nQu6L';
  // private $dbName = 'ELBackup';

  protected function connect() {
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;
    try {
      $pdo = new PDO($dsn, $this->user, $this->pwd);
      $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      return $pdo;
    } catch (PDOException $e) {
      $error_message = $e->getMessage();
  		// include('error.php');
      echo $error_message;
  		exit();
    }
  }
}
