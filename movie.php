<?php
	session_start();

	$pdo = new PDO("sqlite:MMDataBase.db");
	$stmt = $pdo->query("SELECT * FROM Movies");
	$all = $stmt->fetchall();


?>
<?php
	$userId = $_SESSION["UID"];

	if(isset($_POST['MovieId'])){
		echo "<h1>here</h1>";
		$movieId = $_POST['MovieId'];
		$pdo = new PDO("sqlite:MMDataBase.db");
		$sql = "INSERT INTO Watchlist VALUES(?, ?, 0)";
		$insertStmt = $pdo->prepare($sql);
		$insertStmt->execute([$userId, $movieId]);

		// $pdo->query("INSERT INTO Watchlist VALUES('$userId','$movieId', 0)");
	}
?>
<html>
	<head>
		<SCRIPT SRC="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></SCRIPT>
		<title>Swipe</title>
		<link rel="stylesheet" href="stylesheet.css">
	</head>
	<body>
		<div class = header>
			<div class=MMatcher>
				Movie Matchers
			</div>

			<div class = links>

				<a href="search.php">Search</a>
				<a href="movie.php">Swipe</a>
				<a href="profile.php">Profile</a>

			</div>
		</div>
		<div class= container>
			<div class=overlay>
				<div class="movieinfo">
					<h1 id="title">Movie Title</h1>
				</div>

				<div class="swipe">

					<button class="button" id="pass" onclick="nextmovie()">Pass</button>
				</div>

				<div class="movieposter">
					<img id="poster" src="popcorn.jpg" >
				</div>

				<div class="swipe">

					<button class="button" id="watch" onclick="watchmovie()">Watch</button>
				</div>

				<div class="movieinfo">
					<h2 id="dir">Director</h2>
					<h2 id="year">Release Year</h2>
					<h2 id="act">Actors</h2>
				</div>
			</div>
		</div>

	</body>

</html>

<script>
	var movies = <?php echo json_encode($all)?>;
	var moviecount = 0;
	populatemovie();

	function populatemovie(){
		document.getElementById("poster").src=movies[moviecount]["Poster"];
		document.getElementById("title").innerHTML = movies[moviecount]["Title"];
		document.getElementById("dir").innerHTML = "Director: " + movies[moviecount]["Director"];
		document.getElementById("year").innerHTML ="Release Year: " + movies[moviecount]["ReleaseYear"];
		document.getElementById("act").innerHTML = "Actors: " +movies[moviecount]["Actors"];
	}


	function nextmovie(){
		moviecount = moviecount + 1;
		populatemovie();
	}

	function watchmovie(){
		var movietitle = movies[moviecount]["Title"];
		var movieIdentity = movies[moviecount]["MID"];
		$.ajax({
		type: 'POST',
		url: 'movie.php',
		data: {'MovieId': movieIdentity},
		success: function(data)
		{
			alert(movietitle + " added to watchlist");
		}
		});
		nextmovie();
	}

</script>
