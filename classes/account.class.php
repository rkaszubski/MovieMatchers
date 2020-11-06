<?php

class Account {

  public function loginUser(string $paramUsername, string $paramPassword) {
    $username = trim($paramUsername);
    $password = trim($paramPassword);
    if (empty($username) || empty($password)) {
      return "No empty strings";
    }
    // check for special characters, exit if found
    if (preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $username)
      || preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $password))
    {
      return "Special Characters are not allowed";
    }
    $userController = new UserController();
    $user = $userController->getUserByUsername($username);
    // assert if user exists, otherwise exit
    if ($user == null) {
      return "user does not exist";
    }
    $hashedPassword = $userController->getPasswordByUid($user->getUID());
    if ($hashedPassword == null) {
      return "password does not exist... WEIRD ERROR";
    }
    if (md5($password) != $hashedPassword) {
      return "password incorrect";
    }
    $userView = new UserView();
    $userView->setUserSessionData($user);
    if (!isset($_SESSION["UID"])) {
      return "SESSION DATA NOT SET";
    }
    return $user;
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
    if (preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $username)
      || preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $email)
      || preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $password))
    {
      return "No special characters are allowed in inputs";
    }
    // check for invalid input lengths, exit if found
    if (strlen($username) < 3) {
      return "Username must be 4 characters or longer";
    }
    if (strlen($email) < 3) {
      return "Email must be 4 characters or longer";
    }
    if (strlen($password) < 3) {
      return "Password must be 4 characters or longer";
    }
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
    $userController->insertUser($username, $email, $password);
    if ($userController == null) {
      return "Database issue";
    }
    return true;
    // logic for creating a new user account
  }
}
