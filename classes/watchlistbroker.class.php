<?php
include_once ('dbh.class.php');

class WatchlistBroker extends Dbh {

    public function addToWatchlist($uid, $mid) {
        $sql = "INSERT INTO Watchlist (UserId, MovieId, Watched) VALUES(:userid, :movieid, 0);";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':userid', $uid);
        $stmt->bindParam(':movieid', $mid);
        $stmt->execute();
    }
    public function movieExistsInUserWatchlist($uid, $mid) {
        $sql = "SELECT MovieId FROM Watchlist WHERE UserId=:userid AND MovieId=:movieid;";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':userid', $uid);
        $stmt->bindParam(':movieid', $mid);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}