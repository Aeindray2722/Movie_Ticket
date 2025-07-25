<?php
    require_once __DIR__ . '/../layout/nav.php';
?>

<section class="main-movie-carousel py-5">
        <div class="container">
            <div id="movieCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <img src="https://via.placeholder.com/250x380?text=Tangled" class="img-fluid" alt="Tangled">
                            </div>
                            <div class="col-md-8 text-start">
                                <h2 class="movie-title">Movie Name</h2>
                                <p class="movie-type">Movie Type</p>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo URLROOT; ?>/movie/movieDetail"><button class="btn btn-secondary btn-sm">View Detail</button></a>
                                    <button class="btn btn-outline-secondary btn-sm">▶️ watch trailer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item active">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <img src="https://via.placeholder.com/250x380?text=Tangled" class="img-fluid" alt="Tangled">
                            </div>
                            <div class="col-md-8 text-start">
                                <h2 class="movie-title">Movie Name</h2>
                                <p class="movie-type">Movie Type</p>
                                <div class="d-flex gap-2">
                                     <a href="<?php echo URLROOT; ?>/movie/movieDetail"><button class="btn btn-secondary btn-sm">View Detail</button></a>
                                    <button class="btn btn-outline-secondary btn-sm">▶️ watch trailer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                <button class="carousel-control-prev " type="button" data-bs-target="#movieCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#movieCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <section class="movie-section py-4">
        <div class="container">
            <h3 class="section-title d-flex justify-content-between align-items-center">
                Now Showing
                <a href="<?php echo URLROOT; ?>/movie/nowShowing" class="text-dark text-decoration-none">›</a>
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
                        <img src="https://via.placeholder.com/150x225?text=Movie+3" class="card-img-top" alt="Movie 3">
                    </div>
                </div>
                <div class="col">
                    <div class="card movie-card">
                        <img src="https://via.placeholder.com/150x225?text=Movie+4" class="card-img-top" alt="Movie 4">
                    </div>
                </div>
                <div class="col">
                    <div class="card movie-card">
                        <img src="https://via.placeholder.com/150x225?text=Movie+5" class="card-img-top" alt="Movie 5">
                    </div>
                </div>
            </div>

           

            <h3 class="section-title d-flex justify-content-between align-items-center">
                Movie Trailer
                <a href="<?php echo URLROOT; ?>/movie/trailer" class="text-dark text-decoration-none">›</a>
            </h3>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3 mb-4">
                <div class="col">
                    <div class="card movie-card">
                        <img src="https://via.placeholder.com/150x225?text=Movie+X" class="card-img-top" alt="Movie X">
                    </div>
                </div>
                <div class="col">
                    <div class="card movie-card">
                        <img src="https://via.placeholder.com/150x225?text=Movie+Y" class="card-img-top" alt="Movie Y">
                    </div>
                </div>
                <div class="col">
                    <div class="card movie-card">
                        <img src="https://via.placeholder.com/150x225?text=Movie+Z" class="card-img-top" alt="Movie Z">
                    </div>
                </div>
                <div class="col">
                    <div class="card movie-card">
                        <img src="https://via.placeholder.com/150x225?text=Cargo" class="card-img-top" alt="Cargo">
                    </div>
                </div>
                <div class="col">
                    <div class="card movie-card">
                        <img src="https://via.placeholder.com/150x225?text=Movie+Q" class="card-img-top" alt="Movie Q">
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
    require_once __DIR__ . '/../layout/footer.php';
?>