<?php
session_start();

	if(isset($_POST["username"]) && isset($_POST["password"]))
	{
		$username = $_POST["username"];
		$password = $_POST["password"];
		
		$pdo = new PDO("sqlite:MMDataBase.db");
		$stmt = $pdo->query("SELECT * FROM Users WHERE username='$username' AND password='$password'");
		
		$row = $stmt ->fetch();
		if($row)
		{
			
			$_SESSION["username"] = $row["username"];
			$_SESSION["role"] = $row["role"];
			header("Location: movie.php");
			
		}
	}
?>


<html>

	<head>
		<title>Login</title>
		<link rel="stylesheet" href="stylesheet.css">
	</head>
	
	<body>
		<div class = header>
			<div class=MMatcher>
				Movie Matchers
			</div>
			
			<div class = links>
				
				<a>Search</a>
				<a>Swipe</a>
				<a>Profile</a>
				
			</div>
		</div>
		<div class= container>
			<div class=overlay>	
				<br>
				<form id = msform method="POST">
					<fieldset>
					<h2 class="fs-title">Login</h2>
					<input type="text" placeholder="username" name="username"></input>
					<input type="password" placeholder="password" name="password"></input>
					<input type="submit" value="Login" class = action-button></input><br>

					<a href="register.php">Register Now</a>
					</fieldset>
				</form>
			</div>
		</div>
</body>




</html>