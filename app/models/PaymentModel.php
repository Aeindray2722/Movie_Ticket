<?php

class PaymentModel
{
    // Access Modifier = public, private, protected
    private $id;
    private $account_name;
    private $account_number;
    private $payment_method;
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

    public function setAccountName($account_name)
    {
        $this->$account_name = $account_name;
    }
    public function getAccountName()
    {
        return $this->account_name;
    }
    public function setAccountNumber($account_number)
    {
        $this->account_number = $account_number;
    }
    public function getAccountNumber()
    {
        return $this->account_number;
    }
    public function setPaymentMethod($payment_method)
    {
        $this->payment_method = $payment_method;
    }
    public function getPaymentMethod()
    {
        return $this->payment_method;
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
            "account_name" => $this->getAccountName(),
            "account_number" => $this->getAccountNumber(),
            "payment_method" => $this->getPaymentMethod(),
            "created_at" => $this->getCreatedAt(),
            "updated_at" => $this->getUpdatedAt()
        ];
    }
}