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
                <img src="<?php echo URLROOT . '/images/payslips/' . htmlspecialchars($data['payment']['payslip_image']); ?>"
                    alt="Payment Screen" class="img-fluid">
            </div>
            <div class="detail-text-content">
                <p><strong>Movie: </strong> <?php echo htmlspecialchars($data['movie']['movie_name']); ?></p>
                <p><strong>Showtime: </strong> <?php echo htmlspecialchars($data['movie']['show_time_list']); ?></p>
                <p><strong>Date: </strong> <?php echo htmlspecialchars($data['booking']['booking_date']); ?></p>
                <p><strong>Seat: </strong> <?php echo htmlspecialchars(implode(', ', $data['seats'])); ?></p>
                <p><strong>Total price: </strong> <?php echo htmlspecialchars($data['booking']['total_amount']); ?></p>
            </div>

        </div>
    </div>
</div>