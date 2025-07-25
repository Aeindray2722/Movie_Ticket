<?php
    require_once __DIR__ . '/../layout/nav.php';
?>
<?php
// Check if the user is logged in
$isLoggedIn = true;
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    // Assuming 'user_id' is set in the session upon successful login
    $isLoggedIn = true;
}
?>

<section class="movie-detail-page-content py-4">
    <div class="container">
        <div class="mb-3">
            <a href="<?php echo URLROOT; ?>/movie/movieDetail" class="btn btn-back-to-list">
                <i class="fas fa-arrow-left me-2"></i> 
            </a>
        </div>

        <div class="card movie-detail-card mb-4">
            <div class="row g-0">
                <div class="col-md-5 col-lg-4 movie-detail-poster-col">
                    <img src="http://googleusercontent.com/file_content/0" class="img-fluid rounded-start movie-detail-poster" alt="Movie Poster">
                </div>
                <div class="col-md-7 col-lg-8">
                    <div class="card-body movie-detail-body">
                        <h1 class="card-title movie-detail-title">Movie Name</h1>
                        <p class="movie-detail-type">Movie Type</p>
                        <p class="movie-detail-actors">Actor/Actress Name</p>
                        <p class="movie-detail-description">
                            Description Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="selection-section card mb-4 p-4">
            <div class="row align-items-center mb-3">
                <div class="col-md-2">
                    <p class="mb-0 fw-bold">Month Year</p>
                </div>
                <div class="col-md-10">
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-date">Day</button>
                        <button class="btn btn-date">Day</button>
                        <button class="btn btn-date active">Day</button> <button class="btn btn-date">Day</button>
                        <button class="btn btn-date">Day</button>
                        <button class="btn btn-date">Day</button>
                        <button class="btn btn-date">Day</button>
                    </div>
                </div>
            </div>
            <div class="row mb-4 ">          
                <div class="col-6 offset-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-time">8:00am</button>
                        <button class="btn btn-time active">11:00am</button> 
                        <button class="btn btn-time">5:00pm</button>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mb-4">
                <div class="col-sm-6 col-md-3 mb-2">
                    <div class="cost-info card p-2 text-center">
                        <p class="mb-0">Cost of 1 ticket for</p>
                        <p class="mb-0 fw-bold">A,B= $...</p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-2">
                    <div class="cost-info card p-2 text-center">
                        <p class="mb-0">Cost of 1 ticket for</p>
                        <p class="mb-0 fw-bold">C,D= $...</p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-2">
                    <div class="cost-info card p-2 text-center">
                        <p class="mb-0">Cost of 1 ticket for</p>
                        <p class="mb-0 fw-bold">E,F= $...</p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-2">
                    <div class="cost-info card p-2 text-center">
                        <p class="mb-0">Cost of 1 ticket for</p>
                        <p class="mb-0 fw-bold">G,H= $...</p>
                    </div>
                </div>
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
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2">A</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-available" data-seat="A1"></div>
                                <div class="seat seat-available" data-seat="A2"></div>
                                <div class="seat seat-available" data-seat="A3"></div>
                                <div class="seat seat-available" data-seat="A4"></div>
                                <div class="seat seat-available" data-seat="A5"></div>
                            </div>
                        </div>
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2">B</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-available" data-seat="B1"></div>
                                <div class="seat seat-available" data-seat="B2"></div>
                                <div class="seat seat-available" data-seat="B3"></div>
                                <div class="seat seat-available" data-seat="B4"></div>
                                <div class="seat seat-available" data-seat="B5"></div>
                            </div>
                        </div>
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2">C</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-available" data-seat="C1"></div>
                                <div class="seat seat-available" data-seat="C2"></div>
                                <div class="seat seat-available" data-seat="C3"></div>
                                <div class="seat seat-available" data-seat="C4"></div>
                                <div class="seat seat-available" data-seat="C5"></div>
                            </div>
                        </div>
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2">D</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-available" data-seat="D1"></div>
                                <div class="seat seat-available" data-seat="D2"></div>
                                <div class="seat seat-available" data-seat="D3"></div>
                                <div class="seat seat-available" data-seat="D4"></div>
                                <div class="seat seat-available" data-seat="D5"></div>
                            </div>
                        </div>
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2">E</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-selected" data-seat="E1"></div> <div class="seat seat-selected" data-seat="E2"></div> <div class="seat seat-available" data-seat="E3"></div>
                                <div class="seat seat-available" data-seat="E4"></div>
                                <div class="seat seat-available" data-seat="E5"></div>
                            </div>
                        </div>
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2">F</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-available" data-seat="F1"></div>
                                <div class="seat seat-available" data-seat="F2"></div>
                                <div class="seat seat-available" data-seat="F3"></div>
                                <div class="seat seat-available" data-seat="F4"></div>
                                <div class="seat seat-available" data-seat="F5"></div>
                            </div>
                        </div>
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2">G</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-available" data-seat="G1"></div>
                                <div class="seat seat-available" data-seat="G2"></div>
                                <div class="seat seat-available" data-seat="G3"></div>
                                <div class="seat seat-available" data-seat="G4"></div>
                                <div class="seat seat-available" data-seat="G5"></div>
                            </div>
                        </div>
                        <div class="seat-row d-flex align-items-center">
                            <span class="row-label me-2">H</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-available" data-seat="H1"></div>
                                <div class="seat seat-available" data-seat="H2"></div>
                                <div class="seat seat-available" data-seat="H3"></div>
                                <div class="seat seat-available" data-seat="H4"></div>
                                <div class="seat seat-available" data-seat="H5"></div>
                            </div>
                        </div>
                    </div>

                    <div class="seat-section">
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2 invisible">A</span> <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-available" data-seat="A6"></div>
                                <div class="seat seat-available" data-seat="A7"></div>
                                <div class="seat seat-available" data-seat="A8"></div>
                                <div class="seat seat-available" data-seat="A9"></div>
                                <div class="seat seat-available" data-seat="A10"></div>
                            </div>
                        </div>
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2 invisible">B</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-available" data-seat="B6"></div>
                                <div class="seat seat-available" data-seat="B7"></div>
                                <div class="seat seat-available" data-seat="B8"></div>
                                <div class="seat seat-available" data-seat="B9"></div>
                                <div class="seat seat-available" data-seat="B10"></div>
                            </div>
                        </div>
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2 invisible">C</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-available" data-seat="C6"></div>
                                <div class="seat seat-available" data-seat="C7"></div>
                                <div class="seat seat-available" data-seat="C8"></div>
                                <div class="seat seat-available" data-seat="C9"></div>
                                <div class="seat seat-available" data-seat="C10"></div>
                            </div>
                        </div>
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2 invisible">D</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-available" data-seat="D6"></div>
                                <div class="seat seat-available" data-seat="D7"></div>
                                <div class="seat seat-available" data-seat="D8"></div>
                                <div class="seat seat-available" data-seat="D9"></div>
                                <div class="seat seat-available" data-seat="D10"></div>
                            </div>
                        </div>
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2 invisible">E</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-not-available" data-seat="E6"></div> <div class="seat seat-not-available" data-seat="E7"></div> <div class="seat seat-not-available" data-seat="E8"></div> <div class="seat seat-not-available" data-seat="E9"></div> <div class="seat seat-not-available" data-seat="E10"></div> </div>
                        </div>
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2 invisible">F</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-available" data-seat="F6"></div>
                                <div class="seat seat-available" data-seat="F7"></div>
                                <div class="seat seat-available" data-seat="F8"></div>
                                <div class="seat seat-available" data-seat="F9"></div>
                                <div class="seat seat-available" data-seat="F10"></div>
                            </div>
                        </div>
                        <div class="seat-row d-flex align-items-center mb-2">
                            <span class="row-label me-2 invisible">G</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-not-available" data-seat="G6"></div> <div class="seat seat-not-available" data-seat="G7"></div> <div class="seat seat-not-available" data-seat="G8"></div> <div class="seat seat-not-available" data-seat="G9"></div> <div class="seat seat-not-available" data-seat="G10"></div> </div>
                        </div>
                        <div class="seat-row d-flex align-items-center">
                            <span class="row-label me-2 invisible">H</span>
                            <div class="seat-cluster d-flex gap-1">
                                <div class="seat seat-not-available" data-seat="H6"></div> <div class="seat seat-not-available" data-seat="H7"></div> <div class="seat seat-not-available" data-seat="H8"></div> <div class="seat seat-not-available" data-seat="H9"></div> <div class="seat seat-not-available" data-seat="H10"></div> </div>
                        </div>
                    </div>
                </div>
                <a href="#" id="bookNowLink"><button class="btn btn-book-now mt-4 w-auto mx-auto d-block">Book now</button></a>
            </div>
        </div>
    </div>
</section>

<?php
    require_once __DIR__ . '/../layout/footer.php';
    ?>

<style>
    
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const seats = document.querySelectorAll('.seat-layout .seat');
        seats.forEach(seat => {
            seat.addEventListener('click', function() {
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
            button.addEventListener('click', function() {
                dateButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });

        const timeButtons = document.querySelectorAll('.btn-time');
        timeButtons.forEach(button => {
            button.addEventListener('click', function() {
                timeButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });

        const bookNowLink = document.getElementById('bookNowLink');

        bookNowLink.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior

            const selectedSeats = document.querySelectorAll('.seat-selected');
            const seatNumbers = Array.from(selectedSeats).map(seat => seat.dataset.seat);

            if (seatNumbers.length === 0) {
                alert('Please select at least one seat.');
                return; // Stop execution if no seats are selected
            }

            // PHP variable passed to JavaScript
            const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>; 

            if (isLoggedIn) {
                console.log("User is logged in. Redirecting to payment."); // Debug JS
                window.location.href = "<?php echo URLROOT; ?>/payment/payment";
            } else {
                console.log("User is NOT logged in. Redirecting to login."); // Debug JS
                window.location.href = "<?php echo URLROOT; ?>/pages/login";
            }
        });
    });
</script>