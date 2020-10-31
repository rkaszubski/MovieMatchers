<?php

	session_start();
	if (!isset($_SESSION["UID"]))
	{
			header("Location: login.php");
	}
	$user = $_SESSION["username"];
	$uid = $_SESSION["UID"];

	$user = $_SESSION["username"];
	$uid = $_SESSION["UID"];
	$pdo = new PDO("sqlite:MMDataBase.db");
	$sqlUserWatchlistMID = "SELECT MovieId FROM Watchlist WHERE UserId=?"; //Query to get all movies
	$stmt = $pdo->prepare($sqlUserWatchlistMID); //Prepare Statement
	$stmt->execute([$uid]);											 // Inject variable information into query and Execute
	$userWatchlistMID = $stmt->fetchAll();			 // Get all records from executing query

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
					<div class= watchlist style="height:80%; width:90%;margin-left:10%; margin-right:5%; ">
				<?php
				//$pdo = new PDO("sqlite:MMDataBase.db");
				$getrecommendedmovies = "SELECT * FROM SCORES WHERE UserId = :uid ORDER BY Score DESC LIMIT 5 "; // Query to get top 5 categories with the highest scores
				$stmtRecommendations = $pdo->prepare($getrecommendedmovies); //prepare satement
				$stmtRecommendations->bindParam(":uid", $uid); 							 // inject parameter information into Query
				$stmtRecommendations->execute(); 														 // Execute the query
				$recommendations = $stmtRecommendations->fetchAll();
				foreach ($recommendations as $category) { //For each category previously recieved
					$category = ($category["CategoryName"]);
					$moviesWithCategory = "SELECT * FROM Movies WHERE Category LIKE ? ORDER BY random() LIMIT 1"; // Get one random movie that has current category in Catrgory field
					$movies = $pdo->prepare($moviesWithCategory);																									// Prepare Query
					$movies->execute(["%$category%"]);																														// Inject varible information and executing query
					$results = $movies->fetchAll();																																// Get all records from query execution

					//Storing  the Title and poster information of randomly selected movie in variables
					$title = $results[0]["Title"];
					$poster = $results[0]["Poster"];

					//This code injects the html div that will populate the recommendations on the profile page
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


				<center><h1 style="border-bottom:1px; border-bottom-style:solid;color:white;"><?= $user ?>'s Watchlist</h1></center>
				<br>
				<div class= watchlist style="height:80%; width:90%;margin-left:10%; margin-right:5%; ">
						<?php
							// iterate through all movieId's (MID) and find the according movie titles (or entire object... later) per each MID
							foreach($userWatchlistMID as $row)
							{
								$mid = $row["MovieId"];
								$sqlMovieTitle = "SELECT Title, Director, Poster FROM Movies WHERE MID=?"; // Query to get all information of movies in watchlist by their Movie IDs from movie table
								$stmt2 = $pdo->prepare($sqlMovieTitle);																		 // Prepare Query
								$stmt2->execute([$mid]);																									 // Inject variable information into query and execute
								$data = $stmt2->fetch();																									 // Get all records from query execution

								//Storing Title and Poster information of movies
								$title = $data["Title"];
								$director = $data["Director"];
								$poster = $data["Poster"];

								//This code injects the HTML div that populates the movie information on the Profile Page
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
