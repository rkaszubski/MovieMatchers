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

function populateMovie($string){
	//if movie does not exist in Database
	$data = getOmdbRecord("$string", "2f79417c");

	global $title,$director,$actors,$year,$imdbRating,$category,$poster,$rated,$plot;
	//Uncomment following code to see full Json file from OMDB
//		echo '<h1> Raw data </h1>';
//		var_dump($data);
//		echo '<br><br>';

//	if movie was not found redirect to search page
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
	}
}

function populateMovieAdd($string){
	//if movie does not exist in Database
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
if(noSpecialChar($input_term) == true){
	if(movieExistsInDb($input_term) == true){
			populateMovie($input_term);
	}else{
		populateMovieAdd($input_term);
	}
}else{
	header('Location: http://localhost:8080/search.php');// replace with hosted URL
	exit();
}

?>
<html>
<head>
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
					<h1><?= $display = ($title != null) ? $title : "Title: "; ?></h1><br>
					<h3>Director: <?= $director ?></h3><br>
					<h3>Year: <?= $year ?> </h3><br>
					<h3>Imdb Score: <?= $imdbRating ?></h3><br>
					<h3>Actors: <?= $actors ?> </h3><br>
					<h3>Genre: <?= $category ?></h3><br>
					<h3>Rated: <?= $rated ?></h3><br>
					<h3>Plot:</h3>
					<p><?= $plot ?></p>
			</div>
			<div class="addtowtchlist" style="width:100%; float:left; height:5%;">
				<form method="add">
					<center><button type= "submit" name="button1" style="padding: 10px;">Add To Watchlist</button></center>
				</form>
			</div>
		</div>
	</div>
<?php include('components/footer.php'); ?>
</body>
</html>
