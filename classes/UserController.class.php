<?php
include_once ('dbh.class.php');
include_once ('User.class.php');

class UserController extends Dbh {
  
  public function getWatchlistByUid($uid) {
    $sqlMidList = "SELECT movieId FROM Watchlist WHERE userId=$uid";
    $sql = "SELECT * FROM Movies WHERE MID IN ($sqlMidList)";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute();	
    $data = $stmt->fetchAll();
    return $data;
  }

  // returns <> if not found, returns UID if found
  public function getUserByUsername(string $username) {
    $sql = "SELECT * FROM Users WHERE username=:username";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $results = $stmt->fetch();
    if ($results != false) {
      $user = new User($results["uid"],
                    $results["username"],
                    $results["email"],
                    $results["role"],
                    $results["init"]);
      return $user;
    }
    return null;
  }

  public function getUserByEmail(string $email) {
    $sql = "SELECT * FROM Users WHERE email=:email";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $results = $stmt->fetch();
    if ($results != false) {
      $user = new User
      (
        $results["uid"],
        $results["username"],
        $results["email"],
        $results["role"],
        $results["init"]
      );
      return $user;
    }
    return null;
  }

  public function getPasswordByUid(string $uid) {
    $sql = "SELECT password FROM Passwords WHERE UserId=:uid";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(':uid', $uid);
    $stmt->execute();
    $resultPassword = $stmt->fetchColumn();
    if ($resultPassword != false) {
      $hashedPassword = $resultPassword;
      return $hashedPassword;
    }
    return null;
  }

  public function insertUser(string $username, string $email, string $password) {
    // get user id and hash password
    $pwd = md5($password);
    // prepare, execute insert User statement
    $sql = "INSERT INTO Users (username, email, role, init) VALUES(:username, :email, 'user', 0)";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    if ($stmt->execute()) {
      $stmtUid = $this->connect()->prepare("SELECT uid FROM Users WHERE Username=:usern");
      $stmtUid->bindParam(':usern', $username);
      $stmtUid->execute();
      $uid = $stmtUid->fetchColumn();
      // prepare, execute password storage insert stmt
      $sqlPassword = "INSERT INTO Passwords (Password, UserId) VALUES(:password, :userid)";
      $stmtPassword = $this->connect()->prepare($sqlPassword);
      $stmtPassword->bindParam(':password', $pwd);
      $stmtPassword->bindParam(':userid', $uid);
      $stmtPassword->execute();
      return 'success';
    }
    return 'failure';
  }
}
