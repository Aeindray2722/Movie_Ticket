<?php
require_once 'BaseMiddleware.php';

class AdminMiddleware extends BaseMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 0) {
            setMessage('error', 'Only admin can access this page.');
            redirect('pages/login');
            exit;
        }
    }
}
