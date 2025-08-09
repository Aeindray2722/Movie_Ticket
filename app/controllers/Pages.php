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
       $trailers = $this->db->readWithCondition('view_trailers_info',
            "1 ORDER BY trailer_created_at DESC LIMIT 5");

        $data = [
            'now_showing_movies' => $nowShowingMovies,
            'trailers' => $trailers
        ];

        $this->view('customer/movie/dashboard', $data);
    }


}
