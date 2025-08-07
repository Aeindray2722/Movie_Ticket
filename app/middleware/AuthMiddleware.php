<?php
require_once 'BaseMiddleware.php';

class AuthMiddleware extends BaseMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user_id'])) {
            setMessage('error', 'You need to login first.');
            redirect('pages/login');
            exit;
        }
    }
}
