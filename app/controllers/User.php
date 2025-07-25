<?php

class User extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function profile()
    {
        $this->view('admin/profile/account_profile');
    }
    public function staffList()
    {
        $this->view('admin/profile/staff_list');
    }
    public function userList()
    {
        $this->view('admin/profile/user_list');
    }
    public function changePassword()
    {
        $this->view('admin/profile/change_password');
    }
    public function editProfile()
    {
        $this->view('admin/profile/edit_profile');
    }
     public function addStaff()
    {
        $this->view('admin/profile/create_staff');
    }
     public function addUser()
    {
        $this->view('admin/profile/create_user');
    }

    public function Userprofile()
    {
        $this->view('customer/profile/account_profile');
    }
   
    public function UserchangePassword()
    {
        $this->view('customer/profile/change_password');
    }
    public function UsereditProfile()
    {
        $this->view('customer/profile/edit_profile');
    }

}
