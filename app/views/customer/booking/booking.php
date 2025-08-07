<?php
require_once __DIR__ . '/../layout/nav.php';
?>
<?php
$isLoggedIn = false;
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    $isLoggedIn = true;
}
?>

<section class="movie-detail-page-content py-4">
    <div class="container">
        <div class="mb-3">
            <a href="<?= URLROOT; ?>/movie/movieDetail/<?= $data['movie']['id'] ?>" class="btn btn-back-to-list">
                <i class="fas fa-arrow-left me-2"></i>
            </a>
        </div>

        <div class="card movie-detail-card mb-4">
            <div class="row g-0">
                <div class="col-md-5 col-lg-4 movie-detail-poster-col">
                    <img src="<?= URLROOT . '/images/movies/' . htmlspecialchars($data['movie']['movie_img']) ?>"
                        class="img-fluid rounded-start movie-detail-poster"
                        alt="<?= htmlspecialchars($data['movie']['movie_name']) ?>">
                </div>
                <div class="col-md-7 col-lg-8">
                    <div class="card-body movie-detail-body">
                        <h1 class="card-title movie-detail-title"><strong style="color : black">Title:
                            </strong><?= htmlspecialchars($data['movie']['movie_name']) ?>
                        </h1>
                        <p class="movie-detail-type"><strong style="color : black">Type:
                            </strong><?= htmlspecialchars($data['movie']['type_name']) ?></p>
                        <p class="movie-detail-actors"><strong style="color : black">Actor/Actress:
                            </strong><?= htmlspecialchars($data['movie']['actor_name']) ?></p>
                        <p class="movie-detail-description"><strong style="color : black">Description: </strong>
                            <?= htmlspecialchars($data['movie']['description']) ?>
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

        <div class="selection-section card mb-4 p-4">
            <div class="row align-items-center mb-3">
                <div class="col-md-10 offset-1 d-flex justify-content-center">
                    <div class="d-flex flex-wrap gap-2">
                        <?php
                        $today = new DateTime('today');
                        foreach ($data['date'] as $date):
                            $isPast = $date < $today;
                            ?>
                            <a href="<?= $isPast ? '#' : URLROOT . '/booking/index/' . $data['movie']['id'] . '?date=' . $date->format('Y-m-d') . '&time=' . $data['selected_time'] ?>"
                                class="btn btn-date <?= $isPast ? 'disabled' : '' ?> <?= ($date->format('Y-m-d') === $data['selected_date']) ? 'active' : '' ?>"
                                <?= $isPast ? 'onclick="return false;"' : '' ?>>
                                <?= $date->format('D, M j') ?>
                            </a>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>

            <div class="row mb-4 text-center">
                <div class="col-6 offset-3 d-flex justify-content-center">
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($data['show_times'] as $time): ?>
                            <?php
                            $t = DateTime::createFromFormat('H:i:s', $time);
                            $formatted = $t ? $t->format('g:i A') : $time;
                            ?>
                            <a href="<?= URLROOT ?>/booking/index/<?= $data['movie']['id'] ?>?date=<?= $data['selected_date'] ?>&time=<?= $time ?>"
                                class="btn btn-time <?= ($time === $data['selected_time']) ? 'active' : '' ?>">
                                <?= htmlspecialchars($formatted) ?>
                            </a>
                            <!-- <button class="btn btn-time shadow-sm"
                                style="color: black;"><?= htmlspecialchars($formatted) ?></button> -->
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>


            <?php
            // Group seat rows dynamically by price
            function group_rows_by_price($map)
            {
                $grouped = [];

                foreach ($map as $row => $price) {
                    $grouped[$price][] = $row; // Group rows with same price
                }

                return $grouped; // eg: [3000 => ['A'], 4000 => ['B'], 5000 => ['C', 'D']]
            }
            ?>

            <?php
            $grouped_rows = group_rows_by_price($data['seat_price_map']);
            ?>

            <div class="row justify-content-center mb-4">
                <?php foreach ($grouped_rows as $price => $rows): ?>
                    <div class="col-sm-6 col-md-3 mb-2">
                        <div class="cost-info card p-2 text-center">
                            <p class="mb-0">Cost of 1 ticket for</p>
                            <p class="mb-0 fw-bold">
                                <?= implode(', ', $rows) ?> = $<?= $price ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="seating-chart-container text-center">
                <h4 class="mb-4">Select Your Seats</h4>
                <div class="seating-legend d-flex justify-content-center mb-3 gap-3 flex-wrap">
                    <div class="legend-item d-flex align-items-center">
                        <div class="legend-color-box bg-danger"></div>
                        <span>Not Available</span>
                    </div>
                    <div class="legend-item d-flex align-items-center">
                        <div class="legend-color-box bg-success"></div>
                        <span>Selected</span>
                    </div>
                    <div class="legend-item d-flex align-items-center">
                        <div class="legend-color-box bg-light-green"></div> <span>Available</span>
                    </div>
                </div>

                <div class="seat-layout d-inline-flex border p-3 rounded">
                    <div class="seat-section me-4">
                        <?php foreach ($data['seats_grouped_by_row'] as $row => $seatNumbers): ?>
                            <div class="seat-row d-flex align-items-center mb-2">
                                <span class="row-label me-2"><?= htmlspecialchars($row) ?></span>
                                <div class="d-flex gap-3">
                                    <?php
                                    // Split seatNumbers into two groups: first 5 and the rest
                                    $firstFiveSeats = array_slice($seatNumbers, 0, 5);
                                    $secondSeats = array_slice($seatNumbers, 5);
                                    ?>
                                    <!-- First column with 5 seats -->
                                    <div class="seat-cluster d-flex gap-1">
                                        <?php foreach ($firstFiveSeats as $seatNum): ?>
                                            <?php
                                            $seatStatus = 0;
                                            // var_dump($data['booked_seat_ids']); exit;
                                            foreach ($data['seats'] as $seat) {
                                                if ($seat['seat_row'] === $row && $seat['seat_number'] == $seatNum) {
                                                    $seat_id = $seat['id'];
                                                    if (in_array($seat_id, $data['booked_seat_ids'])) {
                                                        $seatStatus = 1; // already booked
                                                    } else {
                                                        $seatStatus = (int) $seat['status']; // status from seats table
                                                    }
                                                    break;
                                                }
                                            }
                                            $class = $seatStatus === 0 ? 'seat-available' : 'seat-not-available';

                                            ?>
                                            <div class="seat <?= $class ?>"
                                                data-seat="<?= htmlspecialchars($row . $seatNum) ?>"></div>
                                        <?php endforeach; ?>
                                    </div>

                                    <!-- Second column with remaining seats -->
                                    <div class="seat-cluster d-flex gap-1">
                                        <?php foreach ($secondSeats as $seatNum): ?>
                                            <?php
                                            $seatStatus = 0;
                                            foreach ($data['seats'] as $seat) {
                                                if ($seat['seat_row'] === $row && $seat['seat_number'] == $seatNum) {
                                                    $seat_id = $seat['id'];
                                                    if (in_array($seat_id, $data['booked_seat_ids'])) {
                                                        $seatStatus = 1; // already booked
                                                    } else {
                                                        $seatStatus = (int) $seat['status']; // status from seats table
                                                    }
                                                    break;
                                                }
                                            }
                                            $class = $seatStatus === 0 ? 'seat-available' : 'seat-not-available';
                                            // var_dump($class); exit;
                                            ?>
                                            <div class="seat <?= $class ?>"
                                                data-seat="<?= htmlspecialchars($row . $seatNum) ?>"></div>
                                        <?php endforeach; ?>
                                        
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <form id="bookingForm" method="POST" action="<?= URLROOT ?>/booking/store" style="display:none;">
                    <input type="hidden" name="movie_id" value="<?= $data['movie']['id'] ?>">
                    <input type="hidden" name="show_time" value="<?= $data['selected_time'] ?>">
                    <input type="hidden" name="booking_date" value="<?= $data['selected_date'] ?>">
                    <input type="hidden" name="selected_seats" id="selectedSeatsInput">
                </form>

                <a href="" id="bookNowLink"><button class="btn btn-book-now mt-4 w-auto mx-auto d-block">Book
                        now</button></a>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

