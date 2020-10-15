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
		<link rel="stylesheet" href="stylesheet.css">
	</head>
	
	<body>
		<body>
		<div class = header style="font-size:4vw;">
				
				<center>Movie Matchers</center>
		</div>
		<div class= container>
			<div class=overlay>
				<br>
				<form id = msform method="POST">
					<fieldset>
					<h2 class="fs-title">Create your account</h2>
					<input type="text" placeholder="username" name="username"></input><br><br>
					<input type="email" placeholder="email" name="email"></input><br><br>
					<input type="password" placeholder="password" name="password"></input><br><br>
					<input type="submit" value="Create Account" class = action-button></input><br>

					<a href="index.php">Log In</a>
					</fieldset>
				</form>


			</div>
		</div>
	</body>




</html>
