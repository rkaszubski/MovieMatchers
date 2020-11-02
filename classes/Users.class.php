<?php

class Users extends Dbh {

  protected function getUser(int $uid) {
    $sql = "SELECT * FROM Users WHERE UID=?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$uid]);

    $results = $stmt->fetchAll();
    return $results;
  }

  protected function getUsers() {
    $sql = "SELECT * FROM Users";
    $stmt = $this->connect()->query($sql);
    while ($row = $stmt->fetch()) {
      echo $row['username'];
    }
  }
}
