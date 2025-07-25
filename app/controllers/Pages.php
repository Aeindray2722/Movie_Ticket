<?php

class Pages extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function index()
    {
        $this->view('admin/movie/dashboard');
    }

    public function login()
    {
        $this->view('pages/login1');
    }

    public function register()
    {
        $this->view('pages/register1');
    }

    public function about()
    {
        $this->view('customer/payment/about');
    }
     public function home()
    {
        $this->view('customer/movie/dashboard');
    }

    public function dashboard()
    {
        $income = $this->db->incomeTransition();
        $expense = $this->db->expenseTransition();

        $data = [
            'income' => isset($income['amount']) ? $income : ['amount' => 0],
            'expense' => isset($expense['amount']) ? $expense : ['amount' => 0]
        ];

        $this->view('pages/dashboard', $data);
    }

}
