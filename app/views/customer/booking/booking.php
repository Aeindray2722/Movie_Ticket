<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Movie Booking</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/booking.css" />
</head>

<body>
    <!-- Sample PHP variables for demonstration -->
    <?php
    require_once __DIR__ . '/../layout/nav.php';

    $isLoggedIn = false;
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        $isLoggedIn = true;
    }

    // CSRF token
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    $tz = new DateTimeZone('Asia/Yangon');
    $now = new DateTime('now', $tz);

    // Movie end date
    $movieEndDate = new DateTime($data['movie']['end_date'], $tz);

    // 1️⃣ Determine next available showtime
    $nextAvailableDate = null;
    $nextAvailableTime = null;
    $availableShowtimes = [];

    foreach ($data['date'] as $dateObj) {
        $dateStr = $dateObj->format('Y-m-d');
        $futureTimes = [];

        foreach ($data['show_times'] as $time) {
            $showDateTime = new DateTime("$dateStr $time", $tz);
            if ($showDateTime >= $now) {
                $futureTimes[] = $time;
            }
        }

        $availableShowtimes[$dateStr] = $futureTimes;

        if (!empty($futureTimes) && $nextAvailableDate === null) {
            $nextAvailableDate = $dateStr;
            $nextAvailableTime = $futureTimes[0];
        }
    }

    // If all showtimes are past, disable booking
    $disableBooking = $nextAvailableDate === null;

    // Determine selected date/time
    $selectedDate = $_GET['date'] ?? $nextAvailableDate;
    $selectedTime = $_GET['time'] ?? ($availableShowtimes[$selectedDate][0] ?? null);


    ?>

    <section class="movie-detail-page-content">
        <div class="container">
            <div class="mb-3">
                <a href="<?= URLROOT; ?>/movie/movieDetail/<?= $data['movie']['id'] ?>" class="btn btn-back-to-list">
                    <i class="fas fa-arrow-left "></i>
                </a>
            </div>

            <div class="card movie-detail-card mb-4">
                <div class="row g-0 flex-column flex-md-row">
                    <div class="col-12 col-md-5 col-lg-4 movie-detail-poster-col">
                        <img src="<?= URLROOT . '/images/movies/' . htmlspecialchars($data['movie']['movie_img']) ?>"
                            class="img-fluid movie-detail-poster w-100"
                            alt="<?= htmlspecialchars($data['movie']['movie_name']) ?>">
                    </div>
                    <div class="col-12 col-md-7 col-lg-8">
                        <div class="card-body movie-detail-body">
                            <h1 class="card-title movie-detail-title">
                                <strong style="color: black">Title: </strong><?= $data['movie']['movie_name'] ?>
                            </h1>
                            <p class="movie-detail-type">
                                <strong style="color: black">Type: </strong><?= $data['movie']['type_name'] ?>
                            </p>
                            <p class="movie-detail-actors">
                                <strong style="color: black">Actor/Actress: </strong><?= $data['movie']['actor_name'] ?>
                            </p>
                            <p class="movie-detail-description">
                                <strong style="color: black">Description: </strong>
                                <?= $data['movie']['description'] ?>
                            </p>
                            <div class="movie-detail-rating mb-3">
                                <?php
                                $avg = $data['avg_rating'] ?? 0;
                                for ($i = 1; $i <= 5; $i++):
                                    $class = $i <= $avg ? 'text-warning' : 'text-secondary';
                                    ?>
                                    <i class="fas fa-star <?= $class ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($disableBooking): ?>
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This movie has already been shown.
                </div>
            <?php else: ?>
                <div class="selection-section card mb-4">
                    <!-- Date selection -->
                    <div class="row align-items-center mb-4">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="d-flex flex-wrap gap-3 justify-content-center">
                                <?php foreach ($data['date'] as $dateObj): ?>
                                    <?php
                                    $dateStr = $dateObj->format('Y-m-d');
                                    $futureTimes = $availableShowtimes[$dateStr] ?? [];
                                    $isDisabled = empty($futureTimes);
                                    $isActive = $dateStr === $selectedDate;
                                    ?>
                                    <a href="<?= $isDisabled ? '#' : URLROOT . '/booking/index/' . $data['movie']['id'] . '?date=' . $dateStr . '&time=' . urlencode($futureTimes[0]) ?>"
                                        class="btn btn-date <?= $isDisabled ? 'disabled' : '' ?> <?= $isActive ? 'active' : '' ?>"
                                        <?= $isDisabled ? 'onclick="return false;"' : '' ?>>
                                        <?= $dateObj->format('D, M j') ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Showtime selection -->
                    <div class="row mb-4">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="d-flex flex-wrap gap-3">
                                <?php foreach ($availableShowtimes[$selectedDate] as $time): ?>
                                    <?php $isActive = $time === $selectedTime; ?>
                                    <a href="<?= URLROOT . '/booking/index/' . $data['movie']['id'] . '?date=' . $selectedDate . '&time=' . urlencode($time) ?>"
                                        class="btn btn-time <?= $isActive ? 'active' : '' ?>">
                                        <?= date('g:i A', strtotime($time)) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Seat price info -->
                    <?php
                    function group_rows_by_price($map)
                    {
                        $grouped = [];
                        foreach ($map as $row => $price) {
                            $grouped[$price][] = $row;
                        }
                        return $grouped;
                    }
                    $grouped_rows = group_rows_by_price($data['seat_price_map']);
                    ?>
                    <div class="row justify-content-center mb-4">
                        <?php foreach ($grouped_rows as $price => $rows): ?>
                            <div class="col-12 col-sm-6 col-md-3 mb-2">
                                <div class="cost-info card p-3 text-center">
                                    <p class="mb-0">Cost of 1 ticket for</p>
                                    <p class="mb-0 fw-bold"><?= implode(', ', $rows) ?> = $<?= $price ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>

                    <!-- Seating chart -->
                    <div class="seating-chart-container text-center">
                        <h4 class="mb-4">
                            <i class="fas fa-chair me-3"></i>
                            Select Your Seats
                        </h4>
                        <div class="seating-legend d-flex justify-content-center mb-4 gap-4 flex-wrap">
                            <div class="legend-item d-flex align-items-center">
                                <div class="legend-color-box bg-danger"></div>
                                <span>Not Available</span>
                            </div>
                            <div class="legend-item d-flex align-items-center">
                                <div class="legend-color-box bg-success"></div>
                                <span>Selected</span>
                            </div>
                            <div class="legend-item d-flex align-items-center">
                                <div class="legend-color-box bg-light-green"></div>
                                <span>Available</span>
                            </div>
                        </div>

                        <div class="seat-layout d-inline-flex border p-3 rounded flex-column flex-md-row">
                            <div class="seat-section">
                                <?php foreach ($data['seats_grouped_by_row'] as $row => $seatNumbers): ?>
                                    <!-- Row A -->
                                    <div class="seat-row d-flex align-items-center mb-2">
                                        <span class="row-label me-2"><?= htmlspecialchars($row) ?></span>
                                        <div class="d-flex gap-3 flex-wrap">
                                            <?php foreach (array_chunk($seatNumbers, 5) as $seatCluster): ?>
                                                <div class="seat-cluster d-flex gap-1 show-sm">
                                                    <?php foreach ($seatCluster as $seatNum): ?>
                                                        <?php
                                                        $seatStatus = 0;
                                                        foreach ($data['seats'] as $seat) {
                                                            if ($seat['seat_row'] === $row && $seat['seat_number'] == $seatNum) {
                                                                $seat_id = $seat['id'];

                                                                $seatStatus = in_array($seat_id, $data['booked_seat_ids']) ? 1 : (int) $seat['status'];
                                                                break;
                                                            }
                                                        }
                                                        $class = $seatStatus === 0 ? 'seat-available' : 'seat-not-available';

                                                        ?>
                                                        <div class="seat <?= $class ?>"
                                                            data-seat="<?= htmlspecialchars($row . $seatNum) ?>"></div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>


                    </div>
                    <form id="bookingForm" method="POST" action="<?= URLROOT ?>/booking/store" style="display:none;">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="movie_id" value="<?= $data['movie']['id'] ?>">
                        <input type="hidden" name="show_time" value="<?= $selectedTime ?>">
                        <input type="hidden" name="booking_date" value="<?= $selectedDate ?>">
                        <input type="hidden" name="selected_seats" id="selectedSeatsInput">
                    </form>
                    <div class="book-now-container">
                        <a href="#" id="bookNowLink">
                            <button class="btn btn-book-now">
                                <i class="fas fa-ticket-alt me-2"></i>
                                Book Now
                            </button>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const seats = document.querySelectorAll('.seat-layout .seat');
            seats.forEach(seat => {
                seat.addEventListener('click', function () {
                    if (this.classList.contains('seat-available') || this.classList.contains('seat-selected')) {
                        this.classList.toggle('seat-selected');
                        this.classList.toggle('seat-available');
                    }
                });
            });

            document.querySelectorAll('.btn-date').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.btn-date').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            document.querySelectorAll('.btn-time').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.btn-time').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });


            document.getElementById('bookNowLink').addEventListener('click', function (e) {
                e.preventDefault();
                const selectedSeats = Array.from(document.querySelectorAll('.seat-selected')).map(seat => seat.dataset.seat);

                if (selectedSeats.length === 0) {
                    alert('Please select at least one seat.');
                    return;
                }

                if (selectedSeats.length > 4) {
                    alert('You can select a maximum of 4 seats at a time.');
                    return;
                }

                document.getElementById('selectedSeatsInput').value = JSON.stringify(selectedSeats);
                document.getElementById('bookingForm').submit();
            });


        });
    </script>
</body>

</html>