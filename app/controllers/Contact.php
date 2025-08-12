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

            if (!isset($_SESSION['user_id'])) {
                throw new Exception('You must be logged in to send a message.');
            }

            $email = $_POST['email'] ?? '';
            $contactText = trim($_POST['contact_text'] ?? '');
            $userId = $_SESSION['user_id'] ?? null;

            if (empty($email) || empty($contactText)) {
                throw new Exception('Email and message are required.');
            }

            $data = [
                'user_id'    => $userId,
                'message'    => $contactText,
                'email'      => $email,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if (!$this->db->create('contacts', $data)) {
                throw new Exception('Failed to send message.');
            }

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
