<html>
	<head>
		<title>Contact Us</title>
		<link rel="stylesheet" href="stylesheet.css">
	</head>

	<body>
		<div class="header">
		<?php include('components/header.php'); ?>
		</div>

		<div class= container>
			<div class=overlay>
				<br><br>
				<form id = msform method="POST" style="width: 700px;">
					<fieldset>
					<h2 class="fs-title">Contact Us</h2>
					<input type="text" placeholder="Full Name" name="fullname"></input>
					<input type="email" placeholder="Email" name="email"></input>
					<textarea id="subject" name="subject" placeholder="Write something.." style="height:200px"></textarea>
					<input type="submit" value="Submit" class = action-button></input><br>

					<a href="register.php">Register Now</a>
					</fieldset>
				</form>
			</div>
		</div>

		<div id="footer">
		<?php include('components/footer.php'); ?>
		</div>
</body>

</html>
