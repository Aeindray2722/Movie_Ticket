<?php
require_once __DIR__ . '/../services/TrailerService.php';

class Trailer extends Controller
{
    private $service;

    public function __construct()
    {
        try {
            $db = new Database();
            $this->model('TrailerModel');
            $this->service = new TrailerService($db);
            // CSRF token generation
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
        } catch (Exception $e) {
            setMessage('error', 'Initialization failed: ' . $e->getMessage());
            redirect('error');
        }
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
        ];
    }

    public function index()
    {

        try {
            
            $page = max((int) ($_GET['page'] ?? 1), 1);
            $limit = 3;

            $result = $this->service->getAllPaged($limit, $page);
            $this->view('admin/trailer/add_trailer', [
                'trailers' => $result['data'],
                'types' => $this->service->getTypes(),
                'movies' => $this->service->getMovies(),
                'page' => $page,
                'totalPages' => $result['totalPages']
            ]);
        } catch (Exception $e) {
            setMessage('error', 'Failed to load trailers: ' . $e->getMessage());
            redirect('trailer');
        }
    }

    public function create()
    {
        try {
            $this->view('admin/trailer/add_trailer', [
                'trailers' => $this->service->getAllPaged(100, 1)['data'],
                'types' => $this->service->getTypes(),
                'movies' => $this->service->getMovies(),
                'index' => 'movie'
            ]);
        } catch (Exception $e) {
            setMessage('error', 'Failed to load create form: ' . $e->getMessage());
            redirect('trailer');
        }
    }

    public function store()
    {
        try {
                    // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('pages/login');
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('trailer');
                return;
            }

            $data = ['movie_id' => $_POST['movie_id']];
            $this->service->create($data, $_FILES);
            setMessage('success', 'Trailer added successfully!');
            redirect('trailer');
        } catch (Exception $e) {
            setMessage('error', 'Failed to add trailer: ' . $e->getMessage());
            redirect('trailer');
        }
    }

    public function edit($id)
    {
        try {
            $trailer = $this->service->findById($id);
            if (!$trailer) {
                throw new Exception('Trailer not found!');
            }

            $this->view('admin/trailer/edit_trailer', [
                'movies' => $this->service->getMovies(),
                'trailers' => $trailer
            ]);
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('trailer');
        }
    }

    public function update()
    {
        try {
            // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('admin/trailer/edit_trailer');
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('trailer');
                return;
            }

            $id = $_POST['id'];
            $data = ['movie_id' => $_POST['movie_name']];
            if ($this->service->update($id, $data, $_FILES)) {
                setMessage('success', 'Trailer updated successfully!');
            } else {
                throw new Exception('Failed to update trailer!');
            }
            redirect('trailer');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('trailer');
        }
    }

    public function destroy($id)
    {
        try {
            $id = base64_decode($id);
            if ($this->service->delete($id)) {
                setMessage('success', 'Trailer deleted successfully!');
            } else {
                throw new Exception('Trailer not found!');
            }
            redirect('trailer');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('trailer');
        }
    }

    public function trailer()
    {
        try {
            // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('pages/register');
                exit;
            }
            $page = max((int) ($_GET['page'] ?? 1), 1);
            $limit = 4;
            $type = $_GET['type'] ?? null;
            $search = trim($_GET['search'] ?? '');

            $result = $this->service->searchTrailers($type, $search, $limit, $page);
            $this->view('customer/movie/trailer', [
                'trailers' => $result['data'],
                'page' => $page,
                'totalPages' => $result['totalPages'],
                'type' => $type,
                'types' => $this->service->getTypes(),
                'search' => $search
            ]);
        } catch (Exception $e) {
            setMessage('error', 'Failed to load trailers: ' . $e->getMessage());
            redirect('pages/home');
        }
    }

    public function movieDetail($id)
    {
        try {
            $detail = $this->service->getMovieDetail($id);
            if (!$detail) {
                throw new Exception('Movie not found!');
            }
            $this->view('customer/movie/trailer_detail', $detail);
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('trailer/trailer');
        }
    }
}
