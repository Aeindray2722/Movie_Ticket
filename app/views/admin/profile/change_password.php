<?php
require_once __DIR__ . '/../layout/sidebar.php';
?>
<div class="detail-content-wrapper">
    <?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>
    <div class="content-area">

        <div class="profile-card-container">
            <div class="profile-header-text">
                <h4>Change Password</h4>
            </div>
            <div class="change-password-form-content">
                <form action="<?= URLROOT ?>/user/updatePassword" method="post">
                    <div class="form-group mb-3">
                        <input type="password" class="form-control" name="old_password" placeholder="Enter Old Password"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" class="form-control" name="new_password" placeholder="Enter New Password"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" class="form-control" name="confirm_password"
                            placeholder="Confirm New Password" required>
                    </div>
                    <button type="submit" class="btn btn-change-password-submit">Change</button>
                </form>

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelector('form').addEventListener('submit', function (e) {
    e.preventDefault(); // Stop form submission

    const newPassword = document.querySelector('input[name="new_password"]').value;
    const confirmPassword = document.querySelector('input[name="confirm_password"]').value;

    if (newPassword !== confirmPassword) {
        Swal.fire({
            icon: 'error',
            title: 'Password Mismatch',
            text: 'New password and confirm password must be the same!',
        });
    } else {
        Swal.fire({
            icon: 'success',
            title: 'Are you sure?',
            text: 'Do you want to change your password?',
            showCancelButton: true,
            confirmButtonText: 'Yes, change it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit(); // Submit the form if confirmed
            }
        });
    }
});
</script>
