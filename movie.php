<?php
include_once ('classes/UserView.class.php');
include_once ('classes/User.class.php');
include_once ('classes/swipe.class.php');
include_once ('classes/account.class.php');
include_once ('classes/gallery.class.php');
include_once ('classes/watchlistbroker.class.php');
$gallery = $title = $director = $releaseYear = $actors = $categories = $poster = $mid = "";

$userSession = new UserView();
$userSession->session();
// echo "UserID: " . $_SESSION['uid'] . 
// 	"<br>Username: " . $_SESSION['username'] . 
// 	"<br>Email: " . $_SESSION['email'] . 
// 	"<br>Role: " . $_SESSION['role'] . 
// 	"<br>Init: " . $_SESSION['init'] . 
// 	"<br>";

if (!isset($_SESSION['gallery'])) {
	$swipe = new Swipe();
	$moviesArr = $swipe->initializeMovies(intval($_SESSION['uid']));
	$gallery = new Gallery($moviesArr);
	$_SESSION['gallery'] = $gallery;
} else {
	$gallery = $_SESSION['gallery'];
}
$movie = $gallery->getQueuedMovie();
// echo "index = " . $gallery->getIndex();
$title = $movie['Title'];
$director = $movie['Director'];
$releaseYear = $movie['ReleaseYear'];
$actors = $movie['Actors'];
$categories = $movie['Category'];
$poster = $movie['Poster'];
$mid = $movie['MID'];

if (isset($_POST['watchMovie'])) {
	echo $mid;
	$watchlistBroker = new WatchlistBroker();
	$ret = $watchlistBroker->movieExistsInUserWatchlist($_SESSION['uid'], $mid);
	if (is_null($ret)) {
		$watchlistBroker->addToWatchlist($_SESSION['uid'], $mid);
	}
}
if (isset($_POST['passMovie'])) {
	
}
?>
<html>
	<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<title>Swipe</title>
		<link rel="stylesheet" href="css/stylesheet.css">
    <link rel="icon" href="assets/favicon/favicon.ico">
	</head>
	<body>
		<?php include('components/header.php'); ?>
		<div class= container>
			<div class=overlay>
        <br><br>
				<div class="movieinfo">
					<h1 id="title"><?php echo (!empty($title)) ? $title : "Title"?></h1>
				</div>
				<div class="swipe">
					<form id="passmovie" method="POST">
						<input type="submit" id="pass" name="passMovie" value="pass"></input>
					</form> 
				</div>
				<div class="movieposter">
					<img id="poster" src="<?php echo (!empty($poster)) ? $poster : "assets/popcorn.jpg"?>">
				</div>
				<div class="swipe">
					<!-- <button class="button" id="watch" onclick="watchmovie()">Watch</button> -->
					<form method="POST" action="movie.php"> 
						<input type="submit" id="watch" name="watchMovie" value="watch"></input>
					</form>
				</div>
				<div class="movieinfo">
					<h2 id="dir"><?php echo (!empty($director)) ? "Director: " . $director : "Director"?></h2>
					<h2 id="year"><?php echo (!empty($releaseYear)) ? "Release Year: " . $releaseYear : "Release Year"?></h2>
					<h2 id="act"><?php echo (!empty($actors)) ? "Actors: " . $actors : "Actors"?></h2>
          			<h2 id="cat"><?php echo (!empty($categories)) ? "Categories: " . $categories : "Categories"?></h2>
					<h2 id="mid"><?php echo (!empty($mid)) ? "MID: " . $mid : "MID"?></h2>
				</div>
			</div>
		</div>
		<?php include('components/footer.php'); ?>
	</body>
</html>
<script>

	function watchmovie() {
		var movieIdentity = document.getElementById("mid").innerHTML;
		var movieTitle = document.getElementById("title").innerHTML;
		var movieCategories = document.getElementById("cat").innerHTML;
		const data = {
			movieIdWatch: movieIdentity,
			title: movieTitle,
			categories: movieCategories
		};
		$.ajax({
				method: "POST",
				url: "movie.php",
				data,
				success: function(data) {
					console.log(document.getElementById("title").innerHTML + 'success');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("some error");
				}
			}).done(function(id) {
				document.getElementById("poster").src="<?php echo $poster ?>";
				document.getElementById("title").innerHTML = "<?php echo $title ?>";
				document.getElementById("dir").innerHTML = "Director: " + "<?php echo $director ?>";
				document.getElementById("year").innerHTML ="Release Year: " + "<?php echo $releaseYear ?>";
				document.getElementById("act").innerHTML = "Actors: " + "<?php echo $actors ?>";
				document.getElementById("cat").innerHTML = "Category: " + "<?php echo $categories ?>";
				document.getElementById("mid").innerHTML = "MID: " + "<?php echo $mid ?>";
			}).fail(function(jqXHR, textState) {
				alert('failed' + jqXHR + ' ' + textState);
			});
	}
	function pass() {
    	var movieIdentity = document.getElementById("mid").innerHTML;
		var movieTitle = document.getElementById("title").innerHTML;
		var movieCategories = document.getElementById("cat").innerHTML;
		const data = {
			movieIdPass: movieIdentity,
			title: movieTitle,
			categories: movieCategories
		};
		const passbutton = document.getElementById('pass');
		$.ajax({
			method: "POST",
			url: "movie.php",
			data,
			success: function(data) {
				console.log('success');
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
            	alert("some error");
        	}
		}).done(function(id) {
			alert('Passed: ' + movieTitle);
		}).fail(function(jqXHR, textState) {
			alert('failed' + jqXHR + ' ' + textState);
		});

  }
	function populatemovie(){
		document.getElementById("poster").src=movies[moviecount]["Poster"];
		document.getElementById("title").innerHTML = movies[moviecount]["Title"];
		document.getElementById("dir").innerHTML = "Director: " + movies[moviecount]["Director"];
		document.getElementById("year").innerHTML ="Release Year: " + movies[moviecount]["ReleaseYear"];
		document.getElementById("act").innerHTML = "Actors: " +movies[moviecount]["Actors"];
    	document.getElementById("cat").innerHTML = "Category: " +movies[moviecount]["Category"];
	}
	function nextmovie(){
		moviecount = moviecount + 1;
		populatemovie();
	}
</script>