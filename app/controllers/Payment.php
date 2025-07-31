<?php

class Payment extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
        $this->model('PaymentModel');
    }
    public function index()
    {
        $limit = 3;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $page = ($page < 1) ? 1 : $page;
        $offset = ($page - 1) * $limit;

        $payments = $this->db->readPaged('payments', $limit, $offset);
        $totalpayments = count($this->db->readAll('payments'));
        $totalPages = ceil($totalpayments / $limit);

        $data = [
            'payments' => $payments,
            'page' => $page,
            'totalPages' => $totalPages
        ];

        $this->view('admin/payment/add_payment', $data);
    }
   public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payment_method = trim($_POST['payment_method']);
            $account_number = trim($_POST['account_number']);
            $account_name = trim($_POST['account_name']);


            // Optional: Validate values here

            $paymentData = [
                'payment_method' => $payment_method,
                'account_number' => $account_number,
                'account_name' => $account_name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->create('payments', $paymentData);
            $_SESSION['success'] = "Payment added successfully!";
            header("Location: " . URLROOT . "/payment");
            exit;
        }
    }

    public function edit($id)
    {
        $payment = $this->db->getById('payments', $id);
        if (!$payment) {
            setMessage('error', 'Invalid payment ID!');
            redirect('payment');     
        }

        $data = [
            'payments' => $payment
        ];

        $this->view('admin/payment/edit_payment', $data);
    }
     public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $payment_method = $_POST['payment_method'];
            $account_number = $_POST['account_number'];
            $account_name = $_POST['account_name'];

            $paymentData = [
                'payment_method' => $payment_method,
                'account_number' => $account_number,
                'account_name' => $account_name,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $isUpdated = $this->db->update('payments', $id, $paymentData);

            if ($isUpdated) {
                setMessage('success', 'payment updated successfully!');
            } else {
                setMessage('error', 'Failed to update payment!');
            }

            redirect('payment');
        }
    }

    public function destroy($id)
    {
        $id = base64_decode($id);

        $isDeleted = $this->db->delete('payments', $id);

        if ($isDeleted) {
            setMessage('success', 'Payments deleted successfully!');
        } else {
            setMessage('error', 'Failed to delete payment!');
        }

        redirect('payment');
    }

    public function Payment()
    {
        $this->view('customer/payment/payment');
    }

    
   

}
