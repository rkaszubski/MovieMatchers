<?php
include_once ('dbh.class.php');
include_once ('movie.class.php');

class MovieController extends Dbh {

    public function getMovieByTitle(string $title) {

        $sql = "SELECT * FROM Movies WHERE TITLE=:title";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->execute();
        $movie = $stmt->fetch();

        return $movie;
    }
    public function getAllMovies() {
        $sql = "SELECT * FROM Movies";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $movies = $stmt->fetchAll();

        return $movies;
    }
}
