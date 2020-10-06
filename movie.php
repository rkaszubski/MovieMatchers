<?php
	session_start();

	$pdo = new PDO("sqlite:MMDataBase.db");
	$stmt = $pdo->query("SELECT * FROM Movie");
	$all = $stmt->fetchall();
	
	
?>
<?php
	$user = $_SESSION["username"];

	if(isset($_POST['outtitle'])){
		echo "here";
		$title = $_POST['outtitle'];
		$pdo = new PDO("sqlite:MMDataBase.db");
		$pdo->query("INSERT INTO Watchlist VALUES('$user','$title')");
	}
?>
<html>
	<head>
		<SCRIPT SRC="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></SCRIPT>
		<title>Swipe</title>
	</head>
	<body>
		<h1 id="title">Movie Title</h1>
		<img id="poster" src="" width="400px">
		<h2 id="dir">Director</h2>
		<h2 id="year">Release Year</h2>
		<h2 id="act">Actors</h2>
	</body>
	<button class="button" onclick="nextmovie()">Pass</button>
	<button class="button" onclick="watchmovie()">Watch</button>
</html>

<script>
	var movies = <?php echo json_encode($all)?>;
	var moviecount = 0;
	populatemovie();
	
	function populatemovie(){
		document.getElementById('poster').src=movies[moviecount]["PosterLink"];
		document.getElementById("title").innerHTML = movies[moviecount]["Title"];
		document.getElementById("dir").innerHTML = "Director: " + movies[moviecount]["Director"];
		document.getElementById("year").innerHTML ="Release Year: " + movies[moviecount]["Year"];
		document.getElementById("act").innerHTML = "Actors: " +movies[moviecount]["Actors"];
	}
	
	
	function nextmovie(){
		moviecount = moviecount + 1;
		populatemovie();
	}
	
	function watchmovie(){
		var movietitle = movies[moviecount]["Title"];
		$.ajax({
		type: 'POST',
		url: 'movie.php',
		data: {'outtitle': movietitle},
		success: function(data)
		{
			alert(movietitle + " added to watchlist");
		}
		});
		nextmovie();
	}
	
</script>

