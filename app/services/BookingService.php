<?php
require_once __DIR__ . '/../repositories/BookingRepository.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../services/CustomerFactory.php';
require_once __DIR__ . '/../services/CustomerService.php';
require_once __DIR__ . '/../libraries/Mail.php';

class BookingService
{
    // private $repo;
    private BookingRepositoryInterface $repo;
    private $bookingModel;

    public function __construct(BookingRepositoryInterface $repo)
    {
        $this->repo = $repo;
        $this->bookingModel = new BookingModel();
    }

    // public function __construct(Database $db)
    // {

    //     $this->repo = new BookingRepository($db);
    //     $this->bookingModel = new BookingModel();
    // }


    public function getMovieWithDetails(int $movieId, string $selectedDate = null, string $selectedTime = null)
    {
        $movie = $this->repo->findMovieWithDetails($movieId);
        if (!$movie)
            return null;

        $today = new DateTime('today');

        // Default: today
        $selected_date = $selectedDate ?: $today->format('Y-m-d');
        $selected_date_dt = DateTime::createFromFormat('Y-m-d', $selected_date);

        // If date invalid or earlier than today, fix it
        if (!$selected_date_dt || $selected_date_dt < $today) {
            $selected_date = $today->format('Y-m-d');
        }

        // 🚨 Check if today's showtimes are already over
        if ($selected_date === $today->format('Y-m-d')) {
            $allShowTimes = explode(',', $movie['show_time_list']);
            $now = new DateTime();

            // Find if any showtime left today
            $futureShowExists = false;
            foreach ($allShowTimes as $showTime) {
                $showDt = DateTime::createFromFormat('Y-m-d H:i', $today->format('Y-m-d') . ' ' . $showTime);
                if ($showDt && $showDt > $now) {
                    $futureShowExists = true;
                    break;
                }
            }

            // If no future showtime today, shift to tomorrow
            if (!$futureShowExists) {
                $selected_date = $today->modify('+1 day')->format('Y-m-d');
            }
        }


        $show_times = explode(',', $movie['show_time_list']);
        $selected_time_str = $selectedTime ?: ($show_times[0] ?? '');

        $avg_rating = $this->repo->getAvgRating($movieId);

        $start = new DateTime($movie['start_date']);
        $end = (new DateTime($movie['end_date']))->modify('+1 day');
        $dateRange = new DatePeriod($start, new DateInterval('P1D'), $end);

        $seats = $this->repo->getSeats();
        $seat_price_map = [];
        $seats_grouped_by_row = [];
        foreach ($seats as $seat) {
            $seat_price_map[$seat['seat_row']] = $seat['price'];
            $seats_grouped_by_row[$seat['seat_row']][] = $seat['seat_number'];
        }

        $show_time_id = $this->repo->getShowTimeId($selected_time_str);
        $bookings = $this->repo->getBookingsByMovieDateShowtime($movieId, $show_time_id, $selected_date);

        $booked_seat_ids = [];
        foreach ($bookings as $booking) {
            $seats_arr = json_decode($booking['seat_id'], true);
            if (is_array($seats_arr)) {
                $booked_seat_ids = array_merge($booked_seat_ids, $seats_arr);
            }
        }
        $booked_seat_ids = array_unique($booked_seat_ids);

        return [
            'movie' => $movie,
            'avg_rating' => $avg_rating,
            'date' => $dateRange,
            'show_times' => $show_times,
            'seat_price_map' => $seat_price_map,
            'seats_grouped_by_row' => $seats_grouped_by_row,
            'booked_seat_ids' => $booked_seat_ids,
            'selected_date' => $selected_date,
            'seats' => $seats,
            'selected_time' => $selected_time_str,
        ];
    }

    public function createBooking(array $postData, int $userId): bool
    {
        $movie_id = $postData['movie_id'] ?? null;
        $show_time_str = $postData['show_time'] ?? null;
        $booking_date = $postData['booking_date'] ?? null;
        $selected_seats = json_decode($postData['selected_seats'] ?? '[]', true);

        if (!$movie_id || !$show_time_str || !$booking_date || empty($selected_seats)) {
            return false;
        }

        $show_time_id = $this->repo->getShowTimeId($show_time_str);
        $seats = $this->repo->getSeats();

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
            return false;
        }

        $user = new UserModel();
        $user->setId($userId);
        $customerService = new CustomerService($this->repo->getDb());
        // $customerService = new CustomerService($this->repo->getdb);
        // $customerService = new CustomerService($this->repo->db);

        $customerService->updateCustomerType($user);

        $customer = CustomerFactory::create($user, $this->repo->getdb());
        if ($customer->getCustomerType() === 'VIP') {
            $total_amount *= 0.95; // 5% discount
        }

        $this->bookingModel->setUserId($userId);
        $this->bookingModel->setMovieId($movie_id);
        $this->bookingModel->setShowTimeId($show_time_id);
        $this->bookingModel->setSeatId(json_encode($seat_ids));
        $this->bookingModel->setStatus(1);
        $this->bookingModel->setTotalAmount($total_amount);
        $this->bookingModel->setBookingDate($booking_date);
        $this->bookingModel->setCreatedAt(date('Y-m-d H:i:s'));
        $this->bookingModel->setUpdatedAt(date('Y-m-d H:i:s'));

