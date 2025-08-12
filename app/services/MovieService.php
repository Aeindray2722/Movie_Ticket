<?php

class MovieService
{
    private $db;
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getMoviesWithPagination(string $search = '', int $limit = 5, int $page = 1): array
    {
        $offset = ($page - 1) * $limit;
        $columnsToSearch = ['movie_name', 'type_name', 'actor_name'];

        if ($search !== '') {
            $result = $this->db->search('view_movies_info', $columnsToSearch, $search, $limit, $offset);
            $movies = $result['data'] ?? [];
            $totalMovies = $result['total'] ?? 0;
        } else {
            $movies = $this->db->readPaged('view_movies_info', $limit, $offset);
            $totalMovies = count($this->db->readAll('view_movies_info'));
        }

        $totalPages = ceil($totalMovies / $limit);

        return [
            'movies' => $movies,
            'totalPages' => $totalPages,
            'totalMovies' => $totalMovies,
            'page' => $page,
            'limit' => $limit,
            'search' => $search
        ];
    }

    public function getTypes(): array
    {
        return $this->db->readAll('types');
    }

    public function getShowTimes(): array
    {
        return $this->db->readAll('show_times');
    }

    public function getMovieById(int $id): ?array
    {
        return $this->db->getById('movies', $id);
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

        return $this->db->create('movies', $movieData);
    }

    public function updateMovie(array $data, array $files): bool
    {
        $id = $data['id'];
        $old_movie = $this->getMovieById($id);

        if (!$old_movie) {
            return false;
        }

        $movie_img = $old_movie['movie_img'];

        if (isset($files['movie_image']) && $files['movie_image']['error'] === 0) {
            $oldImagePath = __DIR__ . '/../../public/images/movies/' . $movie_img;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            $newFilename = $this->db->uploadImage($files['movie_image'], '/../../public/images/movies/');
            if ($newFilename !== null) {
                $movie_img = $newFilename;
            }
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

        return $this->db->update('movies', $id, $movieData);
    }

    public function deleteMovie(int $id): bool
    {
        $movie = $this->getMovieById($id);

        if (!$movie) {
            return false;
        }

        $movieImg = $movie['movie_img'];
        $imagePath = __DIR__ . '/../../public/images/movies/' . $movieImg;

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        return $this->db->delete('movies', $id);
    }

    public function getRecentBookings(int $limit = 5): array
    {
        // Get all seat data for recent bookings
        $recentBookings = $this->db->readWithCondition(
            'view_bookings_info',
            "1 ORDER BY booking_date DESC"
        );

        // Group seats by booking_id
        $grouped = [];
        foreach ($recentBookings as $booking) {
            $id = $booking['booking_id'];
            if (!isset($grouped[$id])) {
                $grouped[$id] = $booking;
                $grouped[$id]['seats'] = [];
            }
            $grouped[$id]['seats'][] = $booking['seat_row'] . $booking['seat_number'];
        }

        // Convert seats array to "A1,A2,A3"
        foreach ($grouped as &$booking) {
            $booking['seats'] = implode(',', $booking['seats']);
        }

        // Limit to latest $limit bookings
        $grouped = array_slice(array_values($grouped), 0, $limit);

        // Map status code to text
        $statusMap = [0 => 'Confirmed', 1 => 'Pending', 2 => 'Rejected'];
        foreach ($grouped as &$booking) {
            $booking['status_text'] = $statusMap[$booking['status']] ?? 'Unknown';
        }

        return $grouped;
    }

    public function getAllMovies(): array
    {
        return $this->db->readAll('movies');
    }


    public function getMonthlySummary()
    {
        return $this->db->getMonthlySummary();
    }

    public function getNowShowingMovies(?string $type = null, string $search = '', int $page = 1, int $limit = 4): array
    {
        $offset = ($page - 1) * $limit;
        $types = $this->getTypes();
        $columnsToSearch = ['movie_name', 'type_name', 'actor_name'];

        $filteredMovies = [];
        $total = 0;

        if ($search !== '') {
            $result = $this->db->search('view_movies_info', $columnsToSearch, $search, $limit, $offset);

            $today = date('Y-m-d');
            $filtered = array_filter($result['data'], function ($movie) use ($today, $type) {
                $isShowing = ($movie['start_date'] <= $today && $movie['end_date'] >= $today);
                $isTypeMatch = !$type || strtolower($movie['type_name']) === strtolower($type);
                return $isShowing && $isTypeMatch;
            });

            $total = count($filtered);
            $movies = array_slice(array_values($filtered), $offset, $limit);
        } else {
            $result = $this->db->paginateByType(
                'view_movies_info',
                $limit,
                $page,
                $type,
                ['CURDATE() BETWEEN start_date AND end_date']
            );
            $movies = $result['data'];
            $total = $result['total'] ?? count($movies);
        }

        $totalPages = ceil($total / $limit);

        return [
            'movies' => $movies,
            'page' => $page,
            'totalPages' => $totalPages,
            'type' => $type,
            'types' => $types,
            'search' => $search,
        ];
    }

    public function getMovieDetail(int $id): ?array
    {
        $movie = $this->db->getById('view_movies_info', $id);
        if (!$movie) {
            return null;
        }

        $this->db->incrementViewCount($id);

        $avg_rating = $this->db->getAvgRatingByMovieId($id);
        $comments = $this->db->getCommentsWithUserInfo($id);
        // var_dump($comments); exit;
        $relatedMovies = $this->db->readWithCondition(
            'view_movies_info',
            "type_name = '{$movie['type_name']}' AND id != {$movie['id']} LIMIT 6"
        );

        return [
            'movie' => $movie,
            'avg_rating' => $avg_rating,
            'comments' => $comments,
            'related_movies' => $relatedMovies,
        ];
    }
}
