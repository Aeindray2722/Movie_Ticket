<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Website</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/customer.css" />
    <style>
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Fixed navbar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: #343a40 !important;
        }
        
        /* Add padding to body to account for fixed navbar */
        body {
            padding-top: 70px;
        }
        
        /* Section styling */
        section {
            min-height: 100vh;
            padding-top: 2rem;
        }
        
        /* Movie card styling */
        .movie-card {
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        
        .movie-card:hover {
            transform: translateY(-5px);
        }
        
        .movie-card-lg {
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        
        .movie-card-lg:hover {
            transform: translateY(-3px);
        }
        
        /* Carousel styling */
        .main-movie-carousel {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        /* Filter buttons */
        .btn-filter {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #495057;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .btn-filter.active {
            background-color: #495057;
            color: white;
        }
        
        /* Team member styling */
        .team-member {
            text-align: center;
            padding: 1rem;
        }
        
        .team-member img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }
        
        /* About us styling */
        .about-us-image {
            max-width: 300px;
        }
        
        /* Active nav link styling */
        .navbar-nav .nav-link.active {
            color: #ffc107 !important;
            font-weight: bold;
        }
        
        /* Section visibility for scroll spy */
        .section-marker {
            position: absolute;
            top: -70px;
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target="#navbar" data-bs-offset="100">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="#home">Movie Hub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#now-showing">Now Showing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#movie-trailer">Movie Trailer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Home Section -->
    <section id="home" class="main-movie-carousel py-5">
        <div class="section-marker"></div>
        <div class="container">
            <div id="movieCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <img src="https://via.placeholder.com/250x380?text=Tangled" class="img-fluid" alt="Tangled">
                            </div>
                            <div class="col-md-8 text-start">
                                <h2 class="movie-title">Tangled</h2>
                                <p class="movie-type">Animation • Adventure • Comedy</p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-secondary btn-sm" onclick="scrollToSection('movie-detail')">View Detail</button>
                                    <button class="btn btn-outline-secondary btn-sm">▶️ Watch Trailer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <img src="https://via.placeholder.com/250x380?text=Avengers" class="img-fluid" alt="Avengers">
                            </div>
                            <div class="col-md-8 text-start">
                                <h2 class="movie-title">Avengers: Endgame</h2>
                                <p class="movie-type">Action • Adventure • Drama</p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-secondary btn-sm" onclick="scrollToSection('movie-detail')">View Detail</button>
                                    <button class="btn btn-outline-secondary btn-sm">▶️ Watch Trailer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#movieCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#movieCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <!-- Featured Movies Preview -->
        <div class="container mt-5">
            <h3 class="section-title d-flex justify-content-between align-items-center text-white">
                Featured Movies
                <a href="#now-showing" class="text-white text-decoration-none">›</a>
            </h3>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3 mb-4">
                <div class="col">
                    <div class="card movie-card">
                        <img src="https://via.placeholder.com/150x225?text=Tangled" class="card-img-top" alt="Tangled">
                    </div>
                </div>
                <div class="col">
                    <div class="card movie-card">
                        <img src="https://via.placeholder.com/150x225?text=Cargo" class="card-img-top" alt="Cargo">
                    </div>
                </div>
                <div class="col">
                    <div class="card movie-card">
                        <img src="https://via.placeholder.com/150x225?text=Spider-Man" class="card-img-top" alt="Spider-Man">
                    </div>
                </div>
                <div class="col">
                    <div class="card movie-card">
                        <img src="https://via.placeholder.com/150x225?text=Wonder+Woman" class="card-img-top" alt="Wonder Woman">
                    </div>
                </div>
                <div class="col">
                    <div class="card movie-card">
                        <img src="https://via.placeholder.com/150x225?text=Black+Panther" class="card-img-top" alt="Black Panther">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Now Showing Section -->
    <section id="now-showing" class="now-showing-page-content py-4">
        <div class="section-marker"></div>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="section-heading mb-4">Now Showing</h2>
                    <p class="section-subheading mt-3">Today</p>
                </div>
                <div class="search-bar-on-page">
                    <form class="d-flex">
                        <input class="form-control me-2" type="search" placeholder="Search movies..." aria-label="Search">
                        <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <div class="filter-buttons mb-4 me-3">
                <button class="btn btn-filter active" onclick="filterMovies('all')">All</button>
                <button class="btn btn-filter" onclick="filterMovies('english')">English</button>
                <button class="btn btn-filter" onclick="filterMovies('myanmar')">Myanmar</button>
                <button class="btn btn-filter" onclick="filterMovies('korea')">Korea</button>
                <button class="btn btn-filter" onclick="filterMovies('india')">India</button>
                <button class="btn btn-filter" onclick="filterMovies('cartoon')">Cartoon</button>
                <button class="btn btn-filter" onclick="filterMovies('japan')">Japan</button>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2 g-4 mb-4">
                <div class="col" data-category="cartoon">
                    <div class="card movie-card-lg">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/180x250?text=Tangled" class="img-fluid rounded-start movie-poster-lg" alt="Tangled">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title-lg">Tangled</h5>
                                    <p class="card-text-lg">Animation • Adventure</p>
                                    <button class="btn btn-view-detail btn-secondary">View Detail</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-category="english">
                    <div class="card movie-card-lg">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/180x250?text=Cargo" class="img-fluid rounded-start movie-poster-lg" alt="Cargo">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title-lg">Cargo</h5>
                                    <p class="card-text-lg">Thriller • Drama</p>
                                    <button class="btn btn-view-detail btn-secondary">View Detail</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-category="korea">
                    <div class="card movie-card-lg">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/180x250?text=Parasite" class="img-fluid rounded-start movie-poster-lg" alt="Parasite">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title-lg">Parasite</h5>
                                    <p class="card-text-lg">Drama • Thriller</p>
                                    <button class="btn btn-view-detail btn-secondary">View Detail</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-category="english">
                    <div class="card movie-card-lg">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/180x250?text=Avengers" class="img-fluid rounded-start movie-poster-lg" alt="Avengers">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title-lg">Avengers: Endgame</h5>
                                    <p class="card-text-lg">Action • Adventure</p>
                                    <button class="btn btn-view-detail btn-secondary">View Detail</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-category="japan">
                    <div class="card movie-card-lg">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/180x250?text=Your+Name" class="img-fluid rounded-start movie-poster-lg" alt="Your Name">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title-lg">Your Name</h5>
                                    <p class="card-text-lg">Animation • Romance</p>
                                    <button class="btn btn-view-detail btn-secondary">View Detail</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-category="india">
                    <div class="card movie-card-lg">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/180x250?text=RRR" class="img-fluid rounded-start movie-poster-lg" alt="RRR">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title-lg">RRR</h5>
                                    <p class="card-text-lg">Action • Drama</p>
                                    <button class="btn btn-view-detail btn-secondary">View Detail</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&lt;</span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&gt;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </section>

    <!-- Movie Trailer Section -->
    <section id="movie-trailer" class="now-showing-page-content py-4" style="background-color: #f8f9fa;">
        <div class="section-marker"></div>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="section-heading mb-4">Movie Trailers</h2>
                </div>
                <div class="search-bar-on-page">
                    <form class="d-flex">
                        <input class="form-control me-2" type="search" placeholder="Search trailers..." aria-label="Search">
                        <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <div class="filter-buttons mb-4 me-3">
                <button class="btn btn-filter active" onclick="filterTrailers('all')">All</button>
                <button class="btn btn-filter" onclick="filterTrailers('english')">English</button>
                <button class="btn btn-filter" onclick="filterTrailers('myanmar')">Myanmar</button>
                <button class="btn btn-filter" onclick="filterTrailers('korea')">Korea</button>
                <button class="btn btn-filter" onclick="filterTrailers('india')">India</button>
                <button class="btn btn-filter" onclick="filterTrailers('cartoon')">Cartoon</button>
                <button class="btn btn-filter" onclick="filterTrailers('japan')">Japan</button>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2 g-4 mb-4">
                <div class="col" data-trailer-category="cartoon">
                    <div class="card movie-card-lg">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/180x250?text=Frozen+2" class="img-fluid rounded-start movie-poster-lg" alt="Frozen 2">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title-lg">Frozen 2</h5>
                                    <p class="card-text-lg">Animation • Musical</p>
                                    <button class="btn btn-view-detail btn-primary">▶️ Watch Trailer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-trailer-category="english">
                    <div class="card movie-card-lg">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/180x250?text=Top+Gun" class="img-fluid rounded-start movie-poster-lg" alt="Top Gun">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title-lg">Top Gun: Maverick</h5>
                                    <p class="card-text-lg">Action • Drama</p>
                                    <button class="btn btn-view-detail btn-primary">▶️ Watch Trailer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-trailer-category="korea">
                    <div class="card movie-card-lg">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/180x250?text=Squid+Game" class="img-fluid rounded-start movie-poster-lg" alt="Squid Game">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title-lg">Squid Game</h5>
                                    <p class="card-text-lg">Thriller • Drama</p>
                                    <button class="btn btn-view-detail btn-primary">▶️ Watch Trailer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-trailer-category="japan">
                    <div class="card movie-card-lg">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/180x250?text=Demon+Slayer" class="img-fluid rounded-start movie-poster-lg" alt="Demon Slayer">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title-lg">Demon Slayer</h5>
                                    <p class="card-text-lg">Animation • Action</p>
                                    <button class="btn btn-view-detail btn-primary">▶️ Watch Trailer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&lt;</span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&gt;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="about-us-page-content py-4">
        <div class="section-marker"></div>
        <div class="container">
            <div class="card about-us-card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-7 col-lg-8">
                            <h3 class="about-us-section-title mb-3">Our Story</h3>
                            <p class="about-us-text">
                                Welcome to Movie Hub, your premier destination for the latest and greatest in cinema entertainment. 
                                Since our founding, we've been dedicated to bringing you the most comprehensive movie experience, 
                                from blockbuster hits to indie gems. Our passion for storytelling drives us to curate an exceptional 
                                collection of films from around the world, ensuring there's something for every movie lover.
                            </p>
                            <p class="about-us-text">
                                At Movie Hub, we believe that movies have the power to inspire, entertain, and bring people together. 
                                Our team works tirelessly to provide you with the best viewing experience, complete with detailed 
                                information, trailers, and exclusive content that enhances your movie journey.
                            </p>
                        </div>
                        <div class="col-md-5 col-lg-4 text-center">
                            <img src="https://via.placeholder.com/300x200?text=Cinema+Experience" class="img-fluid rounded about-us-image" alt="Cinema Seats">
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <div class="card about-us-card mt-5">
                <div class="card-body">
                    <h3 class="about-us-section-title mb-4">Our Team</h3>
                    <div class="row text-center">
                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                            <div class="team-member">
                                <img src="https://via.placeholder.com/120x120?text=Sarah" alt="Sarah Johnson">
                                <h5 class="team-member-name">Sarah Johnson</h5>
                                <p class="team-member-role">Director & Founder</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                            <div class="team-member">
                                <img src="https://via.placeholder.com/120x120?text=Mike" alt="Mike Chen">
                                <h5 class="team-member-name">Mike Chen</h5>
                                <p class="team-member-role">Content Curator</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                            <div class="team-member">
                                <img src="https://via.placeholder.com/120x120?text=Emma" alt="Emma Rodriguez">
                                <h5 class="team-member-name">Emma Rodriguez</h5>
                                <p class="team-member-role">Marketing Director</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                            <div class="team-member">
                                <img src="https://via.placeholder.com/120x120?text=David" alt="David Kim">
                                <h5 class="team-member-name">David Kim</h5>
                                <p class="team-member-role">Technical Lead</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Movie Hub</h5>
                    <p>Your premier destination for movies and entertainment.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2025 Movie Hub. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Update active nav link based on scroll position
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            
            let currentSection = '';
            sections.forEach(section => {
                const rect = section.getBoundingClientRect();
                if (rect.top <= 100 && rect.bottom >= 100) {
                    currentSection = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + currentSection) {
                    link.classList.add('active');
                }
            });
        });

        // Filter movies function
        function filterMovies(category) {
            const movieCards = document.querySelectorAll('#now-showing .col[data-category]');
            const filterButtons = document.querySelectorAll('#now-showing .btn-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Show/hide movies
            movieCards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Filter trailers function
        function filterTrailers(category) {
            const trailerCards = document.querySelectorAll('#movie-trailer .col[data-trailer-category]');
            const filterButtons = document.querySelectorAll('#movie-trailer .btn-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Show/hide trailers
            trailerCards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-trailer-category') === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Function to scroll to specific section
        function scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }

        // Initialize - set home as active on page load
        document.addEventListener('DOMContentLoaded', function() {
            const homeLink = document.querySelector('.navbar-nav .nav-link[href="#home"]');
            if (homeLink) {
                homeLink.classList.add('active');
            }
        });
    </script>
</body>
</html>