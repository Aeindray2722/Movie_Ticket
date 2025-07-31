<?php
require_once __DIR__ . '/../layout/nav.php';
?>

<section class="now-showing-page-content py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="section-heading mb-4">Movie Trailer</h2>

            </div>
            <div class="search-bar-on-page">
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="search" aria-label="Search">
                    <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>

        <div class="filter-buttons mb-4 me-3">
            <a href="<?= URLROOT ?>/trailer/trailer"
                class="btn btn-filter <?= empty($_GET['type']) ? 'active' : '' ?>">All</a>

            <?php foreach ($data['types'] as $t): ?>
                <a href="<?= URLROOT ?>/trailer/trailer?type=<?= strtolower($t['name']) ?>"
                    class="btn btn-filter <?= ($_GET['type'] ?? '') === strtolower($t['name']) ? 'active' : '' ?>">
                    <?= ucfirst($t['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2 gy-5 mb-4">
            <?php foreach ($data['trailers'] as $trailer): ?>
                <div class="col ">
                    <div class="card movie-card-lg shadow-sm">
                        <div class="row g-0 ">
                            <div class="col-md-4">
                                <img src="<?= URLROOT . '/images/movies/' . htmlspecialchars($trailer['movie_img']) ?>"
                                    class="img-fluid rounded-start movie-poster-lg shadow-sm"
                                    alt="<?= $trailer['movie_name']; ?>">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <p class="card-text-lg"><strong style="color : black">Title:</strong>
                                        <?= htmlspecialchars($trailer['movie_name'] ?? 'N/A'); ?></p>
                                    <p class="card-text-lg"><strong style="color : black">Type:</strong>
                                        <?= htmlspecialchars($trailer['type_name'] ?? 'N/A'); ?></p>
                                    <p class="card-text-lg"><strong style="color : black">Actor:</strong>
                                        <?= htmlspecialchars($trailer['actor_name'] ?? 'N/A'); ?></p>
                                    <a href="<?= URLROOT; ?>/trailer/movieDetail/<?= $trailer['movie_id'] ?>">
                                        <button class="btn btn-view-detail">View Detail</button>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?> <!-- ✅ close foreach here -->
        </div> <!-- ✅ close row after the loop -->
        <?php if (!empty($_GET['type']) && empty($data['now_showing_movies'])): ?>
            <div class="alert alert-warning text-center">No <?= htmlspecialchars($_GET['type']) ?> movies .</div>
        <?php endif; ?>

        <?php if ($data['totalPages'] > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">
                    <?php for ($p = 1; $p <= $data['totalPages']; $p++): ?>
                        <li class="page-item <?= ($p == $data['page']) ? 'active' : '' ?>">
                            <a class="page-link" href="?type=<?= urlencode($data['type'] ?? '') ?>&page=<?= $p ?>"><?= $p ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</section>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>