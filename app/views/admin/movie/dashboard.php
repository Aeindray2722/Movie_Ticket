<?php
require_once __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content-wrapper">
    <?php
    require_once __DIR__ . '/../layout/nav.php';

    ?>
    <div class="dashboard-content">
        <div class="row">
            <div class="col-md-3">
                <div class="dashboard-card">
                    <h4><i class="fa-solid fa-video"></i> Best Seller </h4>
                    <h3><?= htmlspecialchars($data['report']['best_selling_movie']) ?></h3> 
                    <!-- (<?= (int) $data['report']['best_selling_count'] ?>
                    bookings) -->
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <h4><i class="fa-solid fa-ticket"></i>Total Bookings</h4>
                    <h3><?= (int) $data['report']['total_bookings'] ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <h4><i class="fa-solid fa-square-poll-vertical"></i> Revenue</h4>
                    <h3><?= number_format($data['report']['total_revenue']) ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <h4><i class="fa-solid fa-users"></i>Total Users</h4>
                    <h3><?= (int) $data['report']['total_customers'] ?></h3>
                </div>
            </div>
        </div>


        <div class="recent-bookings-table mt-3">
            <h4>Recent Booking</h4>
            <div class="table-responsive mt-3">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Movie</th>
                            <th>Show Time</th>
                            <th>Tickets</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['recentBookings'])): ?>
                            <?php foreach ($data['recentBookings'] as $booking): ?>
                                <tr>
                                    <td><?= htmlspecialchars($booking['user_name']) ?></td>
                                    <td><?= htmlspecialchars($booking['movie_name']) ?></td>
                                    <td><?= htmlspecialchars($booking['show_time']) ?></td>
                                    <td><?= htmlspecialchars($booking['seats']) ?></td>
                                    <td>$<?= number_format($booking['total_amount'], 2) ?></td>
                                    <td><?= htmlspecialchars($booking['status_text']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No recent bookings found.</td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>