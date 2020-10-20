<?php
	session_start();


?>
<html>
	<header>
		<title>My Profile</title>
	</header>
	<body>
		<h1><?php echo $_SESSION["username"]?></h1>
		<h2> My Watchlist </h2>
		<?php
		$user = $_SESSION["username"];
		$uid = $_SESSION["UID"];
		$pdo = new PDO("sqlite:MMDataBase.db");
		$sqlUserWatchlistMID = "SELECT MovieId FROM Watchlist WHERE UserId=?";
		$stmt = $pdo->prepare($sqlUserWatchlistMID);
		$stmt->execute([$uid]);
		$userWatchlistMID = $stmt->fetchAll();

		// iterate through all movieId's (MID) and find the according movie titles (or entire object... later) per each MID
		foreach($userWatchlistMID as $row)
		{
			$mid = $row["MovieId"];
			$sqlMovieTitle = "SELECT Title FROM Movies WHERE MID=?";
			$stmt2 = $pdo->prepare($sqlMovieTitle);
			$stmt2->execute([$mid]);
			$title = $stmt2->fetchColumn();
			echo "$title<br>";
		}
		?>
	</body>


</html>
