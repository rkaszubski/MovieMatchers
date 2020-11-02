<?php
spl_autoload_register('myAutoLoader');
function myAutoLoader($className) {
  include 'classes/' . str_replace('\\', "/", $className) . '.class.php';
}

 ?>
