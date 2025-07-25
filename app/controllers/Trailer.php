<?php

class Trailer extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
        $this->model('TrailerModel');
    }
    public function index()
    {
        $limit = 3; // number of trailers per page
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1)
            $page = 1;
        $offset = ($page - 1) * $limit;

        // Get paged movies
        $trailers = $this->db->readPaged('trailers', $limit, $offset);

        // You might also want total movie count for calculating total pages
        $totaltrailers = count($this->db->readAll('trailers')); // or create a count query for better performance
        $totalPages = ceil($totaltrailers / $limit);

        $types = $this->db->readAll('types');
        $movies = $this->db->readAll('movies');

        $data = [
            'trailers' => $trailers,
            'types' => $types,
            'page' => $page,
            'movies' => $movies,
            'totalPages' => $totalPages
        ];


        $this->view('admin/trailer/add_trailer', $data);
    }
    public function create()
    {
        $trailers = $this->db->readAll('trailers');
        $types = $this->db->readAll('types'); // ✅ correct
        $movies = $this->db->readAll('movies');

        $data = [
            'trailers' => $trailers,
            'types' => $types, // ✅ this makes the type dropdown work
            'movies' => $movies,
            'index' => 'movie'
        ];


        $this->view('admin/trailer/add_trailer', $data);
    }


    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $movie_id = $_POST['movie_id']; // ✅ correct name
            $trailer_vd = '';

            // ✅ Check file field is correct
            if (isset($_FILES['trailer_file']) && $_FILES['trailer_file']['error'] == 0) {
                $targetDir = __DIR__ . '/../../public/videos/trailers/';
                $trailer_vd = time() . '_' . basename($_FILES['trailer_file']['name']);
                $targetFile = $targetDir . $trailer_vd;
                move_uploaded_file($_FILES['trailer_file']['tmp_name'], $targetFile);
            }


            $trailerData = [
                'movie_id' => $movie_id,
                'trailer_vd' => $trailer_vd,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->create('trailers', $trailerData);
            $_SESSION['success'] = "Trailer added successfully!";
            header("Location: " . URLROOT . "/trailer");
            exit;

        }
    }

    public function edit($id)
    {
        $trailers = $this->db->getById('trailers', $id);
        $movies = $this->db->readAll('movies'); // Get available movie movies
        // var_dump($movies);
        if (!$trailers) {
            setMessage('error', 'Your Movie id is not have');
            return;
        }
        $data = [
            'movies' => $movies,
            'trailers' => $trailers
        ];


        $this->view('admin/trailer/edit_trailer', $data); // Display the edit form with existing movie data
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $movie_id = $_POST['movie_name'];

            $old_trailer = $this->db->getById('trailers', $id);
            $trailer_vd = $old_trailer['trailer_vd'];

            if (isset($_FILES['trailer_file']) && $_FILES['trailer_file']['error'] == 0) {
                $targetDir = __DIR__ . '/../../public/videos/trailers/';
                $trailer_vd = time() . '_' . basename($_FILES['trailer_file']['name']);
                $targetFile = $targetDir . $trailer_vd;
                move_uploaded_file($_FILES['trailer_file']['tmp_name'], $targetFile);
            }

            // Prepare data for update
            $trailerData = [
                'movie_id' => $movie_id,
                'trailer_vd' => $trailer_vd,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $isUpdated = $this->db->update('trailers', $id, $trailerData);

            if ($isUpdated) {
                setMessage('success', 'Trailer updated successfully!');
            } else {
                setMessage('error', 'Failed to update trailer!');
            }
            redirect('trailer');
        }
    }

    // Destroy - Delete a movie from the database
    public function destroy($id)
    {

        $id = base64_decode($id); // Decode ID (optional)

        $trailers = new TrailerModel();
        $trailers->setId($id);

        // Delete the movie
        $isDeleted = $this->db->delete('trailers', $trailers->getId());

        setMessage('success', 'Trailers deleted successfully!');
        redirect('trailer'); // Redirect to movie index page
    }

    public function addTrailer()
    {
        $this->view('admin/trailer/add_trailer');
    }


    public function trailer()
    {
        $this->view('customer/movie/trailer');
    }
    public function trailerWatch()
    {
        $this->view('customer/movie/trailer_watch');
    }

}
