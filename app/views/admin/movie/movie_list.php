<?php



// PHP for handling search (very basic example)
$search_query = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = htmlspecialchars($_GET['search']);
    $filtered_movies = [];
    foreach ($movies as $movie) {
        // Simple search by name, type, or actor/actress
        if (
            stripos($movie['name'], $search_query) !== false ||
            stripos($movie['type'], $search_query) !== false ||
            stripos($movie['actor_actress'], $search_query) !== false
        ) {
            $filtered_movies[] = $movie;
        }
    }
    $movies = $filtered_movies; // Use filtered list
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
            <form class="d-flex" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
                <input class="form-control me-2" type="search" placeholder="search" aria-label="Search" name="search"
                    value="">
                <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <a href="<?php echo URLROOT; ?>/movie/create">
                <button class="btn btn-movie-add">Add Movie</button>
            </a>

        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Start date</th>
                        <th>End date</th>
                        <th>Show time</th>
                        <th>Actor/ Actress</th>

                        <th>Genre</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if (empty($data['movies'])): ?>
                        <tr>
                            <td colspan="10" class="text-center">No movies found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['movies'] as $movie): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo URLROOT . '/images/movies/' . htmlspecialchars($movie['movie_img']); ?>"
                                        alt="<?php echo htmlspecialchars($movie['movie_name']); ?>" class="movie-thumb">

                                </td>
                                <?php
                                // Create a map from type_id to type name
                                $typeMap = [];
                                foreach ($data['types'] as $type) {
                                    $typeMap[$type['id']] = $type['name'];
                                }

                                // Create a map from show_time_id to show_time name
                                $showTimeMap = [];
                                foreach ($data['show_times'] as $showTime) {
                                    $showTimeMap[$showTime['id']] = $showTime['show_time'];
                                }
                                ?>

                                <td><?php echo htmlspecialchars($movie['movie_name']); ?></td>
                                <td><?php echo isset($typeMap[$movie['type_id']]) ? htmlspecialchars($typeMap[$movie['type_id']]) : 'Unknown'; ?>
                                </td>
                                <td><?php echo htmlspecialchars($movie['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($movie['end_date']); ?></td>
                                <td><?php $showTimeIds = json_decode($movie['show_time'], true);
                                if (is_array($showTimeIds)) {
                                    $names = [];
                                    foreach ($showTimeIds as $stid) {
                                        if (isset($showTimeMap[$stid])) {
                                            $names[] = $showTimeMap[$stid];
                                        }
                                    }
                                    echo htmlspecialchars(implode(', ', $names));
                                } else {
                                    echo 'N/A';
                                } ?></td>
                                <td><?php echo htmlspecialchars($movie['actor_name']); ?></td>

                                <td><?php echo htmlspecialchars($movie['genre']); ?></td>

                                <td class="text-center"> <a href="<?php echo URLROOT . '/movie/edit/' . $movie['id']; ?>"
                                        class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button class="btn-action btn btn-sm btn-outline-danger"
                                        onclick="deleteMovie('<?php echo base64_encode($movie['id']); ?>')">
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
                <nav aria-label="Page navigation ">
                    <ul class="pagination justify-content-end ">
                        <?php for ($p = 1; $p <= $data['totalPages']; $p++): ?>
                            <li class="page-item <?= ($p == $data['page']) ? 'active' : '' ?>">
                                <a class="page-link " href="?page=<?= $p ?>"><?= $p ?></a>
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
                window.location.href = '<?php echo URLROOT; ?>/movie/destroy/' + encodedId;
            }
        });
    }

</script>