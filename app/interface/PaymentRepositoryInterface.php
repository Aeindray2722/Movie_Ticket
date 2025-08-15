<?php
// app/interface/PaymentRepositoryInterface.php

interface PaymentRepositoryInterface
{
    public function getPaginatedPayments(int $limit, int $offset): array;

    public function countPayments(): int;

    public function createPayment(array $data): bool;

    public function getPaymentById(int $id): ?array;

    public function updatePayment(int $id, array $data): bool;

    public function deletePayment(int $id): bool;

    public function getAllPayments(): array;

    public function getPaymentByMethod(string $method): ?array;

    public function uploadPayslip(array $file): string;
}
