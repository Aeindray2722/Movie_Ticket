<?php

class MovieModel
{
    // Access Modifier = public, private, protected
    private $id;
    private $movie_name;
    private $actor_name;
    private $genre;
    private $description;
    private $movie_img;
    private $type_id;
    private $show_time_id;
    private $start_date;
    private $end_date;
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

    public function setMovieName($movie_name)
    {
        $this->movie_name = $movie_name;
    }
    public function getMovieName()
    {
        return $this->movie_name;
    }
    public function setActorName($actor_name)
    {
        $this->actor_name = $actor_name;
    }
    public function getActorName()
    {
        return $this->actor_name;
    }
    public function setGenre($genre)
    {
        $this->genre = $genre;
    }
    public function getGenre()
    {
        return $this->genre;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setMovieImg($movie_img)
    {
        $this->movie_img = $movie_img;
    }
    public function getMovieImg()
    {
        return $this->movie_img;
    }
    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;
    }
    public function getTypeId()
    {
        return $this->type_id;
    }
    public function setShowTimeId($show_time_id)
    {
        $this->show_time_id = $show_time_id;
    }
    public function getShowTimeId()
    {
        return $this->show_time_id;
    }
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
    }
    public function getStartDate()
    {
        return $this->start_date;
    }
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
    }
    public function getEndDate()
    {
        return $this->end_date;
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
            "movie_name" => $this->getMovieName(),
            "actor_name" => $this->getActorName(),
            "genre" => $this->getGenre(),
            "description" => $this->getDescription(),
            "movie_img" => $this->getMovieImg(),
            "type_id" => $this->getTypeId(),
            "show_time" => $this->getShowTimeId(),
            "start_date" => $this->getStartDate(),
            "end_date" => $this->getEndDate(),
            "created_at" => $this->getCreatedAt(),
            "updated_at" => $this->getUpdatedAt()
        ];
    }
}