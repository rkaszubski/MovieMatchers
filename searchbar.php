<?php
session_start();
if (!isset($_SESSION["UID"]))
{
		header("Location: login.php");
}
//test
$userId = $_SESSION["UID"];
$title = $director = $actors = $category = $poster = $rated = $plot = "";
$year = 0;
$imdbRating = 0.0;
$pdo = new PDO("sqlite:MMDataBase.db");
$input_term =$_POST['search'];


function noSpecialChar($string) {
	if (preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $string)) {
	    // one or more of the 'special characters' found in $string
			return false;
	}
	return true;
}


function movieExistsInDb($string){
//Check if searched movie exists in Movie table
	$data = getOmdbRecord("$string", "2f79417c");
	$string = $data["Title"];
	$pdo = new PDO("sqlite:MMDataBase.db");
	$movieCheckSqlStmt = "SELECT * FROM Movies WHERE Title LIKE :string ";
	$stmt = $pdo->prepare($movieCheckSqlStmt);
	$stmt->bindParam(':string',$string);
	$stmt->execute();
	$result = $stmt->fetchAll();

	//if number of found records is less than 1
	if(count($result) > 0){
		return true;
	}
	else{
		return false;
	}
}

function getOmdbRecord($movieName, $ApiKey)
{
	//replace spaces " " with plus sign "+"
	$movieName = str_replace(' ','+',$movieName);
	//Stitch url that gets JSON file
	$path = "http://www.omdbapi.com/?apikey=$ApiKey&t=$movieName";
	//gets contents of json file
	$json = file_get_contents($path);
	return json_decode($json, TRUE);
}

//Populate page using Database and not ping OMDB
function populateMovieFromDB($string){
	global $title,$director,$actors,$year,$imdbRating,$category,$poster,$rated,$plot;
	$pdo = new PDO("sqlite:MMDataBase.db");
	$movieCheckSqlStmt = "SELECT * FROM Movies WHERE Title LIKE :string ";
	$stmt = $pdo->prepare($movieCheckSqlStmt);
	$stmt->bindParam(':string',$string);
	$stmt->execute();
	$data = $stmt->fetchAll();

	$title = $data[0]["Title"];
	$director = $data[0]["Director"];
	$actors = $data[0]["Actors"];
	$year = $data[0]["ReleaseYear"];
	$year = intval($year); //convert to int to add to database
	$imdbRating = $data[0]["IMDB_score"];
	$imdbRating =floatval($imdbRating); //convert to float (cannot do int because it rounds up/down)
	$category = $data[0]["Category"];
	$poster = $data[0]["Poster"];
	$rated = $data[0]["Rated"];
	$plot = $data[0]["Plot"];
}


//Populate movies from OMDB
function populateMovieOMDB($string){
	$data = getOmdbRecord("$string", "2f79417c");
	global $pdo;
	global $title,$director,$actors,$year,$imdbRating,$category,$poster,$rated,$plot;
//	Uncomment following code to see full Json file from OMDB
//		echo '<h1> Raw data </h1>';
//		var_dump($data);
//		echo '<br><br>';
//
	//if movie was not found redirect to search page
	if(count($data) < 3){
		header('Location: http://localhost:8080/search.php');// replace with hosted URL
		exit();
	}
	else{
	//Get relevant infomation from returned Jsonfile
		 $title = $data["Title"];
		 $director = $data["Director"];
		 $actors = $data["Actors"];
		 $year = $data["Year"];
		 $year = intval($year); //convert to int to add to database
		 $imdbRating = $data["imdbRating"];
		 $imdbRating =floatval($imdbRating); //convert to float (cannot do int because it rounds up/down)
		 $category = $data["Genre"];
		 $poster = $data["Poster"];
		 $rated = $data["Rated"];
		 $plot = $data["Plot"];

		 $newMovieInsertSqlStmt = "INSERT INTO Movies (Title, Director, Actors, ReleaseYear, Poster, IMDB_score, Rated, Category) VALUES (?,?,?,?,?,?,?,?)";
		 $pdo->prepare($newMovieInsertSqlStmt)->execute([$title, $director, $actors, $year, $poster, $imdbRating, $rated, $category]);
	}
}
function handlescores($string){
	$uid = $_SESSION["UID"];

	$uid = intval($uid);
	$searched = $string;
	$pdo = new PDO("sqlite:MMDataBase.db");
	$movieCheckSqlStmt = "SELECT * FROM Movies WHERE Title LIKE :string ";
	$stmt = $pdo->prepare($movieCheckSqlStmt);
	$stmt->bindParam(':string',$searched);
	$stmt->execute();
	$data = $stmt->fetchAll();
	$searchedMovieCategories = $data[0]["Category"];
	$searchedMovieCategories = explode(',', $searchedMovieCategories);

  // echo '<br>';
  // var_dump($uid);


  foreach ($searchedMovieCategories as $category) {
      if(categoryExists($category) == false){
          $pdo = new PDO("sqlite:MMDataBase.db");
          $categoryCheckSqlStmt = "INSERT INTO Scores (UserId,CategoryName,Score) VALUES (?,?,100)";
          $stmt = $pdo->prepare($categoryCheckSqlStmt);
          $stmt->execute([$uid,$category]);
}else{

          $pdo = new PDO("sqlite:MMDataBase.db");
        	$getExistingRowsSqlStmt = "SELECT * FROM Scores WHERE CategoryName = :string";
        	$stmt = $pdo->prepare($getExistingRowsSqlStmt);

          $stmt->bindParam(':string', $category);
        	$stmt->execute();
        	$data = $stmt->fetchAll();
          $score = intval($data[0]['Score']);
          $score = $score + 5;

          $pdo = new PDO("sqlite:MMDataBase.db");
          $categoryCheckSqlStmt = "UPDATE Scores SET Score = :score WHERE UserId= :uid AND CategoryName= :category";
          $stmt = $pdo->prepare($categoryCheckSqlStmt);
          $stmt->bindParam(":score", $score);
          $stmt->bindParam(':uid',$uid);
          $stmt->bindParam(":category",$category);
          $stmt->execute();
  }
}
}
function categoryExists($string){
    $uid = $_SESSION["UID"];

	$uid = intval($uid);
  //Check if searched movie exists in Movie table
  	$pdo = new PDO("sqlite:MMDataBase.db");
  	$movieCheckSqlStmt = "SELECT * FROM Scores WHERE CategoryName = :string AND UserId = :uid";
  	$stmt = $pdo->prepare($movieCheckSqlStmt);
  	$stmt->bindParam(':uid',$uid);
    $stmt->bindParam(':string', $string);
  	$stmt->execute();
  	$result = $stmt->fetchAll();
  	if(count($result) > 0){
  		return true;
  	}
  	else{
  		return false;
  	}
  }


