<?php
class UserController extends Dbh {

  // returns <> if not found, returns UID if found
  public function getUserByUsername(string $username) {
    $sql = "SELECT * FROM Users WHERE username=:username";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $results = $stmt->fetch();
    if ($results != null) {
      $user = new User($results["UID"],
                    $results["username"],
                    $results["email"],
                    $results["role"],
                    $results["init"]);
      return $user;
    }
    return null;
  }

  public function getPasswordByUid(string $uid) {
    $sql = "SELECT * FROM Passwords WHERE UserId=:uid";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(':uid', $uid);
    $stmt->execute();
    $results = $stmt->fetch();
    if ($results != null) {
      $hashedPassword = $results["Password"];
      return $hashedPassword;
    }
    return null;
  }
}
