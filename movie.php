<?php
include_once ('classes/UserView.class.php');
include_once ('classes/User.class.php');
$userSession = new UserView();
$userSession->session();
echo "UserID: " . $_SESSION['uid'] . "<br>Username: " . $_SESSION['username'] . "<br>Email: " . $_SESSION['email'] . "<br>Role: " . $_SESSION['role'] . "<br>Init: " . $_SESSION['init'];
?>
<html>
	<head>
		<SCRIPT SRC="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></SCRIPT>
		<title>Swipe</title>
		<link rel="stylesheet" href="css/stylesheet.css">
    <link rel="icon" href="assets/favicon/favicon.ico">
	</head>
	<body>
		<?php include('components/header.php'); ?>
		<div class= container>
			<div class=overlay>
        <br><br>
				<div class="movieinfo">
					<h1 id="title">Movie Title</h1>
				</div>
				<div class="swipe">
					<button class="button" id="pass" onclick="pass()">Pass</button>
				</div>
				<div class="movieposter">
					<img id="poster" src="assets/popcorn.jpg" >
				</div>
				<div class="swipe">
					<button class="button" id="watch" onclick="watchmovie()">Watch</button>
				</div>
				<div class="movieinfo">
					<h2 id="dir">Director</h2>
					<h2 id="year">Release Year</h2>
					<h2 id="act">Actors</h2>
          <h2 id="cat">Categories</h2>
				</div>
			</div>
		</div>
		<?php include('components/footer.php'); ?>
	</body>
</html>
