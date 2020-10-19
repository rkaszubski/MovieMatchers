<?php
$usernameErr = $passwordErr = $emailErr = "";
$attUsername = $attPassword = $attEmail = "";
$username = $password = $email = "";

function noSpecialChar($string) {
	if (preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $string)) {
	    // one or more of the 'special characters' found in $string
			return false;
	}
	return true;
	}

	if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"]))
	{
		$attUsername = $_POST["username"];
		$attPassword = $_POST["password"];
		$attEmail = $_POST["email"];
		// assert inputs are not empty
		if (!empty($attUsername) &&
				!empty($attPassword) &&
				!empty($attEmail))
		{
			//Username check
			if (noSpecialChar($attUsername) && strlen($attUsername) > 3) {
				// good input
				$username = trim($attUsername);
			} else {
				$usernameErr = "Username must be 4 characters or longer and special Characters are not allowed, please change your username and try again";
			}
			//Password check
			if (noSpecialChar($attPassword) && strlen($attPassword) > 7) {
				// good input
				$password = md5(trim($attPassword));
			} else {
				$passwordErr = "Password must be 8 characters or longer and special characters are not allowed, please change your password and try again";
			}
			//Email check
			if (noSpecialChar($attEmail)) {
				// good input
				$email = trim($attEmail);
			} else {
				$emailErr = "Special Characters are not allowed, please use a valid email and try again";
			}
			$pdo = new PDO("sqlite:MMDataBase.db");
			$newUserAccountInsertSqlStmt = "INSERT INTO Users (username, email, role) VALUES(?, ?, 'user')";
			$pdo->prepare($newUserAccountInsertSqlStmt)->execute([$username, $email]);

			$stmt = $pdo->prepare("SELECT UID FROM Users WHERE username=?");
			$stmt->execute([$username]);
			$uid = $stmt->fetchColumn();

			$newUserPasswordInsertSqlStmt = "INSERT INTO Passwords (Password, UserId) VALUES(?, ?)";
			$pdo->prepare($newUserPasswordInsertSqlStmt)->execute([$password, $uid]);
			
			echo "Account successfully created";



			// $pdo = null;
			// $pdo2 = new PDO("sqlite:MMDataBase.db");
			// $exists = $pdo2->prepare('SELECT UID FROM Users WHERE username=?');
      // $exists->execute([$username]);
      // $userId = $exists->fetchColumn();
			// $pdo2 = null;
			// $pdo3 = new PDO("sqlite:MMDataBase.db");
			// $pdo3->query("INSERT INTO Passwords (UserId, Password) VALUES('$userId', '$password')");
			// $pdo3 = null;
		}
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
					<input type="text" placeholder="username" name="username"></input><br><?php echo $usernameErr ?><br>
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
