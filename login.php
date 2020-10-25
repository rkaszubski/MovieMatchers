<?php
	function noSpecialChar($string)
	{
		if (preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $string)) {
		    // one or more of the 'special characters' found in $string
				return false;
		}
		return true;
	}

	if(isset($_POST["username"]) && isset($_POST["password"]))
	{
		$username = trim($_POST["username"]);
		$password = trim($_POST["password"]);
		if (noSpecialChar($username) && noSpecialChar($password)) {
			$pdo = new PDO("sqlite:MMDataBase.db");
			$findUserQuery = "SELECT UID FROM Users WHERE username=?";
			$stmt = $pdo->prepare($findUserQuery);
			$stmt->execute([$username]);
			// returns boolean "false" if user does not exist
			$uid = $stmt->fetchColumn();
			if ($uid != false) {
				$hashPW = md5($password);
				$findUserPasswordQuery = "SELECT Password FROM Passwords WHERE UserId=?";
				$stmt2 = $pdo->prepare($findUserPasswordQuery);
				$stmt2->execute([$uid]);
				$existingHashedPW = $stmt2->fetchColumn();
				if ($hashPW == $existingHashedPW) {
						session_start();
						$_SESSION["username"] = $username;
						$_SESSION["UID"] = $uid;
						// How are we getting the role? How should it be set for the session?
						// $_SESSION["role"] = $row["role"];
						header("Location: movie.php");
				}
			} else {
				echo "User account does not exist, please try again or register for an account";
			}

		}
	}
?>


<html>

		<title>Login</title>
		<link rel="stylesheet" href="css/stylesheet.css">
		<link rel="icon" href="assets/favicon/favicon.ico">
	</head>

	<body>
		<div class = header style="font-size:4vw;">
				<img src="assets/MMHeader2.png" style="width:60%">
		</div>
		<div class= container>
			<div class=overlay>
				<br>
				<form id = msform method="POST">
					<fieldset>
					<h2 class="fs-title">Login</h2>
					<input type="text" placeholder="username" name="username" required></input>
					<input type="password" placeholder="password" name="password" required></input>
					<input type="submit" value="Login" class = action-button></input><br>

					<a href="register.php">Register Now</a>
					</fieldset>
				</form>
			</div>
		</div>
		<?php include('components/footer.php'); ?>
</body>




</html>
