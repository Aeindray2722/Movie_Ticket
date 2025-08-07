<?php

class ContactModel
{
    // Access Modifier = public, private, protected
    private $id;
    private $user_id;
    private $email;
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

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
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
            "id" =>$this->getId(),
            "user_id" => $this->getUserId(),
            "email" => $this->getEmail(),
            "message" => $this->getMessage(),
            "created_at" => $this->getCreatedAt(),
            "updated_at" => $this->getUpdatedAt()
        ];
    }
}