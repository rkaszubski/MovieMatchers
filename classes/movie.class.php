<?php

class Movie {
  private int $mid;
  private string $title;
  private string $director;
  private string $actors;
  private string $releaseYear;
  private string $poster;
  private floatval $imdb_score;
  private string $rated;
  private $category;
  private $plot;

  public function __construct($mid, $title, $director, $actors, $releaseYear,
                            $poster, $imdb_score, $rated, $category, $plot) {
    $this->mid = $mid;
    $this->title = $title;
    $this->director = $director;
    $this->actors = $actors;
    $this->releaseYear = $releaseYear;
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
  public function getPlot() {
    return $this->plot;
  }
}
