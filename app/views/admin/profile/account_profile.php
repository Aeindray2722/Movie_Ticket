<?php
// PHP code to simulate fetching admin data
// In a real application, you would connect to your database
// and fetch data for the admin user here.

$admin_info = [
    'name' => 'Admin',
    'email' => 'admin@gmail.com',
    'phone' => '09975005602',
    'profile_image' => 'http://googleusercontent.com/file_content/0', // Using the uploaded image directly
];
?>

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
                <h4>Account Info <span class="role-text">(Role)</span></h4>
            </div>
            <div class="profile-content">
                <div class="profile-image-wrapper">
                    <img src="<?php echo htmlspecialchars($admin_info['profile_image']); ?>" alt="Profile Picture">
                </div>
                <div class="profile-details-and-buttons">
                    <div class="profile-details-text">
                        <p><strong>Name :</strong> <?php echo htmlspecialchars($admin_info['name']); ?></p>
                        <p><strong>Email :</strong> <?php echo htmlspecialchars($admin_info['email']); ?></p>
                        <p><strong>Phone :</strong> <?php echo htmlspecialchars($admin_info['phone']); ?></p>
                    </div>
                    <div class="profile-buttons">
                        <a href="<?php echo URLROOT; ?>/user/changePassword"><button class="btn btn-change-password">Change Password</button></a>
                        <a href="<?php echo URLROOT; ?>/user/editProfile"><button class="btn btn-edit-profile">Edit Profile</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>