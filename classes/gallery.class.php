<?php


class Gallery {
  private $movies;
  private $mIndex;

  public function __construct($movies) {
    if (is_array($movies)) {
        $this->movies = $movies;
        $this->mIndex = 0;
    }
  }

  public function getMovies() {
    if ($this->movies == null) {
        return 'error, not initialized';
    } else {
        return $this->movies;
    }
  }

  public function getQueuedMovie() {
      if ($this->mIndex >= count($this->movies)) {
          $this->mIndex = 0;
      }
      $index = $this->mIndex;
      $movie = $this->movies[$index];
      $this->mIndex += 1;
      return $movie;
  }

  public function getIndex() {
      return $this->mIndex;
  }
}
