<?php

class Movie extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('MovieModel'); // Assuming you have a MovieModel
        $this->db = new Database();
    }
    public function index()
    {
        $limit = 3; // number of movies per page
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1)
            $page = 1;
        $offset = ($page - 1) * $limit;

        // Get paged movies
        $movies = $this->db->readPaged('movies', $limit, $offset);

        // You might also want total movie count for calculating total pages
        $totalMovies = count($this->db->readAll('movies')); // or create a count query for better performance
        $totalPages = ceil($totalMovies / $limit);

        $show_times = $this->db->readAll('show_times');
        $types = $this->db->readAll('types');

        $data = [
            'movies' => $movies,
            'show_times' => $show_times,
            'types' => $types,
            'page' => $page,
            'totalPages' => $totalPages
        ];

        $this->view('admin/movie/movie_list', $data);
    }





    public function create()
    {
        $movies = $this->db->readAll('movies');
        $show_times = $this->db->readAll('show_times');
        $types = $this->db->readAll('types'); // ✅ correct

        $data = [
            'movies' => $movies,
            'show_times' => $show_times,
            'types' => $types, // ✅ this makes the type dropdown work
            'index' => 'movie'
        ];

        $this->view('admin/movie/add_movie', $data);
    }


    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $movie_name = $_POST['movie_name'];
            $actor_name = $_POST['actor_name'];
            $genre = $_POST['genre'];
            $description = $_POST['description'];
            $type_id = $_POST['type_id'];
            $show_times = isset($_POST['show_times']) ? $_POST['show_times'] : [];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $movie_img = '';

            // Handle file upload
            if (isset($_FILES['movie_image']) && $_FILES['movie_image']['error'] == 0) {
                $targetDir = __DIR__ . '/../../public/images/movies/';
                // $targetDir = "uploads/movies/";
                $movie_img = time() . '_' . basename($_FILES['movie_image']['name']); // prevent duplicate file name
                $targetFile = $targetDir . $movie_img;
                move_uploaded_file($_FILES['movie_image']['tmp_name'], $targetFile);
            }

            // Prepare data array for insert
            $movieData = [
                'movie_name' => $movie_name,
                'actor_name' => $actor_name,
                'genre' => $genre,
                'description' => $description,
                'type_id' => $type_id,                     // <-- type id only
                'show_time' => json_encode($show_times),   // <-- JSON string of show times
                'start_date' => $start_date,
                'end_date' => $end_date,
                'movie_img' => $movie_img,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // Insert into database directly (without MovieModel)
            $movieCreated = $this->db->create('movies', $movieData);

            if ($movieCreated) {
                setMessage('success', 'Movie added successfully!');
            } else {
                setMessage('error', 'Failed to add movie.');
            }
            redirect('movie');
        }
    }



    // Edit - Display the form to edit an existing movie
    public function edit($id)
    {
        $movies = $this->db->getById('movies', $id);
        $types = $this->db->readAll('types'); // Get available movie types
        $show_times = $this->db->readAll('show_times'); // Get available showtimes

        $show_time_json = $movies['show_time'] ?? '[]';
        // var_dump($show_time_json);
        // exit;
        $movies['show_time_ids'] = json_decode($movies['show_time'], true) ?? [];
        // var_dump($movies['show_time_ids']);
        // exit;

        if (!$movies) {
            setMessage('error', 'Your Movie id is not have');
            return;
        }
        $data = [
            'types' => $types,
            'show_times' => $show_times,
            'movies' => $movies
        ];


        $this->view('admin/movie/edit_movie', $data); // Display the edit form with existing movie data
    }

    // Update - Save the edited movie data
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $movie_name = $_POST['movie_name'];
            $actor_name = $_POST['actor_name'];
            $genre = $_POST['genre'];
            $description = $_POST['description'];
            $type_id = $_POST['type_id'];
            $show_times = isset($_POST['show_times']) ? $_POST['show_times'] : [];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];

            // Get old image in case no new image is uploaded
            $old_movie = $this->db->getById('movies', $id);
            $movie_img = $old_movie['movie_img'];

            // Handle file upload if new image is uploaded
            if (isset($_FILES['movie_image']) && $_FILES['movie_image']['error'] == 0) {
                $movie_img = $_FILES['movie_image']['name'];
                $targetDir = __DIR__ . '/../../public/images/movies/';
                $targetFile = $targetDir . basename($movie_img);
                move_uploaded_file($_FILES['movie_image']['tmp_name'], $targetFile);
            }

            // Create and populate model
            $movie = new MovieModel();
            $movie->setId($id);
            $movie->setMovieName($movie_name);
            $movie->setActorName($actor_name);
            $movie->setGenre($genre);
            $movie->setDescription($description);
            $movie->setTypeId($type_id);
            $movie->setShowTimeId(json_encode($show_times)); // JSON encode
            $movie->setStartDate($start_date);
            $movie->setEndDate($end_date);
            $movie->setMovieImg($movie_img);
            $movie->setUpdatedAt(date('Y-m-d H:i:s'));
            $data = $movie->toArray();
            unset($data['created_at']);

            $isUpdated = $this->db->update('movies', $movie->getId(), $data);

            setMessage('success', 'Movie updated successfully!');
            redirect('movie');
        }
    }


    // Destroy - Delete a movie from the database
    public function destroy($id)
    {
        $id = base64_decode($id); // Decode ID (optional)

        $movie = new MovieModel();
        $movie->setId($id);

        // Delete the movie
        $isDeleted = $this->db->delete('movies', $movie->getId());

        setMessage('success', 'Movie deleted successfully!');
        redirect('movie'); // Redirect to movie index page
    }

    // Fetch movie data as JSON (for AJAX requests)
    public function movieData()
    {
        $movies = $this->db->readAll('movies');
        $json = array('data' => $movies);
        echo json_encode($json);
    }




    public function dashboard()
    {
        $this->view('admin/movie/dashboard');
    }

     public function nowShowing()
    {
        $this->view('customer/movie/nowshowing');
    }
    
    public function movieDetail()
    {
        $this->view('customer/movie/movie_detail');
    }
    




}
?>