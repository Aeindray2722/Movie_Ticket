<?php
require_once __DIR__ . '/../layout/sidebar.php';
// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div class="detail-content-wrapper">
    <?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>
    <div class="content-area">
        <div class="back-arrow-container">
            <a href="<?php echo URLROOT; ?>/trailer/create" class="d-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i>
            </a>
        </div>
        <div class="profile-card-container">
            <div class="profile-header-text">
                <h4>Update Movie Trailer</h4>
            </div>
            <div class="profile-content">

                <div class="profile-edit-form">
                    <form action="<?php echo URLROOT; ?>/trailer/update" method="post" enctype="multipart/form-data">
                        <!-- CSRF hidden input -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="id" value="<?= $data['trailers']['id'] ?>">
                        <div class="mb-3 d-flex align-items-end">
                            <input type="file" id="trailer_file" name="trailer_file" accept="video/*"
                                onchange="previewFile(event)">
                            <span id="file-name-display" class="file-name-display mt-2">
                                <?= !empty($data['trailers']['trailer_vd']) ? htmlspecialchars($data['trailers']['trailer_vd']) : 'No file chosen' ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <select class="form-select" id="type" name="movie_name" required>
                                <option value="">Select Movie</option>
                                <?php foreach ($data['movies'] as $movie): ?>
                                    <option value="<?= htmlspecialchars($movie['id']) ?>"
                                        <?= $movie['id'] == $data['trailers']['movie_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($movie['movie_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button class="btn btn-update-profile">Update</button>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to update file name display
    document.getElementById('profileImage').addEventListener('change', function () {
        const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
        document.getElementById('profileFileName').textContent = fileName;
    });
</script>