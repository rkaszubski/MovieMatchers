<?php
	session_start();
?>

<html>
	<head>
		<title>Movie Matcher</title>
	</head>
	
	<body>
		<ul>
			<li><a href="register.php">Register Now</a></li>
			<li><a href="login.php">Log In</a></li>
		</ul>
		<?php
			if(isset($_SESSION["username"]))
			{
				echo "Hello ".$_SESSION["username"];
			}
			else
			{
				echo "Please Login";
			}
		?>
	</body>




</html>