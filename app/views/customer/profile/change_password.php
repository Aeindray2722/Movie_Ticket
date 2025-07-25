<?php
    require_once __DIR__ . '/../layout/nav.php';
?>
<div class="detail-content-wrapper">
   
    <div class="content-area">

        <div class="profile-card-container">
            <div class="profile-header-text">
                <h4>Change Password</h4>
            </div>
            <div class="change-password-form-content">
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Enter Old Password">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Enter New Password">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Enter Confirmed Password">
                </div>
                <button class="btn btn-change-password-submit">Change</button>
            </div>
        </div>
    </div>
</div>
<?php
    require_once __DIR__ . '/../layout/footer.php';
?>