<?php
session_start();
//If user is not logged in redirect to login page
if (!isset($_SESSION["UID"]))
{
		header("Location: login.php");
}
//Store user ID and declare global variables
$userId = intval($_SESSION["UID"]);
$movieError = "";
$title = $director = $actors = $category = $poster = $rated = $plot = "";
$year = 0;
$imdbRating = 0.0;
$input_term = "";
// $pdo = new PDO("sqlite:MMDataBase.db"); //Establish Database connection


// Function to ensure no special characters are used in movie name
function noSpecialChar($string) {
	if (preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $string)) {
	    // one or more of the 'special characters' found in $string
			return false;
	}
	return true;
}

// This function uses the OMDB API to get the information of the movie
// the user has searched
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

function movieExistsinDB($movieTitle) {
	$pdo = new PDO("sqlite:MMDataBase.db");
	$sqlMovieExistsInDB = "SELECT MID FROM Movies WHERE Title LIKE :title "; //Query to check if movie with Specific name is already in Database
	$stmtMovieExistsInDB = $pdo->prepare($sqlMovieExistsInDB);
	$stmtMovieExistsInDB->bindParam(':title',$movieTitle);
	$stmtMovieExistsInDB->execute();
	$movieExistsInDB = $stmtMovieExistsInDB->fetchColumn();
	// this will return the corresponding MID or false
	return $movieExistsInDB;
}
//-------------- swipe methods --------------//
function AddCategories($userId, $catArr) {
	if (count($catArr) > 0) {
		$pdo = new PDO("sqlite:MMDataBase.db");
		$sqlCatExists = "SELECT CategoryName FROM Scores WHERE UserId=:uid AND CategoryName=:category"; // Get all records containing scores for categories specific to current user
		$sqlInsertCat = "INSERT INTO Scores (UserId, CategoryName, Score) VALUES (:uid,:category,100)"; // Query to add a category if a record does not already exist
		foreach ($catArr as $cat) {
			$category = trim($cat); // remove spaces
			$stmtCatExists = $pdo->prepare($sqlCatExists);
			$stmtCatExists->bindParam(':uid', $userId);
			$stmtCatExists->bindParam(':category', $category);
			$stmtCatExists->execute();
			$catExists = $stmtCatExists->fetchColumn();
			if ($catExists == false) { //If recode does not already exist insert it into table with initial score of 100
				$stmtInsertCat = $pdo->prepare($sqlInsertCat);
				$stmtInsertCat->bindParam(':uid', $userId);
				$stmtInsertCat->bindParam(':category', $category);
				$stmtInsertCat->execute();
			}
			// else do nothing, category exists
		}
	} else {
		echo "Error, category array is empty";
	}
}

//The following function adjusts the score
// of a category based on whether a user passes on
// movie of that category or adds to watchlist
function AdjustScore($amt, $userId, $catArr) {
	if (count($catArr) > 0) {
		$pdo = new PDO("sqlite:MMDataBase.db");
		$sqlCatExists = "SELECT Score FROM Scores WHERE UserId=:uid AND CategoryName=:name";
		$sqlUpdateScore = "UPDATE Scores SET Score=:score WHERE UserId=:uid AND CategoryName=:name";
		foreach ($catArr as $cat) {
			// assert the category exists
			$category = trim($cat);
			$stmtCatExists = $pdo->prepare($sqlCatExists);
			$stmtCatExists->bindParam(':uid', $userId);
			$stmtCatExists->bindParam(':name', $category);
			$stmtCatExists->execute();
			$catExists = $stmtCatExists->fetchColumn();
			if ($catExists != false) {
				// category exists, update the score
				$newScore = $catExists + $amt;
				$stmtUpdateScore = $pdo->prepare($sqlUpdateScore);
				$stmtUpdateScore->bindParam(':score', $newScore);
				$stmtUpdateScore->bindParam(':uid', $userId);
				$stmtUpdateScore->bindParam(':name', $category);
				$stmtUpdateScore->execute();
			}
			// else do nothing, category exists
		}
	} else {
		echo "Error, category array is empty";
	}
}
// ------------- end Swipe methods ----------//

function populateMovie($movieTitle) {
	$userId = intval($_SESSION["UID"]);
	$mTitle = trim($movieTitle);
	if ($mTitle == null) {
		echo "error, movietitle is null";
		return false;
	}

	global $title,$director,$actors,$year,$imdbRating,$category,$poster,$rated,$plot; // Using global varables to store information that will be used later
	$pdo = new PDO("sqlite:MMDataBase.db");
	if (movieExistsinDB($mTitle) != false) {
		// movie exists in database, use database to populate
		$sqlGetMovieDataByName = "SELECT * FROM Movies WHERE Title LIKE :title ";						// Query to select movie with specific name
		$stmtGetMovieDataByName = $pdo->prepare($sqlGetMovieDataByName);																				// Prepare Query
		$stmtGetMovieDataByName->bindParam(':title',$mTitle);																							// Different syntax to inject variable information into Query
		$stmtGetMovieDataByName->execute();																																	// Execute
		$movieData = $stmtGetMovieDataByName->fetch();																												// Get all records from query execution

		// Store all relevant information into global variable
		$title = $movieData["Title"];
		$director = $movieData["Director"];
		$actors = $movieData["Actors"];
		$year = intval($movieData["ReleaseYear"]); //convert to int to add to database
		$imdbRating =floatval($movieData["IMDB_score"]); //convert to float (cannot do int because it rounds up/down)
		$category = $movieData["Category"];
		$poster = $movieData["Poster"];
		$rated = $movieData["Rated"];
		$plot = $movieData["Plot"];

	} else {
		// movie does NOT exist, get data from omdbapi
		$movieData = getOmdbRecord($mTitle, "2f79417c");  //Use OMDB to get movie information
		//if movie was not found redirect to search page
		if(count($movieData) < 3) {
			// If the  resulting JSON file has less that 3 keys then movie was not found, redirect to search page
			header('Location: search.php');// replace with hosted URL
			exit();
		} else{
			//Get relevant infomation from returned Jsonfile
			 $title = $movieData["Title"];
			 $director = $movieData["Director"];
			 $actors = $movieData["Actors"];
			 $year = intval($movieData["Year"]); //convert to int to add to database
			 $imdbRating =floatval($movieData["imdbRating"]); //convert to float (cannot do int because it rounds up/down)
			 $category = $movieData["Genre"];
			 $poster = $movieData["Poster"];
			 $rated = $movieData["Rated"];
			 $plot = $movieData["Plot"];

			 // Insert movie information into Movie Table
			 $sqlInsertNewMovieIntoMovies = "INSERT INTO Movies (Title, Director, Actors, ReleaseYear, Poster, IMDB_score, Rated, Category, Plot) VALUES (?,?,?,?,?,?,?,?,?)";
			 $stmtInsertNewMovieIntoMovies = $pdo->prepare($sqlInsertNewMovieIntoMovies);
			 $stmtInsertNewMovieIntoMovies->execute([$title, $director, $actors, $year, $poster, $imdbRating, $rated, $category, $plot]);
		}
	}
	$categories = explode(',', $category);
	AddCategories($userId, $categories);
}

