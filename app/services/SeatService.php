<?php

class SeatService
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getSeatsPaged(int $limit, int $page): array
    {
        $offset = ($page - 1) * $limit;
        $seats = $this->db->readPaged('seats', $limit, $offset);
        $totalSeats = count($this->db->readAll('seats'));
        $totalPages = ceil($totalSeats / $limit);

        return [
            'seats' => $seats,
            'page' => $page,
            'totalPages' => $totalPages,
        ];
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
        $seatData = [
            'seat_row' => trim($data['seat_row']),
            'seat_number' => trim($data['seat_number']),
            'price' => trim($data['price']),
            'status' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Optional: Add validation here or throw exceptions

        return $this->db->create('seats', $seatData);
    }

    public function updateSeat(int $id, array $data): bool
    {
        $seatData = [
            'seat_row' => trim($data['seat_row']),
            'seat_number' => trim($data['seat_number']),
            'price' => trim($data['price']),
            'status' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return $this->db->update('seats', $id, $seatData);
    }

    public function deleteSeat(int $id): bool
    {
        return $this->db->delete('seats', $id);
    }
}
