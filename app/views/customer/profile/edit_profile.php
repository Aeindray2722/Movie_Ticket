<?php 
extract($data); 
require_once __DIR__ . '/../layout/nav.php';

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>
<div class="detail-content-wrapper">
    <div class="content-area container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="back-arrow-container mb-4">
                    <a href="<?php echo URLROOT; ?>/user/Userindex" class="d-flex align-items-center">
                        <i class="fas fa-arrow-left me-2"></i>

                    </a>
                </div>
                <div class="profile-card-container">
                    <div class="profile-header-text">
                        <h4>Account Info</h4>
                    </div>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error']; ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    <form action="<?= URLROOT ?>/user/update" method="post" enctype="multipart/form-data" id="movieForm">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="id" value="<?= $data['users']['id'] ?>">
                        <input type="hidden" name="role" value="<?= $data['users']['role'] ?>">
                        <div
                            class="profile-content d-flex flex-column flex-md-row align-items-center align-items-md-start">
                            <div class="profile-image-upload-section text-center mb-4 me-md-4 mb-md-0">
                                <div class="profile-image-wrapper">
                                    <img src="<?= URLROOT ?>/images/users/<?= htmlspecialchars($data['users']['profile_img']) ?>"
                                        alt="Profile Picture" class="img-fluid rounded-circle">
                                </div>
                                <div class="choose-file-section mt-3">
                                    <label for="profileImage" class="custom-file-upload">
                                        Choose file
                                    </label>
                                    <input type="file" id="profileImage" name="profile_image" accept="image/*">
                                    <span class="file-name-display d-block mt-2" id="profileFileName">
                                        <?= htmlspecialchars($data['users']['profile_img']) ?: 'No file chosen' ?>
                                    </span>
                                </div>
                            </div>
                            <div class="profile-edit-form flex-grow-1">
                                <div class="row">
                                    <div class="col-12 col-sm-6 mb-3">
                                        <input type="text" name="name" class="form-control" placeholder="Name"
                                            value="<?php echo htmlspecialchars($data['users']['name']); ?>">
                                    </div>
                                    <div class="col-12 col-sm-6 mb-3">
                                        <input type="email" name="email" class="form-control" placeholder="Email"
                                            value="<?php echo htmlspecialchars($data['users']['email']); ?>">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="tel" name="phone" class="form-control" placeholder="Phone"
                                        value="<?php echo htmlspecialchars($data['users']['phone']); ?>">
                                </div>
                                <button class="btn btn-book-now w-100">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../layout/footer.php';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('profileImage').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.querySelector('.profile-image-wrapper img').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }

        const fileName = file ? file.name : 'No file chosen';
        document.getElementById('profileFileName').textContent = fileName;
    });
</script>