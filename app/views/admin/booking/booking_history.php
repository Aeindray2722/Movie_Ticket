<?php
$search_query = '';
$bookings = isset($data['bookings']) && is_array($data['bookings']) ? $data['bookings'] : [];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = htmlspecialchars($_GET['search']);
    $filtered = [];

    foreach ($bookings as $booking) {
        if (
            stripos((string) ($booking['movie_name'] ?? ''), $search_query) !== false ||
            stripos((string) ($booking['user_name'] ?? ''), $search_query) !== false ||
            stripos((string) ($booking['seat_names'] ?? ''), $search_query) !== false ||
            stripos((string) ($booking['show_time'] ?? ''), $search_query) !== false ||
            stripos((string) ($booking['booking_date'] ?? ''), $search_query) !== false
        ) {
            $filtered[] = $booking;
        }
    }

    $data['bookings'] = $filtered;
    $data['search'] = $search_query;
}

?>

<body>
    <?php
    require_once __DIR__ . '/../layout/sidebar.php';
    ?>
    <div class="booking-content-wrapper">
        <?php
        require_once __DIR__ . '/../layout/nav.php';
        ?>
        <div class="booking-list-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <form class="d-flex" action="" method="get">
                    <input class="form-control me-2" type="search" placeholder="search" aria-label="Search"
                        name="search" value="<?= htmlspecialchars($data['search']) ?>">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>

                            <th>User Name</th>
                            <th>Movie Name</th>
                            <th>Showtime</th>
                            <th>Date</th>
                            <th>Seat</th>
                            <th>Total amount</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['bookings'])): ?>
                            <tr>
                                <td colspan="10" class="text-center">No bookings found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['bookings'] as $booking): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($booking['user_name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($booking['movie_name']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['show_time']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['seat_names']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['total_amount']); ?></td>
                                    <td>
                                        <select class="form-select form-select-sm"
                                            onchange="updateBookingStatus(this.value, <?php echo $booking['id']; ?>)">
                                            <option value="0" <?php echo ($booking['status'] == 0) ? 'selected' : ''; ?>>Confirmed
                                            </option>
                                            <option value="1" <?php echo ($booking['status'] == 1) ? 'selected' : ''; ?>>Pending
                                            </option>
                                            <option value="2" <?php echo ($booking['status'] == 2) ? 'selected' : ''; ?>>Cancelled
                                            </option>
                                        </select>

                                    </td>

                                    <td class="d-flex text-center">
                                        <a href="<?php echo URLROOT; ?>/booking/bookingDetail/<?php echo $booking['id']; ?>"
                                            class="btn btn-sm btn-outline-secondary me-1 ">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <button class="btn btn-sm btn-outline-danger btn-action"
                                            onclick="deleteMovie('<?= base64_encode($booking['id']) ?>')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- pagination -->
                <?php if ($data['totalPages'] > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            <?php for ($p = 1; $p <= $data['totalPages']; $p++): ?>
                                <li class="page-item <?= ($p == $data['page']) ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?search=<?= urlencode($data['search']) ?>&page=<?= $p ?>"><?= $p ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <script>
        function updateBookingStatus(status, booking_id) {
            fetch('<?php echo URLROOT; ?>/booking/updateStatus', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `status=${encodeURIComponent(status)}&booking_id=${encodeURIComponent(booking_id)}`
            })
                .then(response => response.text())
                .then(text => {
                    console.log("Raw response:", text);
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            alert('Booking status updated successfully.');
                        } else {
                            alert('Failed to update status: ' + data.message);
                        }
                    } catch (err) {
                        alert("Response is not valid JSON:\n" + text);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error);
                });
        }

    </script>
    <script>
        function deleteMovie(encodedId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= URLROOT ?>/booking/destroy/' + encodedId;
                }
            });
        }
    </script>