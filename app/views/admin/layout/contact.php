<?php

?>

<body>
    <?php
    require_once __DIR__ . '/../layout/sidebar.php';
    ?>
    <div class="booking-content-wrapper">
        <?php
        require_once __DIR__ . '/../layout/nav.php';
        ?>
        <div class="booking-list-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
               
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['contacts'])): ?>
                            <tr>
                                <td colspan="10" class="text-center">No contacts found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['contacts'] as $contact): ?>
                                <tr>
                                    <td class=""><?php echo htmlspecialchars($contact['user_id'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                    <td><?php echo htmlspecialchars($contact['message']); ?></td>
                                    <td><?php echo htmlspecialchars($contact['created_at']); ?></td>

                                    <td class="d-flex text-center">
                                        
                                        <button class="btn btn-sm btn-outline-danger btn-action"
                                            onclick="deleteMovie('<?= base64_encode($contact['id']) ?>')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- pagination -->
                <?php if ($data['totalPages'] > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            <?php for ($p = 1; $p <= $data['totalPages']; $p++): ?>
                                <li class="page-item <?= ($p == $data['page']) ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?search=<?= urlencode($data['search']) ?>&page=<?= $p ?>"><?= $p ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <script>
        function deleteMovie(encodedId) {
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
                    window.location.href = '<?= URLROOT ?>/contact/destroy/' + encodedId;
                }
            });
        }
    </script>
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