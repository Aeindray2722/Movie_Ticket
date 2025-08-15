<?php
require_once __DIR__ . '/../layout/nav.php';
// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>
<div class="detail-content-wrapper">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="profile-card-container">
                    <div class="profile-header-text">
                        <h4>Change Password</h4>
                    </div>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error']; ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    <div class="change-password-form-content">
                        <form action="<?= URLROOT ?>/user/updatePassword" method="post">
                            <!-- CSRF hidden input -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <div class="form-group mb-3">
                                <input type="password" name="old_password" class="form-control"
                                    placeholder="Enter Old Password">
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" name="new_password" class="form-control"
                                    placeholder="Enter New Password">
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" name="confirm_password" class="form-control"
                                    placeholder="Enter Confirmed Password">
                            </div>
                            <button class="btn btn-change-password-submit w-100">Change</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../layout/footer.php';
?>