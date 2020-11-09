<?php
include_once ('UserController.class.php');
include_once ('UserView.class.php');
include_once ('User.class.php');

class Account {

  public function getUserWatchlist($uid) {
      $userControl = new UserController();
      $watchlist = $userControl->getWatchlistByUid($uid);
      return $watchlist;
  }
  public function adjustUserScore($amt, $userId, $catArr) {
    // if (count($catArr) > 0) {
    //   $pdo = new PDO("sqlite:MMDataBase.db");
		// 	$sqlCatExists = "SELECT CategoryName FROM Scores WHERE UserId=:uid AND CategoryName=:category"; // Get all records containing scores for categories specific to current user
		// 	$sqlInsertCat = "INSERT INTO Scores (UserId, CategoryName, Score) VALUES (:uid,:category,100)"; // Query to add a category if a record does not already exist
		// 	foreach ($catArr as $cat) {
		// 		$category = trim($cat); // remove spaces
		// 		$stmtCatExists = $pdo->prepare($sqlCatExists);
		// 		$stmtCatExists->bindParam(':uid', $userId);
		// 		$stmtCatExists->bindParam(':category', $category);
		// 		$stmtCatExists->execute();
		// 		$catExists = $stmtCatExists->fetchColumn();
		// 		if ($catExists == false) { //If recode does not already exist insert it into table with initial score of 100
		// 			$stmtInsertCat = $pdo->prepare($sqlInsertCat);
		// 			$stmtInsertCat->bindParam(':uid', $userId);
		// 			$stmtInsertCat->bindParam(':category', $category);
		// 			$stmtInsertCat->execute();
		// 		}
		// 		// else do nothing, category exists
		// 	}
		// } else {
		// 	echo "Error, category array is empty";
		// }
  }

  public function loginUser(string $paramUsername, string $paramPassword) {
    $username = trim($paramUsername);
    $password = trim($paramPassword);
    // check for empty input
    if (empty($username) || empty($password)) {
      return "No empty strings";
    }
    // check for special characters, exit if found
    else if (preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $username)
      || preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $password))
    {
      return "Special Characters are not allowed";
    }
    // input is injection safe
    else {
      // get User Account by given username
      $userController = new UserController();
      $user = $userController->getUserByUsername($username);
      // var_dump($user);
      // assert if user exists, otherwise exit
      if ($user == null) {
        return "username is invalid";
      } else {
        // get Password by returned user object UID
        $uid = $user->getUID();
        $storedPassword = $userController->getPasswordByUid($uid);
        // if password is null something is wrong
        if ($storedPassword == null) {
          return "password does not exist... WEIRD ERROR";
        }
        // hash given password and compare it to the stored hashed password in DB
        $hashedPassword = md5($password);
        if ($hashedPassword == $storedPassword) {
          // password is correct,
          // set User session data
          $userView = new UserView();
          $res = $userView->setUserSessionData($user);
          // return true to resemble 'success'
          return 'success';
        } else {
          // password was not correct
          return "password was incorrect\n" . "dbPW: " . $storedPassword . " enteredPW: " . $hashedPassword;
        }
      }
    }
  }

  public function registerUser(string $paramUsername, string $paramEmail, string $paramPassword) {
    $username = trim($paramUsername);
    $email    = trim($paramEmail);
    $password = trim($paramPassword);
    // check for empty input, exit if found
    if (empty($username)
      || empty($email)
      || empty($password))
    {
      return "inputs cannot be empty";
    }
    // check for special characters, exit if found
    else if (preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $username)
      || preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $email)
      || preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $password))
    {
      return "No special characters are allowed in inputs";
    }
    // check for invalid input lengths, exit if found
    else if (strlen($username) < 3) {
      return "Username must be 4 characters or longer";
    }
    else if (strlen($email) < 3) {
      return "Email must be 4 characters or longer";
    }
    else if (strlen($password) < 3) {
      return "Password must be 4 characters or longer";
    }
    else {
      // check if username already exists
      $usernameExists = (new UserController)->getUserByUsername($username);
      if ($usernameExists != null) {
        return "This username already exists";
      }
      // check if email already exists
      $emailExists = (new UserController)->getUserByEmail($email);
      if ($emailExists != null) {
        return "This email is already in use";
      }
      // input should be safe to create a new user account with.
      $userController = new UserController();
      $res = $userController->insertUser($username, $email, $password);

      // will be 'success' or 'failure' returned
      return $res;
    }
    return 'failure';
  }
}
