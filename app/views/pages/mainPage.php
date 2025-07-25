
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Booking Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/login.css" />
</head>
<body>

    <div class="main-dashboard-wrapper">
        <nav class="public-navbar">
            <div class="navbar-left">
                <h3 class="logo-text">Minglarpr</h3>
            </div>
            <div class="navbar-right">
                <ul class="nav-links">
                    <li><a  href="<?php echo URLROOT; ?>/pages/register">Register</a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/login">Login</a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/home">Guest</a></li>
                </ul>
            </div>
        </nav>

        <div class="dashboard-content-area">
            <div class="background-overlay"></div>
            <div class="content-container">
                <div class="text-content-right">
                    <h1 class="movie-tagline">Experience the Magic of Cinema</h1>
                    <p class="movie-description">
                        Discover the latest blockbusters, timeless classics, and captivating indie films.
                        Book your tickets online, choose your favorite seats, and enjoy an unforgettable cinematic journey.
                        Your ultimate movie experience starts here! We offer a wide range of genres, from action-packed thrillers to heartwarming dramas, ensuring there's something for everyone.
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
