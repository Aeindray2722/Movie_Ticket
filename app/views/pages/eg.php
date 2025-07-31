<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luscious - Taste & Join Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<style>/* Custom CSS for ada42f48b0ea34ea5df1f08b276db9e9.jpg */

body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f8f8; /* Light background as in the image */
    overflow-x: hidden; /* Prevent horizontal scroll */
}

/* Navigation Bar */
.custom-navbar {
    background-color: #fff; /* White background for navbar */
    box-shadow: 0 2px 10px rgba(0,0,0,0.05); /* Subtle shadow */
    padding-left: 80px; /* Adjust padding to match image */
    padding-right: 80px;
}

.luscious-logo {
    font-family: 'Playfair Display', serif; /* Elegant font for logo */
    font-size: 1.8em;
    font-weight: 700;
    color: #333;
    text-decoration: none;
}

.custom-navbar .nav-link {
    color: #555;
    font-weight: 500;
    margin-right: 30px; /* Space between nav items */
    transition: color 0.3s ease;
}

.custom-navbar .nav-link:hover,
.custom-navbar .nav-link.active {
    color: #FF5722; /* Orange accent for active/hover */
}

.login-btn {
    background-color: #FF5722; /* Orange button */
    color: #fff;
    border: none;
    padding: 8px 25px;
    border-radius: 25px; /* Pill shape */
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.login-btn:hover {
    background-color: #e64a19;
}

/* Hero Section */
.hero-section {
    min-height: calc(100vh - 80px); /* Adjust based on navbar height */
    align-items: center;
    background-color: #fff; /* White background for the whole section */
    position: relative; /* For absolute positioning of background details */
}

.left-content {
    padding-left: 100px; /* More padding for text */
    padding-right: 50px;
}

.main-heading {
    font-family: 'Playfair Display', serif;
    font-size: 3.8em;
    font-weight: 700;
    line-height: 1.2;
    color: #333;
    margin-bottom: 20px;
}

.sub-heading {
    font-size: 1.8em;
    font-weight: 600;
    color: #555;
    margin-bottom: 15px;
}

.description {
    font-size: 1em;
    color: #777;
    max-width: 500px; /* Control text width */
    line-height: 1.6;
    margin-bottom: 30px;
}

.explore-btn {
    background-color: #FF5722; /* Orange button */
    color: #fff;
    border: none;
    padding: 12px 40px;
    border-radius: 30px;
    font-weight: 600;
    width: fit-content; /* Make button only as wide as its content */
    transition: background-color 0.3s ease;
}

.explore-btn:hover {
    background-color: #e64a19;
}

.right-image-content {
    background-color: #fdfdfd; /* Very light background for this section */
    position: relative;
    min-height: 500px; /* Ensure space for images */
}

.main-dumpling-image {
    position: absolute;
    max-width: 90%; /* Adjust size */
    height: auto;
    object-fit: contain;
    z-index: 2; /* Bring to front */
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* Center the image */
    filter: drop-shadow(0 15px 30px rgba(0,0,0,0.1)); /* Soft shadow for depth */
}

/* Floating Images (requires your image assets with transparent backgrounds) */
.floating-spice-img {
    position: absolute;
    width: 200px; /* Adjust size */
    top: 50px;
    left: -50px; /* Position off-screen left to match image */
    z-index: 1;
    filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));
}

.floating-tomatoes-img {
    position: absolute;
    width: 180px; /* Adjust size */
    bottom: 50px;
    right: 50px; /* Position */
    z-index: 1;
    filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));
}

.floating-sauce-img {
    position: absolute;
    width: 120px; /* Adjust size */
    bottom: 0px;
    left: 200px; /* Position relative to main dumpling */
    z-index: 2;
    filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));
}

/* Abstract White Circle */
.abstract-circle {
    position: absolute;
    top: 15%;
    right: 15%;
    width: 40px;
    height: 40px;
    background-color: rgba(255, 255, 255, 0.7); /* Slightly transparent white */
    border-radius: 50%;
    box-shadow: 0 0 15px rgba(0,0,0,0.05);
    z-index: 1;
}


/* Responsive Adjustments */
@media (max-width: 991.98px) {
    .custom-navbar {
        padding-left: 20px;
        padding-right: 20px;
    }
    .left-content {
        padding-left: 20px;
        padding-right: 20px;
        text-align: center;
    }
    .main-heading {
        font-size: 3em;
    }
    .sub-heading {
        font-size: 1.5em;
    }
    .description {
        margin-left: auto;
        margin-right: auto;
    }
    .explore-btn {
        margin-left: auto;
        margin-right: auto;
        display: block;
    }
    .right-image-content {
        min-height: 400px;
        padding-top: 50px;
        padding-bottom: 50px;
    }
    .main-dumpling-image {
        position: relative; /* Change to relative for better centering on small screens */
        transform: none;
        top: auto;
        left: auto;
        max-width: 70%;
    }
    .floating-spice-img,
    .floating-tomatoes-img,
    .floating-sauce-img {
        display: none; /* Hide floating elements on smaller screens for simplicity */
    }
    .abstract-circle {
        display: none;
    }
}

@media (max-width: 767.98px) {
    .custom-navbar .navbar-nav {
        text-align: center;
        width: 100%;
    }
    .custom-navbar .nav-item {
        margin-right: 0;
    }
    .main-heading {
        font-size: 2.5em;
    }
    .sub-heading {
        font-size: 1.2em;
    }
    .description {
        font-size: 0.9em;
    }
}</style>
<body>

    <div class="main-wrapper container-fluid p-0">
        <nav class="navbar navbar-expand-lg custom-navbar py-3 px-5">
            <div class="container-fluid">
                <a class="navbar-brand luscious-logo" href="#">luscious</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/pages/register">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/pages/login">Login</a>
                        </li>
                        <li class="nav-item me-4">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/pages/home">Guest</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        <section class="hero-section row g-0">
            <div class="col-lg-6 left-content d-flex flex-column justify-content-center px-5 py-5">
                <!-- <h1 class="display-3 main-heading">Take a taste <br> Come join us.</h1> -->
                <h2 class="sub-heading">Experience the Magic of Cinema.</h2>
                <p class="description">Discover the latest blockbusters, timeless classics, and captivating indie films.
                        Book your tickets online, choose your favorite seats, and enjoy an unforgettable cinematic journey.
                        Your ultimate movie experience starts here! We offer a wide range of genres, from action-packed thrillers to heartwarming dramas, ensuring there's something for everyone.</p>
                <a href="<?php echo URLROOT; ?>/pages/home"><button class="btn explore-btn mt-4">Explore Now</button></a>
            </div>
            <div class="col-lg-6 right-image-content position-relative d-flex justify-content-center align-items-center">
                <img src="your-dumplings-image.png" alt="Delicious Dumplings" class="img-fluid main-dumpling-image">

                <img src="your-spices-image.png" alt="Spices" class="img-fluid floating-spice-img">
                <img src="your-tomatoes-image.png" alt="Tomatoes and Onion" class="img-fluid floating-tomatoes-img">
                <img src="your-sauce-bowl-image.png" alt="Sauce Bowl" class="img-fluid floating-sauce-img">

                <div class="abstract-circle"></div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>