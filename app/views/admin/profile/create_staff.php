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
           <h3 class="list-title">Add Staff</h3>
            <a href="<?php echo URLROOT; ?>/user/staffList"> <button class="btn btn-staff-add">Staff List</button>
            </a>
        </div>
            <div class="create-staff-form-content">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirmed Password">
                </div>
                <button class="btn btn-create-staff-submit">Create staff</button>
            </div>
        </div>
    </div>
</div>