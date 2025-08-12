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
                <h4>Account Info(Admin) </h4>
            </div>
            <div class="profile-content">
                <div class="profile-image-wrapper">
                    <img src="<?= URLROOT ?>/images/users/<?= htmlspecialchars($data['user_info']['profile_img']) ?>" alt="Profile Picture">
                </div>

                <div class="profile-details-and-buttons">
                    <div class="profile-details-text">
                        <p><strong>Name :</strong> <?php echo htmlspecialchars($data['user_info']['name']); ?></p>
                        <p><strong>Email :</strong> <?php echo htmlspecialchars($data['user_info']['email']); ?></p>
                        <p><strong>Phone :</strong> <?php echo htmlspecialchars($data['user_info']['phone']); ?></p>
                    </div>
                    <div class="profile-buttons">
                        <a href="<?php echo URLROOT; ?>/user/changePassword"><button
                                class="btn btn-change-password">Change Password</button></a>
                        <a href="<?php echo URLROOT; ?>/user/editProfile/<?php echo $data['user_info']['id']; ?>">
                            <button class="btn btn-edit-profile">Edit Profile</button>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (isset($_SESSION['success'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= $_SESSION['success'] ?>',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>