// Add movie to watchlist called using Ajax
function addToWatchlist($movieTitle) {
	$userId = intval($_SESSION["UID"]);
	$pdo = new PDO("sqlite:MMDataBase.db");
	$sqlGetMovieIdByTitle = "SELECT MID, Category FROM Movies WHERE Title LIKE :title";
	$stmtGetMovieIdByTitle = $pdo->prepare($sqlGetMovieIdByTitle);
	$stmtGetMovieIdByTitle->bindParam(':title',$movieTitle);
	$stmtGetMovieIdByTitle->execute();
	$movieExistsByTitle = $stmtGetMovieIdByTitle->fetch();
	if ($movieExistsByTitle == false) {
		$movieError = "This movie does not exist by associated title";
		return;
	}
	$movieId = intval($movieExistsByTitle["MID"]);
	$categories = explode(',', $movieExistsByTitle["Category"]);

	// check if movie is in watchlist for user
	$sqlMovieExistsInWatchlist = "SELECT MovieId FROM Watchlist WHERE UserId=:userId AND MovieId=:movieId";
	$stmtMovieExistsInWatchlist = $pdo->prepare($sqlMovieExistsInWatchlist);
	$stmtMovieExistsInWatchlist->bindParam(':userId',$userId);
	$stmtMovieExistsInWatchlist->bindParam(':movieId',$movieId);
	$stmtMovieExistsInWatchlist->execute();
	$movieExistsInWatchlist = $stmtMovieExistsInWatchlist->fetchColumn();
	// fetchColumn() returns "false" if no records are found
	if ($movieExistsInWatchlist == false) {
		// movie does NOT exist in user's watchlist, ADD MOVIE, ADD CATEGORIES w/ Scores
		// THIS IS ADDING 5 TO SCORES
		AdjustScore(5,$userId, $categories);
		// insert into watchlist
		$sqlInsertMovieIdToWatchlist = "INSERT INTO Watchlist (UserId, MovieId, Watched) VALUES(?, ?, 0)";
		$insertMovieIdToWatchlist = $pdo->prepare($sqlInsertMovieIdToWatchlist);
	  $insertMovieIdToWatchlist->execute([$userId, $movieId]);
		header('Location: search.php');// replace with hosted URL
	} else {
		// movie already is in watchlist, error out
		echo "Error, this movie is already watched";
	}
}


/* -------------------- BELOW ALL FUNCTIONS ARE CALLED/INITIATED --------------------- */

// If Watch button is clicked, add to watchlist
if(isset($_POST['MovieTitle'])) {
	$titleOfMovie = trim($_POST['MovieTitle']);
	addToWatchlist($titleOfMovie);
}

if (isset($_POST['search'])) {
	// Store user input from search page
	$input_term = trim($_POST['search']);
	if(!preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $input_term) == true){				//Make sure searched movie has no special characters
		populateMovie($input_term);
	} else {
		header('Location: search.php');// replace with hosted URL
		exit();
	}
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
					<h3 id="category">Genre: <?= $category ?></h3><br>
					<h3>Rated: <?= $rated ?></h3><br>
					<h3>Plot:</h3>
					<p><?= $plot ?></p>
			</div>
			<h3><?php echo $movieError ?></h3>
			<div class="addtowtchlist" style="width:100%; float:center; height:5%;">
				<button class="button" id="watch" onclick="watchmovie()">Watch</button>
			</div>
			<div class="searchMore" style="width:100%; float:center; height:5%;">
				<button class="button" id="searchMore" onclick="searchMore()">Back to Search</button>
			</div>
		</div>
	</div>
<?php include('components/footer.php'); ?>
</body>
</html>
<script>
// This function is too overloaded for SQLite to handle, how can we support?
function watchmovie(){
	var titleOfMovie = String(document.getElementById("title").textContent);
	var category = String(document.getElementById("category").textContent);
	const data = {
		MovieTitle: titleOfMovie,
		Category: category
	};
	$.ajax({
	type: 'POST',
	url: 'searchbar.php',
	data,
	success: function(data)
	{
		alert(titleOfMovie + " added to watchlist");
	}
	});
}

function searchMore() {
	alert("searchmore");
}
</script>
