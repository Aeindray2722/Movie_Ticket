<?php
require_once __DIR__ . '/../layout/sidebar.php';
?>
<div class="type-content-wrapper">
    <?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>
    <div class="dashboard-content">
        <div class="movie-type-container">
            <div class="movie-type-form-card">
                <h4>Add Movie Type</h4>
                <form action="<?php echo URLROOT; ?>/type/store" method="post">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="movieType" name="type_name" placeholder="Movie type"
                            required>
                    </div>
                    <button type="submit" class="btn btn-add-movie-type">Add</button>
                </form>
            </div>

            <div class="movie-type-list-card">
                <h4>Movie Type</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Movie Type</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php


                            if (!empty($data['movieData'])) {
                                foreach ($data['movieData'] as $type) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($type['name']) . '</td>';
                                    echo '<td>';
                                    echo '<a href="' . URLROOT . '/type/editType/' . $type['id'] . '"><button class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></button></a>';
                                    echo '<button class="btn-action delete" onclick="deleteMovieType(\'' . base64_encode($type['id']) . '\')">
                                            <i class="fas fa-trash-alt"></i></button>';

                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="2" class="text-center">No movie types found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- pagination -->
                    <?php if ($data['totalPages'] > 1): ?>
                        <nav aria-label="Page navigation ">
                            <ul class="pagination justify-content-end ">
                                <?php for ($p = 1; $p <= $data['totalPages']; $p++): ?>
                                    <li class="page-item <?= ($p == $data['page']) ? 'active' : '' ?>">
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
</div>
<!-- SweetAlert2 CSS & JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function deleteMovieType(encodedId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
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