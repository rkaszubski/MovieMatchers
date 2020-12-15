<?php
	include_once ('classes/account.class.php');
	$logonError = "";
	if(isset($_POST["username"]) && isset($_POST["password"])) {
		$username = $_POST["username"];
		$password = $_POST["password"];
		$loginFunc = new Account();
		$result = $loginFunc->loginUser($username, $password);
		if ($result == 'success') {
			header("Location: movie.php");
		} else {
			$logonError = $result;
		}
	}
?>
<html>
		<title>Login</title>
		<link rel="stylesheet" href="css/reset.css">
		<link rel="stylesheet" href="css/login.css">
		<link rel="icon" href="assets/favicon/favicon.ico">
	</head>
	<body>
		<div class="header">
				<img src="assets/MMHeader2.png">
		</div>
		<div class="login-card">
			<div class="login-card-contents">
				<form class="login-card-form" method="POST">
					<h2 class="login-card-form-title">Login</h2>
					<input type="text" class="login-card-form-input" placeholder="username" name="username" required></input>
					<input type="password" class="login-card-form-input" placeholder="password" name="password" required></input><br>
					<span class="help-block"><?php echo $logonError; ?></span>
					<input type="submit" value="Login" class="login-card-form-submit"></input><br>
					<a href="register.php">Register Now</a>
				</form>
			</div>
		</div>
	</body>
</html>
