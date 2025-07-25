<?php

class Booking extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function bookingHistory()
    {
        $this->view('admin/booking/booking_history');
    }
    public function bookingDetail()
    {
        $this->view('admin/booking/booking_detail');
    }
    public function history()
    {
        $this->view('customer/booking/booking_history');
    }
    public function booking()
    {
        $this->view('customer/booking/booking');
    }


    
   

}
