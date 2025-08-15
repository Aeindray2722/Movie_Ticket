<?php
require_once __DIR__ . '/../services/BookingService.php';
require_once __DIR__ . '/../repositories/BookingRepository.php';

class Booking extends Controller
{
    private BookingService $bookingService;

    public function __construct(?BookingService $bookingService = null)
    {
        // Allow injecting a BookingService instance for easier testing or customization
        if ($bookingService !== null) {
            $this->bookingService = $bookingService;
        } else {
            // Default behavior: create BookingRepository with Database, then BookingService
            $repository = new BookingRepository(new Database());
            $this->bookingService = new BookingService($repository);
        }
    }
    public function middleware()
    {
        return [
            'bookingDetail' => ['AdminMiddleware'],
            'bookingHistory' => ['AdminMiddleware'],
            'history' => ['CustomerMiddleware'],
        ];
    }

    public function index($id = null)
    {
        try {
            if ($id === null) {
                redirect('booking/bookingHistory');
                return;
            }

            $id = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);

            $movieData = $this->bookingService->getMovieWithDetails(
                $id,
                $_GET['date'] ?? null,
                $_GET['time'] ?? null
            );

            if (!$movieData) {
                redirect('movie/nowShowing');
                return;
            }

            $this->view('customer/booking/booking', $movieData);
        } catch (Exception $e) {
            error_log($e->getMessage());
            setMessage('error', 'An error occurred while loading booking page.');
            redirect('movie/nowShowing');
        }
    }

    public function store()
    {
        // var_dump($_POST); exit;
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('movie/nowShowing');
                return;
            }

            $user_id = $_SESSION['user_id'] ?? null;
            if (!$user_id) {
                setMessage('error', 'Please log in to book seats.');
                redirect('pages/login');
                return;
            }

            if ($this->bookingService->createBooking($_POST, $user_id)) {
                redirect('payment/payment');
                return;
            }

            $movie_id = $_POST['movie_id'] ?? '';
            redirect("booking/index/$movie_id");
        } catch (Exception $e) {
            error_log($e->getMessage());
            setMessage('error', 'Failed to process your booking. Please try again.');
            redirect('movie/nowShowing');
        }
    }

    public function bookingDetail($id = null)
    {
        try {
            if (!$id) {
                setMessage('error', 'Booking ID is missing.');
                redirect('booking/bookingHistory');
                return;
            }

            $detail = $this->bookingService->getBookingDetail((int) $id);
            if (!$detail) {
                setMessage('error', 'Booking not found.');
                redirect('booking/bookingHistory');
                return;
            }

            $this->view('admin/booking/booking_detail', $detail);
        } catch (Exception $e) {
            error_log($e->getMessage());
            setMessage('error', 'Unable to load booking details.');
            redirect('booking/bookingHistory');
        }
    }

    public function updateStatus()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }

            $booking_id = $_POST['booking_id'] ?? null;
            $status = $_POST['status'] ?? null;

            if ($booking_id === null || $status === null) {
                echo json_encode(['success' => false, 'message' => 'Invalid data']);
                exit;
            }

            $result = $this->bookingService->updateBookingStatus((int) $booking_id, (int) $status);
            echo json_encode($result);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'An error occurred while updating status']);
        }
        exit;
    }

    public function destroy($id)
    {
        try {
            $decodedId = base64_decode($id);
            $bookingId = (int) filter_var($decodedId, FILTER_SANITIZE_NUMBER_INT);

            if (!$bookingId) {
                redirect('booking');
                return;
            }

            if ($this->bookingService->deleteBooking($bookingId)) {
                redirect('booking');
            } else {
                setMessage('error', 'Failed to delete booking.');
                redirect('booking');
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            setMessage('error', 'An error occurred while deleting booking.');
            redirect('booking');
        }
    }

    public function bookingHistory()
    {
        try {
            $limit = 10;
            $page = max(1, (int) ($_GET['page'] ?? 1));
            $search = trim($_GET['search'] ?? '');

            $data = $this->bookingService->getBookingHistoryForAdmin($limit, $page, $search);

            $this->view('admin/booking/booking_history', $data);
        } catch (Exception $e) {
            error_log($e->getMessage());
            setMessage('error', 'Unable to load booking history.');
            redirect('dashboard');
        }
    }

    public function history()
    {
        try {
            $user_id = $_SESSION['user_id'] ?? null;
            if (!$user_id) {
                setMessage('error', 'Please log in first.');
                redirect('pages/login');
                return;
            }

            $bookings = $this->bookingService->getBookingHistoryForUser($user_id);

            $this->view('customer/booking/booking_history', ['bookings' => $bookings]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            setMessage('error', 'Unable to load your booking history.');
            redirect('movie/nowShowing');
        }
    }
    
}
