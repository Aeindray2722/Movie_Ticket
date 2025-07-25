<?php

class TrailerModel
{
    // Access Modifier = public, private, protected
    private $id;
    private $movie_id;
    private $trailer_vd;
    private $created_at;
    private $updated_at;

     public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setMovieId($movie_id)
    {
        $this->movie_id = $movie_id;
    }
    public function getMovieId()
    {
        return $this->movie_id;
    }
    public function setTrailerVd($trailer_vd)
    {
        $this->trailer_vd = $trailer_vd;
    }
    public function getTrailerVd()
    {
        return $this->trailer_vd;
    }
   
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }

   
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function toArray() {
        return [
            "id" => $this->getId(),
            "movie_id" => $this->getMovieId(),
            "trailer_vd" => $this->getTrailerVd(),
            "created_at" => $this->getCreatedAt(),
            "updated_at" => $this->getUpdatedAt()
        ];
    }
}