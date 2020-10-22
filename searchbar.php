<<<<<<< HEAD
<?php
$title = $director = $actors = $category = $poster = $rated = "";
$year = 0;
$imdbRating = 0.0;
$pdo = new PDO("sqlite:MMDataBase.db");

function noSpecialChar($string)
{
	if (preg_match('/[\'^£%&*()}{#~?><>,|=_¬-]/', $string)) {
=======
<?php 

$input_term = $_POST['search'];
 $pdo = new PDO("sqlite:MMDataBase.db");


function noSpecialChar($string) {
	if (preg_match("/[\'^£%&*()}{#~?><>,|=_+¬-]/", $string)) {
>>>>>>> 406257c864160b743fda1410da2090ba235e9261
	    // one or more of the 'special characters' found in $string
			return false;
	}
	return true;
}

<<<<<<< HEAD
=======
function movieExistsInDb($string){
//Check if searched movie exists in Movie table
	$pdo = new PDO("sqlite:MMDataBase.db");
	$movieCheckSqlStmt = "SELECT * FROM Movies where Title = ?";
	$result = $pdo->prepare($movieCheckSqlStmt)->execute([$string]);
	
	
	if($result > 0){
		return true;
	}
	else{
		return false;	
	}
}

//If movie exists in database
if(movieExistsInDb($input_term) == true){
	echo "The movie is already in here";
}else{
	echo "The movie has been added to the Database";
}


//if movie does not exist in Database
if(noSpecialChar([$input_term]) == true) {
	$data = getOmdbRecord("$input_term", "2f79417c");
	
//Uncomment following code to see full Json file from OMDB
	//echo '<h1> Raw data </h1>';
	//var_dump($data);
	//echo '<br><br>';
	
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
	$mid = 11;
}


>>>>>>> 406257c864160b743fda1410da2090ba235e9261
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

<<<<<<< HEAD
function movieExistsInDb($string, $pdoIn)
{
	//Check if searched movie exists in Movie table
	$movieCheckSqlStmt = "SELECT Title FROM Movies where Title = ?";
	$stmt = $pdoIn->prepare($movieCheckSqlStmt);
	$res = $stmt->execute([$string])->fetchColumn();
	if($res){
		return true;
	}
	else {
		return false;
	}
}

if (isset($_POST['search'])) {
	$input_term = $_POST['search'];

	//If movie exists in database
	if(movieExistsInDb($input_term)){

		echo "The movie is already in here";
	}else{
		$data = getOmdbRecord("$input_term", "2f79417c");
		//Uncomment following code to see full Json file from OMDB
		//echo '<h1> Raw data </h1>';
		//var_dump($data);
		//echo '<br><br>';
		$title = $data["Title"];
		$director = $data["Director"];
		$actors = $data["Actors"];
		$year = intval($data["Year"]); //convert to int to add to database
		$imdbRating = floatval($data["imdbRating"]); //convert to float (cannot do int because it rounds up/down)
		$category = $data["Genre"];
		$poster = $data["Poster"];
		$rated = $data["Rated"];

		//Inserting into movie table
		$pdo = new PDO("sqlite:MMDataBase.db");
		$newMovieInsertSqlStmt =
		"INSERT INTO Movies (Title, Director, Actors, ReleaseYear, Poster, IMDB_score, Rated, Category)
		VALUES (?,?,?,?,?,?,?,?)";

		$pdo->prepare($newMovieInsertSqlStmt)->execute([$title, $director, $actors, $year, $poster, $imdbRating, $rated, $category]);
		echo "The movie has been added to the Database";
	}
=======
//Inserting into movie table
$newMovieInsertSqlStmt = 
	"INSERT INTO Movies (Title, Director, Actors, ReleaseYear, Poster, IMDB_score, Rated, Category)
	VALUES (?,?,?,?,?,?,?,?)";
	
$pdo->prepare($newMovieInsertSqlStmt)->execute([$title, $director, $actors, $year, $poster, $imdbRating, $rated, $category]);
>>>>>>> 406257c864160b743fda1410da2090ba235e9261

?>

<html>
<head>
    <link rel="stylesheet" href="css/stylesheet.css">
	<title></title>
</head>

<body>
	<?php include('components/header.php'); ?>
	<div class= container>
		<div class=overlay>
			<div style="float: left; margin-left: 10%;">
				<img style="height: 600px; padding:3%; background-color:white; width: 400px;" src='<? echo $poster ?>'>
			</div>
			<div style="float: left; padding-left:3%; color:#f5f5f5">
					<h1>Title: <?= $title ?></h1><br>
					<h3>Director: <?= $director ?></h3><br>
					<h3>Imdb Score: <?= $imdbRating ?></h3><br>
					<h3>Actors: <?= $actors ?> </h3><br>
					<h3>Genre: <?= $category ?></h3><br>
					<h3>Rated: <?= $rated ?></h3><br>
			</div>
		</div>
	</div>
<?php include('components/footer.php'); ?>
</body>
</html>
