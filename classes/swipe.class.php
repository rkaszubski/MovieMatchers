<?php
include_once ('dbh.class.php');
include_once ('movie.class.php');



class Swipe extends Dbh {
    public int $swipes;
    public $movies; 
    //sqlRandomMoviesLikeCategory = "SELECT * FROM Movies WHERE Category LIKE ? AND MID NOT IN ($sqlWatchedMovies) ORDER BY random()";
    public function initializeMovies($uid) {
        $sqlWatchedMovies = "SELECT MovieId FROM Watchlist WHERE UserId=$uid";
        $sql = "SELECT * FROM Movies WHERE MID NOT IN ($sqlWatchedMovies)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $this->movies = $stmt->fetchAll();
        if ($this->movies == false) {
            return false;
        }
    }

    public function getMovies() {
        return $this->movies;
    }

    public function addToWatchlist(int $mid, int $uid) { 
        if (!is_int($mid)  || !is_int($uid)) {
            return "false";
        }
        $sql = "INSERT INTO Watchlist (UserId, MovieId, Watched) VALUES(:userId, :movieId, 0)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':userId', $uid);
        $stmt->bindParam(':movieId', $mid);
        $stmt->execute();
    }
}

