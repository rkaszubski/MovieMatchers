<?php
	session_start();


?>
<html>
	<header>
		<title>My Profile</title>
		<link rel="stylesheet" href="css/stylesheet.css">
		<style>
			table, th, td {
			  border: 1px solid black;
			  border-collapse: collapse;
			}
			th, td {
			  padding: 5px;
			  text-align: left;
			}
			table.center {
				margin-left: auto;
				margin-right: auto;
			}
		</style>
	</header>
	<body>
		<?php include('components/header.php'); ?>
		<h1><strong><?php echo $_SESSION["username"]?></strong></h1>
		<table class="center" style="width:70%">
			<caption><h2> My Watchlist </h2></caption>
		  <tr>
		    <th><strong>Title</strong></th>
		    <th><strong>Director</strong></th>
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
				echo "<th> $title </th>";
				echo "<th> $director </th>";
				echo "</tr>";
			}
			?>
		</table>
		<?php include('components/footer.php'); ?>
	</body>


</html>
