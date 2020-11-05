<?php

class Account {

  public function loginUser(string $paramUsername, string $paramPassword) {
    $username = trim($paramUsername);
    $password = trim($paramPassword);
    if (empty($username) || empty($password)) {
      return "No empty strings";
    }
    // check for special characters, exit if found
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
    return $user;
  }

  public function registerUser(string $paramUsername, sting $paramEmail, string $paramPassword) {
    $username = trim($paramUsername);
    $password = trim($paramPassword);
    if (empty($username) || empty($password)) {
      return "No empty strings";
    }
    // logic for creating a new user account 
  }
}
