<?php
// app/repositories/SeatRepository.php

require_once __DIR__ . '/../interface/SeatRepositoryInterface.php';

class SeatRepository implements SeatRepositoryInterface
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getSeatsPaged(int $limit, int $offset): array
    {
        return $this->db->readPaged('seats', $limit, $offset);
    }

    public function countSeats(): int
    {
        return count($this->db->readAll('seats'));
    }

    public function getAllSeats(): array
    {
        return $this->db->readAll('seats');
    }

    public function getSeatById(int $id): ?array
    {
        return $this->db->getById('seats', $id);
    }

    public function createSeat(array $data): bool
    {
        return $this->db->create('seats', $data);
    }

    public function updateSeat(int $id, array $data): bool
    {
        return $this->db->update('seats', $id, $data);
    }

    public function deleteSeat(int $id): bool
    {
        return $this->db->delete('seats', $id);
    }
}
