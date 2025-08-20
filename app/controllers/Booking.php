<?php
require_once __DIR__ . '/../services/BookingService.php';
require_once __DIR__ . '/../repositories/BookingRepository.php';

class Booking extends Controller
{
    private BookingService $bookingService;

    public function __construct(?BookingService $bookingService = null)
    {
        // CSRF token generation
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
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
            // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('customer/booking/booking');
                exit;
            }
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
                redirect('booking/bookingHistory');
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

            // Get filter values from GET
            $search = trim($_GET['search'] ?? '');
            $start_date = $_GET['start_date'] ?? '';
            $end_date = $_GET['end_date'] ?? '';

            // Prepare date range array
            $dateRange = [];
            if ($start_date)
                $dateRange['start'] = $start_date;
            if ($end_date)
                $dateRange['end'] = $end_date;

            // Fetch filtered & paginated bookings
            $data = $this->bookingService->getBookingHistoryForAdmin($limit, $page, $search, $dateRange);

            // Pass current filters to view for pagination links
            $data['search'] = $search;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;

            // Now $page and $totalPages exist in $data
            $this->view('admin/booking/booking_history', $data);

        } catch (Exception $e) {
            error_log($e->getMessage());
            setMessage('error', 'Unable to load booking history.');
            redirect('dashboard');
        }

    }

    public function export()
    {
        try {
            // 1️⃣ Get filters from GET
            $search = trim($_GET['search'] ?? '');
            $start_date = $_GET['start_date'] ?? '';
            $end_date = $_GET['end_date'] ?? '';

            $dateRange = [];
            if ($start_date)
                $dateRange['start'] = $start_date;
            if ($end_date)
                $dateRange['end'] = $end_date;

            // 2️⃣ Fetch all filtered bookings (no pagination for export)
            $bookings = $this->bookingService->getBookingHistoryForAdmin(null, null, $search, $dateRange);

            // CSV headers
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="bookings_' . date('Y-m-d_H-i-s') . '.csv"');

            // Add UTF-8 BOM at the start
            echo "\xEF\xBB\xBF";

            $output = fopen('php://output', 'w');

            // Column headers
            fputcsv($output, ['User Name', 'Movie Name', 'Showtime', 'Date', 'Seat', 'Total Amount', 'Status']);

            // Your loop
            foreach ($bookings as $b) {
                fputcsv($output, [
                    $b['user_name'] ?? '',
                    $b['movie_name'] ?? '',
                    $b['show_time'] ?? '',
                    $b['booking_date'] ?? '',
                    $b['seat_names'] ?? '',
                    $b['total_amount'] ?? '',
                    match ($b['status'] ?? 0) {
                        0 => 'Confirmed',
                        1 => 'Pending',
                        2 => 'Cancelled',
                        default => 'Unknown',
                    },
                ]);
            }

            fclose($output);
            exit;


        } catch (Exception $e) {
            error_log($e->getMessage());
            setMessage('error', 'Failed to export bookings.');
            redirect('booking/bookingHistory');
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
