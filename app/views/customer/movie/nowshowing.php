<?php 
extract($data); 
require_once __DIR__ . '/../layout/nav.php'; 
// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<section class="now-showing-page-content py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <div>
                <h2 class="section-heading mb-4">Now Showing</h2>
                <p class="section-subheading mt-3">Today</p>
            </div>
            <div class="search-bar-on-page">
                <form class="d-flex" method="GET" action="<?= URLROOT ?>/movie/nowShowing">
                    <!-- CSRF hidden input -->
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="type" value="<?= htmlspecialchars($data['type'] ?? '') ?>">
                    <input class="form-control me-2" type="search" name="search"
                        value="<?= htmlspecialchars($data['search'] ?? '') ?>" placeholder="search">
                    <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>

        <div class="filter-buttons mb-4 me-3">
            <a href="<?= URLROOT ?>/movie/nowShowing"
                class="btn btn-filter <?= empty($_GET['type']) ? 'active' : '' ?>">All</a>
            <?php foreach ($data['types'] as $t): ?>
                <a href="<?= URLROOT ?>/movie/nowShowing?type=<?= strtolower($t['name']) ?>"
                    class="btn btn-filter <?= ($_GET['type'] ?? '') === strtolower($t['name']) ? 'active' : '' ?>">
                    <?= ucfirst($t['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="row gy-5 mb-4">
            <?php foreach ($data['now_showing_movies'] as $movie): ?>
                <div class="col-12 col-md-6">
                    <div class="card movie-card-lg shadow-sm h-100">
                        <div class="row g-0 flex-column flex-md-row">
                            <div class="col-12 col-md-4">
                                <img src="<?= URLROOT . '/images/movies/' . htmlspecialchars($movie['movie_img']) ?>"
                                    class="img-fluid rounded-top rounded-md-start movie-poster-lg shadow-sm w-100"
                                    alt="<?= $movie['movie_name']; ?>">
                            </div>
                            <div class="col-12 col-md-8">
                                <div class="card-body">
                                    <p class="card-text-lg"><strong style="color : black">Title:</strong>
                                        <?= htmlspecialchars($movie['movie_name'] ?? 'N/A'); ?></p>
                                    <p class="card-text-lg"><strong style="color : black">Type:</strong>
                                        <?= htmlspecialchars($movie['type_name'] ?? 'N/A'); ?></p>
                                    <p class="card-text-lg"><strong style="color : black">Actor:</strong>
                                        <?= htmlspecialchars($movie['actor_name'] ?? 'N/A'); ?></p>
                                    <a href="<?= URLROOT; ?>/movie/movieDetail/<?= $movie['id'] ?>">
                                        <button class="btn btn-view-detail mt-3">View Detail</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($_GET['type']) && empty($data['now_showing_movies'])): ?>
            <div class="alert alert-warning text-center">No <?= htmlspecialchars($_GET['type']) ?> movies found.</div>
        <?php endif; ?>

        <?php if ($data['totalPages'] > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">
                    <?php for ($p = 1; $p <= $data['totalPages']; $p++): ?>
                        <li class="page-item <?= ($p == $data['page']) ? 'active' : '' ?>">
                            <a class="page-link"
                                href="?type=<?= urlencode($data['type'] ?? '') ?>&search=<?= urlencode($data['search'] ?? '') ?>&page=<?= $p ?>">
                                <?= $p ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>