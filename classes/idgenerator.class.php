<?php

class IDGenerator extends Dbh {

  public function getNewUserId() {
    $userId = null;
    $sql = "SELECT seq FROM sqlite_sequence WHERE name='Users';";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute();
    $userId = $stmt->fetchColumn();
    // update User Id sequence
    $newUid = $userId + 1;
    $sqlupdateUserIdSequence = "UPDATE sqlite_sequence SET seq=:uid WHERE name='Users';";
    $stmtupdateUserIdSequence = $this->connect()->prepare($sqlupdateUserIdSequence);
    $stmtupdateUserIdSequence->bindParam(':uid', $newUid);
    $stmtupdateUserIdSequence->execute();
    return $userId;
  }

  public function getNewPasswordId() {
    $passwordId = null;
    $sql = "SELECT seq FROM sqlite_sequence WHERE name='Passwords';";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute();
    $passwordId = $stmt->fetchColumn();
    return $passwordId;
  }
  public function updatePasswordIdSequence(int $passwordId) {

  }


  public function getNewMovieId() {
    $movieId = null;
    $sql = "SELECT seq FROM sqlite_sequence WHERE name='Movies';";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute();
    $movieId = $stmt->fetchColumn();
    return $movieId;
  }
  public function updateMovieIdSequence(int $movieId) {

  }

}
