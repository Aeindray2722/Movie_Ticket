<?php

class Payment extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
        $this->model('PaymentModel');
    }
    public function middleware()
    {
        return [
            'index' => ['AdminMiddleware'],
            'store' => ['AdminMiddleware'],
            'edit' => ['AdminMiddleware'],
            'update' => ['AdminMiddleware'],
            'destroy' => ['AdminMiddleware'],
            'payment' => ['CustomerMiddleware'],
            'storePayment' => ['CustomerMiddleware'],
        ];
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
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            setMessage('error', 'Please log in first.');
            redirect('pages/login');
            return;
        }

        $payment = $this->db->readAll('payments');
        $users = $this->db->getById('users', $user_id);

        $stmt = $this->db->pdo->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$user_id]);
        $booking = $stmt->fetch();

        // Convert seat_id JSON to array
        // $seatIds = json_decode($booking['seat_id'], true);
        $seatIds = [];

        if (isset($booking['seat_id']) && !empty($booking['seat_id'])) {
            $decoded = json_decode($booking['seat_id'], true);
            if (is_array($decoded)) {
                $seatIds = $decoded;
            }
        }
        // Get seat names by IDs
        $seatMap = $this->db->getSeatNamesByIds($seatIds);
        $seatNames = [];
        foreach ($seatIds as $id) {
            if (isset($seatMap[$id])) {
                $seatNames[] = $seatMap[$id];
            }
        }

        $data = [
            'payments' => $payment,
            'users' => $users,
            'booking' => $booking,
            'seat_names' => $seatNames
        ];

        $this->view('customer/payment/payment', $data);
    }
    public function storePayment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;
            if (!$user_id) {
                setMessage('error', 'Please login first.');
                redirect('pages/login');
                return;
            }

            // Get latest booking of this user (same as in Payment())
            $stmt = $this->db->pdo->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY id DESC LIMIT 1");
            $stmt->execute([$user_id]);
            $booking = $stmt->fetch();

            if (!$booking) {
                setMessage('error', 'No booking found for payment.');
                redirect('customer/booking/booking_history');
                return;
            }

            // Get posted payment method (payment_method is actually the payment ID or method string?)
            $payment_method = $_POST['payment_method'] ?? null;

            // Find payment_id from payments table by payment_method string
            $paymentRow = $this->db->columnFilter('payments', 'payment_method', $payment_method);

            if (!$paymentRow) {
                setMessage('error', 'Invalid payment method selected.');
                redirect('payment/payment');
                return;
            }

            $payment_id = $paymentRow['id'];

            $payslip_img = '';

            if (isset($_FILES['payslip_img']) && $_FILES['payslip_img']['error'] == 0) {
                $targetDir = __DIR__ . '/../../public/images/payslips/';

                $payslip_img = time() . '_' . basename($_FILES['payslip_img']['name']);
                $targetFile = $targetDir . $payslip_img;

                move_uploaded_file($_FILES['payslip_img']['tmp_name'], $targetFile);
            }

            // Prepare data for payment_history table
            $paymentHistoryData = [
                'booking_id' => $booking['id'],
                'payment_id' => $payment_id,
                'payslip_image' => $payslip_img,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $insertId = $this->db->create('payment_history', $paymentHistoryData);

            if ($insertId) {
                // setMessage('success', 'Payment recorded successfully.');
                redirect('booking/history');
            } else {
                // setMessage('error', 'Failed to record payment.');
                redirect('payment/payment');
            }
        } else {
            redirect('payment/payment');
        }
    }








}
