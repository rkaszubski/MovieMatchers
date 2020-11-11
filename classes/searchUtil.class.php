<?php
include_once ('dbh.class.php');
include_once ('movie.class.php');

class SearchUtil extends Dbh {

    public function doesMovieExist($movieTitle) {
        
        
        $sql = "SELECT * FROM Movies WHERE Title=:title";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':title', $movieTitle);
        $stmt->execute();
        $movieExists = $stmt->fetch();
        if ($movieExists != false) {
            $movieData = new Movie(0, $movieExists["Title"], 
                                    $movieExists["Director"], 
                                    $movieExists["Actors"], 
                                    $movieExists["ReleaseYear"],  
                                    $movieExists["Poster"], 
                                    $movieExists["IMDB_score"], 
                                    $movieExists["Rated"],
                                    $movieExists["Category"], 
                                    $movieExists["Plot"]);
            return $movieData;
        }
        return $movieExists;
    }

    public function getOmdbRecord($movieName) {
        // movie will be returned as false if not found in ombd
        $movie = false; 
        //replace spaces " " with plus sign "+"
        $ApiKey = '2f79417c';
        $movieName = str_replace(' ','+',$movieName);
        //Stitch url that gets JSON file
        $path = "http://www.omdbapi.com/?apikey=$ApiKey&t=$movieName";
        if (is_null($path)) {
            return 'Path is null, omdb failed';
        }
        //gets contents of json file
        $json = file_get_contents($path);
        $omdbMovie = json_decode($json, TRUE);
        $movie = new Movie(0, $omdbMovie["Title"], 
                            $omdbMovie["Director"],
                            $omdbMovie["Actors"],
                            $omdbMovie["Year"],
                            $omdbMovie["Poster"],
                            $omdbMovie["imdbRating"],
                            $omdbMovie["Rated"],
                            $omdbMovie["Genre"],
                            $omdbMovie["Plot"]);
        return $movie;
    }

    public function insertOMDBMovie($movie) {
        // Insert movie information into Movie Table
        $title      = $movie->getTitle();
        $director   = $movie->getDirector();
        $actors     = $movie->getActors();
        $year       = $movie->getReleaseYear();
        $poster     = $movie->getPoster();
        $imdbRating = $movie->getIMDBScore();
        $rated      = $movie->getRated();
        $category   = $movie->getCategories();
        $plot       = $movie->getPlot();

        // if any input is null, return error
        if (is_null($title)             || 
                is_null($director)      || 
                is_null($actors)        ||
                is_null($year)          ||
                is_null($poster)        ||
                is_null($imdbRating)    ||
                is_null($rated)         ||
                is_null($category)      ||
                is_null($plot))
        {
            return "Error, input has null";
        } else {
            // Insert movie information into Movie Table
            $sql = "INSERT INTO Movies (Title, "    .
                                    "Director, "    .
                                    "Actors, "      . 
                                    "ReleaseYear, " .
                                    "Poster, "      . 
                                    "IMDB_score, "  . 
                                    "Rated, "       . 
                                    "Category, "    . 
                                    "Plot) "        . 
            "VALUES (:title, :director, :actors, :releaseYear, " . 
                ":poster, :imdbScore, :rated, :category, :plot);";
            
            //testing
            // echo "<br><br>inside insertOMDBMovie(), Dump sql statement:<br>";
            // var_dump($sql);
            // echo "<br>" .
            // "<br>Title: "       . $title        .
            // "<br>Director: "    . $director     .
            // "<br>Actors: "      . $actors       .
            // "<br>ReleaseYear: " . $year         .
            // "<br>Poster: "      . $poster       .
            // "<br>IMDB_score: "  . $imdbRating   .
            // "<br>Rated: "       . $rated        .
            // "<br>Category: "    . $category     .
            // "<br>Plot: "        . $plot;

            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':director', $director);
            $stmt->bindParam(':actors', $actors);
            $stmt->bindParam(':releaseYear', $year, PDO::PARAM_INT);
            $stmt->bindParam(':poster', $poster);
            $stmt->bindParam(':imdbScore', $imdbRating);
            $stmt->bindParam(':rated', $rated);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':plot', $plot);
            $stmt->execute();

            // testing //
            // echo "<br><br>";
            // $stmt->debugDumpParams();
        }
    }
}