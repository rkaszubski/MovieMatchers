<?php
$usernameErr = $passwordErr = $emailErr = $globalErr = "";
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
		$attUsername = trim($_POST["username"]);
		$attPassword = trim($_POST["password"]);
		$attEmail = trim($_POST["email"]);
		// assert inputs are not empty
		if (empty($attUsername) ||
				empty($attPassword) ||
				empty($attEmail))
		{
			// fields were empty after trimming
			$globalErr = "error, all fields must be filled";
		} else if (!noSpecialChar($attUsername) || strlen($attUsername) < 3) {
			// Username has special Characters
			$usernameErr = "Username must be 4 characters or longer and special Characters are not allowed, please change your username and try again";
		} else if (!noSpecialChar($attEmail) || strlen($attEmail) < 3) {
			// email has special characters
			$emailErr = "Special Characters are not allowed, please use a valid email and try again";
		} else if (!noSpecialChar($attPassword) || strlen($attPassword) < 3) {
			// Password has special Characters
			$passwordErr = "Password must be 5 characters or longer and special characters are not allowed, please change your password and try again";
		} else {
			// ALL INPUT IS SAFE (no risk of sql injection/xss attacks)
			$username = $attUsername;
			$email = $attEmail;
			$password = md5($attPassword);

			$pdo = new PDO("sqlite:MMDataBase.db");
			$sqlUEPrep = $pdo->prepare("SELECT UID FROM Users WHERE username=?");
			$sqlUEPrep->execute([$username]);
			$sqlUsernameExists = $sqlUEPrep->fetchColumn();
			if ($sqlUsernameExists == false) {
				$newUserAccountInsertSqlStmt = "INSERT INTO Users (username, email, role) VALUES(?, ?, 'user')";
				$pdo->prepare($newUserAccountInsertSqlStmt)->execute([$username, $email]);

				$stmt = $pdo->prepare("SELECT UID FROM Users WHERE username=?");
				$stmt->execute([$username]);
				$uid = $stmt->fetchColumn();

				$newUserPasswordInsertSqlStmt = "INSERT INTO Passwords (Password, UserId) VALUES(?, ?)";
				$pdo->prepare($newUserPasswordInsertSqlStmt)->execute([$password, $uid]);

				echo "Account successfully created";
				header("Location: login.php");
			} else {
				$usernameErr = "Username already exists";
			}
		}
	}
?>


<html>

	<head>
		<title>Register</title>
		<link rel="stylesheet" href="css/stylesheet.css">
		<link rel="stylesheet" href="css/register.css">
		<link rel="icon" href="assets/favicon/favicon.ico">
	</head>

	<body>
		<body>
		<div class="header" style="font-size:4vw;">
			<img src="assets/MMHeader2.png" style="width:60%">
		</div>
		<div class="container">
			<div class="overlay">
				<br>
				<form id="msform" method="POST">
					<fieldset>
					<h2 class="fs-title">Create your account</h2>
					<input type="text" placeholder="username" name="username"></input><br>
					<span class="help-block"><?php echo $usernameErr; ?></span><br>

					<input type="email" placeholder="email" name="email"></input><br>
					<span class="help-block"><?php echo $emailErr; ?></span><br>

					<input type="password" placeholder="password" name="password"></input><br>
					<span class="help-block"><?php echo $passwordErr; ?></span><br>
					<span class="help-block"><?php echo $globalErr; ?></span>
					<input type="submit" value="Create Account" class="action-button"></input><br>
					<a href="index.php">Log In</a>
					</fieldset>
				</form>
			</div>
		</div>
		<?php include('components/footer.php'); ?>
	</body>
</html>
