<?php
include_once ('dbh.class.php');
class IDGenerator extends Dbh {

  public function getNewUserId() {
    $userId = null;
    $sql = "SELECT seq FROM sqlite_sequence WHERE name='Users';";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute();
    $userId = $stmt->fetchColumn();
    // update User Id sequence
    $newUid = $userId + 1;
    $sqlupdateUserIdSequence = "UPDATE sqlite_sequence SET seq=:userId WHERE name='Users';";
    $stmtupdateUserIdSequence = $this->connect()->prepare($sqlupdateUserIdSequence);
    $stmtupdateUserIdSequence->bindParam(':userId', $newUid);
    $stmtupdateUserIdSequence->execute();
    return $userId;
  }

  public function getNewPasswordId() {
    $passwordId = null;
    $sql = "SELECT seq FROM sqlite_sequence WHERE name='Passwords';";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute();
    $passwordId = $stmt->fetchColumn();
    // update Password Id sequence
    $newPassword = $passwordId + 1;
    $sqlupdatePasswordIdSequence = "UPDATE sqlite_sequence SET seq=:passwordId WHERE name='Passwords';";
    $stmtupdatePasswordIdSequence = $this->connect()->prepare($sqlupdatePasswordIdSequence);
    $stmtupdatePasswordIdSequence->bindParam(':passwordId', $newPassword);
    $stmtupdatePasswordIdSequence->execute();
    return $passwordId;
  }

  public function getNewMovieId() {
    $movieId = null;
    $sql = "SELECT seq FROM sqlite_sequence WHERE name='Movies';";
    $stmt = $this->connect()->prepare($sql);
    if ($stmt->execute()) {
      $movieId = $stmt->fetchColumn();
      // update Password Id sequence
      $newMovieId = $movieId + 1;
      $sqlupdateMovieIdSequence = "UPDATE sqlite_sequence SET seq=:movieId WHERE name='Movies';";
      $stmtupdateMovieIdSequence = $this->connect()->prepare($sqlupdateMovieIdSequence);
      $stmtupdateMovieIdSequence->bindParam(':movieId', $newMovieId);
      $stmtupdateMovieIdSequence->execute();
      return $movieId;
    } else {
      echo "failure";
    }
    
  }
}
