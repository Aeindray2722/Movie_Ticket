<?php

require_once __DIR__ . '/../interface/MovieRepositoryInterface.php';

class MovieRepository implements MovieRepositoryInterface
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

        return [
            'movies' => $movies,
            'totalMovies' => $totalMovies
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

    public function createMovie(array $movieData): bool
    {
        return $this->db->create('movies', $movieData);
    }

    public function updateMovie(int $id, array $movieData): bool
    {
        return $this->db->update('movies', $id, $movieData);
    }

    public function deleteMovie(int $id): bool
    {
        return $this->db->delete('movies', $id);
    }

    public function getAllMovies(): array
    {
        return $this->db->readAll('movies');
    }

    public function getRecentBookings(): array
    {
        return $this->db->readWithCondition(
            'view_bookings_info',
            "1 ORDER BY booking_date DESC"
        );
    }

    public function paginateByType(string $table, int $limit, int $page, ?string $type, array $extraConditions = []): array
    {
        return $this->db->paginateByType($table, $limit, $page, $type, $extraConditions);
    }

    public function getMonthlySummary()
    {
        return $this->db->getMonthlySummary();
    }

    public function incrementViewCount(int $movieId): void
    {
        $this->db->incrementViewCount($movieId);
    }

    public function getAvgRatingByMovieId(int $movieId)
    {
        return $this->db->getAvgRatingByMovieId($movieId);
    }

    public function getCommentsWithUserInfo(int $movieId): array
    {
        return $this->db->getCommentsWithUserInfo($movieId);
    }

    public function getRelatedMovies(string $typeName, int $excludeId, int $limit = 6): array
    {
        return $this->db->readWithCondition(
            'view_movies_info',
            "type_name = '{$typeName}' AND id != {$excludeId} LIMIT {$limit}"
        );
    }
}
