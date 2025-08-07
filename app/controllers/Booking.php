<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../services/CustomerFactory.php';
require_once __DIR__ . '/../services/CustomerService.php';

class Booking extends Controller
{
    private $db;
    private $bookingModel;

    public function __construct()
    {
        $this->db = new Database();
        $this->bookingModel = $this->model('BookingModel');

    }
    public function middleware()
    {
        return [
            'index' => ['CustomerMiddleware'],
            'store' => ['CustomerMiddleware'],
            'bookingDetail' => ['AdminMiddleware'],
            'bookingHistory' => ['AdminMiddleware'],
            'history' => ['CustomerMiddleware'],
            'booking' => ['CustomerMiddleware'],
            'destory' => ['AdminMiddleware'],
        ];
    }
    

    public function index($id = null)
    {
        if ($id === null) {
            // setMessage('error', 'Missing movie ID.');
            redirect('booking/bookingHistory');
            return;
        }
        $id = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $movie = $this->db->getById('view_movies_info', $id);

        if (!$movie) {
            // setMessage('error', 'Movie not found!');
            redirect('movie/nowShowing');
        }

        $today = new DateTime('today');
        $selected_date = $_GET['date'] ?? $today->format('Y-m-d');
        $selected_date_dt = DateTime::createFromFormat('Y-m-d', $selected_date);
        if (!$selected_date_dt || $selected_date_dt < $today) {
            $selected_date = $today->format('Y-m-d');
        }

        $selected_time_str = $_GET['time'] ?? explode(',', $movie['show_time_list'])[0];

        // Get average rating
        $avg_rating = $this->db->getAvgRatingByMovieId($id);

        // Date range
        $start = new DateTime($movie['start_date']);
        $end = (new DateTime($movie['end_date']))->modify('+1 day');
        $dateRange = new DatePeriod($start, new DateInterval('P1D'), $end);

        $show_times = explode(',', $movie['show_time_list']);
        $seats = $this->db->readAll('seats');

        $seat_price_map = [];
        $seats_grouped_by_row = [];
        foreach ($seats as $seat) {
            $seat_price_map[$seat['seat_row']] = $seat['price'];
            $seats_grouped_by_row[$seat['seat_row']][] = $seat['seat_number'];
        }

        // Get show_time_id
        $this->db->query("SELECT id FROM show_times WHERE show_time = :show_time LIMIT 1");
        $this->db->bind(':show_time', $selected_time_str);
        $this->db->stmt->execute();
        $showTimeRow = $this->db->stmt->fetch(PDO::FETCH_ASSOC);
        $show_time_id = $showTimeRow ? (int) $showTimeRow['id'] : 0;

        // Booked seats
        // get only active bookings for this movie, showtime, date
        $bookings = $this->db->getBookingsByMovieDateShowtime($id, $show_time_id, $selected_date);

        $booked_seat_ids = [];
        foreach ($bookings as $booking) {
            $seats_arr = json_decode($booking['seat_id'], true);
            if (is_array($seats_arr)) {
                $booked_seat_ids = array_merge($booked_seat_ids, $seats_arr);
            }
        }

        // Remove duplicates
        $booked_seat_ids = array_unique($booked_seat_ids);

        $data = [
            'movie' => $movie,
            'avg_rating' => $avg_rating,
            'date' => $dateRange,
            'show_times' => $show_times,
            'seat_price_map' => $seat_price_map,
            'seats_grouped_by_row' => $seats_grouped_by_row,
            'seats' => $seats,
            'booked_seat_ids' => $booked_seat_ids,
            'selected_date' => $selected_date,
            'selected_time' => $selected_time_str
        ];

        $this->view('customer/booking/booking', $data);
    }

