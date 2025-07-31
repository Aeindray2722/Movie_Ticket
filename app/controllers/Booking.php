<?php

class Booking extends Controller
{
    private $db;
    private $bookingModel;

    public function __construct()
    {
        $this->db = new Database();
        $this->bookingModel = $this->model('BookingModel');
    }

    public function index($id)
    {
        $id = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $movie = $this->db->getById('view_movies_info', $id);

        if (!$movie) {
            setMessage('error', 'Movie not found!');
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
        $avg_rating=$this->db->getAvgRatingByMovieId($id);

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
        $bookings = $this->db->getBookingsByMovieDateShowtime($id, $show_time_id, $selected_date);
        $booked_seat_ids = [];
        foreach ($bookings as $booking) {
            $seats_arr = json_decode($booking['seat_id'], true);
            if (is_array($seats_arr)) {
                $booked_seat_ids = array_merge($booked_seat_ids, $seats_arr);
            }
        }

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
            setMessage('error', 'Invalid booking data.');
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

        if (empty($seat_ids)) {
            setMessage('error', 'Selected seats are invalid.');
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

        if ($this->db->create('bookings', $data)) {
            redirect('payment/payment');
        } else {
            setMessage('error', 'Booking failed, try again.');
            redirect("booking/index/$movie_id");
        }
    }

    public function bookingHistory()
    {
        $this->view('admin/booking/booking_history');
    }

    public function bookingDetail()
    {
        $this->view('admin/booking/booking_detail');
    }

    public function history()
    {
        $this->view('customer/booking/booking_history');
    }

    public function booking()
    {
        $this->view('customer/booking/booking');
    }
}
