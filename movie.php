<?php
	session_start();

	$pdo = new PDO("sqlite:MMDataBase.db");
	
	$stmt = $pdo->query("SELECT * FROM Movies");
	$all = $stmt->fetchall();
	
	$countStmt = $pdo->query("SELECT COUNT(MID) FROM Movies");
	$count = $countStmt->fetch();
	$count = intval($count[0]);
	
	
	$watchListStmt = $pdo->query("SELECT * FROM Watchlist");
	$watchListAll = $watchListStmt->fetchAll();
	print_r($watchListAll);
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
		<link rel="stylesheet" href="css/stylesheet.css">
	</head>
	<body>
		<?php include('components/header.php'); ?>

		<div class= container>
			<div class=overlay>
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
	var watchList = <?php echo json_encode($watchListAll)?>;
	var clickcount = 0;
	
	console.log(movies[moviecount]["MID"])
	var moviecount = Math.floor(Math.random()* <? echo $count ?>);
	populatemovie();

	function populatemovie(){
		document.getElementById("poster").src=movies[moviecount]["Poster"];
		document.getElementById("title").innerHTML = movies[moviecount]["Title"];
		document.getElementById("dir").innerHTML = "Director: " + movies[moviecount]["Director"];
		document.getElementById("year").innerHTML ="Release Year: " + movies[moviecount]["ReleaseYear"];
		document.getElementById("act").innerHTML = "Actors: " +movies[moviecount]["Actors"];
	}
	
	//Check if movie is already in watchlist
	function verifyMovie(){
		var recommend = movies[moviecount]["MID"];
		var watched = watchList[moviecount]["MovieId"];
		
		if(recommend == watched){
			return true;
		}else{
			return false;
		}
	}

	//populate next movie
	function nextmovie(){
		moviecount = Math.floor(Math.random()* <? echo $count ?>);
		populatemovie();
		clickcount = clickcount+1
		alert(clickcount)
	}

	//
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
	//alert((watchList[moviecount]['MovieId']));
</script>
