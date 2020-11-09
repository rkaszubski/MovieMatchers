<?php
include_once ('classes/UserView.class.php');
include_once ('classes/User.class.php');
include_once ('classes/swipe.class.php');
include_once ('classes/Account.class.php');

$userSession = new UserView();
$userSession->session();
// echo "UserID: " . $_SESSION['uid'] . 
// 	"<br>Username: " . $_SESSION['username'] . 
// 	"<br>Email: " . $_SESSION['email'] . 
// 	"<br>Role: " . $_SESSION['role'] . 
// 	"<br>Init: " . $_SESSION['init'] . "<br>";

$swipe = new Swipe();
$swipe->initializeMovies($_SESSION['uid']);
$moviesArr = $swipe->getMovies();
// var_dump($moviesArr);

if (isset($_POST['movieId'])) {
	$mid = $_POST['movieId'];
	$uid = $_SESSION['uid'];
	$cat = $_POST['categories'];
	$categories = explode(',', $cat);
	$watchlistAdd = new Swipe();
	$ret = $watchlistAdd->addToWatchlist($mid, $uid);
	if ($ret != "false") {
		// adjust score
		// $account = new Account();
		// $account->adjustUserScore($uid, $categories);
	} else {
		echo "error";
	}
	
}
if (isset($_POST['decrementCategories'])) {
	$cat = $_POST['decrementCategories'];
	$categories = explode(',', $cat);
	$uid = $_SESSION['uid'];
	// adjust score
	// $account = new Account();
	// $account->adjustUserScore($uid, $categories);
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
					<h1 id="title">Movie Title</h1>
				</div>
				<div class="swipe">
					<button class="button" id="pass" onclick="pass()">Pass</button>
				</div>
				<div class="movieposter">
					<img id="poster" src="assets/popcorn.jpg" >
				</div>
				<div class="swipe">
					<button class="button" id="watch" onclick="watchmovie()">Watch</button>
				</div>
				<div class="movieinfo">
					<h2 id="dir">Director</h2>
					<h2 id="year">Release Year</h2>
					<h2 id="act">Actors</h2>
          			<h2 id="cat">Categories</h2>
				</div>
			</div>
		</div>
		<?php include('components/footer.php'); ?>
	</body>
</html>
<script>
	var movies = <?php echo json_encode($moviesArr)?>;
	var moviecount = 0;
	populatemovie();

	function watchmovie() {
		var movieTitle = movies[moviecount]['Title'];
		var movieIdentity = movies[moviecount]['mid'];
		var movieCategories = movies[moviecount]['Category'];
		const data = {
			movieId: movieIdentity,
			title: movieTitle,
			categories: movieCategories
		};
		const watchbutton = document.getElementById('watch');
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
			alert('Movie id: ' + movies[moviecount]['mid']);
			nextmovie();
		}).fail(function(jqXHR, textState) {
			alert('failed' + jqXHR + ' ' + textState);
		});
		// watchbutton.addEventListener('click', watchRequest);
	}
	function pass() {
    	var movieTitle = movies[moviecount]["Title"];
		var movieIdentity = movies[moviecount]['mid'];
		var movieCategories = movies[moviecount]['Category'];
		const data = {
			movieId: movieIdentity,
			title: movieTitle,
			decrementCategories: movieCategories
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
			alert('Passed: ' + movies[moviecount]['Title']);
			nextmovie();
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