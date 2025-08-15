<?php
// app/services/SeatService.php

require_once __DIR__ . '/../interface/SeatRepositoryInterface.php';

class SeatService
{
    private SeatRepositoryInterface $seatRepository;

    public function __construct(SeatRepositoryInterface $seatRepository)
    {
        $this->seatRepository = $seatRepository;
    }

    public function getSeatsPaged(int $limit, int $page): array
    {
        $offset = ($page - 1) * $limit;
        $seats = $this->seatRepository->getSeatsPaged($limit, $offset);
        $totalSeats = $this->seatRepository->countSeats();
        $totalPages = ceil($totalSeats / $limit);

        return [
            'seats' => $seats,
            'page' => $page,
            'totalPages' => $totalPages,
        ];
    }
    

    public function getAllSeats(): array
    {
        return $this->seatRepository->getAllSeats();
    }

    public function getSeatById(int $id): ?array
    {
        return $this->seatRepository->getSeatById($id);
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

        // Add validation here if you want

        return $this->seatRepository->createSeat($seatData);
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

        return $this->seatRepository->updateSeat($id, $seatData);
    }

    public function deleteSeat(int $id): bool
    {
        return $this->seatRepository->deleteSeat($id);
    }
}
