<?php
include_once ('classes/searchUtil.class.php');
include_once ('classes/UserView.class.php');
$userSession = new UserView();
$userSession->session();
echo "UserID: " . $_SESSION['uid'] . 
	"<br>Username: " . $_SESSION['username'] . 
	"<br>Email: " . $_SESSION['email'] . 
	"<br>Role: " . $_SESSION['role'] . 
	"<br>Init: " . $_SESSION['init'] . 
    "<br>";
    
$movieError = "";
$title = $director = $actors = $categories = $poster = $rated = $plot = "";
$releaseYear = 0;
$imdbRating = 0.0;
$searchedTitle = "";

function populateMovie($movieTitle) {
    if (is_null($movieTitle)) {
        return 'Movie Title is null, please enter good input';
    }
    else if (empty($movieTitle)) {
        return 'Movie Title is empty, please enter good input';
    }
    $searchRes = new SearchUtil();
    $movieData = $searchRes->doesMovieExist($movieTitle);
    // var_dump($movieData);
    if ($movieData != false) {
        // movie exists in database, use the returned data (don't call ombd)
        return $movieData;
    } else {
        // movie was not found in database, use ombd api
        echo "movie not found, check ombd";
    }
}

// this if method receives and initiates
if (isset($_POST['search'])) {
	// Store user input from search page
	$searchedTitle = trim($_POST['search']);
	if(!preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $searchedTitle)) {				//Make sure searched movie has no special characters
        $movieExists = populateMovie($searchedTitle);
        $title = $movieExists["Title"];
		$director = $movieExists["Director"];
		$actors = $movieExists["Actors"];
		$year = intval($movieExists["ReleaseYear"]); //convert to int to add to database
		$imdbRating =floatval($movieExists["IMDB_score"]); //convert to float (cannot do int because it rounds up/down)
		$categories = $movieExists["Category"];
		$poster = $movieExists["Poster"];
		$rated = $movieExists["Rated"];
		$plot = $movieExists["Plot"];
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
					<h1 id="title"><?= ($title != null) ? $title : "Title: "; ?></h1><br>
					<h3>Director: <?= $director ?></h3><br>
					<h3>Year: <?= $releaseYear ?> </h3><br>
					<h3>Imdb Score: <?= $imdbRating ?></h3><br>
					<h3>Actors: <?= $actors ?> </h3><br>
					<h3 id="category">Genre: <?= $categories ?></h3><br>
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