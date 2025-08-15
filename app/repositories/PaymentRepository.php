<?php
// app/repositories/PaymentRepository.php

require_once __DIR__ . '/../interface/PaymentRepositoryInterface.php';

class PaymentRepository implements PaymentRepositoryInterface
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getPaginatedPayments(int $limit, int $offset): array
    {
        return $this->db->readPaged('payments', $limit, $offset);
    }

    public function countPayments(): int
    {
        return count($this->db->readAll('payments'));
    }

    public function createPayment(array $data): bool
    {
        return $this->db->create('payments', $data);
    }

    public function getPaymentById(int $id): ?array
    {
        return $this->db->getById('payments', $id);
    }

    public function updatePayment(int $id, array $data): bool
    {
        return $this->db->update('payments', $id, $data);
    }

    public function deletePayment(int $id): bool
    {
        return $this->db->delete('payments', $id);
    }

    public function getAllPayments(): array
    {
        return $this->db->readAll('payments');
    }

    public function getPaymentByMethod(string $method): ?array
    {
        return $this->db->columnFilter('payments', 'payment_method', $method);
    }

    public function uploadPayslip(array $file): string
    {
        if (isset($file) && $file['error'] === 0) {
            return $this->db->uploadImage($file, '/../../public/images/payslips/');
        }
        return '';
    }
}
