<?php 
extract($data); 
require_once __DIR__ . '/../layout/sidebar.php';
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div class="trailer-content-wrapper">
    <?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>
    <div class="trailer-content-section">
        <div class="trailer-form-card">
            <!-- <h4>Add Trailer</h4> -->
            <form action="<?php echo URLROOT; ?>/trailer/store" method="post" enctype="multipart/form-data">
                <!-- CSRF hidden input -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class=" mb-4">
                    <h4>Add Trailer</h4>
                </div>

                <!-- <div class="video-placeholder mb-3 mx-auto">
                    <i class="fas fa-video"></i>
                </div> -->
                <div class="mb-3 d-flex align-items-end">
                    <!-- <label for="trailer_file" class="custom-file-upload me-2" visually-hidden>Choose file</label> -->
                    <input type="file" id="trailer_file" name="trailer_file" accept="video/*" required>
                    <!-- <span id="file-name" class="file-name-display">No file chosen</span> -->
                </div>

                <div class="mb-3">

                    <label for="type" class="form-label visually-hidden">Movie Name</label>
                    <select class="form-select" id="movie_id" name="movie_id" required>
                        <option value="">Select Movie</option>

                        <?php foreach ($data['movies'] as $movie): ?>
                            <option value="<?= htmlspecialchars($movie['id']) ?>">
                                <?= htmlspecialchars($movie['movie_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="d-grid movie-button">
                    <button type="submit" class="btn btn-add-movie">Add trailer</button>
                </div>
            </form>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <h4>Trailer</h4>
                <table class="table table-bordered table-custom align-middle">
                    <thead>
                        <tr>

                            <th>Movie Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        // Create a map from type_id to type name
                        $typeMap = [];
                        foreach ($data['types'] as $type) {
                            $typeMap[$type['id']] = $type['name'];
                        }

                        // Create a map from show_time_id to show_time name
                        $movieMap = [];
                        foreach ($data['movies'] as $movie) {
                            $movieMap[$movie['id']] = $movie['movie_name'];
                        }

                        if (!empty($data['trailers'])) {
                            foreach ($data['trailers'] as $trailer) {
                                echo '<tr>';
                                echo '<td>';
                                echo isset($movieMap[$trailer['movie_id']]) ? htmlspecialchars($movieMap[$trailer['movie_id']]) : 'Unknown';
                                echo '</td>';
                                echo '<td class="text-center">';
                                echo '<a href="' . URLROOT . '/trailer/edit/' . $trailer['id'] . '" class="btn btn-sm btn-outline-primary me-1" title="Edit">';
                                echo '<i class="fas fa-edit"></i></a>';

                                echo '<button class="btn-action btn btn-sm btn-outline-danger" onclick="deleteMovieType(\'' . base64_encode($trailer['id']) . '\')">';

                                echo '<i class="fas fa-trash-alt"></i></button>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="2" class="text-center">No movie trailer found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <!-- pagination -->
                <?php if (!empty($totalPages) && $totalPages > 1): ?>
                    <nav aria-label="Page navigation ">
                        <ul class="pagination justify-content-end ">
                            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
                                    <a class="page-link " href="?page=<?= $p ?>"><?= $p ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function deleteMovieType(encodedId) {
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
                window.location.href = '<?php echo URLROOT; ?>/trailer/destroy/' + encodedId;
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