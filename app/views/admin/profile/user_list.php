<?php
$user_members = $data['user_members'] ?? [];
$search_query = $data['search_query'] ?? '';

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $filtered_user = [];
    foreach ($user_members as $user) {
        if (
            stripos($user['name'], $_GET['search']) !== false ||
            stripos($user['email'], $_GET['search']) !== false ||
            stripos($user['phone'], $_GET['search']) !== false
        ) {
            $filtered_user[] = $user;
        }
    }
    $user_members = $filtered_user;
}
?>
<?php
require_once __DIR__ . '/../layout/sidebar.php';
?>
<div class="list-content-wrapper">
    <?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>
    <div class="movie-list-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="list-title">user List</h3>
            <form class="d-flex" action="<?php echo URLROOT; ?>/user/userList" method="get">
                <input class="form-control me-2" type="search" placeholder="search" aria-label="Search" name="search"
                    value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <a href="<?php echo URLROOT; ?>/user/addUser"> <button class="btn btn-staff-add">Add user</button>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Create date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['user_members'])): ?>
                        <tr>
                            <td colspan="6" class="text-center">No user members found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['user_members'] as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                                <td>
                                    <button class="btn-action btn btn-sm btn-outline-danger"
                                        onclick="deleteUser('<?php echo base64_encode($user['id']); ?>')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
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
<script>
    function deleteUser(encodedId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?php echo URLROOT; ?>/user/destroy/' + encodedId;
            }
        });
    }

</script>