<?php
require_once __DIR__ . '/../interface/BookingRepositoryInterface.php';

class BookingRepository implements BookingRepositoryInterface
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getDb()
    {
        return $this->db;
    }

    // Implement all interface methods exactly as before...

    public function findMovieWithDetails(int $movieId)
    {
        return $this->db->getById('view_movies_info', $movieId);
    }

    public function getAvgRating(int $movieId)
    {
        return $this->db->getAvgRatingByMovieId($movieId);
    }

    public function getSeats()
    {
        return $this->db->readAll('seats');
    }

    public function getShowTimeId(string $time)
    {
        return $this->db->getShowTimeIdByValue($time);
    }

    public function getBookingsByMovieDateShowtime(int $movieId, int $showTimeId, string $date)
    {
        return $this->db->getBookingsByMovieDateShowtime($movieId, $showTimeId, $date);
    }

    public function createBooking(array $data)
    {
        return $this->db->create('bookings', $data);
    }

    public function findBookingById(int $bookingId)
    {
        return $this->db->getById('bookings', $bookingId);
    }

    public function deleteBooking(int $bookingId)
    {
        return $this->db->delete('bookings', $bookingId);
    }

    public function updateStatus(int $bookingId, int $status)
    {
        return $this->db->updateStatus($bookingId, $status);
    }

    public function readPagedBookings(int $limit, int $offset)
    {
        return $this->db->readPaged('bookings', $limit, $offset);
    }

    public function readAllBookings()
    {
        return $this->db->readAll('bookings');
    }

    public function searchBookings(string $search, array $columns, int $limit, int $offset)
    {
        return $this->db->search('view_bookings_info', $columns, $search, $limit, $offset);
    }

    public function getBookingsByUser(int $userId)
    {
        return $this->db->getBookingsByUser($userId);
    }

    public function getPaymentByBookingId(int $bookingId)
    {
        return $this->db->columnFilter('payment_history', 'booking_id', $bookingId);
    }

    public function deletePaymentByBookingId(int $bookingId)
    {
        $this->db->query("DELETE FROM payment_history WHERE booking_id = :booking_id");
        $this->db->bind(':booking_id', $bookingId);
        return $this->db->stmt->execute();
    }

    public function getSeatNamesByIds(array $seatIds)
    {
        return $this->db->getSeatNamesByIds($seatIds);
    }

    public function getReadableSeatNames(array $booking)
    {
        return $this->db->getReadableSeatNames($booking);
    }

    public function getUserById(int $userId)
    {
        return $this->db->getById('users', $userId);
    }

    public function getMovieById(int $movieId)
    {
        return $this->db->getById('movies', $movieId);
    }

    public function getShowTimeById(int $showTimeId)
    {
        return $this->db->getById('show_times', $showTimeId);
    }    
}
