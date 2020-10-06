<?php
	if(isset($_POST["username"]) && isset($_POST["password"]))
	{
		$username = $_POST["username"];
		$password = $_POST["password"];
		
		$pdo = new PDO("sqlite:MMDataBase.db");
		$stmt = $pdo->query("SELECT * FROM Users WHERE username='$username' AND password='$password'");
		
		$row = $stmt ->fetch();
		if($row)
		{
			session_start();
			$_SESSION["username"] = $row["username"];
			$_SESSION["role"] = $row["role"];
			header("Location: movie.php");
			
		}
	}
?>


<html>
	<head>
		<title>Login</title>
	</head>
	
	<body>
		<ul>
			<li><a href="register.php">Register Now</a></li>
			<li><a href="login.php">Log In</a></li>
		</ul>
		<br>
		<form method="POST">
			<input type="text" placeholder="username" name="username"></input>
			<input type="password" placeholder="password" name="password"></input>
			<input type="submit" value="Login"></input>
		</form>
	</body>




</html>