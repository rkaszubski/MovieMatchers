<?php
    session_start();
    if (!isset($_SESSION["UID"]))
  	{
  			header("Location: login.php");
  	}
    //test
    // Initialize PDO Object
    $pdo = new PDO("sqlite:MMDataBase.db");

    $userId = $_SESSION["UID"];
    $sqlWatchedMovies = "SELECT MovieId FROM Watchlist WHERE UserId=$userId";
    $sqlFilteredMovies = "SELECT * FROM Movies WHERE MID NOT IN ($sqlWatchedMovies)";
    $stmtFill = $pdo->query($sqlFilteredMovies);
    $all = $stmtFill->fetchall();
?>
<?php
    // This chuck of PHP code adds a movie to the users watchlist if the MovieId is set (if it is clicked)
    // since "$userId" is defined in the first PHP code chunk, can we omit this variable declaration (below)?

    // movies that are watched need to update the fetchAll "$all" variable with innerjoin in watchlist (movie innerjoin watchlist)
	$userId = $_SESSION["UID"];

	if(isset($_POST['MovieId'])){
		echo "<h1>here</h1>";
		$movieId = $_POST['MovieId'];
		$pdo = new PDO("sqlite:MMDataBase.db");
		$sql = "INSERT INTO Watchlist VALUES(?, ?, 0)";
		$insertStmt = $pdo->prepare($sql);
        $insertStmt->execute([$userId, $movieId]);

        $stmtFill = $pdo->query($sqlFilteredMovies);
        $all = $stmtFill->fetchall();
        // foreach($all as $result) {
        //     echo $result["MovieId"], "<br>";
        // }

	}
?>
<html>
	<head>
		<SCRIPT SRC="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></SCRIPT>
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

					<button class="button" id="pass" onclick="nextmovie()">Pass</button>
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
				</div>
			</div>
		</div>
		<?php include('components/footer.php'); ?>
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
