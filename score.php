<?php
session_start();
if (!isset($_SESSION["UID"]))
{
    header("Location: login.php");
}
//test
  $uid = $_SESSION["UID"];
  $uid = intval($uid);
  $searched = $_POST['moviename'];

  $pdo = new PDO("sqlite:MMDataBase.db");
	$movieCheckSqlStmt = "SELECT * FROM Movies WHERE Title LIKE :string ";
	$stmt = $pdo->prepare($movieCheckSqlStmt);
	$stmt->bindParam(':string',$searched);
	$stmt->execute();
	$data = $stmt->fetchAll();

  $searchedMovieCategories = $data[0]["Category"];
  $searchedMovieCategories = explode(',', $searchedMovieCategories);


  var_dump($searchedMovieCategories);
  echo '<br>';
  var_dump($uid);

  foreach ($searchedMovieCategories as $category) {
          $pdo = new PDO("sqlite:MMDataBase.db");
          $categoryCheckSqlStmt = "INSERT INTO Scores (UserId,CategoryName,Score) VALUES (?,?,1)";
          $stmt = $pdo->prepare($categoryCheckSqlStmt);
          $stmt->execute([$uid,$category]);

  }

  function categoryExists($string){
    global $uid;
  //Check if searched movie exists in Movie table
  	$pdo = new PDO("sqlite:MMDataBase.db");
  	$movieCheckSqlStmt = "SELECT * FROM Scores WHERE CategoryName = :string AND UserId = :uid";
  	$stmt = $pdo->prepare($movieCheckSqlStmt);
  	$stmt->bindParam(':uid',$uid);
    $stmt->bindParam(':string', $string);
  	$stmt->execute();
  	$result = $stmt->fetchAll();
    var_dump($result);
  	//if number of found records is less than 1
  	if(count($result) > 0){
  		return true;
  	}
  	else{
  		return false;
  	}
  }

?>

<html>
<head></head>
  <body>
  <form method="post">
    <input type="text" name="moviename">
    <input type="submit" name="button" value ="GO">
  </form>
  </body>
</html>
