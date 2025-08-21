<?php
// load model and views

class Controller
{
    protected $session;

    public function __construct()
    {
        require_once __DIR__ . "/../libraries/SessionManager.php";
        $this->session = new SessionManager();
    }
    // Load Model
    public function model($model) // Product
    {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }
    // Load views
    public function view($view, $data = [])
    {
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once('../app/views/' . $view . '.php');
        } else {
            die('View does not exist');
        }
    }
    protected function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }
    }

}
