<?php

class ShowTimeModel
{
    // Access Modifier = public, private, protected
    private $id;
    private $show_time;
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
    public function setShowTime($show_time)
    {
        $this->show_time = $show_time;
    }
    public function getShowTime()
    {
        return $this->show_time;
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
            "show_time" => $this->getShowTime(),
            "created_at" => $this->getCreatedAt(),
            "updated_at" => $this->getUpdatedAt()
        ];
    }
}