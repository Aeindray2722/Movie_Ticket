<?php extract($data); 
// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<?php
require_once __DIR__ . '/../layout/sidebar.php';
?>
<div class="detail-content-wrapper">
    <?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>
    <div class="content-area">
        <div class="back-arrow-container">
            <a href="<?php echo URLROOT; ?>/user/profile" class="d-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i>
            </a>
        </div>
        <div class="profile-card-container">
            <div class="profile-header-text">
                <h4>Account Info </h4>
            </div>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <form action="<?= URLROOT ?>/user/update" method="post" enctype="multipart/form-data" id="movieForm">
                 <!-- CSRF hidden input -->
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="id" value="<?= $data['users']['id'] ?>">
                <input type="hidden" name="role" value="<?= $data['users']['role'] ?>">
                <div class="profile-content">
                    <div class="profile-image-upload-section">
                        <div class="profile-image-wrapper">
                            <img src="<?= URLROOT ?>/images/users/<?= htmlspecialchars($data['users']['profile_img']) ?>"
                                alt="Profile Picture">
                        </div>
                        <div class="choose-file-section">
                            <label for="profileImage" class="custom-file-upload">
                                Choose file
                            </label>
                            <input type="file" id="profileImage" name="profile_image" accept="image/*">
                            <span class="file-name-display" id="profileFileName">
                                <?= htmlspecialchars($data['users']['profile_img']) ?: 'No file chosen' ?>
                            </span>

                        </div>
                    </div>
                    <div class="profile-edit-form">
                        <div class="form-group-inline">
                            <input type="text" class="form-control" name="name" placeholder="Name"
                                value="<?php echo htmlspecialchars($data['users']['name']); ?>">
                            <input type="email" class="form-control" name="email" placeholder="Email"
                                value="<?php echo htmlspecialchars($data['users']['email']); ?>" >
                        </div>
                        <div class="form-group">
                            <input type="tel" class="form-control" name="phone" placeholder="Phone"
                                value="<?php echo htmlspecialchars($data['users']['phone']); ?>">
                        </div>
                        <button class="btn btn-update-profile">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
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