function addToWatch($string){
	$userId = $_SESSION["UID"];
	$pdo = new PDO("sqlite:MMDataBase.db");
	$sql = "SELECT MID FROM Movies WHERE Title LIKE :string ";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':string',$string);
	$stmt->execute();
	$MID = intval($stmt->fetchColumn());
	$sql = "INSERT INTO Watchlist VALUES(?, ?, 0)";
	$insertStmt = $pdo->prepare($sql);
    $insertStmt->execute([$userId, $MID]);
	header('Location: http://localhost:8080/search.php');// replace with hosted URL
}

if(isset($_POST['MovieTitle'])){
	$movietitle = $_POST['MovieTitle'];
	handlescores($movietitle);
	addToWatch($movietitle);
}

if(noSpecialChar($input_term) == true){
	if(movieExistsInDb($input_term) == true){
			populateMovieFromDB($input_term);
	}else{
		populateMovieOMDB($input_term);
	}
}else{
	header('Location: http://localhost:8080/search.php');// replace with hosted URL
	exit();
}


?>

<html>
<head>
	<SCRIPT SRC="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></SCRIPT>
	<link rel="stylesheet" href="css/stylesheet.css">
	<link rel="stylesheet" href="css/searchbar.css">
	<link rel="icon" href="assets/favicon/favicon.ico">
	<title></title>
</head>

<body>
	<?php include('components/header.php'); ?>
	<div class= container>
		<div class=overlay>
			<br><br>
			<div style="float: left; margin-left: 10%;">
				<img style="height: 600px; padding:3%; background-color:white; width: 400px;" src="<?php echo $poster ?>">
			</div>
			<div style="float: left; padding-left:4%; color:#f5f5f5; width:60%; float-right:none;">
					<h1 id="title"><?= $display = ($title != null) ? $title : "Title: "; ?></h1><br>
					<h3>Director: <?= $director ?></h3><br>
					<h3>Year: <?= $year ?> </h3><br>
					<h3>Imdb Score: <?= $imdbRating ?></h3><br>
					<h3>Actors: <?= $actors ?> </h3><br>
					<h3>Genre: <?= $category ?></h3><br>
					<h3>Rated: <?= $rated ?></h3><br>
					<h3>Plot:</h3>
					<p><?= $plot ?></p>
			</div>
			<div class="addtowtchlist" style="width:100%; float:center; height:5%;">

				<button class="button" id="watch" onclick="watchmovie()">Watch</button>
			</div>
		</div>
	</div>
<?php include('components/footer.php'); ?>
</body>
</html>
<script>
function watchmovie(){
	var movtitle = String(document.getElementById("title").textContent);
	$.ajax({
	type: 'POST',
	url: 'searchbar.php',
	data: {'MovieTitle': movtitle},
	success: function(data)
	{
		alert(movtitle + " added to watchlist");
	}
	});
}
</script>
