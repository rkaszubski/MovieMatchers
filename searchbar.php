<?php 
//Get information user inputs into search bar
if(isset($_POST['search']) ) {
	$input_term = $_POST['search']; 
	$data = getImdbRecord("$input_term", "2f79417c");
	echo print_r($data);
}

function getImdbRecord($movieName, $ApiKey)
{
	//replace spaces " " with plus sign "+"
	$movieName = str_replace(' ','+',$movieName);
	
	//Stitch url that gets JSON file
    $path = "http://www.omdbapi.com/?apikey=$ApiKey&t=$movieName";
	
    $json = file_get_contents($path);
    return json_decode($json, TRUE);
}

?>	