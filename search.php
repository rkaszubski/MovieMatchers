<html>
	<head>
		<title>Search</title>
		<link rel="stylesheet" href="css/stylesheet.css">
	</head>
	<body>
		<?php include('components/header.php'); ?>

		<div class= container>
				<center><h1>Search</h1>

				<form method="post" action="searchbar.php">
					<input type="text" placeholder="Search..." name ="search" style="width: 30%; height:3%;">
					<input type="submit" Value="Go" name="submit" style="width:3%; height:4%;">
				</form>

				</center>
		</div>
		<?php include('components/footer.php'); ?>
	</body>


</html>
