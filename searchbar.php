<?php
$title = $director = $actors = $category = $poster = $rated = "";
$year = 0;
$imdbRating = 0.0;
$pdo = new PDO("sqlite:MMDataBase.db");

function noSpecialChar($string)
{
	if (preg_match('/[\'^£%&*()}{#~?><>,|=_¬-]/', $string)) {
	    // one or more of the 'special characters' found in $string
			return false;
	}
	return true;
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
