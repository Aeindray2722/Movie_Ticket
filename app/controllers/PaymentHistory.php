<?php

class PaymentHistoryController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('PaymentHistoryModel'); // You need to create this
        $this->db = new Database();
    }

    // Index - Show all payment histories
    public function index()
    {
        $histories = $this->db->readAll('payment_histories'); // Can join users/payments in DB layer
        $data = [
            'histories' => $histories
        ];
        $this->view('payment_history/index', $data);
    }

    // Create - Show form for uploading payslip
    public function create()
    {
        $users = $this->db->readAll('users');
        $payments = $this->db->readAll('payments');

        $data = [
            'users' => $users,
            'payments' => $payments,
            'index' => 'payment_history'
        ];
        $this->view('payment_history/create', $data);
    }

    // Store - Save new payment history
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $payment_id = $_POST['payment_id'];
            $total_amount = $_POST['total_amount'];
            $payslip_image = $_FILES['payslip_image']['name'];

            // Upload payslip image
            if ($_FILES['payslip_image']['error'] == 0) {
                $targetDir = "uploads/payslips/";
                $targetFile = $targetDir . basename($payslip_image);
                move_uploaded_file($_FILES['payslip_image']['tmp_name'], $targetFile);
            }

            $history = new PaymentHistoryModel();

            $history->setUserId($user_id);
            $history->setPaymentId($payment_id);
            $history->setPayslipImage($payslip_image);
            $history->setTotalAmount($total_amount);
            $history->setCreatedAt(date('Y-m-d H:i:s'));
            $history->setUpdatedAt(date('Y-m-d H:i:s'));

            $this->db->create('payment_histories', $history->toArray());

            setMessage('success', 'Payment history recorded successfully!');
            redirect('paymentHistory');
        }
    }

    // Edit - Show form to edit a payment history
    public function edit($id)
    {
        $history = $this->db->getById('payment_histories', $id);
        $users = $this->db->readAll('users');
        $payments = $this->db->readAll('payments');

        $data = [
            'history' => $history,
            'users' => $users,
            'payments' => $payments
        ];

        $this->view('payment_history/edit', $data);
    }

    // Update - Save changes to payment history
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $user_id = $_POST['user_id'];
            $payment_id = $_POST['payment_id'];
            $total_amount = $_POST['total_amount'];
            $payslip_image = $_FILES['payslip_image']['name'];

            if ($_FILES['payslip_image']['error'] == 0) {
                $targetDir = "uploads/payslips/";
                $targetFile = $targetDir . basename($payslip_image);
                move_uploaded_file($_FILES['payslip_image']['tmp_name'], $targetFile);
            }

            $history = new PaymentHistoryModel();

            $history->setId($id);
            $history->setUserId($user_id);
            $history->setPaymentId($payment_id);
            $history->setPayslipImage($payslip_image);
            $history->setTotalAmount($total_amount);
            $history->setUpdatedAt(date('Y-m-d H:i:s'));

            $this->db->update('payment_histories', $history->getId(), $history->toArray());

            setMessage('success', 'Payment history updated successfully!');
            redirect('paymentHistory');
        }
    }

    // Destroy - Delete payment history
    public function destroy($id)
    {
        $id = base64_decode($id);
        $history = new PaymentHistoryModel();
        $history->setId($id);

        $this->db->delete('payment_histories', $history->getId());

        setMessage('success', 'Payment history deleted successfully!');
        redirect('paymentHistory');
    }

    // JSON - Fetch all payment histories (for DataTables or API)
    public function paymentHistoryData()
    {
        $histories = $this->db->readAll('payment_histories');
        echo json_encode(['data' => $histories]);
    }
}
?>
