<?php
require_once __DIR__ . '/../repositories/SeatRepository.php';
require_once __DIR__ . '/../services/SeatService.php';

class Seat extends Controller
{
    private $seatService;

    public function __construct()
    {
        try {
            $db = new Database();
            $seatRepository = new SeatRepository($db);
            $this->seatService = new SeatService($seatRepository);
            // CSRF token generation
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            $this->model('SeatModel');
        } catch (Exception $e) {
            setMessage('error', 'Initialization error: ' . $e->getMessage());
            redirect('error');
        }
    }

    public function middleware()
    {
        return [
            'index' => ['AdminMiddleware'],
            'edit' => ['AdminMiddleware'],
        ];
    }

    public function index()
    {
        try {
            $limit = 10;
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $page = max(1, $page);

            $data = $this->seatService->getSeatsPaged($limit, $page);

            $this->view('admin/seat/add_seat', $data);
        } catch (Exception $e) {
            setMessage('error', 'Failed to load seats: ' . $e->getMessage());
            redirect('seat');
        }
    }

    public function create()
    {
        try {
            $data = [
                'seats' => $this->seatService->getAllSeats(),
                'index' => 'movie',
            ];

            $this->view('admin/seat/add_seat', $data);
        } catch (Exception $e) {
            setMessage('error', 'Failed to prepare create form: ' . $e->getMessage());
            redirect('seat');
        }
    }

    public function store()
    {
        try {
            // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('seat/create');
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('seat');
                return;
            }

            $success = $this->seatService->createSeat($_POST);

            if ($success) {
                setMessage('success', "Seat added successfully!");
            } else {
                setMessage('error', "Failed to add seat!");
            }

            redirect('seat');
        } catch (Exception $e) {
            setMessage('error', 'Error adding seat: ' . $e->getMessage());
            redirect('seat');
        }
    }

    public function edit($id)
    {
        try {
            $seat = $this->seatService->getSeatById((int) $id);

            if (!$seat) {
                throw new Exception('Invalid seat ID!');
            }

            $this->view('admin/seat/edit_seat', ['seats' => $seat]);
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('seat');
        }
    }

    public function update()
    {
        try {
            // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('admin/seat/edit_seat');
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('seat');
                return;
            }

            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new Exception('Invalid seat ID!');
            }

            $success = $this->seatService->updateSeat((int) $id, $_POST);

            if ($success) {
                setMessage('success', 'Seat updated successfully!');
            } else {
                throw new Exception('Failed to update seat!');
            }

            redirect('seat');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('seat');
        }
    }

    public function destroy($id)
    {
        try {
            $decodedId = base64_decode($id);
            $id = (int) filter_var($decodedId, FILTER_SANITIZE_NUMBER_INT);

            if (!$id) {
                throw new Exception('Invalid seat ID!');
            }

            $success = $this->seatService->deleteSeat($id);

            if ($success) {
                setMessage('success', 'Seat deleted successfully!');
            } else {
                throw new Exception('Failed to delete seat!');
            }

            redirect('seat');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('seat');
        }
    }
}
