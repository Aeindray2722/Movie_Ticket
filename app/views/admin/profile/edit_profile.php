<?php
// PHP code to simulate fetching admin data for editing
// In a real application, you would connect to your database
// and fetch current admin data here to pre-fill the form.

$admin_info_edit = [
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
                <div class="back-arrow-container">
                <a href="<?php echo URLROOT; ?>/user/profile" class="d-flex align-items-center">
                    <i class="fas fa-arrow-left me-2"></i>
                </a>
            </div>
        <div class="profile-card-container">
            <div class="profile-header-text">
                <h4>Account Info <span class="role-text">(Role)</span></h4>
            </div>
            <div class="profile-content">
                <div class="profile-image-upload-section">
                    <div class="profile-image-wrapper">
                        <img src="<?php echo htmlspecialchars($admin_info_edit['profile_image']); ?>" alt="Profile Picture">
                    </div>
                    <div class="choose-file-section">
                        <label for="profileImage" class="custom-file-upload">
                            Choose file
                        </label>
                        <input type="file" id="profileImage" name="profile_image" accept="image/*">
                        <span class="file-name-display" id="profileFileName">No file chosen</span>
                    </div>
                </div>
                <div class="profile-edit-form">
                    <div class="form-group-inline">
                        <input type="text" class="form-control" placeholder="Name" value="<?php echo htmlspecialchars($admin_info_edit['name']); ?>">
                        <input type="email" class="form-control" placeholder="Email" value="<?php echo htmlspecialchars($admin_info_edit['email']); ?>">
                    </div>
                    <div class="form-group">
                        <input type="tel" class="form-control" placeholder="Phone" value="<?php echo htmlspecialchars($admin_info_edit['phone']); ?>">
                    </div>
                    <button class="btn btn-update-profile">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to update file name display
    document.getElementById('profileImage').addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
        document.getElementById('profileFileName').textContent = fileName;
    });
</script>