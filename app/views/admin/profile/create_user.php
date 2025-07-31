<?php
require_once __DIR__ . '/../layout/sidebar.php';
?>
<div class="list-content-wrapper">
    <?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>
    <div class="content-area">

        <div class="profile-card-container">
            <div class="d-flex justify-content-end align-items-center mb-4">
                <h3 class="list-title">Add User</h3>
                <a href="<?php echo URLROOT; ?>/user/userList"> <button class="btn btn-staff-add">User List</button>
                </a>
            </div>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <form action="<?php echo URLROOT; ?>/user/storeUserOrStaff" method="POST">
                <input type="hidden" name="role" value="1"> <!-- User -->
                <div class="create-staff-form-content">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    
                    <div class="form-group">
                        <input type="phone" name="phone" class="form-control" placeholder="Phone">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <button class="btn btn-create-staff-submit">Create user</button>
                </div>
            </form>
        </div>
    </div>
</div>