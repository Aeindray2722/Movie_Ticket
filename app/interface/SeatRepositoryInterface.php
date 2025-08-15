<?php
// app/interface/SeatRepositoryInterface.php

interface SeatRepositoryInterface
{
    public function getSeatsPaged(int $limit, int $offset): array;

    public function countSeats(): int;

    public function getAllSeats(): array;

    public function getSeatById(int $id): ?array;

    public function createSeat(array $data): bool;

    public function updateSeat(int $id, array $data): bool;

    public function deleteSeat(int $id): bool;
}
