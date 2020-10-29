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
		<meta charset="utf-8">
    		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
		<title>My Profile</title>
		<link rel="stylesheet" href="css/stylesheet.css">
		<link rel="icon" href="assets/favicon/favicon.ico">
	</head>
	<body>
		<?php include('components/header.php'); ?>
		<div class= container>
			<div class = overlay style=" overflow:scroll; overflow-x:hidden; padding-right:10%;">
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
							  echo"<div class = movie style='width:300px; height:500px; margin-top:1%; float:left; margin-left: 3%; border:1px;'>
										<img src = '$poster' style='width:100%;height:90%; padding:2px; background-color:white;'>
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
