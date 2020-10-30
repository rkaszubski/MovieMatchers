<?php
    session_start();
    if (!isset($_SESSION["UID"]))
  	{
  			header("Location: login.php");
  	}
    //test
    // Initialize PDO Object
    $pdo = new PDO("sqlite:MMDataBase.db");

    $userId = intval($_SESSION["UID"]);
    $sqlWatchedMovies = "SELECT MovieId FROM Watchlist WHERE UserId=$userId";
    $sqlFilteredMovies = "SELECT * FROM Movies WHERE MID NOT IN ($sqlWatchedMovies)";
    $stmtFill = $pdo->query($sqlFilteredMovies);
	  $moviesArr = $stmtFill->fetchall();


	?>
	<?php

    // This chunk of PHP code adds a movie to the users watchlist if the MovieId is set (if it is clicked)
	// since "$userId" is defined in the first PHP code chunk, can we omit this variable declaration (below)?
	function AddCategories($userId, $catArr) {
		if (count($catArr) > 0) {
      $pdo = new PDO("sqlite:MMDataBase.db");
			$sqlCatExists = "SELECT CategoryName FROM Scores WHERE UserId=:uid AND CategoryName=:category";
			$sqlInsertCat = "INSERT INTO Scores (UserId, CategoryName, Score) VALUES (:uid,:category,100)";
			foreach ($catArr as $cat) {
				$category = trim($cat);
				$stmtCatExists = $pdo->prepare($sqlCatExists);
				$stmtCatExists->bindParam(':uid', $userId);
				$stmtCatExists->bindParam(':category', $category);
				$stmtCatExists->execute();
				$catExists = $stmtCatExists->fetchColumn();
				if ($catExists == false) {
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

	$userId = $_SESSION["UID"];
	if(isset($_POST['MovieId'])){
		// implement scoring functionality
		$pdo = new PDO("sqlite:MMDataBase.db");
		$categories = $_POST['Category'];
		$catArr = explode(',', $categories);
    if (count($catArr) < 1) {
      header("Location: login.php");
    }
		AddCategories($userId, $catArr);
		AdjustScore(5,$userId, $catArr);

		// add movie to user's watchlist
		$movieId = $_POST['MovieId'];
		$sql = "INSERT INTO Watchlist VALUES(?, ?, 0)";
		$insertStmt = $pdo->prepare($sql);
    $insertStmt->execute([$userId, $movieId]);

    $stmtFill = $pdo->query($sqlFilteredMovies);
    $all = $stmtFill->fetchall();

    // grab a random MID from movies user hasn't added to watchlist or passed on yet
    // still don't have a passed table yet
    // $minMID = $pdo->query("SELECT min(MID) FROM ($sqlFilteredMovies)");
    // $maxMID = $pdo->query("SELECT max(MID) FROM ($sqlFilteredMovies)");
    // $randomMID = rand($minMID, $maxMID);
    // echo $minMID;
    // echo $maxMID;
    // echo $randomMID;
	}

  if(isset($_POST['NotCategory'])) {
    // decrement score for passed movie
    $pdo = new PDO("sqlite:MMDataBase.db");
		$categories = $_POST['NotCategory'];
		$catArr = explode(',', $categories);
    if (count($catArr) < 1) {
      header("Location: login.php");
    }
		AddCategories($userId, $catArr);
		AdjustScore(-5,$userId, $catArr);
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

	function populatemovie(){
		document.getElementById("poster").src=movies[moviecount]["Poster"];
		document.getElementById("title").innerHTML = movies[moviecount]["Title"];
		document.getElementById("dir").innerHTML = "Director: " + movies[moviecount]["Director"];
		document.getElementById("year").innerHTML ="Release Year: " + movies[moviecount]["ReleaseYear"];
		document.getElementById("act").innerHTML = "Actors: " +movies[moviecount]["Actors"];
	}

  function pass() {
    var movietitle = movies[moviecount]["Title"];
		var movieCategories = movies[moviecount]["Category"];
		// console.log(movieCategories + " " + movieIdentity);
    const data = {
      NotCategory: movieCategories
    };
		$.ajax({
		type: 'POST',
		url: 'movie.php',
		data,
		success: function(data)
		{
			alert(movietitle + " disliked");
			nextmovie();
		}
		});

  }
	function nextmovie(){
		moviecount = moviecount + 1;
		populatemovie();
	}

	function watchmovie(){
		var movietitle = movies[moviecount]["Title"];
		var movieIdentity = movies[moviecount]["MID"];
		var movieCategories = movies[moviecount]["Category"];
		// console.log(movieCategories + " " + movieIdentity);
    const data = {
      MovieId: movieIdentity,
      Category: movieCategories
    };
		$.ajax({
		type: 'POST',
		url: 'movie.php',
		data,
		success: function(data)
		{
			alert(movietitle + " added to watchlist");
			nextmovie();
		}
		});

	}

</script>
