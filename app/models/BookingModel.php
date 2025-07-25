<?php

class BookingModel
{
    // Access Modifier = public, private, protected
    private $id;
    private $user_id;
    private $movie_id;
    private $show_time_id;
    private $seat_id;
    private $status;
    private $total_amount;
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

    public function setUserId($user_id)
    {
        $this->$user_id = $user_id;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
    public function setMovieId($movie_id)
    {
        $this->movie_id = $movie_id;
    }
    public function getMovieId()
    {
        return $this->movie_id;
    }
    public function setShowTimeId($show_time_id)
    {
        $this->show_time_id = $show_time_id;
    }
    public function getShowTimeId()
    {
        return $this->show_time_id;
    }
    public function setSeatId($seat_id)
    {
        $this->seat_id = $seat_id;
    }
    public function getSeatId()
    {
        return $this->seat_id;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setTotalAmount($total_amount)
    {
        $this->total_amount = $total_amount;
    }
    public function getTotalAmount()
    {
        return $this->total_amount;
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
            "user_id" => $this->getUserId(),
            "movie_id" => $this->getMovieId(),
            "show_time_id" => $this->getShowTimeId(),
            "seat_id" => $this->getSeatId(),
            "status" => $this->getStatus(),
            "total_amount" => $this->getTotalAmount(),
            "created_at" => $this->getCreatedAt(),
            "updated_at" => $this->getUpdatedAt()
        ];
    }
}