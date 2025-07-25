

<?php
// PHP code to simulate fetching movie data
// In a real application, you would connect to your database (e.g., MySQL)
// and fetch data from your 'movies' table here.

// Dummy data for demonstration purposes
$booking_details = [
    'name' => 'Hla Hla',
    'movie' => 'Titanic',
    'show_time' => '10:00AM',
    'date' => '7-May-2025',
    'seat' => 'A1',
    'total_price' => '$100',
    'image' => 'http://googleusercontent.com/file_content/0', // Using the uploaded image directly
];
?>

    <?php
    require_once __DIR__ . '/../layout/sidebar.php';
    ?>
    <div class="detail-content-wrapper">
        <?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>
        <div class="content-area">
            <div class="back-arrow-container">
                <a href="<?php echo URLROOT; ?>/booking/bookingHistory" class="d-flex align-items-center">
                    <i class="fas fa-arrow-left me-2"></i>
                </a>
            </div>

            <div class="detail-card-container">
                <div class="detail-image-wrapper">
                    <img src="<?php echo htmlspecialchars($booking_details['image']); ?>" alt="Payment Screen" class="img-fluid">
                </div>
                <div class="detail-text-content">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($booking_details['name']); ?></p>
                    <p><strong>Movie:</strong> <?php echo htmlspecialchars($booking_details['movie']); ?></p>
                    <p><strong>Showtime:</strong> <?php echo htmlspecialchars($booking_details['show_time']); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($booking_details['date']); ?></p>
                    <p><strong>Seat:</strong> <?php echo htmlspecialchars($booking_details['seat']); ?></p>
                    <p><strong>Total price:</strong> <?php echo htmlspecialchars($booking_details['total_price']); ?></p>
                </div>
            </div>
        </div>
    </div>
