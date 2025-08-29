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
/* Features Section */
        .features-section {
            padding: 80px 0;
            background: #fff;
        }

        .feature-card {
            text-align: center;
            padding: 40px 20px;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            background: rgba(242, 230, 242, 0.1);
            border: 1px solid rgba(242, 230, 242, 0.3);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(139, 90, 139, 0.1);
        }

        .feature-icon {
            font-size: 3rem;
            color: #8B5A8B;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #a279a2ff, #d090d0ff);
            padding: 80px 0;
            color: white;
        }

        .stat-item {
            text-align: center;
            margin-bottom: 30px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: block;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* How It Works Section */
        .how-it-works {
            padding: 80px 0;
            background: rgba(242, 230, 242, 0.1);
        }

        .step-card {
            text-align: center;
            padding: 30px;
            position: relative;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #8B5A8B, #A569A5);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 20px;
        }

        .step-card h4 {
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .step-card p {
            color: #666;
            line-height: 1.6;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #5c465cff, #444444ff);
            padding: 80px 0;
            color: white;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            background: #6b626bff;
            color: black;
            padding: 40px 0 20px;
        }

        .footer-links {
            display: flex;
            gap: 30px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .footer-links a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #A569A5;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #8a838cff;
            color: #050101ff;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .cta-section h2 {
                font-size: 2rem;
            }
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


    <main class="hero-section mb-3">
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
                        <img src="/images/layout/movie3.jpg" alt="Movie Poster 1">
                        <img src="/images/layout/movie4.jpg" alt="Movie Poster 2">
                        <img src="/images/layout/movie6.jpg" alt="Movie Poster 3">
                        <img src="/images/layout/poster5.jpg" alt="Movie Poster 4">
                        <img src="/images/layout/movie8.jpg" alt="Movie Poster 5">
                        <img src="/images/layout/poster1.jpg" alt="Movie Poster 6">
                    </div>
                </div>
            </div>
        </div>
    </main>


<!-- Features Section -->
    <section class="features-section mb-3">
        <div class="container mt-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark">Why Choose MovieFlow?</h2>
                <p class="lead text-muted">Experience cinema like never before with our premium features</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card shadow-lg">
                        <div class="feature-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <h3>Easy Booking</h3>
                        <p>Book your tickets in just a few clicks. Select your seats, choose your showtime, and you're ready to go!</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card shadow-lg">
                        <div class="feature-icon">
                            <i class="fas fa-couch"></i>
                        </div>
                        <h3>Premium Seats</h3>
                        <p>Choose from a variety of seating options including VIP loungers and premium recliners for ultimate comfort.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card shadow-lg">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Mobile Tickets</h3>
                        <p>Skip the lines with digital tickets. Show your phone at the theater and walk right in.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">50K+</span>
                        <span class="stat-label">Happy Customers</span>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">200+</span>
                        <span class="stat-label">Movies Available</span>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">15+</span>
                        <span class="stat-label">Theater Locations</span>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Online Support</span>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- How It Works Section -->
    <section class="how-it-works">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark">How It Works</h2>
                <p class="lead text-muted">Get your movie tickets in 3 simple steps</p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="step-card shadow-sm">
                        <div class="step-number">1</div>
                        <h4>Choose Your Movie</h4>
                        <p>Browse through our extensive collection of latest releases and classic films. Find the perfect movie for your mood.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="step-card shadow-sm">
                        <div class="step-number">2</div>
                        <h4>Select Seats & Time</h4>
                        <p>Pick your preferred showtime and select the best seats from our interactive seating chart.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="step-card shadow-sm">
                        <div class="step-number">3</div>
                        <h4>Enjoy Your Movie</h4>
                        <p>Complete your purchase and receive instant digital tickets. Show up and enjoy the show!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Start Your Movie Journey?</h2>
            <p>Join thousands of movie lovers who trust MovieFlow for their entertainment needs</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="<?php echo URLROOT; ?>/pages/register" class="btn btn-register">Create Account</a>
                <a href="<?php echo URLROOT; ?>/pages/home" class="btn btn-outline-light">Browse Movies</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-links">
                <a href="#">About Us</a>
                <a href="#">Contact</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">FAQ</a>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 MovieFlow. All rights reserved. | Designed with ❤️ for movie lovers</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>