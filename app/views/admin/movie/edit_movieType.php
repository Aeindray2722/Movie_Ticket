<?php
// PHP code to simulate fetching admin data for editing
// In a real application, you would connect to your database
// and fetch current admin data here to pre-fill the form.


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
            <a href="<?php echo URLROOT; ?>/type/movieType" class="d-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i>
            </a>
        </div>
        <div class="profile-card-container">
            <div class="profile-header-text">
                <h4>Update Movie Type</h4>
            </div>
            <div class="profile-content">

                <form action="<?php echo URLROOT; ?>/type/update" method="post">
                    <input type="hidden" name="id" value="<?php echo $data['editData']['id']; ?>">
                    <div class="profile-edit-form">
                        <div class="form-group-inline">
                            <input type="text" class="form-control" name="type_name" placeholder="Movie Type"
                                value="<?php echo $data['editData']['name']; ?>">
                        </div>
                        <button class="btn btn-update-profile">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to update file name display
    document.getElementById('profileImage').addEventListener('change', function () {
        const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
        document.getElementById('profileFileName').textContent = fileName;
    });
</script>