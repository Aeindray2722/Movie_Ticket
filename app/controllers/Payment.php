<?php
require_once __DIR__ . '/../repositories/PaymentRepository.php';
require_once __DIR__ . '/../services/PaymentService.php';

class Payment extends Controller
{
    private $paymentService;

    public function __construct()
    {
        try {
            parent::__construct();
            $this->requireAuth();
            $db = new Database();
            $paymentRepository = new PaymentRepository($db);
            $this->paymentService = new PaymentService($paymentRepository);
            // CSRF token generation
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            $this->model('PaymentModel');
        } catch (Exception $e) {
            setMessage('error', 'Initialization failed: ' . $e->getMessage());
            redirect('error');
        }
    }

    public function middleware()
    {
        return [
            'index' => ['AdminMiddleware'],
            'edit' => ['AdminMiddleware'],
            'payment' => ['CustomerMiddleware'],
            'storePayment' => ['CustomerMiddleware'],
        ];
    }

    public function index()
    {
        try {
            $limit = 3;
            $page = isset($_GET['page']) ? max((int) $_GET['page'], 1) : 1;

            $data = $this->paymentService->getPaginatedPayments($limit, $page);
            $this->view('admin/payment/add_payment', $data);
        } catch (Exception $e) {
            setMessage('error', 'Unable to load payments: ' . $e->getMessage());
            redirect('dashboard');
        }
    }

    public function store()
    {
        try {
            // var_dump($_SESSION); var_dump($_POST); exit;
            // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('payment');
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $paymentData = [
                    'payment_method' => trim($_POST['payment_method']),
                    'account_number' => trim($_POST['account_number']),
                    'account_name' => trim($_POST['account_name']),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $this->paymentService->createPayment($paymentData);
                setMessage('success', 'Payment added successfully!');
                redirect('payment/index');
            }
        } catch (Exception $e) {
            setMessage('error', 'Failed to add payment: ' . $e->getMessage());
            redirect('payment');
        }
    }

    public function edit($id)
    {
        try {
            $payment = $this->paymentService->getPaymentById($id);
            if (!$payment) {
                throw new Exception('Invalid payment ID!');
            }
            $this->view('admin/payment/edit_payment', ['payments' => $payment]);
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('payment');
        }
    }

    public function update()
    {
        try {
            // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('payment/edit');
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'];
                $paymentData = [
                    'payment_method' => $_POST['payment_method'],
                    'account_number' => $_POST['account_number'],
                    'account_name' => $_POST['account_name'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                if ($this->paymentService->updatePayment($id, $paymentData)) {
                    setMessage('success', 'Payment updated successfully!');
                } else {
                    throw new Exception('Failed to update payment!');
                }
                redirect('payment/index');
            }
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('payment');
        }
    }

    public function destroy($id)
    {
        try {
            $id = base64_decode($id);
            if ($this->paymentService->deletePayment($id)) {
                setMessage('success', 'Payment deleted successfully!');
            } else {
                throw new Exception('Failed to delete payment!');
            }
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
        }
        redirect('payment/index');
    }

    public function payment()
    {
        try {
            $user_id = $_SESSION['user_id'] ?? null;
            if (!$user_id) {
                throw new Exception('Please log in first.');
            }

            $payment = $this->paymentService->getAllPayments();
            $db = new Database();
            $users = $db->getById('users', $user_id);
            $booking = $db->getLatestBookingByUserId($user_id);
            $seatNames = $db->getReadableSeatNames($booking);

            $this->view('customer/payment/payment', [
                'payments' => $payment,
                'users' => $users,
                'booking' => $booking,
                'seat_names' => $seatNames
            ]);
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('pages/login');
        }
    }

    public function storePayment()
{
    try {
        // 1️⃣ CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            setMessage('error', 'Invalid CSRF token. Please refresh the page.');
            redirect('payment/payment');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;
            if (!$user_id) {
                throw new Exception('Please login first.');
            }

            // 2️⃣ Get latest booking
            $db = new Database();
            $booking = $db->getLatestBookingByUserId($user_id);
            if (!$booking) {
                throw new Exception('No booking found for payment.');
            }

            // 3️⃣ Validate payment method BEFORE calling service
            $payment_method = $_POST['payment_method'] ?? '';
            if (empty($payment_method)) {
                setMessage('error', 'Please choose a payment method before booking.');
                redirect('payment/payment');
                exit;
            }

            // 4️⃣ Now it is safe to call service
            $paymentRow = $this->paymentService->getPaymentByMethod($payment_method);
            if (!$paymentRow) {
                throw new Exception('Invalid payment method selected.');
            }

            // 5️⃣ Handle file upload
            $payslip_img = $this->paymentService->uploadPayslip($_FILES['payslip_img']);

            // 6️⃣ Save payment history
            $paymentHistoryData = [
                'booking_id' => $booking['id'],
                'payment_id' => $paymentRow['id'],
                'payslip_image' => $payslip_img,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($db->create('payment_history', $paymentHistoryData)) {
                redirect('booking/history');
            } else {
                throw new Exception('Failed to save payment history.');
            }
        }
    } catch (Exception $e) {
        setMessage('error', $e->getMessage());
        redirect('payment/payment');
    }
}

}
