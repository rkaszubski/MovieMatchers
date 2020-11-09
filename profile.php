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
	$user = $_SESSION['username'];
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
				<center><h1 style="border-bottom:1px; border-bottom-style:solid;color:white;"><?php echo $user ?>'s Recommendations</h1></center>
					<div class= recommendations style="height:80%; width:90%;margin-left:10%; margin-right:5%; ">
				<?php
						// db call
					// 	$movieErrorLog = "";
					// 	$sqlTopCategoriesByScoreByUser = "SELECT CategoryName FROM SCORES WHERE UserId=:uid ORDER BY Score DESC"; // Query to get top 5 categories with the highest scores
					// 	$stmtTopCategoriesByScoreByUser = $pdo->prepare($sqlTopCategoriesByScoreByUser); //prepare satement
					// 	$stmtTopCategoriesByScoreByUser->bindParam(":uid", $uid); 							 // inject parameter information into Query
					// 	$stmtTopCategoriesByScoreByUser->execute(); 														 // Execute the query
					// 	$topCategoriesByScoreByUser = $stmtTopCategoriesByScoreByUser->fetchAll();

					// 	// amount of rows returned by query MINUS ONE
					// 	$lengthOfCategories = count($topCategoriesByScoreByUser) - 1;

					// 	// shell of array to keep track of recs
					// 	$existingRecommendationTitles = array("empty0", "empty1", "empty2", "empty3", "empty4");

					// 	// count of categories that successfully added as recommendations on page.
					// 	$countRecommendation = 0;

					// 	foreach ($topCategoriesByScoreByUser as $cat) {
					// 		if ($countRecommendation > 4) {
					// 			break;
					// 		}
					// 		$category = "%" . $cat["CategoryName"] . "%";
					// 		$sqlMoviesInWatchlist = "SELECT MovieId FROM Watchlist WHERE UserId=$uid";
					// 		$sqlRandomMovieLikeCategory = "SELECT Title, Poster FROM Movies WHERE Category LIKE :cat AND MID NOT IN ($sqlMoviesInWatchlist) ORDER BY random() LIMIT 1";
					// 		// Prepare Query
					// 		$stmtRandomMovieLikeCategory = $pdo->prepare($sqlRandomMovieLikeCategory);
					// 		//Bind variables
					// 		$stmtRandomMovieLikeCategory->bindParam(":cat", $category);
					// 		$stmtRandomMovieLikeCategory->execute();
					// 		$randomMovieLikeCategory = $stmtRandomMovieLikeCategory->fetch();
					// 		if ($randomMovieLikeCategory == false) {
					// 			// Movie with category doesn't exist or is already contained in user's watchlistmovie, next Query
					// 			continue;
					// 		}
					// 		$movieTitle = $randomMovieLikeCategory["Title"];
					// 		$moviePoster = $randomMovieLikeCategory["Poster"];
					// 		if (!in_array($movieTitle, $existingRecommendationTitles)) {
					// 			// echo $countRecommendation . " " . $movieTitle . " movie does not exist in reccs. ";
					// 			// A valid recommendation
					// 			$existingRecommendationTitles[$countRecommendation] = $movieTitle;
					// 			echo"<div class = watchlistmovie>
					// 						<img src = '$moviePoster'>
					// 						<br>
					// 						<center>
					// 								<h3 style = 'color:white;'>$movieTitle<h3>
					// 						</center>
					// 				</div>";
					// 			$countRecommendation++;
					// 			if ($countRecommendation > 4) { break; }
					// 	} else {
					// 		$movieErrorLog = $movieErrorLog . " " . $movieTitle . "[" . $countRecommendation . "]: already recommended<br>";
					// 	}
					// }
					// echo $movieErrorLog;

				?>
					</div>


				<center><h1 style="border-bottom:1px; border-bottom-style:solid;color:white;"><?php echo $user ?>'s Watchlist</h1></center>
				<br>
				<div class= watchlist style="height:80%; width:90%;margin-left:10%; margin-right:5%; ">
						<?php
						$acc = new Account();
						$userWatchlist = $acc->getUserWatchlist($_SESSION['uid']);
						foreach ($userWatchlist as $row) {
							//Storing Title and Poster information of movies
							$title = $row["Title"];
							$poster = $row["Poster"];
							$director = $row["Director"];
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