    public function store()
    {
        // var_dump($_POST); exit;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('movie/nowShowing');
        }

        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            setMessage('error', 'Please log in to book seats.');
            redirect('pages/login');
        }

        $movie_id = $_POST['movie_id'] ?? null;
        $show_time_str = $_POST['show_time'] ?? null;
        $booking_date = $_POST['booking_date'] ?? null;
        $selected_seats = json_decode($_POST['selected_seats'] ?? '[]', true);
        if (!$movie_id || !$show_time_str || !$booking_date || empty($selected_seats)) {
            // setMessage('error', 'Invalid booking data.');
            redirect('movie/nowShowing');
        }

        // Get show_time_id
        $this->db->query("SELECT id FROM show_times WHERE show_time = :show_time LIMIT 1");
        $this->db->bind(':show_time', $show_time_str);
        $this->db->stmt->execute();
        $showTimeRow = $this->db->stmt->fetch(PDO::FETCH_ASSOC);
        $show_time_id = $showTimeRow ? (int) $showTimeRow['id'] : 0;

        // Get all seats
        $seats = $this->db->readAll('seats');
        $seat_ids = [];
        // 1. Create base user and set ID
        $user = new UserModel();
        $user->setId($user_id);

        // 2. Initialize CustomerService with DB dependency
        $customerService = new CustomerService($this->db);

        // 3. Update customer type in DB (VIP or Normal)
        $customerService->updateCustomerType($user);

        // 4. Get the current customer object (VIPCustomer or NormalCustomer)
        $customer = CustomerFactory::create($user, $this->db);

        // 5. Get customer type from the object
        $customerType = $customer->getCustomerType();

        // 6. Calculate total amount (you can implement your logic here)
        $total_amount = 0;

        foreach ($selected_seats as $seatCode) {
            $row = substr($seatCode, 0, 1);
            $number = substr($seatCode, 1);
            foreach ($seats as $seat) {
                if ($seat['seat_row'] === $row && $seat['seat_number'] == $number) {
                    $seat_ids[] = $seat['id'];
                    $total_amount += (int) $seat['price'];
                    break;
                }
            }
        }
        if ($customerType === 'VIP') {
            $total_amount = $total_amount * 0.95; // 5% discount
        }

        if (empty($seat_ids)) {
            // setMessage('error', 'Selected seats are invalid.');
            redirect("booking/index/$movie_id");
        }

        // Set data to model
        $this->bookingModel->setUserId($user_id);
        $this->bookingModel->setMovieId($movie_id);
        $this->bookingModel->setShowTimeId($show_time_id);
        $this->bookingModel->setSeatId(json_encode($seat_ids));
        $this->bookingModel->setStatus(1);
        $this->bookingModel->setTotalAmount($total_amount);
        $this->bookingModel->setBookingDate($booking_date);
        $this->bookingModel->setCreatedAt(date('Y-m-d H:i:s'));
        $this->bookingModel->setUpdatedAt(date('Y-m-d H:i:s'));

        $data = $this->bookingModel->toArray();
        // var_dump($data); exit;

        if ($this->db->create('bookings', $data)) {
            redirect('payment/payment');
        } else {
            // setMessage('error', 'Booking failed, try again.');
            redirect("booking/index/$movie_id");
        }
    }

    public function bookingDetail($id = null)
    {
        if (!$id) {
            setMessage('error', 'Booking ID is missing.');
            redirect('booking/bookingHistory');
            return;
        }

        $id = (int) $id;

        // Get booking
        $booking = $this->db->getById('bookings', $id);
        if (!$booking) {
            setMessage('error', 'Booking not found.');
            redirect('booking/bookingHistory');
            return;
        }

        // Get related movie from view_movies_info
        $movie = $this->db->getById('view_movies_info', $booking['movie_id'] ?? 0);

        // Get payment slip image
        $payment = $this->db->columnFilter('payment_history', 'booking_id', $id);

        // Get readable seat names from seat_id JSON
        $seatIds = json_decode($booking['seat_id'], true);
        $seatNames = $this->db->getSeatNamesByIds($seatIds); // You must have this custom function

        // Final data
        $data = [
            'bookings' => $booking,
            'movies' => $movie,
            'payment' => $payment,
            'seats' => $seatNames
        ];
        // var_dump($data['seats']); exit;
        $this->view('admin/booking/booking_detail', $data);
    }


    public function bookingHistory()
    {
        $limit = 10; // number per page
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1)
            $page = 1;

        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $searchColumns = ['movie_name', 'name', 'booking_date', 'status', 'total_amount', 'seat_row', 'seat_number', 'show_time_list'];
        // Fetch raw booking rows
        if (!empty($search)) {
            $results = $this->db->search('view_bookings_info', $searchColumns, $search, 1000, 0); // Get all for grouping
            $rawBookings = $results['data'];
        } else {
            $rawBookings = $this->db->readAll('view_bookings_info');
        }
        // Get paged bookings only
        $bookings = $this->db->readPaged('bookings', $limit, $offset);

        // Get total count for pagination
        $totalRecords = count($this->db->readAll('bookings'));
        $totalPages = ceil($totalRecords / $limit);

        // Gather all seat IDs from paged bookings only
        $allSeatIds = [];
        foreach ($bookings as $booking) {
            $seat_ids = json_decode($booking['seat_id'], true);
            if (is_array($seat_ids)) {
                $allSeatIds = array_merge($allSeatIds, $seat_ids);
            }
        }

        // Get seat names map for seat IDs
        $seatMap = $this->db->getSeatNamesByIds($allSeatIds);

        // Attach related info only for paged bookings
        foreach ($bookings as &$booking) {
            // Movie name
            $movie = $this->db->getById('movies', $booking['movie_id']);
            $booking['movie_name'] = $movie['movie_name'] ?? 'Unknown';

            // User name
            $user = $this->db->getById('users', $booking['user_id']);
            $booking['user_name'] = $user['name'] ?? 'Unknown';

            // Seat names list
            $seat_ids = json_decode($booking['seat_id'], true);
            $seat_names = [];
            foreach ($seat_ids as $sid) {
                $seat_names[] = $seatMap[$sid] ?? "Unknown";
            }
            $booking['seat_names'] = implode(', ', $seat_names);

            // Show time
            $showTime = $this->db->getById('show_times', $booking['show_time_id']);
            $booking['show_time'] = $showTime['show_time'] ?? 'Unknown';
        }

        $data = [
            'bookings' => $bookings,
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];

        $this->view('admin/booking/booking_history', $data);
    }


    public function history()
    {
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            setMessage('error', 'Please log in first.');
            redirect('pages/login');
            return;
        }

        // Load bookings for this user
        $bookings = $this->db->getBookingsByUser($user_id);
        // Collect all seat IDs from all bookings
        $allSeatIds = [];
        $allSeatIds = array_filter($allSeatIds, fn($id) => is_numeric($id));
        $allSeatIds = array_values($allSeatIds);
        foreach ($bookings as $booking) {
            $seat_ids = json_decode($booking['seat_id'], true);
            if (is_array($seat_ids)) {
                $allSeatIds = array_merge($allSeatIds, $seat_ids);
            }
        }

        // Remove duplicates
        $uniqueSeatIds = array_unique($allSeatIds);

        // Get seat names for all IDs using helper method
        $seatMap = $this->db->getSeatNamesByIds($uniqueSeatIds);

        // Attach movie name and readable seat list to each booking
        foreach ($bookings as &$booking) {
            // Get movie name
            $movie = $this->db->getById('movies', $booking['movie_id']);
            $booking['movie_name'] = $movie['movie_name'] ?? 'Unknown';

            // Get readable seat names
            $seat_ids = json_decode($booking['seat_id'], true);
            $seat_names = [];
            foreach ($seat_ids as $sid) {
                $seat_names[] = $seatMap[$sid] ?? "Unknown";
            }
            $booking['seat_names'] = $seat_names;
        }

        $data = [
            'bookings' => $bookings
        ];

        $this->view('customer/booking/booking_history', $data);

    }
    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booking_id = $_POST['booking_id'] ?? null;
            $status = $_POST['status'] ?? null;

            if ($booking_id === null || $status === null) {
                echo json_encode(['success' => false, 'message' => 'Invalid data']);
                exit;
            }

            $booking_id = (int) $booking_id;
            $status = (int) $status;

            // Booking 
            $booking = $this->db->getById('bookings', $booking_id);
            if (!$booking) {
                echo json_encode(['success' => false, 'message' => 'Booking not found']);
                exit;
            }

            // change status
            $result = $this->db->updateStatus($booking_id, $status);
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Update failed']);
            }
            exit;
        }
    }

    public function booking()
    {
        $this->view('customer/booking/booking');
    }
    public function destroy($id)
    {
        $decodedId = base64_decode($id);
        $id = (int) filter_var($decodedId, FILTER_SANITIZE_NUMBER_INT);

        if (!$id) {
            // setMessage('error', 'Invalid booking ID.');
            redirect('booking');
            return;
        }

        // Step 1: Get the booking record
        $booking = $this->db->getById('bookings', $id);
        if (!$booking) {
            // setMessage('error', 'Booking not found!');
            redirect('booking');
            return;
        }

        // Step 2: Get the corresponding payment using booking_id
        $payment = $this->db->columnFilter('payment_history', 'booking_id', $id);
        if ($payment) {
            // ✅ Step 3: Use correct image column name
            $paymentImg = $payment['payslip_image'] ?? '';
            $imagePath = __DIR__ . '/../../public/images/payslips/' . $paymentImg;

            if (!empty($paymentImg) && file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Step 4: Delete the payment record
            $this->db->query("DELETE FROM payment_history WHERE booking_id = :booking_id");
            $this->db->bind(':booking_id', $id);
            $this->db->stmt->execute();
        }

        // Step 5: Delete the booking itself
        $isDeleted = $this->db->delete('bookings', $id);

        if ($isDeleted) {
            // setMessage('success', 'Booking and related payment deleted successfully!');
        } else {
            // setMessage('error', 'Failed to delete booking.'); 
        }

        redirect('booking');
    }





}
