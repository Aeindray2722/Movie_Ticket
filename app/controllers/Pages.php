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
        $this->view('pages/main');
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
        $users = $this->db->readWithCondition("users", "role = 0"); // ✅ Correct usage
        $data = ['team' => $users];
        $this->view('customer/payment/about', $data);
    }

    public function home()
    {
        // Fetch now showing movies
        $nowShowingMovies = $this->db->readWithCondition(
            'view_movies_info',
            "CURDATE() BETWEEN start_date AND end_date ORDER BY id DESC LIMIT 5"
        );

        // Fetch trailers with movie_img and movie_name from movies table using JOIN
        $sql = "
        SELECT trailers.*, movies.movie_img, movies.movie_name
        FROM trailers
        JOIN movies ON trailers.movie_id = movies.id
        ORDER BY trailers.created_at DESC
        LIMIT 5
    ";
        $this->db->query($sql);
        $this->db->stmt->execute();
        $trailers = $this->db->fetchAll();

        $data = [
            'now_showing_movies' => $nowShowingMovies,
            'trailers' => $trailers
        ];

        $this->view('customer/movie/dashboard', $data);
    }





    // public function dashboard()
    // {
    //     $income = $this->db->incomeTransition();
    //     $expense = $this->db->expenseTransition();

    //     $data = [
    //         'income' => isset($income['amount']) ? $income : ['amount' => 0],
    //         'expense' => isset($expense['amount']) ? $expense : ['amount' => 0]
    //     ];

    //     $this->view('pages/dashboard', $data);
    // }

}
