<?php


class Movie {
  private $mid;
  private $title;
  private $director;
  private $actors;
  private $releaseYear;
  private $poster;
  private $imdb_score;
  private $rated;
  private $category;
  private $plot;

  public function __construct($mid, 
                            $title, 
                            $director, 
                            $actors, 
                            $releaseYear,
                            $poster, 
                            $imdb_score, 
                            $rated, 
                            $category, 
                            $plot) 
  {
    $this->mid = intval($mid);
    $this->title = $title;
    $this->director = $director;
    $this->actors = $actors;
    $this->releaseYear = intval($releaseYear);
    $this->poster = $poster;
    $this->imdb_score = $imdb_score;
    $this->rated = $rated;
    $this->category = $category;
    $this->plot = $plot;
  }
  public function getMID() {
    return $this->mid;
  }
  public function getTitle() {
    return $this->title;
  }
  public function getDirector() {
    return $this->director;
  }
  public function getActors() {
    return $this->actors;
  }
  public function getReleaseYear() {
    return $this->releaseYear;
  }
  public function getPoster() {
    return $this->poster;
  }
  public function getIMDBScore() {
    return $this->imdb_score;
  }
  public function getRated() {
    return $this->rated;
  }
  public function getCategories() {
    return $this->category;
  }
  public function getPlot() {
    return $this->plot;
  }
}