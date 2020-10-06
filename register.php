<?php
	if(isset($_POST["username"]) && isset($_POST["password"]))
	{
		$username = $_POST["username"];
		$password = $_POST["password"];
		$email = $_POST["email"];
		
		$pdo = new PDO("sqlite:MMDataBase.db");
		$pdo->query("INSERT INTO Users VALUES('$username','$email','$password','user')");
		
		echo "Account successfully created";
	}
?>


<html>
	<head>
		<title>Register</title>
	</head>
	
	<body>
		<ul>
			<li><a href="register.php">Register Now</a></li>
			<li><a href="login.php">Log In</a></li>
		</ul>
		<br>
		<form method="POST">
			<input type="text" placeholder="username" name="username"></input>
			<input type="email" placeholder="email" name="email"></input>
			<input type="password" placeholder="password" name="password"></input>
			<input type="submit" value="Create Account"></input>
		</form>
	</body>




</html>