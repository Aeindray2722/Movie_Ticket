<?php

class PaymentService
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getPaginatedPayments($limit, $page)
    {
        $offset = ($page - 1) * $limit;
        $payments = $this->db->readPaged('payments', $limit, $offset);
        $totalpayments = count($this->db->readAll('payments'));
        $totalPages = ceil($totalpayments / $limit);

        return [
            'payments' => $payments,
            'page' => $page,
            'totalPages' => $totalPages
        ];
    }

    public function createPayment(array $data)
    {
        return $this->db->create('payments', $data);
    }

    public function getPaymentById($id)
    {
        return $this->db->getById('payments', $id);
    }

    public function updatePayment($id, array $data)
    {
        return $this->db->update('payments', $id, $data);
    }

    public function deletePayment($id)
    {
        return $this->db->delete('payments', $id);
    }

    public function getAllPayments()
    {
        return $this->db->readAll('payments');
    }

    public function getPaymentByMethod($method)
    {
        return $this->db->columnFilter('payments', 'payment_method', $method);
    }

    public function uploadPayslip($file)
    {
        if (isset($file) && $file['error'] === 0) {
            return $this->db->uploadImage($file, '/../../public/images/payslips/');
        }
        return '';
    }
}
