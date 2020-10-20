<?php
	session_start();

?>
<html>
	<head>
		<title>My Profile</title>
		<link rel="stylesheet" href="css/stylesheet.css">
	</head>
	<body>
		<?php include('components/header.php'); ?>
		<div class= container>

				<table class="centerTB" style="width:70%">
					<caption><h1><?php echo $_SESSION["username"]?>'s</h1><h2> My Watchlist </h2></caption>
				  <tr>
				    <th>Movie Title</th>
				    <th>Director</th
				  </tr>
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
						echo "<tr>";
						$mid = $row["MovieId"];
						$sqlMovieTitle = "SELECT Title, Director FROM Movies WHERE MID=?";
						$stmt2 = $pdo->prepare($sqlMovieTitle);
						$stmt2->execute([$mid]);
						$data = $stmt2->fetch();
						$title = $data["Title"];
						$director = $data["Director"];
						echo "<td> $title </td>";
						echo "<td> $director </td>";
						echo "</tr>";
					}
					?>
				</table>
		</div>
		<?php include('components/footer.php'); ?>
	</body>


</html>
