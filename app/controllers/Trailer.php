<?php

class Trailer extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
        $this->model('TrailerModel');
    }
     public function middleware()
    {
        return [
            'index' => ['AdminMiddleware'],
            'create' => ['AdminMiddleware'],
            'store' => ['AdminMiddleware'],
            'edit' => ['AdminMiddleware'],
            'update' => ['AdminMiddleware'],
            'destroy' => ['AdminMiddleware'],
            'trailer' => ['CustomerMiddleware'],
            'movieDetail' => ['CustomerMiddleware'],
        ];
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

            $targetDir = __DIR__ . '/../../public/videos/trailers/';

            if (isset($_FILES['trailer_file']) && $_FILES['trailer_file']['error'] == 0) {
                // ✅ Delete old trailer video file
                $oldPath = $targetDir . $trailer_vd;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }

                // ✅ Upload new trailer file
                $trailer_vd = time() . '_' . basename($_FILES['trailer_file']['name']);
                $targetFile = $targetDir . $trailer_vd;
                move_uploaded_file($_FILES['trailer_file']['tmp_name'], $targetFile);
            }

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
        $id = base64_decode($id);

        // Get trailer info (to find file name)
        $trailer = $this->db->getById('trailers', $id);

        if ($trailer) {
            // ✅ Delete trailer file
            $filePath = __DIR__ . '/../../public/videos/trailers/' . $trailer['trailer_vd'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // ✅ Delete record from DB
            $this->db->delete('trailers', $id);
            setMessage('success', 'Trailer deleted successfully!');
        } else {
            setMessage('error', 'Trailer not found!');
        }

        redirect('trailer');
    }



    public function trailer()
    {
        $type = $_GET['type'] ?? null;
        $search = trim($_GET['search'] ?? '');
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 4;
        $offset = ($page - 1) * $limit;

        // All types list for filtering
        $types = $this->db->readAll('types');

        // Columns to search
        $columnsToSearch = ['movie_name', 'type_name', 'actor_name'];

        // Default values
        $trailers = [];
        $total = 0;

        if ($search !== '') {
            // 🔍 Keyword search without pagination (we paginate manually after filtering)
            $result = $this->db->search(
                'trailers JOIN view_movies_info ON trailers.movie_id = view_movies_info.id',
                $columnsToSearch,
                $search,
                $limit, // no limit
                $offset  // no offset
            );

            // Optional filtering by type
            $filtered = array_filter($result['data'], function ($trailer) use ($type) {
                return !$type || strtolower($trailer['type_name']) === strtolower($type);
            });

            // Manual pagination
            $total = count($filtered);
            $trailers = array_slice(array_values($filtered), $offset, $limit);

        } else {
            // No search keyword — use paginated search
            $result = $this->db->search(
                'trailers JOIN view_movies_info ON trailers.movie_id = view_movies_info.id',
                $columnsToSearch,
                '',          // empty search string
                $limit,
                $offset
            );

            $trailers = $result['data'];
            $total = $result['total'] ?? count($trailers);

            // Optional filtering by type (after fetching)
            if ($type) {
                $trailers = array_filter($trailers, function ($trailer) use ($type) {
                    return strtolower($trailer['type_name']) === strtolower($type);
                });
                $trailers = array_values($trailers); // reindex
                $total = count($trailers);
            }
        }

        $totalPages = ceil($total / $limit);

        // Prepare view data
        $data = [
            'trailers' => $trailers,
            'page' => $page,
            'totalPages' => $totalPages,
            'type' => $type,
            'types' => $types,
            'search' => $search
        ];

        // 👁 Show trailer page
        $this->view('customer/movie/trailer', $data);
    }



    public function movieDetail($id)
    {
        // Fetch movie info by ID from view_movies_info
        $movie = $this->db->getById('view_movies_info', $id);

        if (!$movie) {
            setMessage('error', 'Movie not found!');
            redirect('trailer/trailer');
        }

        // ✅ Fetch average rating for this movie (rounded)
        $avg_rating = $this->db->getAvgRatingByMovieId($id);

        $sqlComments = "SELECT c.id, c.message, c.user_id, c.created_at, u.name ,u.profile_img
                    FROM comments c
                    JOIN users u ON c.user_id = u.id
                    WHERE c.movie_id = :movie_id
                    ORDER BY c.created_at DESC";
        $this->db->query($sqlComments);
        $this->db->bind(':movie_id', $id);
        $this->db->stmt->execute();
        $comments = $this->db->stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->db->query("SELECT * FROM trailers WHERE movie_id = :movie_id LIMIT 1");
        $this->db->bind(':movie_id', $id);
        $this->db->stmt->execute();
        $trailer = $this->db->stmt->fetch(PDO::FETCH_ASSOC);

        // Pass data to view
        $data = [
            'movie' => $movie,
            'avg_rating' => $avg_rating,
            'comment' => $comments,
            'trailer' => $trailer
        ];


        $this->view('customer/movie/trailer_detail', $data);
    }



}
