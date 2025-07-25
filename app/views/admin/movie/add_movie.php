<?php
require_once __DIR__ . '/../layout/sidebar.php';

?>
<?php extract($data); ?>

<div class="main-content-wrapper">
    <?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>
    <div class="form-card">
        <form action="<?php echo URLROOT; ?>/movie/store" method="post" enctype="multipart/form-data" id="movieForm" novalidate>
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="image-placeholder" id="imagePreview">
                        <i class="fas fa-image"></i>
                    </div>
                    <div class="choose-file-section">
                        <label for="movie_image" class="custom-file-upload">
                            Choose file
                        </label>
                        <input type="file" class="form-control" id="movie_image" name="movie_image" accept="image/*">
                        <span id="file-name-display" class="file-name-display">No file chosen</span>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label visually-hidden">Name</label>
                            <input type="text" class="form-control" id="name" name="movie_name" placeholder="Name"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="type" class="form-label visually-hidden">Type</label>
                            <select class="form-select" id="type" name="type_id" required>
                                <option value="">Select Type</option>

                                <?php foreach ($data['types'] as $type): ?>
                                    <option value="<?= htmlspecialchars($type['id']) ?>">
                                        <?= htmlspecialchars($type['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label ">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label ">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="genre" class="form-label visually-hidden">Genre</label>
                            <input type="text" class="form-control" id="genre" name="genre" placeholder="Genre"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="actor_actress" class="form-label visually-hidden">Actor/Actress</label>
                            <input type="text" class="form-control" id="actor_actress" name="actor_name"
                                placeholder="Actor/Actress" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="description" class="form-label visually-hidden">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Description" required></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label d-block">Show Times:</label>

                            <?php if (!empty($data['show_times'])): ?>
                                <?php foreach ($data['show_times'] as $time): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="time<?= $time['id']; ?>"
                                            name="show_times[]" value="<?= htmlspecialchars($time['id']); ?>">
                                        <label class="form-check-label" for="time<?= $time['id']; ?>">
                                            <?= htmlspecialchars($time['show_time']); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-danger">No show times available.</p>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="d-grid movie-button">
                        <button type="submit" class="btn btn-add-movie" >Add movie</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById("movieForm");

    form.addEventListener("submit", function (e) {
        e.preventDefault(); // stop form from submitting immediately

        // Get form values
        const movieName = document.getElementById("name").value.trim();
        const type = document.getElementById("type").value;
        const startDate = document.getElementById("start_date").value;
        const endDate = document.getElementById("end_date").value;
        const genre = document.getElementById("genre").value.trim();
        const actor = document.getElementById("actor_actress").value.trim();
        const description = document.getElementById("description").value.trim();
        const image = document.getElementById("movie_image").value;
        const checkboxes = document.querySelectorAll('input[name="show_times[]"]');
        let checked = false;

        checkboxes.forEach(cb => {
            if (cb.checked) checked = true;
        });

        // Validation: check required fields
        if (!movieName || !type || !startDate || !endDate || !genre || !actor || !description || !image || !checked) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Information',
                text: 'Please fill in all fields and select at least one show time.',
            });
            return; // stop here
        }

        // Confirmation alert
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to add this movie?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, add it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // submit if confirmed
            }
        });
    });
});
</script>



<script>
    document.getElementById('movie_image').addEventListener('change', function () {
        const fileName = this.files[0]?.name || 'No file chosen';
        document.getElementById('file-name-display').textContent = fileName;

        // Preview image
        const preview = document.getElementById('imagePreview');
        const file = this.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.innerHTML = '<img src="' + e.target.result + '" class="img-fluid" style="max-height: 200px;" />';
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '<i class="fas fa-image"></i>';
        }
    });
</script>
