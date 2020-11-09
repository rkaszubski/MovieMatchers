<?php
include_once ('classes/account.class.php');
$usernameErr = $passwordErr = $emailErr = $globalErr = "";
$attUsername = $attPassword = $attEmail = "";
$username = $password = $email = "";

if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])) {
	$username = $_POST["username"];
	$password = $_POST["password"];
	$email = $_POST["email"];
	$account = new Account();
	$res = $account->registerUser($username, $email, $password);
	if ($res == 'success') {
		echo 'check database, this may have worked';
	}
	else {
		echo "rubbah: " . $res;
	}
	//header("Location: login.php");
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
					<a href="login.php">Log In</a>
					</fieldset>
				</form>
			</div>
		</div>
		<?php include('components/footer.php'); ?>
	</body>
</html>
