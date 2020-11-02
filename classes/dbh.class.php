<?php


class Dbh {
  private $host = 'localhost';
  private $dbName = 'MMDatabase';
  private $user = 'root';
  private $pwd = 'Pauahi808';
  private $charset = 'utf8mb4';

//   private $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";
//   private $options = [
//     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//     PDO::ATTR_EMULATE_PREPARES   => false,
// ];
// try {
//      $pdo = new PDO($dsn, $user, $pass, $options);
// } catch (\PDOException $e) {
//      throw new \PDOException($e->getMessage(), (int)$e->getCode());
// }

  protected function connect() {
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;
    $pdo = new PDO($dsn, $this->user, $this->pwd);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
  }


}
