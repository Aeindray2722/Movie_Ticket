<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Movie Booking</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/booking.css" />
    <style>
        :root {
        --primary-purple: #8c72cf;
        --purple-hover: #7a60b9;
        --dark-purple: #6a57a0;
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
        --shadow-soft: 0 8px 32px rgba(0, 0, 0, 0.1);
        --shadow-hover: 0 12px 40px rgba(0, 0, 0, 0.15);
        --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --text-primary: #2d3748;
        --text-secondary: #4a5568;
        --border-radius: 16px;
    }
    .back-button {
        background: white;
        border: 1px solid #dee2e6;
        color: var(--primary-color);
        padding: 12px 24px;
        border-radius: 50px;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-primary);
    }

    .back-button:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        color: #333;
        background: #f8f9fa;
    }
    </style>
</head>

<body>
    <?php
    require_once __DIR__ . '/../layout/nav.php';

    $isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

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

    // Disable booking if movie ended
    $disableBooking = $nextAvailableDate === null;

    // Determine selected date/time
    $selectedDate = $_GET['date'] ?? $now->format('Y-m-d'); // always default to today
    $selectedTime = $_GET['time'] ?? ($availableShowtimes[$selectedDate][0] ?? null);

    // Function to group seat rows by price
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
    <section class="movie-detail-page-content">
        <div class="container">
            <div class="">
                <a href="<?= URLROOT; ?>/movie/movieDetail/<?= $data['movie']['id'] ?>" class="back-button">
                    <i class="fas fa-arrow-left "></i>
                    Back
                </a>
            </div>

            <!-- Movie Info Card -->
            <div class="card movie-detail-card mb-4">
                <div class="row g-0 flex-column flex-md-row">
                    <div class="col-12 col-md-5 col-lg-4 movie-detail-poster-col">
                        <img src="<?= URLROOT . '/images/movies/' . htmlspecialchars($data['movie']['movie_img']) ?>"
                            class="img-fluid movie-detail-poster w-100"
                            alt="<?= htmlspecialchars($data['movie']['movie_name']) ?>">
                    </div>
                    <div class="col-12 col-md-7 col-lg-8 ">
                        <div class="card-body movie-detail-body mt-3">
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
                                <strong style="color: black">Synopsis: </strong>
                                <?= $data['movie']['description'] ?>
                            </p>
                            <div class="movie-detail-rating mb-3 mt-3">
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
                                    $isActive = $dateStr === $selectedDate;

                                    // Disable past dates (before today)
                                    $isPast = (new DateTime($dateStr, $tz) < $now->setTime(0, 0, 0));
                                    ?>
                                    <a href="<?= $isPast ? '#' : URLROOT . '/booking/index/' . $data['movie']['id'] . '?date=' . $dateStr . '&time=' . urlencode($futureTimes[0] ?? '') ?>"
                                        class="btn btn-date <?= $isActive ? 'active' : '' ?> <?= $isPast ? 'disabled' : '' ?>"
                                        <?= $isPast ? 'onclick="return false;"' : '' ?>>
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
                            <?php
                            $isToday = ($selectedDate === $now->format('Y-m-d'));
                            $hasFutureShowsToday = !empty($availableShowtimes[$now->format('Y-m-d')]);
                            ?>
                            <?php if ($isToday && !$hasFutureShowsToday): ?>
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    All showtimes for today are over. Please choose another date.
                                </div>


                            <?php else: ?>
                                <div class="seat-section">
                                    <?php foreach ($data['seats_grouped_by_row'] as $row => $seatNumbers): ?>
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
                        <?php endif; ?>
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