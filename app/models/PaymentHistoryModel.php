<?php

class PaymentHistoryModel
{
    // Access Modifier = public, private, protected
    private $id;
    private $user_id;
    private $payment_id;
    private $payslip_image;
    private $payment_method;
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
   
    public function setPayslipImage($payslip_image)
    {
        $this->payslip_image = $payslip_image;
    }
    public function getPayslipImage()
    {
        return $this->payslip_image;
    }
   public function setPaymentId($payment_id)
    {
        $this->payment_id = $payment_id;
    }
    public function getPaymentId()
    {
        return $this->payment_id;
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
            "payslip_image" => $this->getPayslipImage(),
            "payment_id" => $this->getPaymentId(),
            "total_amount" => $this->getTotalAmount(),
            "created_at" => $this->getCreatedAt(),
            "updated_at" => $this->getUpdatedAt()
        ];
    }
}