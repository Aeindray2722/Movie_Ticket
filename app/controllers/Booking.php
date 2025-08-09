<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../services/CustomerFactory.php';
require_once __DIR__ . '/../services/CustomerService.php';
require_once __DIR__ . '/../libraries/Mail.php';
require_once __DIR__ . '/../../vendor/autoload.php';

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
            'index'          => ['CustomerMiddleware'],
            'store'          => ['CustomerMiddleware'],
            'bookingDetail'  => ['AdminMiddleware'],
            'bookingHistory' => ['AdminMiddleware'],
            'history'        => ['CustomerMiddleware'],
            'booking'        => ['CustomerMiddleware'],
            'destroy'        => ['AdminMiddleware'],
            'updateStatus'   => ['AdminMiddleware'],
        ];
    }

    public function index($id = null)
    {
        if ($id === null) {
            redirect('booking/bookingHistory');
            return;
        }

        $id = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $movie = $this->db->getById('view_movies_info', $id);

        if (!$movie) {
            redirect('movie/nowShowing');
            return;
        }

        $today = new DateTime('today');
        $selected_date = $_GET['date'] ?? $today->format('Y-m-d');
        $selected_date_dt = DateTime::createFromFormat('Y-m-d', $selected_date);
        if (!$selected_date_dt || $selected_date_dt < $today) {
            $selected_date = $today->format('Y-m-d');
        }

        $show_times = explode(',', $movie['show_time_list']);
        $selected_time_str = $_GET['time'] ?? $show_times[0] ?? '';

        $avg_rating = $this->db->getAvgRatingByMovieId($id);

        $start = new DateTime($movie['start_date']);
        $end = (new DateTime($movie['end_date']))->modify('+1 day');
        $dateRange = new DatePeriod($start, new DateInterval('P1D'), $end);

        $seats = $this->db->readAll('seats');

        $seat_price_map = [];
        $seats_grouped_by_row = [];
        foreach ($seats as $seat) {
            $seat_price_map[$seat['seat_row']] = $seat['price'];
            $seats_grouped_by_row[$seat['seat_row']][] = $seat['seat_number'];
        }

        $show_time_id = $this->db->getShowTimeIdByValue($selected_time_str);

        $bookings = $this->db->getBookingsByMovieDateShowtime($id, $show_time_id, $selected_date);

        $booked_seat_ids = [];
        foreach ($bookings as $booking) {
            $seats_arr = json_decode($booking['seat_id'], true);
            if (is_array($seats_arr)) {
                $booked_seat_ids = array_merge($booked_seat_ids, $seats_arr);
            }
        }
        $booked_seat_ids = array_unique($booked_seat_ids);

        $data = [
            'movie'             => $movie,
            'avg_rating'        => $avg_rating,
            'date'              => $dateRange,
            'show_times'        => $show_times,
            'seat_price_map'    => $seat_price_map,
            'seats_grouped_by_row' => $seats_grouped_by_row,
            'seats'             => $seats,
            'booked_seat_ids'   => $booked_seat_ids,
            'selected_date'     => $selected_date,
            'selected_time'     => $selected_time_str,
        ];

        $this->view('customer/booking/booking', $data);
    }

    public function store()
    {
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

        $movie_id = $_POST['movie_id'] ?? null;
        $show_time_str = $_POST['show_time'] ?? null;
        $booking_date = $_POST['booking_date'] ?? null;
        $selected_seats = json_decode($_POST['selected_seats'] ?? '[]', true);

        if (!$movie_id || !$show_time_str || !$booking_date || empty($selected_seats)) {
            redirect('movie/nowShowing');
            return;
        }

        $show_time_id = $this->db->getShowTimeIdByValue($show_time_str);
        $seats = $this->db->readAll('seats');

        // Build seat map for quick lookup
        $seatMap = [];
        foreach ($seats as $seat) {
            $seatMap[$seat['seat_row'] . $seat['seat_number']] = $seat;
        }

        $seat_ids = [];
        $total_amount = 0;

        foreach ($selected_seats as $seatCode) {
            if (isset($seatMap[$seatCode])) {
                $seat = $seatMap[$seatCode];
                $seat_ids[] = $seat['id'];
                $total_amount += (int) $seat['price'];
            }
        }

        if (empty($seat_ids)) {
            redirect("booking/index/$movie_id");
            return;
        }

        $user = new UserModel();
        $user->setId($user_id);

        $customerService = new CustomerService($this->db);
        $customerService->updateCustomerType($user);

        $customer = CustomerFactory::create($user, $this->db);
        if ($customer->getCustomerType() === 'VIP') {
            $total_amount *= 0.95; // 5% discount
        }

        $this->bookingModel->setUserId($user_id);
        $this->bookingModel->setMovieId($movie_id);
        $this->bookingModel->setShowTimeId($show_time_id);
        $this->bookingModel->setSeatId(json_encode($seat_ids));
        $this->bookingModel->setStatus(1);
        $this->bookingModel->setTotalAmount($total_amount);
        $this->bookingModel->setBookingDate($booking_date);
        $this->bookingModel->setCreatedAt(date('Y-m-d H:i:s'));
        $this->bookingModel->setUpdatedAt(date('Y-m-d H:i:s'));

        if ($this->db->create('bookings', $this->bookingModel->toArray())) {
            redirect('payment/payment');
            return;
        }

        redirect("booking/index/$movie_id");
    }

    public function bookingDetail($id = null)
    {
        if (!$id) {
            setMessage('error', 'Booking ID is missing.');
            redirect('booking/bookingHistory');
            return;
        }

        $id = (int) $id;
        $booking = $this->db->getById('bookings', $id);

        if (!$booking) {
            setMessage('error', 'Booking not found.');
            redirect('booking/bookingHistory');
            return;
        }

        $movie = $this->db->getById('view_movies_info', $booking['movie_id'] ?? 0);
        $payment = $this->db->columnFilter('payment_history', 'booking_id', $id);
        $seatIds = json_decode($booking['seat_id'], true);
        $seatNames = $this->db->getSeatNamesByIds($seatIds);

        $data = [
            'bookings' => $booking,
            'movies'   => $movie,
            'payment'  => $payment,
            'seats'    => $seatNames,
        ];

        $this->view('admin/booking/booking_detail', $data);
    }

    public function bookingHistory()
    {
        $limit = 10;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $search = trim($_GET['search'] ?? '');
        $searchColumns = ['movie_name', 'name', 'booking_date', 'status', 'total_amount', 'seat_row', 'seat_number', 'show_time_list'];

        if ($search !== '') {
            $results = $this->db->search('view_bookings_info', $searchColumns, $search, 1000, 0);
            $rawBookings = $results['data'];
        } else {
            $rawBookings = $this->db->readAll('view_bookings_info');
        }

        $bookings = $this->db->readPaged('bookings', $limit, $offset);
        $totalRecords = count($this->db->readAll('bookings'));
        $totalPages = ceil($totalRecords / $limit);

        $allSeatIds = [];
        foreach ($bookings as $booking) {
            $seat_ids = json_decode($booking['seat_id'], true);
            if (is_array($seat_ids)) {
                $allSeatIds = array_merge($allSeatIds, $seat_ids);
            }
        }
        $seatMap = $this->db->getSeatNamesByIds($allSeatIds);

        foreach ($bookings as &$booking) {
            $movie = $this->db->getById('movies', $booking['movie_id']);
            $user = $this->db->getById('users', $booking['user_id']);
            $showTime = $this->db->getById('show_times', $booking['show_time_id']);

            $booking['movie_name'] = $movie['movie_name'] ?? 'Unknown';
            $booking['user_name'] = $user['name'] ?? 'Unknown';
            $booking['seat_names'] = implode(', ', $this->db->getReadableSeatNames($booking));
            $booking['show_time'] = $showTime['show_time'] ?? 'Unknown';
        }
        unset($booking);

        $data = [
            'bookings'   => $bookings,
            'page'       => $page,
            'totalPages' => $totalPages,
            'search'     => $search,
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

        $bookings = $this->db->getBookingsByUser($user_id);

        $allSeatIds = [];
        foreach ($bookings as $booking) {
            $seat_ids = json_decode($booking['seat_id'], true);//[1,2]
            if (is_array($seat_ids)) {
                $allSeatIds = array_merge($allSeatIds, $seat_ids);
            }
        }
        $allSeatIds = array_unique(array_filter($allSeatIds, fn($id) => is_numeric($id)));

        $seatMap = $this->db->getSeatNamesByIds($allSeatIds);

        foreach ($bookings as &$booking) {
            $movie = $this->db->getById('movies', $booking['movie_id']);
            $booking['movie_name'] = $movie['movie_name'] ?? 'Unknown';
            $booking['seat_names'] = $this->db->getReadableSeatNames($booking);
        }
        unset($booking);

        $data = ['bookings' => $bookings];

        $this->view('customer/booking/booking_history', $data);
    }

    public function updateStatus()
    {
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

        $booking_id = (int) $booking_id;
        $status = (int) $status;

        $booking = $this->db->getById('bookings', $booking_id);
        if (!$booking) {
            echo json_encode(['success' => false, 'message' => 'Booking not found']);
            exit;
        }

        if ($this->db->updateStatus($booking_id, $status)) {
            if ($status === 0) { // confirmed
                $user = $this->db->getById('users', $booking['user_id']);
                $movie = $this->db->getById('movies', $booking['movie_id']);
                $showTime = $this->db->getById('show_times', $booking['show_time_id']);
                $seatIds = json_decode($booking['seat_id'], true);
                $seatNames = $this->db->getSeatNamesByIds($seatIds);
                $seatList = implode(', ', array_values($seatNames));

                $to = $user['email'];
                $subject = 'Your Movie Booking is Confirmed!';
                $body = "
                    <p>Dear <strong>{$user['name']}</strong>,</p>
                    <p>Your booking has been <span style='color:green; font-weight:bold;'>confirmed</span> successfully!</p>
                    <ul>
                        <li><strong>Movie:</strong> {$movie['movie_name']}</li>
                        <li><strong>Show Time:</strong> {$showTime['show_time']}</li>
                        <li><strong>Booking Date:</strong> {$booking['booking_date']}</li>
                        <li><strong>Seats:</strong> {$seatList}</li>
                        <li><strong>Total Amount:</strong> {$booking['total_amount']} MMK</li>
                        <li><strong>Status:</strong> Confirmed</li>
                    </ul>
                    <p>Thank you for choosing <strong>Central Cinema</strong>!</p>
                ";

                $mail = new Mail();
                $mail->sendMail($to, $subject, $body);
            }

            echo json_encode(['success' => true]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Update failed']);
        exit;
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
            redirect('booking');
            return;
        }

        $booking = $this->db->getById('bookings', $id);
        if (!$booking) {
            redirect('booking');
            return;
        }

        $payment = $this->db->columnFilter('payment_history', 'booking_id', $id);

        if ($payment) {
            $paymentImg = $payment['payslip_image'] ?? '';
            $imagePath = __DIR__ . '/../../public/images/payslips/' . $paymentImg;

            if (!empty($paymentImg) && file_exists($imagePath)) {
                unlink($imagePath);
            }

            $this->db->query("DELETE FROM payment_history WHERE booking_id = :booking_id");
            $this->db->bind(':booking_id', $id);
            $this->db->stmt->execute();
        }

        $this->db->delete('bookings', $id);
        redirect('booking');
    }
}
