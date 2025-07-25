<?php

$movies = [
    [
        'id' => 1,
        'name' => 'Hla Hla',
        'movie_name' => 'titanic',
        'show_time' => '10:00am',
        'date' => '10-May-2025',
        'seat' => 'A1',
        'total_amount' => '$100',
        'status' => 'pending'
    ],
    
];

// PHP for handling search (very basic example)
$search_query = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = htmlspecialchars($_GET['search']);
    $filtered_movies = [];
    foreach ($movies as $movie) {
        // Simple search by name, type, or actor/actress
        if (stripos($movie['name'], $search_query) !== false ||
            stripos($movie['type'], $search_query) !== false ||
            stripos($movie['actor_actress'], $search_query) !== false) {
            $filtered_movies[] = $movie;
        }
    }
    $movies = $filtered_movies; // Use filtered list
}

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
                <form class="d-flex" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
                    <input class="form-control me-2" type="search" placeholder="search" aria-label="Search"
                        name="search" value="">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                </form>
                

            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            
                            <th>Name</th>
                            <th>Movie Name</th>
                            <th>Showtime</th>
                            <th>Date</th>
                            <th>Seat</th>
                            <th>Total amount</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($movies)): ?>
                        <tr>
                            <td colspan="10" class="text-center">No movies found.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($movies as $movie): ?>
                        <tr>
                            
                            <td><?php echo htmlspecialchars($movie['name']); ?></td>
                            <td><?php echo htmlspecialchars($movie['movie_name']); ?></td>
                            <td><?php echo htmlspecialchars($movie['show_time']); ?></td>
                            <td><?php echo htmlspecialchars($movie['date']); ?></td>
                            <td><?php echo htmlspecialchars($movie['seat']); ?></td>
                            <td><?php echo htmlspecialchars($movie['total_amount']); ?></td>
                            <td>
                                <select class="form-select form-select-sm" onchange="updateBookingStatus(this.value, <?php echo $booking['booking_id']; ?>)">
                                    <option value="pending" <?php echo ($movie['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo ($movie['status'] == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="cancelled" <?php echo ($movie['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                        </td>
                                <td class="d-flex"> 
                                    <a href="<?php echo URLROOT; ?>/booking/bookingDetail"class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-eye  "></i></a>
                                    <a href="edit_movie.php?id=<?php echo $movie['id']; ?>"class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="delete_movie.php?id=<?php echo $movie['id']; ?>"class="btn btn-sm btn-outline-danger" title="Delete"
                                    onclick="return confirm('Are you sure you want to delete this movie?');">
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


