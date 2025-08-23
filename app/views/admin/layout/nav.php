<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<?php
$profileImg = $_SESSION['profile_img'] ?? 'default_profile.jpg';
$userName = $_SESSION['user_name'] ?? 'Guest';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - Movie Booking</title>

  <!-- Bootstrap & Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin.css" />

  <style>
    .custom-dropdown-menu {
      display: none;
      position: absolute;
      right: 0;
      top: 100%;
      background-color: white;
      min-width: 180px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    .custom-dropdown-menu.show {
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

    .navbar-admin img {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      object-fit: cover;
      margin-left: 8px;
    }

    .navbar-admin i.fa-user-circle {
      margin-left: 8px;
      font-size: 1.8rem;
      color: #555;
    }
    .navbar-admin {
    /* ... existing styles ... */
    position: sticky; /* Make the navbar stick to its position */
    top: 0; /* Stick it to the top of the scrolling container */
    z-index: 999; /* A value less than the sidebar's z-index so it appears beneath it */
}
  </style>
</head>

<body>
  <div class="main-content-wrapper">
    <nav class="navbar navbar-expand-lg navbar-admin ">
      <div class="container-fluid">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown-wrapper">
            <a class="nav-link" href="#" id="customDropdownToggle">
              <span class="d-none d-lg-inline">
                <?= htmlspecialchars($userName) ?>
              </span>
              <?php if ($profileImg !== 'default_profile.jpg'): ?>
                <img src="<?= URLROOT ?>/images/users/<?= htmlspecialchars($profileImg) ?>" alt="Profile Picture"
                  class="rounded-circle" width="30" height="30">
              <?php else: ?>
                <i class="fas fa-user-circle fa-2x"></i>
              <?php endif; ?>
            </a>


            <div class="custom-dropdown-menu" id="customDropdownMenu">
              <a href="<?php echo URLROOT; ?>/user/index"><i class="fas fa-user fa-sm me-2 text-gray-400"></i>
                Profile</a>
              <a href="<?php echo URLROOT; ?>/user/staffList"><i class="fa-solid fa-users fa-sm me-2 text-gray-400"></i>
                Staff List</a>
              <a href="<?php echo URLROOT; ?>/user/userList"><i class="fa-solid fa-users fa-sm me-2 text-gray-400"></i>
                User List</a>
              <a href="<?php echo URLROOT; ?>/user/changePassword"><i
                  class="fa-solid fa-lock fa-sm me-2 text-gray-400"></i> Change Password</a>
              <hr class="dropdown-divider">
              <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm me-2 text-gray-400"></i> Logout
                </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </div>

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

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom Dropdown Toggle JS -->
  <script>
    const toggle = document.getElementById('customDropdownToggle');
    const menu = document.getElementById('customDropdownMenu');

    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      menu.classList.toggle('show');
    });

    document.addEventListener('click', function (e) {
      if (!toggle.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.remove('show');
      }
    });
  </script>
</body>

</html>