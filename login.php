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
		<link rel="stylesheet" href="css/stylesheet.css">
		<link rel="stylesheet" href="css/register.css">
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
					<input type="password" placeholder="password" name="password" required></input><br>
					<span class="help-block"><?php echo $logonError; ?></span>
					<input type="submit" value="Login" class = action-button></input><br>

					<a href="register.php">Register Now</a>
					</fieldset>
				</form>
			</div>
		</div>
		<?php include('components/footer.php'); ?>
	</body>
</html>
