<?php
include_once ('dbh.class.php');

class SearchUtil extends Dbh {

    public function doesMovieExist($movieTitle) {
        
        
        $sql = "SELECT * FROM Movies WHERE Title=:title";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':title', $movieTitle);
        $stmt->execute();
        $movieExists = $stmt->fetch();
        if ($movieExists != false) {
            return $movieExists;
        } else {
            return $movieExists;
        }
    }
}