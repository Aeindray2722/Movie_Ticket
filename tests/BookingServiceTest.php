<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

use PHPUnit\Framework\TestCase;

// Adjust these paths according to your project structure
require_once __DIR__ . '/../app/services/BookingService.php';
require_once __DIR__ . '/../app/repositories/BookingRepository.php';
class DummyDb
{
    public function getUserMonthlyBookingTotal()
    {
    }
    public function update()
    {
    }
}


class BookingServiceTest extends TestCase
{
    private $repoMock;
    private $service;

    protected function setUp(): void
    {
        $this->repoMock = $this->getMockBuilder(BookingRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'createBooking',
                'getDb',
                'getShowTimeId',
                'getSeats',
                'findMovieWithDetails',
                'getAvgRating',
                'getBookingsByMovieDateShowtime',
                'findBookingById',
                'updateStatus',
                'getUserById',
                'getMovieById',
                'getShowTimeById',
                'getSeatNamesByIds',
                'getPaymentByBookingId',
                'deletePaymentByBookingId',
                'deleteBooking',
            ])
            ->getMock();

        $this->service = new BookingService($this->repoMock);
    }

    public function testGetMovieWithDetailsReturnsNullWhenNotFound(): void
    {
        $movieId = 123;
        $this->repoMock->expects($this->once())
            ->method('findMovieWithDetails')
            ->with($movieId)
            ->willReturn(null);

        $result = $this->service->getMovieWithDetails($movieId, null, null);
        $this->assertNull($result);
    }

    public function testGetMovieWithDetailsBuildsDataWhenFound(): void
    {
        $movieId = 5;
        $basicMovie = [
            'id' => $movieId,
            'title' => 'Test Movie',
            'show_time_list' => '15:00,18:00',
            'start_date' => '2025-08-01',
            'end_date' => '2025-08-10',
        ];

        $this->repoMock->expects($this->once())
            ->method('findMovieWithDetails')
            ->with($movieId)
            ->willReturn($basicMovie);

        $this->repoMock->expects($this->once())
            ->method('getAvgRating')
            ->with($movieId)
            ->willReturn(4.2);

        $this->repoMock->expects($this->once())
            ->method('getSeats')
            ->willReturn([
                ['id' => 1, 'seat_row' => 'A', 'seat_number' => 1, 'price' => 1000],
                ['id' => 2, 'seat_row' => 'A', 'seat_number' => 2, 'price' => 1000],
            ]);

        $this->repoMock->expects($this->once())
            ->method('getShowTimeId')
            ->with('15:00')
            ->willReturn(1);

        $this->repoMock->expects($this->once())
            ->method('getBookingsByMovieDateShowtime')
            ->willReturn([]);

        $result = $this->service->getMovieWithDetails($movieId, '2025-08-12', '15:00');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('movie', $result);
        $this->assertEquals('Test Movie', $result['movie']['title']);
        $this->assertArrayHasKey('avg_rating', $result);
    }


    public function testCreateBookingCallsRepositoryAndReturnsTrue(): void
    {
        $post = [
            'movie_id' => 1,
            'show_time' => '15:00',
            'booking_date' => '2025-08-12',
            'selected_seats' => json_encode(['A1', 'A2']),
        ];
        $userId = 42;

        $mockDb = $this->getMockBuilder(DummyDb::class)
            ->onlyMethods(['getUserMonthlyBookingTotal', 'update'])
            ->getMock();

        $mockDb->method('getUserMonthlyBookingTotal')->willReturn(0);
        $mockDb->method('update')->willReturn(true);

        $this->repoMock->method('getDb')->willReturn($mockDb);
        $this->repoMock->method('getShowTimeId')->willReturn(1);
        $this->repoMock->method('getSeats')->willReturn([
            ['id' => 1, 'seat_row' => 'A', 'seat_number' => 1, 'price' => 1000],
            ['id' => 2, 'seat_row' => 'A', 'seat_number' => 2, 'price' => 1000],
        ]);

        $this->repoMock->expects($this->once())
            ->method('createBooking')
            ->with($this->callback(function ($arg) use ($post, $userId) {
                return isset($arg['user_id'], $arg['seat_id'])
                    && $arg['user_id'] === $userId
                    && $arg['movie_id'] === $post['movie_id'];
            }))
            ->willReturn(true);

        $result = $this->service->createBooking($post, $userId);
        $this->assertTrue($result);
    }

    public function testUpdateBookingStatusReturnsTrue(): void
    {
        $bookingId = 10;
        $status = 2;

        $this->repoMock->expects($this->once())
            ->method('findBookingById')
            ->with($bookingId)
            ->willReturn([
                'user_id' => 1,
                'movie_id' => 1,
                'show_time_id' => 1,
                'seat_id' => json_encode([1, 2]),
                'booking_date' => '2025-08-12',
                'total_amount' => 2000,
            ]);

        $this->repoMock->expects($this->once())
            ->method('updateStatus')
            ->with($bookingId, $status)
            ->willReturn(true);

        $this->repoMock->method('getUserById')->willReturn(['email' => 'test@example.com', 'name' => 'John Doe']);
        $this->repoMock->method('getMovieById')->willReturn(['movie_name' => 'Test Movie']);
        $this->repoMock->method('getShowTimeById')->willReturn(['show_time' => '15:00']);
        $this->repoMock->method('getSeatNamesByIds')->willReturn(['A1', 'A2']);

        $result = $this->service->updateBookingStatus($bookingId, $status);
        $this->assertTrue($result['success']);
    }

    public function testDeleteBookingReturnsTrue(): void
    {
        $bookingId = 7;

        $this->repoMock->method('getPaymentByBookingId')->willReturn(['payslip_image' => 'payslip.png']);
        $this->repoMock->expects($this->once())
            ->method('deletePaymentByBookingId')
            ->with($bookingId);

        $this->repoMock->expects($this->once())
            ->method('deleteBooking')
            ->with($bookingId)
            ->willReturn(true);

        $this->assertTrue($this->service->deleteBooking($bookingId));
    }

    public function testGetBookingDetailReturnsNullWhenNotFound(): void
    {
        $this->repoMock->method('findBookingById')->willReturn(null);

        $result = $this->service->getBookingDetail(999);
        $this->assertNull($result);
    }

    public function testGetBookingDetailReturnsDataWhenFound(): void
    {
        $bookingId = 5;
        $bookingData = ['movie_id' => 1, 'seat_id' => json_encode([1, 2])];

        $this->repoMock->method('findBookingById')->with($bookingId)->willReturn($bookingData);
        $this->repoMock->method('findMovieWithDetails')->with($bookingData['movie_id'])->willReturn(['id' => 1, 'title' => 'Test Movie']);
        $this->repoMock->method('getPaymentByBookingId')->with($bookingId)->willReturn(['amount' => 1000]);
        $this->repoMock->method('getSeatNamesByIds')->with([1, 2])->willReturn(['A1', 'A2']);

        $result = $this->service->getBookingDetail($bookingId);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('booking', $result);
        $this->assertArrayHasKey('movie', $result);
        $this->assertArrayHasKey('payment', $result);
        $this->assertArrayHasKey('seats', $result);
    }
}
