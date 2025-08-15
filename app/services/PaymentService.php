<?php
require_once __DIR__ . '/../interface/PaymentRepositoryInterface.php';

class PaymentService
{
    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function getPaginatedPayments(int $limit, int $page): array
    {
        $offset = ($page - 1) * $limit;
        $payments = $this->paymentRepository->getPaginatedPayments($limit, $offset);
        $totalPayments = $this->paymentRepository->countPayments();
        $totalPages = ceil($totalPayments / $limit);

        return [
            'payments' => $payments,
            'page' => $page,
            'totalPages' => $totalPages,
        ];
    }

    public function createPayment(array $data): bool
    {
        return $this->paymentRepository->createPayment($data);
    }

    public function getPaymentById(int $id): ?array
    {
        return $this->paymentRepository->getPaymentById($id);
    }

    public function updatePayment(int $id, array $data): bool
    {
        return $this->paymentRepository->updatePayment($id, $data);
    }

    public function deletePayment(int $id): bool
    {
        return $this->paymentRepository->deletePayment($id);
    }

    public function getAllPayments(): array
    {
        return $this->paymentRepository->getAllPayments();
    }

    public function getPaymentByMethod(string $method): ?array
    {
        return $this->paymentRepository->getPaymentByMethod($method);
    }

    public function uploadPayslip(array $file): string
    {
        return $this->paymentRepository->uploadPayslip($file);
    }
}
