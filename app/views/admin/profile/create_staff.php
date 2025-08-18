<?php
require_once __DIR__ . '/../layout/sidebar.php';
// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div class="list-content-wrapper">
    <?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>
    <div class="content-area">

        <div class="profile-card-container">
            <div class="d-flex justify-content-end align-items-center mb-4">
                <h3 class="list-title">Add Staff</h3>
                <a href="<?php echo URLROOT; ?>/user/staffList"> <button class="btn btn-staff-add">Staff List</button>
                </a>
            </div>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <form action="<?php echo URLROOT; ?>/user/storeUserOrStaff" method="POST">
            <!-- CSRF hidden input -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="role" value="0"> <!-- Staff -->
                <div class="create-staff-form-content">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input type="text" name="phone" class="form-control" placeholder="Phone">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <button class="btn btn-create-staff-submit">Create staff</button>
                </div>
            </form>
        </div>
    </div>
</div>