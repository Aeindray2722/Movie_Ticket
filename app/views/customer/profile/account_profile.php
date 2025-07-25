<?php
// Start the session at the very beginning of the file
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// In a real application, you'd fetch user data from a database
// For demonstration, let's assume some user data
$user_role = "Admin"; // Can be "Admin" or "User"
$user_name = "Admin";
$user_email = "admin@gmail.com";
$user_phone = "09-975005602";

// Make sure to include your nav.php

?>
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
                        <h3 class="account-info-title mb-4">Account Info <span class="role-text">(<?php echo htmlspecialchars($user_role); ?>)</span></h3>
                        
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center mb-3 mb-md-0">
                                <i class="fas fa-user-circle profile-avatar-large"></i>
                            </div>
                            <div class="col-md-9 profile-details">
                                <p class="detail-item"><strong>Name :</strong> <?php echo htmlspecialchars($user_name); ?></p>
                                <p class="detail-item"><strong>Email :</strong> <?php echo htmlspecialchars($user_email); ?></p>
                                <p class="detail-item"><strong>Phone :</strong> <?php echo htmlspecialchars($user_phone); ?></p>
                                <div class="profile-actions mt-3">
                                    <a href="<?php echo URLROOT; ?>/user/UserchangePassword"><button class="btn btn-change-password me-2">Change Password</button></a>      
                                    <a href="<?php echo URLROOT; ?>/user/Usereditprofile"><button class="btn btn-edit-profile">Edit Profile</button></a>                   
                                    
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