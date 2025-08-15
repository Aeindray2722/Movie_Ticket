<?php
class Contact extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('ContactModel');
        $this->db = new Database();
    }

    public function middleware()
    {
        return [
            'index' => ['AdminMiddleware'],
        ];
    }

    public function index()
    {
        try {
            $limit = 10;
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $page = ($page < 1) ? 1 : $page;
            $offset = ($page - 1) * $limit;

            $contacts = $this->db->readPaged('contacts', $limit, $offset);
            $totalcontacts = count($this->db->readAll('contacts'));
            $totalPages = ceil($totalcontacts / $limit);

            $data = [
                'contacts'   => $contacts,
                'page'       => $page,
                'totalPages' => $totalPages
            ];

            $this->view('admin/layout/contact', $data);
        } catch (Exception $e) {
            setMessage('error', 'Error loading contacts: ' . $e->getMessage());
            redirect('pages/home');
        }
    }

    public function store()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method.');
            }

            // User must be logged in
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('You must be logged in to send a message.');
            }

            // CSRF token validation
            // 1️⃣ CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            setMessage('error', 'Invalid CSRF token. Please refresh the page.');
            redirect('customer/layout/footer');
            exit;
        }

            // Sanitize and validate input
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $contactText = htmlspecialchars(trim($_POST['contact_text'] ?? ''), ENT_QUOTES, 'UTF-8');

            if (empty($email) || empty($contactText)) {
                throw new Exception('Email and message are required.');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid email address.');
            }

            if (strlen($contactText) > 1000) {
                throw new Exception('Message too long.');
            }

            $data = [
                'user_id'    => $_SESSION['user_id'],
                'email'      => $email,
                'message'    => $contactText,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if (!$this->db->create('contacts', $data)) {
                throw new Exception('Failed to send message.');
            }

            // Regenerate CSRF token after successful submission
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            setMessage('success', 'Contact sent successfully!');
            redirect("pages/home");

        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect("pages/home");
        }
    }

    public function destroy($id)
    {
        try {
            $id = base64_decode($id);
            if (!$id) {
                throw new Exception('Invalid contact ID.');
            }

            $contacts = new ContactModel();
            $contacts->setId($id);

            if (!$this->db->delete('contacts', $contacts->getId())) {
                throw new Exception('Failed to delete contact.');
            }

            setMessage('success', 'Contact deleted successfully.');
            redirect('contact');

        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('contact');
        }
    }
}
