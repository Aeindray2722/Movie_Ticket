<?php
// app/interface/BookingRepositoryInterface.php

interface BookingRepositoryInterface
{
    // Access underlying DB object if needed
    public function getDb();

    // Movie / Seats / Showtimes
    public function findMovieWithDetails(int $movieId);
    public function getAvgRating(int $movieId);
    public function getSeats();
    public function getShowTimeId(string $time);
    public function getBookingsByMovieDateShowtime(int $movieId, int $showTimeId, string $date);

    // Bookings
    public function createBooking(array $data);
    public function findBookingById(int $bookingId);
    public function deleteBooking(int $bookingId);
    public function updateStatus(int $bookingId, int $status);
    public function readPagedBookings(int $limit, int $offset);
    public function readAllBookings();
    public function searchBookings(string $search, array $columns, int $limit, int $offset);
    public function getBookingsByUser(int $userId);

    // Payments
    public function getPaymentByBookingId(int $bookingId);
    public function deletePaymentByBookingId(int $bookingId);

    // Utility
    public function getSeatNamesByIds(array $seatIds);
    public function getReadableSeatNames(array $booking);
    public function getUserById(int $userId);
    public function getMovieById(int $movieId);
    public function getShowTimeById(int $showTimeId);
}
