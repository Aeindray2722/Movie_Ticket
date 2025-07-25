<?php

class CommentModel
{
    // Access Modifier = public, private, protected
    private $id;
    private $movie_id;
    private $user_id;
    private $message;

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
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
    public function setMessage($message)
    {
        $this->message = $message;
    }
    public function getMessage()
    {
        return $this->message;
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
            "user_id" => $this->getUserId(),
            "message" => $this->getMessage(),
            "created_at" => $this->getCreatedAt(),
            "updated_at" => $this->getUpdatedAt()
        ];
    }
}