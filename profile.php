<?php

	session_start();
	if (!isset($_SESSION["UID"]))
	{
			header("Location: login.php");
	}
	$user = $_SESSION["username"];
	$uid = $_SESSION["UID"];
//test
	$user = $_SESSION["username"];
	$uid = $_SESSION["UID"];
	$pdo = new PDO("sqlite:MMDataBase.db");
	$sqlUserWatchlistMID = "SELECT MovieId FROM Watchlist WHERE UserId=?";
	$stmt = $pdo->prepare($sqlUserWatchlistMID);
	$stmt->execute([$uid]);
	$userWatchlistMID = $stmt->fetchAll();

?>
<html>
	<head>
		<title>My Profile</title>
		<link rel="stylesheet" href="css/stylesheet.css">
		<link rel="icon" href="assets/favicon/favicon.ico">
	</head>
	<body>
		<?php include('components/header.php'); ?>
		<div class= container>
			<div class = overlay style=" overflow:scroll; overflow-x:hidden; padding-right:10%;">
				<br>
				<center><h1 style="border-bottom:1px; border-bottom-style:solid;color:white;"><?= $user ?>'s Recommendations</h1></center>
				<?php
				//$pdo = new PDO("sqlite:MMDataBase.db");
				$getrecommendedmovies = "SELECT * FROM SCORES WHERE UserId = :uid ORDER BY Score DESC LIMIT 3 "; // get three categories with the highest scores
				$stmtRecommendations = $pdo->prepare($getrecommendedmovies);
				$stmtRecommendations->bindParam(":uid", $uid);
				$stmtRecommendations->execute();
				$recommendations = $stmtRecommendations->fetchAll();
				foreach ($recommendations as $category) {

					$category = ($category["CategoryName"]);
					echo "<br>";

					echo $appendedCategory;
					$moviesWithCategory = "SELECT * FROM Movies WHERE Category LIKE ?";
					$movies = $pdo->prepare($moviesWithCategory);
					$movies->execute(["%$category%"]);
					$results = $movies->fetchAll();
					var_dump($results[0]["Category"]);
				}
				?>

				<br>
				<center><h1 style="border-bottom:1px; border-bottom-style:solid;color:white;"><?= $user ?>'s Watchlist</h1></center>
				<br>
				<div class= watchlist style="height:80%; width:90%;margin-left:10%; margin-right:5%; ">
						<?php
							// iterate through all movieId's (MID) and find the according movie titles (or entire object... later) per each MID
							foreach($userWatchlistMID as $row)
							{
								$mid = $row["MovieId"];
								$sqlMovieTitle = "SELECT Title, Director, Poster FROM Movies WHERE MID=?";
								$stmt2 = $pdo->prepare($sqlMovieTitle);
								$stmt2->execute([$mid]);
								$data = $stmt2->fetch();
								$title = $data["Title"];
								$director = $data["Director"];
								$poster = $data["Poster"];

							  echo"<div class = watchlistmovie>
										<img src = '$poster'>
										<br>
										<center>
												<h3 style = 'color:white;'>$title<h3>
										</center>
								</div>";
						}
						?>
					</div>
			</div>
		<div>
			<?php include('components/footer.php'); ?>
	</body>


</html>
