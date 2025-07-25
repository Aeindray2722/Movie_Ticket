<?php

$user_members = [
    [
        'id' => 1,
        'name' => 'Aung Aung',
        'email' => 'aung@gmail.com',
        'phone' => '099755',
        'create_date' => '5-May-2025',
    ],
    
];

// PHP for handling search (basic example)
$search_query = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = htmlspecialchars($_GET['search']);
    $filtered_user = [];
    foreach ($user_members as $user) {
        // Simple search by name, email, or phone
        if (stripos($user['name'], $search_query) !== false ||
            stripos($user['email'], $search_query) !== false ||
            stripos($user['phone'], $search_query) !== false) {
            $filtered_user[] = $user;
        }
    }
    $user_members = $filtered_user; // Use filtered list
}

?>
 <?php
    require_once __DIR__ . '/../layout/sidebar.php';
    ?>
    <div class="list-content-wrapper">
         <?php
        require_once __DIR__ . '/../layout/nav.php';
        ?>
        <div class="movie-list-container"> <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="list-title">user List</h3> <form class="d-flex" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
                    <input class="form-control me-2" type="search" placeholder="search" aria-label="Search"
                        name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                </form>
                <a href="<?php echo URLROOT; ?>/user/addUser"> <button class="btn btn-staff-add">Add user</button>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Create date</th>
                            <th></th> </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($user_members)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No user members found.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($user_members as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone']); ?></td>
                            <td><?php echo htmlspecialchars($user['create_date']); ?></td>
                            <td>
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>"
                                    class="btn btn-sm btn-outline-danger" title="Delete"
                                    onclick="return confirm('Are you sure you want to delete this user member?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>