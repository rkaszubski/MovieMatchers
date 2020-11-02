<?php

namespace User;

class User {
  private $uid;
  private $username;
  private $email;
  private $role;
  private $init;

  public function __construct($uid, $username, $email, $role, $init) {
    $this->uid = $uid;
    $this->username = $username;
    $this->email = $email;
    $this->role = $role;
    $this->init = $init;
  }
  public function getUID() {
    return $this->uid;
  }
  public function getUsername() {
    return $this->username;
  }
  public function getEmail() {
    return $this->email;
  }
  public function getRole() {
    return $this->role;
  }
  public function getInit() {
    return $this->init;
  }
}
// create a object by: $user = new Users($d);
