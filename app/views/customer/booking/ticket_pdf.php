<!DOCTYPE html>
<html>
<head>
  <style>
    body { font-family: Arial, sans-serif; }
    .ticket { border: 2px dashed #333; padding: 20px; }
    h2 { margin: 0 0 10px 0; }
    .qr { margin-top: 15px; }
  </style>
</head>
<body>
  <div class="ticket">
    <h2>🎟 Movie Ticket</h2>
    <p><strong>Movie:</strong> <?= htmlspecialchars($booking['movie_name']) ?></p>
    <p><strong>Date:</strong> <?= date('d M Y', strtotime($booking['booking_date'])) ?></p>
    <p><strong>Showtime:</strong> <?= date('h:i A', strtotime($booking['show_time'])) ?></p>
    <p><strong>Seats:</strong> <?= implode(', ', $booking['seat_names']) ?></p>
    <p><strong>Total:</strong> $<?= number_format($booking['total_amount'], 2) ?></p>
    <div class="qr">
      <img src="<?= URLROOT ?>/qrcode.php?data=<?= urlencode($booking['id']) ?>" width="100" />
    </div>
  </div>
</body>
</html>
