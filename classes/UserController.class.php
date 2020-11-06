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

  public function getUserByEmail(string $email) {
    $sql = "SELECT * FROM Users WHERE email=:email";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $results = $stmt->fetch();
    if ($results != null) {
      $user = new User
      (
        $results["UID"],
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

  public function insertUser(string $username, string $email, string $password) {
    // get user id and hash password
    $uid = (new IDGenerator)->getNewUserId();
    $pwd = md5($password);
    // prepare, execute insert User statement
    $sql = "INSERT INTO Users (uid, username, email, role, init) VALUES(:uid, :username, :email, 'user', 0)";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(':uid', $uid);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // prepare, execute password storage insert stmt
    $sqlPassword = "INSERT INTO Passwords (Password, UserId) VALUES(:password, :userid)";
    $stmtPassword = $this->connect()->prepare($sqlPassword);
    $stmtPassword->bindParam(':password', $pwd);
    $stmtPassword->bindParam(':userid', $uid);
    $stmtPassword->execute();

    return 'success';
  }

  public function insertPassword(int $uid, string $password) {
    $sql = "INSERT INTO Passwords (Password, UserId) VALUES(:password, :userid)";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':userid', $uid);
    $success = $stmt->execute();
    return $success;
  }

}
