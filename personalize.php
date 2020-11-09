<?php

$categories = array();
if(isset($_POST["submit"])){
if(!empty($_POST["category"])){
  foreach($_POST['category'] as $category) {
        array_push($categories,$category);
    }
    var_dump($categories);
}
}
?>

<html>
<head>
  <title>Movie Matcher</title>
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
      <div class = categories>
        <h1> Select Your Favourite Categries</h1>
        <br>
        <form method="post">
          <ul  class="categoryList">
            <li><input name ="category[]" type="checkbox" value = "Action"> <label> Action </label></li>
            <li><input name ="category[]" type="checkbox" value = "Adventure"> <label> Adventure </label></li>
            <li><input name ="category[]" type="checkbox" value = "Musical"> <label>Musical </label></li>
            <li><input name ="category[]" type="checkbox" value = "Animation"> <label>Animation </label></li>
            <li><input name ="category[]" type="checkbox" value = "Romance"> <label>Romance </label></li>
            <li><input name ="category[]" type="checkbox" value = "Sci-Fi"> <label>Sci-Fi </label></li>
            <li><input name ="category[]" type="checkbox" value = "Horror"> <label>Horror </label></li>
            <li><input name ="category[]" type="checkbox" value = "Thriller"> <label>Thriller </label></li>
            <li><input name ="category[]" type="checkbox" value = "Fantasy"> <label>Fantasy </label></li>
            <li><input name ="category[]" type="checkbox" value = "War"> <label>War </label></li>
            <li><input name ="category[]" type="checkbox" value = "Cartoon"> <label>Cartoon </label></li>
            <li><input name ="category[]" type="checkbox" value = "Biography"> <label>Biography </label></li>
            <li><input name ="category[]" type="checkbox" value = "Crime"> <label>Crime </label></li>
            <li><input name ="category[]" type="checkbox" value = "Mystery"> <label>Mystery </label></li>
            <li><input name ="category[]" type="checkbox" value = "Drama"> <label>Drama </label></li>
            <li><input name ="category[]" type="checkbox" value = "Sports"> <label>Sports </label></li>
            <li><input name ="category[]" type="checkbox" value = "Family"> <label>Children & Family </label></li>
            <li><input name ="category[]" type="checkbox" value = "Classic"> <label>Classic </label></li>
            <li><input name ="category[]" type="checkbox" value = "LGBTQ"> <label>LGBTQ </label></li>
          </ul>
            <input  class="categorySubmit" type="submit" name ="submit" value = "Done">
        </form>
      </div>
    </div>
  </div>

<?php include('components/footer.php'); ?>
</body>

<html>
