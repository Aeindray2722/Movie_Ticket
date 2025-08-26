<?php
$search_query = trim($_GET['search'] ?? '');
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$bookings = isset($data['bookings']) && is_array($data['bookings']) ? $data['bookings'] : [];
$bookings = $data['bookings'] ?? [];
$page = $data['page'] ?? 1;
$totalPages = $data['totalPages'] ?? 1;
$search = $data['search'] ?? '';
$start_date = $data['start_date'] ?? '';
$end_date = $data['end_date'] ?? '';
// Filter bookings by search and/or date range
//  var_dump($data['bookings']); exit;
if ($search_query !== '' || (!empty($start_date) && !empty($end_date))) {
    $filtered = [];

    foreach ($bookings as $booking) {
        $matchSearch = true;
        $matchDate = true;
        // Search filter
        if ($search_query !== '') {
            
            $matchSearch = stripos((string) ($booking['movie_name'] ?? ''), $search_query) !== false ||
                stripos((string) ($booking['user_name'] ?? ''), $search_query) !== false ||
                stripos((string) ($booking['seat_names'] ?? ''), $search_query) !== false ||
                stripos((string) ($booking['show_time'] ?? ''), $search_query) !== false ||
                stripos((string) ($booking['booking_date'] ?? ''), $search_query) !== false;
        }

        // Date range filter
        if (!empty($start_date) && !empty($end_date)) {
            $booking_date = $booking['booking_date'] ?? '';
            $matchDate = ($booking_date >= $start_date && $booking_date <= $end_date);
        }

        if ($matchSearch && $matchDate) {
            $filtered[] = $booking;
        }
    }

    $bookings = $filtered;
}
// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<body>
    <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>
    <div class="booking-content-wrapper">
        <?php require_once __DIR__ . '/../layout/nav.php'; ?>
        <div class="booking-list-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <form id="searchForm" class="d-flex" action="" method="get">
                    <input class="form-control me-2" type="search" placeholder="Search" name="search"
                        value="<?= htmlspecialchars($search_query) ?>">

                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>

                    <button type="button" id="dateRangeBtn" class="btn btn-outline-secondary ms-3">
                        <i class="fas fa-calendar-alt"></i>
                    </button>
                    <a href="<?= URLROOT ?>/booking/bookingHistory" class="btn btn-outline-primary ms-3">
                        All
                    </a>
                    <input type="hidden" name="start_date" id="startDate" value="<?= htmlspecialchars($start_date) ?>">
                    <input type="hidden" name="end_date" id="endDate" value="<?= htmlspecialchars($end_date) ?>">
                    <a href="<?= URLROOT ?>/booking/export?search=<?= urlencode($search) ?>&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>"
                        class="btn btn-success ms-3">
                        Export
                    </a>

                </form>
                <?php if (
                    !empty($start_date) &&
                    !empty($end_date) &&
                    empty($search_query)
                ): ?>
                    <span class="ms-3">Showing bookings from <strong><?= htmlspecialchars($start_date) ?></strong> to
                        <strong><?= htmlspecialchars($end_date) ?></strong></span>
                <?php endif; ?>
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
                        <?php if (empty($bookings)): ?>
                            <tr>
                                <td colspan="8" class="text-center">No bookings found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><?= htmlspecialchars($booking['user_name'] ?? 'Unknown') ?></td>
                                    <td><?= htmlspecialchars($booking['movie_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($booking['show_time'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($booking['booking_date'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($booking['seat_names'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($booking['total_amount'] ?? 'N/A') ?></td>
                                    <td>
                                        <select class="form-select form-select-sm"
                                            onchange="updateBookingStatus(this.value, <?= $booking['id'] ?>)">
                                            <option value="0" <?= ($booking['status'] == 0) ? 'selected' : '' ?>>Confirmed</option>
                                            <option value="1" <?= ($booking['status'] == 1) ? 'selected' : '' ?>>Pending</option>
                                            <option value="2" <?= ($booking['status'] == 2) ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                    </td>
                                    <td class="d-flex text-center">
                                        <a href="<?= URLROOT ?>/booking/bookingDetail/<?= $booking['id'] ?>"
                                            class="btn btn-sm btn-outline-secondary me-1">
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

                <!-- Pagination -->
                <?php if ($data['totalPages'] > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            <?php for ($p = 1; $p <= $data['totalPages']; $p++): ?>
                                <li class="page-item <?= ($p == $data['page']) ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?search=<?= urlencode($search_query) ?>&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>&page=<?= $p ?>"><?= $p ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- JS for update status, delete, and flatpickr -->
    <script>
        function updateBookingStatus(status, booking_id) {
            fetch('<?= URLROOT ?>/booking/updateStatus', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `status=${encodeURIComponent(status)}&booking_id=${encodeURIComponent(booking_id)}`
            })
                .then(res => res.text())
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        alert(data.success ? 'Booking status updated successfully.' : 'Failed: ' + data.message);
                    } catch (e) {
                        alert("Invalid response:\n" + text);
                    }
                });
        }

        function deleteMovie(encodedId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = '<?= URLROOT ?>/booking/destroy/' + encodedId;
                }
            });
        }

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const dateBtn = document.getElementById("dateRangeBtn");
            const startDateInput = document.getElementById("startDate");
            const endDateInput = document.getElementById("endDate");
            const searchForm = document.getElementById("searchForm");
            const searchInput = document.querySelector("input[name='search']");

            // Format date in YYYY-MM-DD local
            function formatDateLocal(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Create hidden input for flatpickr
            const hiddenInput = document.createElement("input");
            hiddenInput.type = "text";
            hiddenInput.style.display = "none";
            document.body.appendChild(hiddenInput);

            // Initialize flatpickr for range
            const picker = flatpickr(hiddenInput, {
                mode: "range",
                dateFormat: "Y-m-d",
                onClose: function (selectedDates) {
                    if (selectedDates.length === 2) {
                        startDateInput.value = formatDateLocal(selectedDates[0]);
                        endDateInput.value = formatDateLocal(selectedDates[1]);
                        searchForm.submit();
                    }
                }
            });

            // Clear date filter when typing in search
            searchInput.addEventListener("input", () => {
                if (searchInput.value.trim() !== "") {
                    startDateInput.value = "";
                    endDateInput.value = "";
                }
            });

            // Open flatpickr on button click
            dateBtn.addEventListener("click", () => picker.open());
        });
    </script>