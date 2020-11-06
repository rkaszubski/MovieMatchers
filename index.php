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
  header("Location: login.php");
 ?>
</body>
</html>
