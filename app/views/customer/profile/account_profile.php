<?php
require_once __DIR__ . '/../layout/nav.php';
?>
<div class="content-area">
    <section class="profile-page-content py-4">
        <div class="container-fluid">
            <div class="row">


                <div class="col-lg-9 col-md-8 main-content-container">

                    <div class="card account-info-card mb-4">
                        <div class="card-body">
                            <h3 class="account-info-title mb-4">Account Info(Customer) </h3>
                            <div class="profile-content">
                                <div class="profile-image-wrapper">
                                    <img src="<?= URLROOT ?>/images/users/<?= htmlspecialchars($data['user_info']['profile_img']) ?>"
                                        alt="Profile Picture">
                                </div>

                                <div class="profile-details-and-buttons">
                                    <div class="profile-details-text">
                                        <p><strong>Name :</strong>
                                            <?php echo htmlspecialchars($data['user_info']['name']); ?></p>
                                        <p><strong>Email :</strong>
                                            <?php echo htmlspecialchars($data['user_info']['email']); ?></p>
                                        <p><strong>Phone :</strong>
                                            <?php echo htmlspecialchars($data['user_info']['phone']); ?></p>
                                    </div>
                                    <div class="profile-buttons">
                                        <a href="<?php echo URLROOT; ?>/user/UserchangePassword"><button
                                                class="btn btn-change-password">Change Password</button></a>
                                        <a
                                            href="<?php echo URLROOT; ?>/user/editUserProfile/<?php echo $data['user_info']['id']; ?>">
                                            <button class="btn btn-edit-profile">Edit Profile</button>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>
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