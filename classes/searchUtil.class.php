<?php
include_once ('dbh.class.php');

class SearchUtil extends Dbh {

    public function doesMovieExist($movieTitle) {
        
        
        $sql = "SELECT * FROM Movies WHERE Title=:title";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':title', $movieTitle);
        $stmt->execute();
        $movieExists = $stmt->fetch();
        return $movieExists;
    }

    public function getOmdbRecord($movieName) {
        //replace spaces " " with plus sign "+"
        $ApiKey = '2f79417c';
        $movieName = str_replace(' ','+',$movieName);
        //Stitch url that gets JSON file
        $path = "http://www.omdbapi.com/?apikey=$ApiKey&t=$movieName";
        //gets contents of json file
        $json = file_get_contents($path);
        return json_decode($json, TRUE);
    }

    public function insertMovie($movie) {
        // Insert movie information into Movie Table
        // var_dump($movie);
        $title = $movie["Title"];
        $director = $movie["Director"];
        $actors = $movie["Actors"];
        $year = intval($movie["Year"]); //convert to int to add to database
        $imdbRating =floatval($movie["imdbRating"]); //convert to float (cannot do int because it rounds up/down)
        $category = $movie["Genre"];
        $poster = $movie["Poster"];
        $rated = $movie["Rated"];
        $plot = $movie["Plot"];

        // Insert movie information into Movie Table
        $sqlInsertNewMovieIntoMovies = "INSERT INTO Movies (Title, Director, Actors, ReleaseYear, Poster, IMDB_score, Rated, Category, Plot) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmtInsertNewMovieIntoMovies = $this->connect()->prepare($sqlInsertNewMovieIntoMovies);
        $stmtInsertNewMovieIntoMovies->execute([$title, $director, $actors, $year, $poster, $imdbRating, $rated, $category, $plot]);
        // $stmt->debugDumpParams();
        // $stmt->commit();
    }
}