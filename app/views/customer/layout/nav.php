<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get the current page's filename

// Get current route for highlighting active menu
$currentPage = $_SERVER['REQUEST_URI'];


// --- TEMPORARY: Simulate logged-in user for testing ---
$_SESSION['logged_in'] = true;
$_SESSION['username'] = 'TestUser123'; // You can change this username
// --- END TEMPORARY ---

$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$username = $is_logged_in ? $_SESSION['username'] : ''; // Get username if logged in
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
  </style>
</head>

<body>
    <?php require_once APPROOT . '/views/inc/header.php'; ?>
    <nav class="navbar navbar-expand-lg ">
        <div class="container-fluid">
            <a class="navbar-brand me-6" href="#">
                <img src="https://via.placeholder.com/30" alt="Logo" class="d-inline-block align-text-top">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item me-3">
                        <a class="nav-link <?php echo (strpos($currentPage, '/pages/home') !== false ? 'active' : ''); ?>" href="<?php echo URLROOT; ?>/pages/home">Home</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link <?php echo (strpos($currentPage, '/movie/nowShowing') !== false ? 'active' : ''); ?>" href="<?php echo URLROOT; ?>/movie/nowShowing">Now Showing</a>
                    </li>
                    
                    <li class="nav-item me-3">
                        <a class="nav-link <?php echo (strpos($currentPage, '/movie/trailer') !== false ? 'active' : ''); ?>" href="<?php echo URLROOT; ?>/movie/trailer">Movie Trailer</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link <?php echo (strpos($currentPage, '/pages/about') !== false ? 'active' : ''); ?>" href="<?php echo URLROOT; ?>/pages/about">About us</a>
                    </li>

                    <?php if ($is_logged_in): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($currentPage, '/booking/history') !== false ? 'active' : ''); ?>" href="<?php echo URLROOT; ?>/booking/history">History</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <form class="d-flex me-3"> <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
                </form>

                <ul class="navbar-nav ms-auto">
                    <?php if ($is_logged_in): ?>
                    <li class="nav-item dropdown-wrapper">
            <a class="nav-link" href="#" id="customDropdownToggle">
              <span class="d-none d-lg-inline">Admin</span>
              <i class="fas fa-user-circle fa-2x"></i>
            </a>
            <div class="custom-dropdown-menu" id="customDropdownMenu">
              <a href="<?php echo URLROOT; ?>/user/Userprofile"><i class="fas fa-user fa-sm me-2 text-gray-400"></i> Profile</a>
              <a href="<?php echo URLROOT; ?>/user/UserchangePassword"><i class="fa-solid fa-lock fa-sm me-2 text-gray-400"></i> Change Password</a>
              <hr class="dropdown-divider">
              <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm me-2 text-gray-400"></i> Logout
              </a>
            </div>
          </li>
                    <?php else: ?>
                        <li class="nav-item me-3">
                            <a class="nav-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>" href="../auth/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'register.php') ? 'active' : ''; ?>" href="../auth/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

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