<style>
    #bookNowLink {
        text-decoration: none;
        /* underline ဖယ်ရှား */
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const seats = document.querySelectorAll('.seat-layout .seat');
        seats.forEach(seat => {
            seat.addEventListener('click', function () {
                if (this.classList.contains('seat-available')) {
                    this.classList.remove('seat-available');
                    this.classList.add('seat-selected');
                } else if (this.classList.contains('seat-selected')) {
                    this.classList.remove('seat-selected');
                    this.classList.add('seat-available');
                }
            });
        });

        const dateButtons = document.querySelectorAll('.btn-date');
        dateButtons.forEach(button => {
            button.addEventListener('click', function () {
                dateButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });

        const timeButtons = document.querySelectorAll('.btn-time');
        timeButtons.forEach(button => {
            button.addEventListener('click', function () {
                timeButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });

        const bookNowLink = document.getElementById('bookNowLink');
        bookNowLink.addEventListener('click', function (event) {
            event.preventDefault();

            const selectedSeats = document.querySelectorAll('.seat-selected');
            const seatNumbers = Array.from(selectedSeats).map(seat => seat.dataset.seat);

            if (seatNumbers.length === 0) {
                alert('Please select at least one seat.');
                return;
            }

            const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;

            if (!isLoggedIn) {
                window.location.href = "<?= URLROOT ?>/pages/login";
                return;
            }

            // Set selected seats as JSON string into hidden input
            document.getElementById('selectedSeatsInput').value = JSON.stringify(seatNumbers);
            // Submit the hidden booking form to store booking in DB
            document.getElementById('bookingForm').submit();
        });
    });
</script>
</script>