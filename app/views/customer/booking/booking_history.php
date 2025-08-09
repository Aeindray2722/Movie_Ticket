<?php
require_once __DIR__ . '/../layout/nav.php';
?>
<section class="history-page-content py-4">
    <div class="container">
        <h2 class="mb-4">History</h2>

        <div class="card history-table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped history-table">
                        <thead>
                            <tr>
                                <th scope="col">Booking Date</th>
                                <th scope="col">Movie Name</th>
                                <th scope="col">Seats</th>
                                <th scope="col">Total Amount</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($data['bookings'])): ?>
                                <?php foreach ($data['bookings'] as $booking): ?>
                                    <tr>
                                        <td><?= date('d.m.Y', strtotime($booking['booking_date'])) ?></td>
                                        <td><?= htmlspecialchars($booking['movie_name']) ?></td>
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
                                                    $statusText = 'Accept';
                                                    $statusClass = 'bg-success history-status-success';
                                                    break;
                                                case 1:
                                                    $statusText = 'Pending';
                                                    $statusClass = 'bg-warning text-dark history-status-pending';
                                                    break;
                                                case 2:
                                                    $statusText = 'Reject';
                                                    $statusClass = 'bg-danger history-status-reject';
                                                    break;
                                                default:
                                                    $statusText = 'Unknown';
                                                    $statusClass = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
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
<script>
    function downloadTicket(bookingId) {
        const printWindow = window.open('<?= URLROOT ?>/booking/downloadTicket?booking_id=' + bookingId, '_blank');

        printWindow.focus();

        // Wait until the content loads and then trigger print
        printWindow.onload = function () {
            printWindow.print();
        };
    }
</script>
<script>
    function printTicket(bookingId) {
        const ticketContent = document.getElementById('ticket_' + bookingId).innerHTML;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
        <html>
        <head>
            <title>Ticket - Central Cinema</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                h2 { color: #333; }
                p { margin: 4px 0; }
            </style>
        </head>
        <body onload="window.print(); window.close();">
            ${ticketContent}
        </body>
        </html>
    `);
        printWindow.document.close();
    }
</script>