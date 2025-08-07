<?php
require_once 'BaseMiddleware.php';

class CustomerMiddleware extends BaseMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
            setMessage('error', 'Only customers can access this page.');
            redirect('pages/login');
            exit;
        }
    }
}
