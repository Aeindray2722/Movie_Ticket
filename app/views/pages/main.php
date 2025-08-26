<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieFlow - Book Your Tickets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/new.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Add this in your <head> before your custom CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 70px; /* your header height */
    background-color: #f2e6f2;
    display: flex;              /* flex container */
    align-items: center;        /* vertical centering */
    justify-content: center;    /* optional, for horizontal center of entire content */
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    z-index: 1000;
}

.header .container {
    display: flex;
    align-items: center;       /* vertical center of logo + nav */
    justify-content: space-between; /* logo left, nav right */
    height: 100%;              /* full header height */
}

.nav ul {
    display: flex;
    gap: 20px;
    list-style: none;
    margin: 0;
    padding: 0;
    align-items: center; /* vertical center nav links */
}

.nav a {
    text-decoration: none;
    color: #000;
    line-height: 1; /* ensures vertical centering inside li */
}
/* Push page content below fixed header */
body {
    padding-top: 70px; /* same as header height */
}

</style>
</head>

<body>
    <header class="header shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo text-center">
                Central
            </div>
            <nav class="nav">
                <ul class="d-flex gap-3 list-unstyled mb-0">
                    <li><a href="<?php echo URLROOT; ?>/pages/register">Register</a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/login">Login</a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/home">Guest</a></li>
                </ul>
            </nav>
        </div>
    </header>


    <main class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>Book Your Tickets<br>Online Now</h1>
                <p class="description">
                    Discover the latest blockbusters and timeless classics. Find showtimes, book your seats,
                    and enjoy a seamless movie-going experience from the comfort of your home.
                </p>
                <a href="<?php echo URLROOT; ?>/pages/home"class="btn btn-register">Browse Movies</a>
            </div>
            <div class="hero-graphic">
                <div class="orange-blob"></div>
                <div class="movie-carousel">
                    <div class="main-movie-poster">
                        <img src="/images/layout/poster7.avif" alt="Main Movie Poster">
                    </div>
                    <div class="small-movie-posters">
                        <img src="/images/layout/poster1.jpg" alt="Movie Poster 1">
                        <img src="/images/layout/poster2.jpg" alt="Movie Poster 2">
                        <img src="/images/layout/poster3.jpg" alt="Movie Poster 3">
                        <img src="/images/layout/poster4.jpg" alt="Movie Poster 4">
                        <img src="/images/layout/poster5.jpg" alt="Movie Poster 5">
                        <img src="/images/layout/poster6.jpg" alt="Movie Poster 6">
                    </div>
                </div>
            </div>
        </div>
    </main>


</body>

</html>