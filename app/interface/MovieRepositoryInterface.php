<?php

interface MovieRepositoryInterface
{
    public function getMoviesWithPagination(string $search = '', int $limit = 5, int $page = 1): array;

    public function getTypes(): array;

    public function getShowTimes(): array;

    public function getMovieById(int $id): ?array;

    public function createMovie(array $movieData): bool;

    public function updateMovie(int $id, array $movieData): bool;

    public function deleteMovie(int $id): bool;

    public function getAllMovies(): array;

    public function getRecentBookings(): array;

    public function paginateByType(string $table, int $limit, int $page, ?string $type, array $extraConditions = []): array;

    public function getMonthlySummary();

    public function incrementViewCount(int $movieId): void;

    public function getAvgRatingByMovieId(int $movieId);

    public function getCommentsWithUserInfo(int $movieId): array;

    public function getRelatedMovies(string $typeName, int $excludeId, int $limit = 6): array;
}
