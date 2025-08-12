<?php
// var_dump($_SESSION);
// exit;

$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

$is_logged_in = $user_id !== null && $user_role == 1; // Only customer
$is_guest = $user_id === null; // Not logged in

$currentPage = $_SERVER['REQUEST_URI'];

$profileImg = $_SESSION['profile_img'] ?? 'default_profile.jpg';
$userName = $_SESSION['user_name'] ?? 'Guest';
$customer_type = $_SESSION['customer_type'] ?? 'Normal';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie Showcase</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/customer.css" />

  <style>
    /*  */
    .custom-dropdown-menu {
      display: none;
      position: absolute;
      right: 0;
      top: 100%;
      background-color: white;
      min-width: 200px;

      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    .custom-dropdown-menu.show {
      border-radius: 10px;
      margin-top: 10px;
      display: block;
    }

    .custom-dropdown-menu a {
      display: block;
      padding: 10px 15px;
      color: #333;
      text-decoration: none;
    }

    .custom-dropdown-menu a:hover {
      background-color: #f0f0f0;
    }

    .dropdown-wrapper {
      position: relative;
    }

    .navbar-brand img {
      width: 50px;
      /* Adjust size as needed */
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #ccc;
      /* Optional: adds subtle border */
    }


    .navbar-brand img:hover {
      transform: scale(1.05);
      /* Slight zoom on hover */
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      /* Vertically center logo */
      margin-right: 30px;
      /* Space between logo and next nav item */
    }
  </style>
</head>
<script>
  window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar-custom');
    if (window.scrollY > 10) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });
</script>

<body>
  <?php require_once APPROOT . '/views/inc/header.php'; ?>
  <nav class="navbar navbar-expand-lg sticky-top navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand me-6 ms-3" href="#">
        <img src="<?php echo URLROOT; ?>/images/layout/logo.png" alt="Logo" class="d-inline-block align-text-top">
      </a>

      <!-- Enable the responsive toggler -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-5 me-auto mb-2 mb-lg-0">
          <li class="nav-item me-3">
            <a class="nav-link <?php echo (strpos($currentPage, '/pages/home') !== false ? 'active' : ''); ?>"
              href="<?php echo URLROOT; ?>/pages/home">Home</a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link <?php echo (strpos($currentPage, '/movie/nowShowing') !== false ? 'active' : ''); ?>"
              href="<?php echo URLROOT; ?>/movie/nowShowing">Now Showing</a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link <?php echo (strpos($currentPage, '/trailer/trailer') !== false ? 'active' : ''); ?>"
              href="<?php echo URLROOT; ?>/trailer/trailer">Movie Trailer</a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link <?php echo (strpos($currentPage, '/pages/about') !== false ? 'active' : ''); ?>"
              href="<?php echo URLROOT; ?>/pages/about">About us</a>
          </li>

          <?php if ($is_logged_in): ?>
            <li class="nav-item">
              <a class="nav-link <?php echo (strpos($currentPage, '/booking/history') !== false ? 'active' : ''); ?>"
                href="<?php echo URLROOT; ?>/booking/history">History</a>
            </li>
          <?php endif; ?>
        </ul>

        <ul class="navbar-nav ms-auto">
          <?php if ($is_logged_in): ?>
            <li class="nav-item dropdown-wrapper me-3">
              <a class="nav-link" href="#" id="customDropdownToggle">
                <?php if ($profileImg !== 'default_profile.jpg'): ?>
                  <img src="<?= URLROOT ?>/images/users/<?= htmlspecialchars($profileImg) ?>" alt="Profile Picture"
                    class="rounded-circle" width="30" height="30">
                <?php else: ?>
                  <i class="fas fa-user-circle fa-2x"></i>
                <?php endif; ?>
                <span class="d-none d-lg-inline">
                  <?= htmlspecialchars($userName)?>
                </span>
                <span class="d-none d-lg-inline text-success">
                  (<?= htmlspecialchars($customer_type)?>)
                </span>
              </a>
              <div class="custom-dropdown-menu" id="customDropdownMenu">
                <a href="<?php echo URLROOT; ?>/user/Userindex">
                  <i class="fas fa-user fa-sm me-2 text-gray-400"></i> Profile
                </a>
                <a href="<?php echo URLROOT; ?>/user/UserchangePassword">
                  <i class="fa-solid fa-lock fa-sm me-2 text-gray-400"></i> Change Password
                </a>
                <hr class="dropdown-divider">
                <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm me-2 text-gray-400"></i> Logout
                </a>
              </div>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/pages/login">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/pages/register">Register</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  
  </nav>


  <!-- Logout Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ready to Leave?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="<?php echo URLROOT; ?>/auth/logout">Logout</a>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script>
    const toggle = document.getElementById('customDropdownToggle');
    const menu = document.getElementById('customDropdownMenu');

    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      menu.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
      if (!toggle.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.remove('show');
      }
    });
    
  </script>
</body>

</html>