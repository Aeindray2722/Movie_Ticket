<?php
// Get current route for highlighting active menu
$currentPage = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Sidebar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
 
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin.css">
  <style>
  #sidebar {
    /* ... existing styles ... */
    position: fixed; /* Make the sidebar stick to the viewport */
    height: 100vh; /* Ensure it takes up the full height of the viewport */
    top: 0; /* Align it to the top of the viewport */
    left: 0; /* Align it to the left side */
    /* You may also want to add a z-index to ensure it's on top of other content */
    z-index: 1000;
}</style>
</head>

<body>
  <div id="sidebar">
    <h3 class="text-center mb-4 mt-2">Dashboard</h3>
    <ul class="nav flex-column">

      <li class="nav-item">
        <a class="nav-link <?php echo (strpos($currentPage, '/movie/dashboard') !== false ? 'active' : ''); ?>"
          href="<?php echo URLROOT; ?>/movie/dashboard">
          <i class="fas fa-home"></i> Dashboard
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo (strpos($currentPage, '/type/index') !== false ? 'active' : ''); ?>"
          href="<?php echo URLROOT; ?>/type/index">
          <i class="fas fa-film"></i> Movie Type
        </a>
      </li>


      <li class="nav-item">
        <a class="nav-link <?php echo (strpos($currentPage, '/movie/create') !== false ? 'active' : ''); ?>"
          href="<?php echo URLROOT; ?>/movie/create">
          <i class="fas fa-plus-circle"></i> Add Movie
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo (strpos($currentPage, '/movie/index') !== false ? 'active' : ''); ?>"
          href="<?php echo URLROOT; ?>/movie/index">
          <i class="fas fa-list"></i> Movie List
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo (strpos($currentPage, '/trailer/create') !== false ? 'active' : ''); ?>"
          href="<?php echo URLROOT; ?>/trailer/create">
          <i class="fas fa-video"></i>Add Trailer
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo (strpos($currentPage, '/seat/index') !== false ? 'active' : ''); ?>"
          href="<?php echo URLROOT; ?>/seat/index">
          <i class="fas fa-chair"></i> Seat Price
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo (strpos($currentPage, '/payment/index') !== false ? 'active' : ''); ?>"
          href="<?php echo URLROOT; ?>/payment/index">
          <i class="fas fa-dollar-sign"></i> Payment
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo (strpos($currentPage, '/contact/index') !== false ? 'active' : ''); ?>"
          href="<?php echo URLROOT; ?>/contact/index">
          <i class="fa-solid fa-address-book"></i> Contact
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo (strpos($currentPage, '/booking/bookingHistory') !== false ? 'active' : ''); ?>"
          href="<?php echo URLROOT; ?>/booking/bookingHistory">
          <i class="fas fa-history"></i> Booking History
        </a>
      </li>
    </ul>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>

</html>