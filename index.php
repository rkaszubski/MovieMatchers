<?php
spl_autoload_register('myAutoLoader');

function myAutoLoader($className) {
  // include 'classes/' . str_replace('\\', "/", $className) . '.class.php';
  $path = 'classes/';
  $extension = '.class.php';
  $fullPath = $path . $className . $extension;
  if (!file_exists($fullPath)) {
    return false;
  }
  include_once $fullPath;

}
  // header("Location: login.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
  <h1> hi </h1>
  <?php
  // public function __construct($uid, $username, $email, $role, $init) {
  //   $this->uid = $uid;
  //   $this->username = $username;
  //   $this->email = $email;
  //   $this->role = $role;
  //   $this->init = $init;
  // }
    // $usersObj = new User\User(11, 'nakana', 'nakana@gmail.com','user',0);
    // echo $usersObj->getUID();
  $userObj = new UsersView();
  $userObj->showUsers();
   ?>
</body>
</html>
