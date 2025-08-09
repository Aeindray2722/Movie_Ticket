<?php
class Contact extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('ContactModel'); // Assuming you have a CommentModel 
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
        $limit = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $page = ($page < 1) ? 1 : $page;
        $offset = ($page - 1) * $limit;

        $contacts = $this->db->readPaged('contacts', $limit, $offset);
        $totalcontacts = count($this->db->readAll('contacts'));
        $totalPages = ceil($totalcontacts / $limit);
        
        $data = [
            'contacts' => $contacts,
            'page' => $page,
            'totalPages' => $totalPages
        ];

        $this->view('admin/layout/contact', $data);
    }
   
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // var_dump($_POST); exit;
            // Validate user login and input
            if (!isset($_SESSION['user_id'])) {
                setMessage('error', 'You must be logged in to comment.');
                redirect('movie/nowShowing');
                exit;
            }
            $email = $_POST['email'];
            $contactText = trim($_POST['contact_text']);
            $userId = $_SESSION['user_id'] ?? null;
            // Prepare data to insert
            $data = [
                'user_id' => $userId,
                'message' => $contactText,
                'email' => $email,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // $contactModel = new contactModel();
            $contactCreated = $this->db->create('contacts', $data);

            if ($contactCreated) {
                setMessage('success',  'Contact send successfully!');
                redirect("pages/home");
            } else {
                setMessage('error', 'Failed to send !');
                redirect("pages/home");
            }
            // redirect('movie/movieDetail/' . $movieId);
            
        }
    }

    public function destroy($id)
    {
        $id = base64_decode($id);

        $contacts = new ContactModel();
        $contacts->setId($id);

        $isdestroy = $this->db->delete('contacts', $contacts->getId());
        redirect('contact');
    }



}
?>