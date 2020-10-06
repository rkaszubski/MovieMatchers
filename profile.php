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
	
		$pdo = new PDO("sqlite:MMDataBase.db");
		$data = $pdo->query("SELECT movietitle FROM Watchlist WHERE username='$user'")->fetchAll();
		foreach($data as $row)
		{
			$title = $row["movietitle"];
			echo "$title<br>";
		}
		?>
	</body>
	

</html>
