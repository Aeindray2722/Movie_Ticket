<?php
require_once __DIR__ . '/../interface/MovieRepositoryInterface.php';
require_once __DIR__ . '/../repositories/MovieRepository.php';
require_once __DIR__ . '/../services/MovieService.php';

class Movie extends Controller
{
    private MovieService $movieService;

    public function __construct()
    {
        try {
            $db = new Database();
            // Use the interface type hint but instantiate concrete class here
            $repo = new MovieRepository($db);
            $this->movieService = new MovieService($repo, $db);
            $this->model('MovieModel');
            // CSRF token generation
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
        } catch (Exception $e) {
            setMessage('error', 'Failed to initialize Movie Controller: ' . $e->getMessage());
            redirect('pages/home');
        }
    }


    public function middleware()
    {
        return [
            'index' => ['AdminMiddleware'],
            'create' => ['AdminMiddleware'],
            'edit' => ['AdminMiddleware'],
            'dashboard' => ['AdminMiddleware'],
        ];
    }

    public function index()
    {
        try {
            // parent::__construct();
            // $this->requireAuth();
            $search = trim($_GET['search'] ?? '');
            $page = max(1, (int) ($_GET['page'] ?? 1));
            $limit = 5;

            $result = $this->movieService->getMoviesWithPagination($search, $limit, $page);
            $types = $this->movieService->getTypes();
            $show_times = $this->movieService->getShowTimes();
            $movielist = $this->movieService->getAllMovies();

            $data = array_merge($result, [
                'types' => $types,
                'show_times' => $show_times,
                'movieList' => $movielist,
            ]);

            $this->view('admin/movie/movie_list', $data);
        } catch (Exception $e) {
            setMessage('error', 'Error loading movies: ' . $e->getMessage());
            redirect('pages/home');
        }
    }

    public function create()
    {
        try {
            // parent::__construct();
            // $this->requireAuth();
            $data = [
                'movies' => $this->movieService->getAllMovies(),
                'show_times' => $this->movieService->getShowTimes(),
                'types' => $this->movieService->getTypes(),
                'index' => 'movie'
            ];

            $this->view('admin/movie/add_movie', $data);
        } catch (Exception $e) {
            setMessage('error', 'Error preparing create form: ' . $e->getMessage());
            redirect('movie');
        }
    }

    public function store()
    {
        try {
            // parent::__construct();
            // $this->requireAuth();
            // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('movie');
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method.');
            }

            $created = $this->movieService->createMovie($_POST, $_FILES);
            setMessage($created ? 'success' : 'error', $created ? 'Movie created successfully!' : 'Failed to create movie.');
            redirect('movie');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('movie/index');
        }
    }

    public function edit($id)
    {
        try {
            // parent::__construct();
            // $this->requireAuth();
            $movie = $this->movieService->getMovieById((int) $id);

            if (!$movie) {
                throw new Exception('Movie not found');
            }

            $movie['show_time_ids'] = json_decode($movie['show_time'], true) ?? [];

            $data = [
                'movies' => $movie,
                'types' => $this->movieService->getTypes(),
                'show_times' => $this->movieService->getShowTimes()
            ];

            $this->view('admin/movie/edit_movie', $data);
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('movie');
        }
    }

    public function update()
    {
        try {
            // parent::__construct();
            // $this->requireAuth();
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('movie/edit');
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method.');
            }

            $updated = $this->movieService->updateMovie($_POST, $_FILES);
            setMessage($updated ? 'success' : 'error', $updated ? 'Movie updated successfully!' : 'Failed to update movie.');
            redirect('movie/index');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('movie');
        }
    }

    public function destroy($id)
    {
        try {
            // parent::__construct();
            // $this->requireAuth();
            $id = base64_decode($id);
            if (!$id) {
                throw new Exception('Invalid movie ID.');
            }

            $deleted = $this->movieService->deleteMovie((int) $id);
            setMessage($deleted ? 'success' : 'error', $deleted ? 'Movie and image deleted successfully!' : 'Failed to delete movie.');
            redirect('movie/index');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('movie');
        }
    }

    public function dashboard()
    {
        try {
            // parent::__construct();
            // $this->requireAuth();
            $recentBookings = $this->movieService->getRecentBookings();
            $report = $this->movieService->getMonthlySummary();

            $data = [
                'recentBookings' => $recentBookings,
                'report' => $report,
            ];

            $this->view('admin/movie/dashboard', $data);
        } catch (Exception $e) {
            setMessage('error', 'Error loading dashboard: ' . $e->getMessage());
            redirect('movie');
        }
    }

    public function nowShowing()
    {
        try {

            $type = $_GET['type'] ?? null;
            $search = trim($_GET['search'] ?? '');
            $page = max(1, (int) ($_GET['page'] ?? 1));
            $limit = 4;

            $result = $this->movieService->getNowShowingMovies($type, $search, $page, $limit);

            $data = [
                'now_showing_movies' => $result['movies'],
                'page' => $result['page'],
                'totalPages' => $result['totalPages'],
                'type' => $result['type'],
                'types' => $result['types'],
                'search' => $result['search'],
            ];

            $this->view('customer/movie/nowshowing', $data);
        } catch (Exception $e) {
            setMessage('error', 'Error loading now showing movies: ' . $e->getMessage());
            redirect('pages/home');
        }
    }

    public function movieDetail($id)
    {
        try {
            $detail = $this->movieService->getMovieDetail((int) $id);

            if (!$detail) {
                throw new Exception('Movie not found!');
            }

            $this->view('customer/movie/movie_detail', $detail);
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('movie/nowShowing');
        }
    }
}
