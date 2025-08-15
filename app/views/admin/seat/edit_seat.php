<?php
require_once __DIR__ . '/../layout/sidebar.php';
// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div class="detail-content-wrapper">
    <?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>
    <div class="content-area">
        <div class="back-arrow-container">
            <a href="add_seat.php" class="d-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i>
            </a>
        </div>
        <div class="profile-card-container">
            <div class="profile-header-text">
                <h4>Update Seat</h4>
            </div>

            <form action="<?php echo URLROOT; ?>/seat/update" method="post">
                <!-- CSRF hidden input -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="profile-content">
                    <div class="profile-edit-form">
                        <div class="form-group-inline">
                            <input type="text" name="seat_row" class="form-control" placeholder="Seat Row"
                                value="<?php echo $data['seats']['seat_row']; ?>">
                        </div>
                        <div class="form-group-inline">
                            <input type="text" name="seat_number" class="form-control" placeholder="Seat Number"
                                value="<?php echo $data['seats']['seat_number']; ?>">
                        </div>
                        <div class="form-group-inline">
                            <input type="text" name="price" class="form-control" placeholder="Price"
                                value="<?php echo $data['seats']['price']; ?>">
                        </div>
                        <!-- Add a hidden input for the seat ID -->
                        <input type="hidden" name="id" value="<?= $data['seats']['id']; ?>">

                        <button type="submit" class="btn btn-update-profile">Update</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>