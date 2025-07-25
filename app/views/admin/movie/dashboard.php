
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
                        <h4 class=""><i class="fa-solid fa-video"></i> Total Movies</h4>
                        <h3><?php echo 30; ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <h4 class=""><i class="fa-solid fa-ticket"></i>Total Bookings</h4>
                        <h3><?php echo 6; ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <h4 class=""><i class="fa-solid fa-square-poll-vertical"></i>Revenue</h4>
                        <h3>$<?php echo 500; ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <h4 class=""><i class="fa-solid fa-users"></i>Total Users</h4>
                        <h3><?php echo 10; ?></h3>
                    </div>
                </div>
            </div>

            <div class="recent-bookings-table mt-3">
                <h4>Recent Booking</h4>
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Movie</th>
                                <th>Show Time</th>
                                <th>Tickets</th>
                                <th>Total Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Dummy data for demonstration as PHP data isn't provided in the initial snippet
                            // You should replace this with your actual PHP logic to fetch $recentBookings
                            $recentBookings = [
                                [
                                    'booking_id' => 1,
                                    'user_name' => 'John Doe',
                                    'movie_title' => 'The Matrix',
                                    'show_time' => '19:00:00',
                                    'num_tickets' => 2,
                                    'total_price' => 25.00,
                                    'status' => 'confirmed'
                                ],
                                [
                                    'booking_id' => 2,
                                    'user_name' => 'Jane Smith',
                                    'movie_title' => 'Inception',
                                    'show_time' => '21:30:00',
                                    'num_tickets' => 1,
                                    'total_price' => 12.50,
                                    'status' => 'pending'
                                ],
                                [
                                    'booking_id' => 3,
                                    'user_name' => 'Peter Jones',
                                    'movie_title' => 'Dune',
                                    'show_time' => '16:00:00',
                                    'num_tickets' => 3,
                                    'total_price' => 37.50,
                                    'status' => 'cancelled'
                                ],
                            ];
                            ?>
                            <?php if (!empty($recentBookings)): ?>
                                <?php foreach ($recentBookings as $booking): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['movie_title']); ?></td>
                                        <td><?php echo date('h:i A', strtotime($booking['show_time'])); ?></td>
                                        <td><?php echo htmlspecialchars($booking['num_tickets']); ?></td>
                                        <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                                        <td>
                                            <select class="form-select form-select-sm" onchange="updateBookingStatus(this.value, <?php echo $booking['booking_id']; ?>)">
                                                <option value="pending" <?php echo ($booking['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                <option value="confirmed" <?php echo ($booking['status'] == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                                <option value="cancelled" <?php echo ($booking['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                        </td>
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

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

