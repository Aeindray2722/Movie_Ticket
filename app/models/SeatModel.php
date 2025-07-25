<?php

class SeatModel
{
    // Access Modifier = public, private, protected
    private $id;
    private $seat_row;
    private $seat_number;
    private $price;
    private $status;
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

    public function setSeatRow($seat_row)
    {
        $this->seat_row = $seat_row;
    }
    public function getSeatRow()
    {
        return $this->seat_row;
    }
    public function setSeatNumber($seat_number)
    {
        $this->seat_number = $seat_number;
    }
    public function getSeatNumber()
    {
        return $this->seat_number;
    }
    public function setPrice($price)
    {
        $this->price = $price;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function getStatus()
    {
        return $this->status;
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
            "seat_row" => $this->getSeatRow(),
            "seat_number" => $this->getSeatNumber(),
            "price" => $this->getPrice(),
            "status" => $this->getStatus(),
            "created_at" => $this->getCreatedAt(),
            "updated_at" => $this->getUpdatedAt()
        ];
    }
}