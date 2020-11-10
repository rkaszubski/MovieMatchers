<?php
include_once ('dbh.class.php');

class Swipe extends Dbh {

    //sqlRandomMoviesLikeCategory = "SELECT * FROM Movies WHERE Category LIKE ? AND MID NOT IN ($sqlWatchedMovies) ORDER BY random()";
    public function initializeMovies($uid) {
        // $sqlWatchedMovies = "SELECT MovieId FROM Watchlist WHERE UserId=$uid";
        // $stmtWatchedMovies = $this->connect()->prepare($sqlWatchedMovies);
        // $stmtWatchedMovies->execute();
        // $watchlistMids = $stmtWatchedMovies->fetchAll();
        // if ($watchlistMids == false) {
            $sqlHasMids = "SELECT * FROM Movies";
            $stmtHasMids = $this->connect()->prepare($sqlHasMids);
            $stmtHasMids->execute();
            return $stmtHasMids->fetchAll();
        // } else {
        //     $sql = "SELECT * FROM Movies WHERE MID NOT IN ($sqlWatchedMovies)";
        //     $stmt = $this->connect()->prepare($sql);
        //     $stmt->execute();
        //     return $stmt->fetchAll();
        // }
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

