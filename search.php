<html>
	<head>
		<title>Search</title>
		<link rel="stylesheet" href="css/stylesheet.css">
		<link rel="icon" href="assets/favicon/favicon.ico">
	</head>
	<body>
		<?php include('components/header.php'); ?>

		<div class= container>
			<div class=overlay>
				<center><h1 style="color:white;">Search</h1>
				<form method="post" action="searchbar.php">
					<input type="text" placeholder="Enter a movie title..." name ="search" style="width: 30%; height:3%; padding:1%;" required>
					<input type="submit" Value="Go" name="submit" style="width:3%; height:4%;">
				</form>
				</center>
		</div>
		</div>
		<?php include('components/footer.php'); ?>
	</body>


</html>
