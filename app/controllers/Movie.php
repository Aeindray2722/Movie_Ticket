<?php

class Movie extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('MovieModel'); // Assuming you have a MovieModel
        $this->db = new Database();
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
            'dashboard' => ['AdminMiddleware'],
        ];
    }

    public function index()
    {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $limit = 5;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1)
            $page = 1;
        $offset = ($page - 1) * $limit;

        $columnsToSearch = ['movie_name', 'type_name', 'actor_name']; // Adjust as needed

        if ($search !== '') {
            $result = $this->db->search('view_movies_info', $columnsToSearch, $search, $limit, $offset);
            $movies = $result['data'] ?? [];
            $totalMovies = $result['total'] ?? 0;
        } else {
            $movies = $this->db->readPaged('view_movies_info', $limit, $offset);
            // Use a COUNT query here for performance in real projects
            $totalMovies = count($this->db->readAll('view_movies_info'));
        }

        $totalPages = ceil($totalMovies / $limit);

        $types = $this->db->readAll('types');
        $show_times = $this->db->readAll('show_times');
        $movielist = $this->db->readAll('movies');

        $data = [
            'movies' => $movies,
            'movieList' => $movielist,
            'types' => $types,
            'show_times' => $show_times,
            'search' => $search,
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

            // if ($movieCreated) {
            //     setMessage('success', 'Movie added successfully!');
            // } else {
            //     setMessage('error', 'Failed to add movie.');
            // }
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

            // Get old movie
            $old_movie = $this->db->getById('movies', $id);
            $movie_img = $old_movie['movie_img']; // Default to old image

            // Check for new image
            if (isset($_FILES['movie_image']) && $_FILES['movie_image']['error'] == 0) {
                // Delete old image
                $oldImagePath = __DIR__ . '/../../public/images/movies/' . $movie_img;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                // Upload new image
                $newFilename = time() . '_' . basename($_FILES['movie_image']['name']);
                $targetDir = __DIR__ . '/../../public/images/movies/';
                $targetFile = $targetDir . $newFilename;

                if (move_uploaded_file($_FILES['movie_image']['tmp_name'], $targetFile)) {
                    $movie_img = $newFilename;
                }
            }

            // Create and populate model
            $movie = new MovieModel();
            $movie->setId($id);
            $movie->setMovieName($movie_name);
            $movie->setActorName($actor_name);
            $movie->setGenre($genre);
            $movie->setDescription($description);
            $movie->setTypeId($type_id);
            $movie->setShowTimeId(json_encode($show_times));
            $movie->setStartDate($start_date);
            $movie->setEndDate($end_date);
            $movie->setMovieImg($movie_img);
            $movie->setUpdatedAt(date('Y-m-d H:i:s'));

            $data = $movie->toArray();
            unset($data['created_at']); // Don't update created_at

            $isUpdated = $this->db->update('movies', $movie->getId(), $data);

            setMessage('success', 'Movie updated successfully!');
            redirect('movie');
        }
    }



    // Destroy - Delete a movie from the database
    public function destroy($id)
    {
        $id = base64_decode($id); // Decode ID (optional)

        // Step 1: Get the movie data
        $movie = $this->db->getById('movies', $id);

        if (!$movie) {
            setMessage('error', 'Movie not found!');
            redirect('movie');
            return;
        }

        // Step 2: Delete the movie image
        $movieImg = $movie['movie_img'];
        $imagePath = __DIR__ . '/../../public/images/movies/' . $movieImg;

        if (file_exists($imagePath)) {
            unlink($imagePath); // delete image file
        }

        // Step 3: Delete the movie record from DB
        $isDeleted = $this->db->delete('movies', $id);

        if ($isDeleted) {
            setMessage('success', 'Movie and image deleted successfully!');
        } else {
            setMessage('error', 'Failed to delete movie.');
        }

        redirect('movie'); // Go back to movie list
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
        // 1. Fetch 5 most recent bookings
        $limit = 5;
        $sql = "SELECT * FROM bookings ORDER BY booking_date DESC LIMIT :limit";
        $this->db->query($sql);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->stmt->execute();
        $recentBookings = $this->db->stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. Collect all seat IDs from recent bookings to map to seat names
        $allSeatIds = [];
        foreach ($recentBookings as $booking) {
            $seat_ids = json_decode($booking['seat_id'], true);
            if (is_array($seat_ids)) {
                $allSeatIds = array_merge($allSeatIds, $seat_ids);
            }
        }

        // 3. Get seat names map for all seat IDs
        $seatMap = $this->db->getSeatNamesByIds($allSeatIds);

        // 4. Enrich bookings with movie, user, seat names, showtime, and status text
        foreach ($recentBookings as &$booking) {
            $movie = $this->db->getById('movies', $booking['movie_id']);
            $booking['movie_name'] = $movie['movie_name'] ?? 'Unknown';

            $user = $this->db->getById('users', $booking['user_id']);
            $booking['user_name'] = $user['name'] ?? 'Unknown';

            $seat_ids = json_decode($booking['seat_id'], true);
            $seat_names = [];
            foreach ($seat_ids as $sid) {
                $seat_names[] = $seatMap[$sid] ?? 'Unknown';
            }
            $booking['seats'] = implode(', ', $seat_names);

            $showTime = $this->db->getById('show_times', $booking['show_time_id']);
            $booking['show_time'] = $showTime['show_time'] ?? 'Unknown';

            $statusMap = [0 => 'Confirmed', 1 => 'Pending', 2 => 'Rejected'];
            $booking['status_text'] = $statusMap[$booking['status']] ?? 'Unknown';
        }
        unset($booking); // break reference

        // 5. Get monthly summary report from stored procedure
        $report = $this->db->getMonthlySummary();

        // 6. Pass both data arrays to the dashboard view
        $data = [
            'recentBookings' => $recentBookings,
            'report' => $report,
        ];

        $this->view('admin/movie/dashboard', $data);
    }

    public function nowShowing()
    {
        $type = $_GET['type'] ?? null;
        $search = trim($_GET['search'] ?? '');
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 4;
        $offset = ($page - 1) * $limit;

        $types = $this->db->readAll('types');
        $columnsToSearch = ['movie_name', 'type_name', 'actor_name'];

        $filteredMovies = [];
        $total = 0;

        if ($search !== '') {
            // Search with keyword
            $result = $this->db->search('view_movies_info', $columnsToSearch, $search, $limit, $offset);

            // Filter result to only show movies with valid date range
            $today = date('Y-m-d');
            $filtered = array_filter($result['data'], function ($movie) use ($today, $type) {
                $isShowing = ($movie['start_date'] <= $today && $movie['end_date'] >= $today);
                $isTypeMatch = !$type || strtolower($movie['type_name']) === strtolower($type);
                return $isShowing && $isTypeMatch;
            });

            // Paginate manually
            $total = count($filtered);
            $movies = array_slice(array_values($filtered), $offset, $limit);
        } else {
            // No search keyword, use pagination
            $result = $this->db->paginateByType(
                'view_movies_info',
                $limit,
                $page,
                $type,
                ['CURDATE() BETWEEN start_date AND end_date']
            );
            $movies = $result['data'];
            $total = $result['total'] ?? count($movies);
        }

        $totalPages = ceil($total / $limit);

        $data = [
            'now_showing_movies' => $movies,
            'page' => $page,
            'totalPages' => $totalPages,
            'type' => $type,
            'types' => $types,
            'search' => $search
        ];

        $this->view('customer/movie/nowshowing', $data);
    }




    public function movieDetail($id)
    {
        // Fetch movie info by ID from view_movies_info
        $movie = $this->db->getById('view_movies_info', $id);

        if (!$movie) {
            setMessage('error', 'Movie not found!');
            redirect('movie/nowShowing');
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
        // Pass data to view
        $data = [
            'movie' => $movie,
            'avg_rating' => $avg_rating,
            'comment' => $comments
        ];


        $this->view('customer/movie/movie_detail', $data);
    }

}
?>