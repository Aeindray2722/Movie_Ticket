<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Booking History</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/booking.css" />
</head>

<body>
    <?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>
    <section class="history-page-content mt-2 pt-0">
        <div class="container">
            <div class="page-header">
                <h3 class="page-title">
                    <i class="fas fa-history me-3"></i>
                    Booking History
                </h3>
            </div>

            <div class="card history-table-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table history-table">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        Booking Date
                                    </th>
                                    <th scope="col">
                                        <i class="fas fa-film me-2"></i>
                                        Movie Name
                                    </th>
                                    <th scope="col">
                                        <i class="fas fa-clock me-2"></i>
                                        Show Time
                                    </th>
                                    <th scope="col">
                                        <i class="fas fa-chair me-2"></i>
                                        Seats
                                    </th>
                                    <th scope="col">
                                        <i class="fas fa-dollar-sign me-2"></i>
                                        Total Amount
                                    </th>
                                    <th scope="col">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Status
                                    </th>
                                    <th scope="col"><i class="fas fa-download me-2"></i>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['bookings'])): ?>
                                    <?php foreach ($data['bookings'] as $booking): ?>
                                        <tr>
                                            <td><?= date('d.m.Y', strtotime($booking['booking_date'])) ?></td>
                                            <td><?= htmlspecialchars($booking['movie_name']) ?></td>
                                            <td><?= date('h:i A', strtotime($booking['show_time'])) ?></td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <?php foreach ($booking['seat_names'] as $seat): ?>
                                                        <span class="badge bg-secondary"><?= htmlspecialchars($seat) ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>
                                            <td>$<?= number_format($booking['total_amount'], 2) ?></td>
                                            <td>
                                                <?php
                                                // Map numeric status to text and class
                                                switch ($booking['status']) {
                                                    case 0:
                                                        $statusText = '<i class="fas fa-check-circle me-1"></i>Accept';
                                                        $statusClass = 'bg-success history-status-success';
                                                        break;
                                                    case 1:
                                                        $statusText = '<i class="fas fa-clock me-1"></i>Pending';
                                                        $statusClass = 'bg-warning text-dark history-status-pending';
                                                        break;
                                                    case 2:
                                                        $statusText = '<i class="fas fa-times-circle me-1"></i>Reject';
                                                        $statusClass = 'bg-danger history-status-reject';
                                                        break;
                                                    default:
                                                        $statusText = 'Unknown';
                                                        $statusClass = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                            </td>
                                                <td>
    <?php if ((int)$booking['status'] === 0): ?>
        <a class="btn btn-sm btn-primary"
           href="<?= URLROOT ?>/booking/download/<?= base64_encode($booking['id']) ?>"
           target="_blank" rel="noopener">
           <i class="fas fa-ticket-alt me-1"></i> Download Ticket
        </a>
    <?php else: ?>
        <span class="text-muted">—</span>
    <?php endif; ?>
</td>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No booking history found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    require_once __DIR__ . '/../layout/footer.php';
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>

</html>