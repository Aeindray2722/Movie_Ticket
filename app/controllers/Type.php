<?php

require_once __DIR__ . '/../repositories/TypeRepository.php';
require_once __DIR__ . '/../services/TypeService.php';

class Type extends Controller
{
    private $typeService;

    public function __construct()
    {
        try {
            parent::__construct();
            $this->requireAuth();
            $db = new Database();
            $repo = new TypeRepository($db);
            $this->typeService = new TypeService($repo);
            // CSRF token generation
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        } catch (Exception $e) {
            setMessage('error', 'Initialization error: ' . $e->getMessage());
            redirect('error');
        }
    }

    public function middleware()
    {
        return [
            'index' => ['AdminMiddleware'],
            'create' => ['AdminMiddleware'],
            'store' => ['AdminMiddleware'],
            'editType' => ['AdminMiddleware'],
            'update' => ['AdminMiddleware'],
            'destroy' => ['AdminMiddleware'],
        ];
    }

    public function index()
    {
        try {
            $limit = 3;
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $data = $this->typeService->listTypes($limit, $page);
            $this->view('admin/movie/movie_type', $data);
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('type');
        }
    }

    public function store()
    {
        try {
            // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('type');
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('type');
                return;
            }

            $name = $_POST['type_name'] ?? '';
            $this->typeService->createType($name);

            setMessage('success', "Type added successfully!");
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
        }
        redirect('type/index');
    }

    public function update()
    {
        try {
            // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('type/editType');
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('type/index');
                return;
            }

            $id = (int) ($_POST['id'] ?? 0);
            $name = $_POST['type_name'] ?? '';

            $this->typeService->updateType($id, $name);

            setMessage('success', 'Update successful!');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
        }
        redirect('type/index');
    }

    public function editType($id)
    {
        try {
            $id = (int) $id;
            $editData = $this->typeService->getTypeById($id);

            if (!$editData) {
                throw new Exception('Type not found.');
            }

            $this->view('admin/movie/edit_movieType', ['editData' => $editData]);
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('type');
        }
    }

    public function destroy($id)
    {
        try {
            $id = (int) base64_decode($id);
            $this->typeService->deleteType($id);
            setMessage('success', 'Type deleted successfully!');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
        }
        redirect('type/index');
    }
}
