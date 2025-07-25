<?php

class Payment extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function addPayment()
    {
        $this->view('admin/payment/add_payment');
    }
    public function Payment()
    {
        $this->view('customer/payment/payment');
    }

    
   

}
