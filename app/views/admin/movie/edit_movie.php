<?php require_once __DIR__ . '/../layout/sidebar.php'; ?>
<?php extract($data); ?>

<div class="main-content-wrapper">
    <?php require_once __DIR__ . '/../layout/nav.php'; ?>
    <div class="back-arrow-container">
            <a href="<?php echo URLROOT; ?>/movie/movie_list" class="d-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i>
            </a>
        </div>
    <div class="form-card">
        <form action="<?= URLROOT ?>/movie/update" method="post" enctype="multipart/form-data" id="movieForm" novalidate>
            <input type="hidden" name="id" value="<?= $movies['id'] ?>">

            <div class="row mb-3">

                <div class="col-md-4">
                     <?php $image_path = __DIR__ . '/../../../../public/images/movies/' . $movies['movie_img'];?>
                    <div class="mb-3">
                        <!-- <label class="form-label d-block">Current Image:</label> -->
                        <div class="image-placeholder" id="imagePreview">
                           
                            <?php if (!empty($movies['movie_img']) && file_exists($image_path)) : ?>
                                <img src="<?= URLROOT ?>/images/movies/<?= htmlspecialchars($movies['movie_img']) ?>" class="img-fluid" style="max-height: 200px;" />
                            <?php else: ?>
                                <i class="fas fa-image fa-5x text-muted"></i>
                            <?php endif; ?>
                        </div>

                    </div>

                    <div class="choose-file-section">
                        <label for="movie_image" class="custom-file-upload btn btn-primary">Choose file</label>
                        <input type="file" class="form-control" id="movie_image" name="movie_image" accept="image/*" onchange="previewFile(event)">
                         <span id="file-name-display" class="file-name-display mt-2">
                            <?= !empty($movies['movie_img']) ? htmlspecialchars($movies['movie_img']) : 'No file chosen' ?>
                        </span>
                    </div>
                </div>


                <div class="col-md-8">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="name" name="movie_name" placeholder="Name" value="<?= htmlspecialchars($movies['movie_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="type" name="type_id" required>
                                <option value="">Select Type</option>
                                <?php foreach ($types as $type): ?>
                                    <option value="<?= htmlspecialchars($type['id']) ?>" <?= $type['id'] == $movies['type_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($type['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($movies['start_date']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($movies['end_date']) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="genre" name="genre" placeholder="Genre" value="<?= htmlspecialchars($movies['genre']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="actor_actress" name="actor_name" placeholder="Actor/Actress" value="<?= htmlspecialchars($movies['actor_name']) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description"><?= htmlspecialchars($movies['description']) ?></textarea>
                        </div>
                    </div>
                                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label d-block">Show Times:</label>
                            <?php if (!empty($show_times)): ?>
                                <?php foreach ($show_times as $time): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox"
                                            id="time<?= htmlspecialchars($time['id']) ?>"
                                            name="show_times[]"
                                            value="<?= htmlspecialchars($time['id']) ?>"
                                            <?= in_array($time['id'], $movies['show_time_ids'] ?? []) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="time<?= htmlspecialchars($time['id']) ?>">
                                            <?= htmlspecialchars($time['show_time']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-danger">No show times available.</p>
                            <?php endif; ?>
                        </div>
                    </div>


                    <div class="d-grid movie-button">
                        <button type="submit" class="btn btn-add-movie">Update Movie</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JS Validation -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById("movieForm");

    form.addEventListener("submit", function (e) {
        const requiredFields = [
            "name", "type", "start_date", "end_date",
            "genre", "actor_actress", "description"
        ];

        let missing = requiredFields.some(id => !document.getElementById(id).value.trim());

        const checkboxes = document.querySelectorAll('input[name="show_times[]"]');
        let checked = Array.from(checkboxes).some(cb => cb.checked);

        if (missing || !checked) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Missing Information',
                text: 'Please fill in all fields and select at least one show time.'
            });
        }
    });
});
</script>

<!-- Image Preview JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('movie_image');
    const fileNameDisplay = document.getElementById('file-name-display');
    const preview = document.getElementById('imagePreview');

    fileInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            fileNameDisplay.textContent = file.name;

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Remove existing <img> or <i>
                    preview.innerHTML = '';

                    // Create new <img> element
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-fluid';
                    img.style.maxHeight = '200px';

                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<i class="fas fa-image fa-5x text-muted"></i>';
                fileNameDisplay.textContent = 'Invalid file';
            }
        } else {
            fileNameDisplay.textContent = 'No file chosen';
            preview.innerHTML = '<i class="fas fa-image fa-5x text-muted"></i>';
        }
    });
});
</script>