        return $this->repo->createBooking($this->bookingModel->toArray());
    }

    public function getBookingDetail(int $bookingId)
    {
        $booking = $this->repo->findBookingById($bookingId);
        if (!$booking)
            return null;

        $movie = $this->repo->findMovieWithDetails($booking['movie_id'] ?? 0);
        $payment = $this->repo->getPaymentByBookingId($bookingId);
        $seatIds = json_decode($booking['seat_id'], true);
        $seatNames = $this->repo->getSeatNamesByIds($seatIds);

        return [
            'booking' => $booking,
            'movie' => $movie,
            'payment' => $payment,
            'seats' => $seatNames,
        ];
    }

    public function updateBookingStatus(int $bookingId, int $status): array
    {
        $booking = $this->repo->findBookingById($bookingId);
        if (!$booking) {
            return ['success' => false, 'message' => 'Booking not found'];
        }

        if ($this->repo->updateStatus($bookingId, $status)) {
            if ($status === 0) {
                $user = $this->repo->getUserById($booking['user_id']);
                $movie = $this->repo->getMovieById($booking['movie_id']);
                $showTime = $this->repo->getShowTimeById($booking['show_time_id']);
                $seatIds = json_decode($booking['seat_id'], true);
                $seatNames = $this->repo->getSeatNamesByIds($seatIds);
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

                (new Mail())->sendMail($to, $subject, $body);
            }
            return ['success' => true];
        }
        return ['success' => false, 'message' => 'Update failed'];
    }

    public function deleteBooking(int $bookingId)
    {
        $payment = $this->repo->getPaymentByBookingId($bookingId);
        if ($payment) {
            $paymentImg = $payment['payslip_image'] ?? '';
            $imagePath = __DIR__ . '/../../public/images/payslips/' . $paymentImg;

            if (!empty($paymentImg) && file_exists($imagePath)) {
                unlink($imagePath);
            }
            $this->repo->deletePaymentByBookingId($bookingId);
        }

        return $this->repo->deleteBooking($bookingId);
    }

    public function getBookingHistoryForAdmin(
        ?int $limit = 10,
        ?int $page = 1,
        string $search = '',
        array $dateRange = []
    ) {
        $searchColumns = ['movie_name', 'user_name', 'booking_date', 'status', 'total_amount', 'seat_row', 'seat_number', 'show_time'];

        // 1️⃣ Get all bookings
        $allResults = $this->repo->readAllBookings();

        // 2️⃣ Enrich bookings
        foreach ($allResults as &$booking) {
            $movie = $this->repo->getMovieById($booking['movie_id']);
            $user = $this->repo->getUserById($booking['user_id']);
            $showTime = $this->repo->getShowTimeById($booking['show_time_id']);

            $booking['movie_name'] = $movie['movie_name'] ?? 'Unknown';
            $booking['user_name'] = $user['name'] ?? 'Unknown';
            $booking['seat_names'] = implode(', ', $this->repo->getReadableSeatNames($booking));
            $booking['show_time'] = $showTime['show_time'] ?? 'Unknown';
        }
        unset($booking);

        // 3️⃣ Apply search filter if provided
        if (!empty($search)) {
            $allResults = array_filter($allResults, function ($booking) use ($search, $searchColumns) {
                foreach ($searchColumns as $col) {
                    if (isset($booking[$col]) && stripos((string) $booking[$col], $search) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        // 4️⃣ Apply date range filter
        $start = $dateRange['start'] ?? $dateRange['start_date'] ?? null;
        $end = $dateRange['end'] ?? $dateRange['end_date'] ?? null;

        if ($start || $end) {
            $allResults = array_filter($allResults, function ($booking) use ($start, $end) {
                $bookingTime = strtotime($booking['booking_date']);
                $startTime = $start ? strtotime($start) : null;
                $endTime = $end ? strtotime($end . ' 23:59:59') : null;

                if ($startTime && $endTime) {
                    return $bookingTime >= $startTime && $bookingTime <= $endTime;
                } elseif ($startTime) {
                    return $bookingTime >= $startTime;
                } elseif ($endTime) {
                    return $bookingTime <= $endTime;
                }
                return true;
            });
        }

        // 5️⃣ Reset array keys
        $allResults = array_values($allResults);

        // 6️⃣ If limit or page is null, return all results (for export)
        if ($limit === null || $page === null) {
            return $allResults;
        }

        // 7️⃣ Pagination
        $totalRecords = count($allResults);
        $totalPages = max(1, ceil($totalRecords / $limit));

        // Reset page to 1 if requested page exceeds total pages
        if ($page > $totalPages) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $bookings = array_slice($allResults, $offset, $limit);

        return [
            'bookings' => $bookings,
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'dateRange' => ['start' => $start, 'end' => $end],
            'totalRecords' => $totalRecords
        ];
    }



    public function getBookingHistoryForUser(int $userId)
    {
        $bookings = $this->repo->getBookingsByUser($userId);

        foreach ($bookings as &$booking) {
            $booking['seat_names'] = $this->repo->getReadableSeatNames($booking);
        }
        unset($booking);

        return $bookings;
    }


}
