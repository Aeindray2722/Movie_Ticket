<?php extract($data); ?>
<?php
require_once __DIR__ . '/../layout/sidebar.php';
?>

<div class="seat-content-wrapper">
    <?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>
    <div class="seat-content-section">
        <div class="seat-form-card">
            <h4>Add Seat</h4>
            <form action="<?php echo URLROOT; ?>/seat/store" method="post">
                <div class="mb-3">
                    <input type="text" class="form-control" id="seat_row" name="seat_row" placeholder="Seat Row"
                        required>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" id="seat_number" name="seat_number"
                        placeholder="Seat Number" required>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" id="price" name="price" placeholder="Price" required>
                </div>
                <div class="d-grid movie-button">
                    <button type="submit" class="btn btn-add-movie">Add</button>
                </div>
            </form>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <h4>Seat</h4>
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Seat Row</th>
                            <th>Seat Number</th>
                            <th>Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($seats)): ?>
                            <tr>
                                <td colspan="3" class="text-center">No seat prices found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($seats as $seat): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($seat['seat_row']); ?></td>
                                    <td><?php echo htmlspecialchars($seat['seat_number']); ?></td>
                                    <td><?php echo htmlspecialchars($seat['price']); ?></td>
                                    <td class="d-flex justify-content-center">
                                        <a href="<?php echo URLROOT; ?>/seat/edit/<?php echo $seat['id']; ?>"
                                            class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="deleteMovieType('<?php echo base64_encode($seat['id']); ?>')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- pagination -->
                <?php if (!empty($totalPages) && $totalPages > 1): ?>
                    <nav aria-label="Page navigation ">
                        <ul class="pagination justify-content-end ">
                            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
                                    <a class="page-link " href="?page=<?= $p ?>"><?= $p ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function deleteMovieType(encodedId) {
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
                window.location.href = '<?php echo URLROOT; ?>/seat/destroy/' + encodedId;
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