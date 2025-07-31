<?php extract($data); ?>
<?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="list-content-wrapper">
    <?php require_once __DIR__ . '/../layout/nav.php'; ?>

    <div class="movie-list-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <form class="d-flex" action="<?= URLROOT ?>/movie/index" method="get">
                <input class="form-control me-2" type="search" placeholder="Search" name="search"
                    value="<?= htmlspecialchars($search ?? '') ?>">
                <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
            </form>

            <a href="<?= URLROOT ?>/movie/create">
                <button class="btn btn-movie-add">Add Movie</button>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Start date</th>
                        <th>End date</th>
                        <th>Show time</th>
                        <th>Actor/Actress</th>
                        <th>Genre</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($movies)): ?>
                        <tr>
                            <td colspan="9" class="text-center">No movies found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($movies as $movie): ?>
                            <tr>
                                <td>
                                    <img src="<?= URLROOT . '/images/movies/' . htmlspecialchars($movie['movie_img']) ?>"
                                        alt="<?= htmlspecialchars($movie['movie_name']) ?>" class="movie-thumb">
                                </td>
                                <td><?= htmlspecialchars($movie['movie_name']) ?></td>
                                <td><?= htmlspecialchars($movie['type_name']) ?></td>
                                <td><?= htmlspecialchars($movie['start_date']) ?></td>
                                <td><?= htmlspecialchars($movie['end_date']) ?></td>
                                <td><?= htmlspecialchars($movie['show_time_list']) ?></td>
                                <td><?= htmlspecialchars($movie['actor_name']) ?></td>
                                <td><?= htmlspecialchars($movie['genre']) ?></td>
                                <td class="text-center">
                                    <a href="<?= URLROOT . '/movie/edit/' . $movie['id'] ?>"
                                        class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger btn-action"
                                        onclick="deleteMovie('<?= base64_encode($movie['id']) ?>')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?search=<?= urlencode($search ?? '') ?>&page=<?= $p ?>"><?= $p ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function deleteMovie(encodedId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= URLROOT ?>/movie/destroy/' + encodedId;
            }
        });
    }
</script>

<?php if (isset($_SESSION['success'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= $_SESSION['success'] ?>',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>



