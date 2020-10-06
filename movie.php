<?php
	$pdo = new PDO("sqlite:MMDataBase.db");
	$stmt = $pdo->query("SELECT * FROM Movie");
	
	$all = $stmt->fetchall();
	$it = 0;
	$row = $all[$it];
	
	if($_POST['Pass'])
	{
		nextmovie();
	}
	
	function nextmovie()
	{
		$it = $it+0;
	}
	
?>
<html>
	<head>
		<title>Swipe</title>
	</head>
	<body>
		<h1><?php echo $row["Title"] ?></h1>
		<img src="<?php echo $row["PosterLink"] ?>" width="400px">
		<h2>Director: <?php echo $row["Director"] ?></h2>
		<h2>Release Year: <?php echo $row["Year"] ?></h2>
		<h2>Actors: <?php echo $row["Actors"] ?></h2>
	</body>
	<form method="post">
    <input type="submit" name="Pass" value="Pass" />
	</form>
</html>