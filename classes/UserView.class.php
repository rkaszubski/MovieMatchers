<?php

class UserView {

  public function setUserSessionData($userObj) {
    session_start();
    $_SESSION['UID'] = $userObj->getUID();
    $_SESSION['username'] = $userObj->getUsername();
    $_SESSION['email'] = $userObj->getEmail();
    $_SESSION['role'] = $userObj->getRole();
    $_SESSION['init'] = $userObj->getInit();
  }

  public function useCurrentUserSession() {

  }
}
