<?php
require_once __DIR__ . '/../layout/nav.php';
?>

<section class="main-movie-carousel py-4">
    <div class="container">
        <div id="movieCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($data['now_showing_movies'] as $index => $movie): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="d-flex align-items-center justify-content-center" style="min-height: 300px;">
                            <div class="row w-100 align-items-center justify-content-center text-center text-md-start">
                                <div class="col-12 col-md-4 mb-3 mb-md-0 text-center">
                                    <div class="ratio ratio-1x1 mx-auto" style="max-width: 250px;">
                                        <img src="<?= URLROOT . '/images/movies/' . htmlspecialchars($movie['movie_img']) ?>"
                                            class="img-fluid rounded object-fit-cover"
                                            alt="<?= htmlspecialchars($movie['movie_name']) ?>">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <h4 class="movie-title mb-1"><?= htmlspecialchars($movie['movie_name']) ?></h4>
                                    <p class="movie-type mb-2"><?= htmlspecialchars($movie['type_name'] ?? 'N/A') ?></p>
                                    <div class="d-flex gap-2 justify-content-center justify-content-md-start">
                                        <a href="<?= URLROOT; ?>/movie/movieDetail/<?= $movie['id'] ?>">
                                            <button class="btn btn-secondary btn-sm">View Detail</button>
                                        </a>
                                        <a href="<?= URLROOT; ?>/trailer/movieDetail/<?= $movie['id'] ?>">
                                            <button class="btn btn-outline-secondary btn-sm">▶️ Watch Trailer</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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
</section>
<section class="movie-section py-4">
    <div class="container">
        <h2 class="section-title d-flex justify-content-between align-items-center">
            Now Showing
            <a href="<?php echo URLROOT; ?>/movie/nowShowing" class="text-dark text-decoration-none">›</a>
        </h2>

        <div class="row g-3 mb-4">
            <?php foreach ($data['now_showing_movies'] as $movie): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="<?= URLROOT . '/movie/movieDetail/' . $movie['id'] ?>" class="text-decoration-none text-dark">
                        <div class="card movie-card h-100">
                            <img src="<?= URLROOT . '/images/movies/' . htmlspecialchars($movie['movie_img']) ?>"
                                class="card-img-top" alt="<?= htmlspecialchars($movie['movie_name']) ?>">
                            <div class="card-body p-2">
                                <h5 class="card-title mb-1 text-truncate"><?= htmlspecialchars($movie['movie_name']) ?></h5>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>


        <h2 class="section-title d-flex justify-content-between align-items-center">
            Movie Trailer
            <a href="<?php echo URLROOT; ?>/trailer/trailer" class="text-dark text-decoration-none">›</a>
        </h2>
        <div class="row g-3 mb-4 mt-4">
            <?php foreach ($data['trailers'] as $trailer): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="<?= URLROOT . '/trailer/movieDetail/' . $trailer['movie_id'] ?>"
                        class="text-decoration-none text-dark">
                        <div class="card movie-card h-100">
                            <img src="<?= URLROOT . '/images/movies/' . htmlspecialchars($trailer['movie_img']) ?>"
                                class="card-img-top" alt="<?= htmlspecialchars($trailer['movie_name']) ?>">
                            <div class="card-body p-2">
                                <h5 class="card-title mb-1 text-truncate"><?= htmlspecialchars($trailer['movie_name']) ?>
                                </h5>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>