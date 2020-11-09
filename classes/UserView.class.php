<?php
include_once ('User.class.php');

class UserView {

  public function setUserSessionData($userObj) {
    session_start();

    if (isset($_SESSION["UID"])) {
      return "User session data is already set";
    }
    if (is_object($userObj) && !is_null($userObj) && !is_bool($userObj)) {
      $uid = $userObj->getUID();
      $username = $userObj->getUsername();
      $email = $userObj->getEmail();
      $role = $userObj->getRole();
      $init = $userObj->getInit();
      $_SESSION['uid'] = $uid;
      $_SESSION['username'] = $username;
      $_SESSION['email'] = $email;
      $_SESSION['role'] = $role;
      $_SESSION['init'] = $init;
      return true;
    } else {
      // object is not an object, or is null, or is a boolean
      return false;
    }
  }

  public function session() {
    session_start();
    if (!isset($_SESSION["UID"])) {
      return "User session data is MISSING";
    }
  }
}
