<?php
require_once __DIR__ . '/../repositories/MovieRepository.php';
require_once __DIR__ . '/../interface/MovieRepositoryInterface.php';

class MovieService
{
    private $repo;
    private $db;

    public function __construct(MovieRepository $repo, Database $db)
    {
        $this->repo = $repo;
        $this->db = $db; // still needed for uploadImage
    }

    public function getMoviesWithPagination(string $search, int $limit, int $page): array
    {
        $data = $this->repo->getMoviesWithPagination($search, $limit, $page);
        $totalPages = ceil($data['totalMovies'] / $limit);

        return [
            'movies' => $data['movies'],
            'totalPages' => $totalPages,
            'totalMovies' => $data['totalMovies'],
            'page' => $page,
            'limit' => $limit,
            'search' => $search
        ];
    }

    public function getTypes(): array
    {
        return $this->repo->getTypes();
    }

    public function getShowTimes(): array
    {
        return $this->repo->getShowTimes();
    }

    public function getMovieById(int $id): ?array
    {
        return $this->repo->getMovieById($id);
    }

    public function createMovie(array $data, array $files): bool
    {
        $movie_img = '';
        if (isset($files['movie_image']) && $files['movie_image']['error'] === 0) {
            $movie_img = $this->db->uploadImage($files['movie_image'], '/../../public/images/movies/');
        }

        $movieData = [
            'movie_name' => $data['movie_name'],
            'actor_name' => $data['actor_name'],
            'genre' => $data['genre'],
            'description' => $data['description'],
            'type_id' => $data['type_id'],
            'show_time' => json_encode($data['show_times'] ?? []),
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'movie_img' => $movie_img,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return $this->repo->createMovie($movieData);
    }

    public function updateMovie(array $data, array $files): bool
    {
        $id = $data['id'];
        $old_movie = $this->repo->getMovieById($id);

        if (!$old_movie) {
            return false;
        }

        $movie_img = $old_movie['movie_img'];

        if (isset($files['movie_image']) && $files['movie_image']['error'] === 0) {
            $oldImagePath = __DIR__ . '/../../public/images/movies/' . $movie_img;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            $movie_img = $this->db->uploadImage($files['movie_image'], '/../../public/images/movies/');
        }

        $movieData = [
            'movie_name' => $data['movie_name'],
            'actor_name' => $data['actor_name'],
            'genre' => $data['genre'],
            'description' => $data['description'],
            'type_id' => $data['type_id'],
            'show_time' => json_encode($data['show_times'] ?? []),
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'movie_img' => $movie_img,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return $this->repo->updateMovie($id, $movieData);
    }

    public function deleteMovie(int $id): bool
    {
        $movie = $this->repo->getMovieById($id);
        if (!$movie) {
            return false;
        }

        $imagePath = __DIR__ . '/../../public/images/movies/' . $movie['movie_img'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        return $this->repo->deleteMovie($id);
    }

    public function getRecentBookings(int $limit = 5): array
    {
        $recentBookings = $this->repo->getRecentBookings();

        // Group by booking_id
        $grouped = [];
        foreach ($recentBookings as $booking) {
            $id = $booking['booking_id'];
            if (!isset($grouped[$id])) {
                $grouped[$id] = $booking;
                $grouped[$id]['seats'] = [];
            }
            $grouped[$id]['seats'][] = $booking['seat_row'] . $booking['seat_number'];
        }

        // Format seats & status
        $statusMap = [0 => 'Confirmed', 1 => 'Pending', 2 => 'Rejected'];
        foreach ($grouped as &$booking) {
            $booking['seats'] = implode(',', $booking['seats']);
            $booking['status_text'] = $statusMap[$booking['status']] ?? 'Unknown';
        }

        return array_slice(array_values($grouped), 0, $limit);
    }

    public function getMonthlySummary()
    {
        return $this->repo->getMonthlySummary();
    }

    public function getNowShowingMovies(?string $type, string $search, int $page, int $limit): array
    {
        $types = $this->repo->getTypes();
        $today = date('Y-m-d');

        // ✅ Normalize empty type to null
        $type = !empty($type) ? $type : null;

        // Extra WHERE for "now showing"
        $additionalWhere = ['CURDATE() BETWEEN start_date AND end_date'];

        // If searching
        if ($search !== '') {
            // We use LIKE matching inside paginateByType
            $additionalWhere[] = [
                "(movie_name LIKE ? OR type_name LIKE ? OR actor_name LIKE ?)",
                [$searchLike = "%$search%", $searchLike, $searchLike]
            ];

            $result = $this->repo->paginateByType(
                'view_movies_info',
                $limit,
                $page,
                $type,
                $additionalWhere
            );

        } else {
            // No search → just filter by now showing
            $result = $this->repo->paginateByType(
                'view_movies_info',
                $limit,
                $page,
                $type,
                $additionalWhere
            );
        }

        return [
            'movies' => $result['data'],
            'page' => $result['page'],
            'totalPages' => $result['totalPages'],
            'type' => $type,
            'types' => $types,
            'search' => $search
        ];
    }

    public function getMovieDetail(int $id): ?array
    {
        $movie = $this->db->getById('view_movies_info', $id);
        if (!$movie) {
            return null;
        }

        $this->repo->incrementViewCount($id);

        return [
            'movie' => $movie,
            'avg_rating' => $this->repo->getAvgRatingByMovieId($id),
            'comments' => $this->repo->getCommentsWithUserInfo($id),
            'related_movies' => $this->repo->getRelatedMovies($movie['type_name'], $movie['id'])
        ];
    }
    public function getAllMovies(): array
    {
        return $this->db->readAll('movies');
    }
}
