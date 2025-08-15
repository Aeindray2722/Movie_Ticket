<?php
class Comment extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('CommentModel'); // Assuming you have a CommentModel
        $this->db = new Database();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    // public function middleware()
    // {
    //     return [
    //         'store' => ['CustomerMiddleware'],
    //     ];
    // }

    // Store - Add a new comment
    public function store()
    {
        try {
            // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('customer/movie/trailer_detail');
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method.');
            }

            if (!isset($_SESSION['user_id'])) {
                setMessage('error', 'You must login first to comment.');
                redirect('pages/login'); // redirect to login page
                exit;
            }


            $commentText = trim($_POST['comment_text'] ?? '');
            $movieId = (int) ($_POST['movie_id'] ?? 0);
            $userId = $_SESSION['user_id'];

            if (empty($commentText)) {
                throw new Exception('Comment cannot be empty.');
            }

            $data = [
                'movie_id' => $movieId,
                'user_id' => $userId,
                'message' => $commentText,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if (!$this->db->create('comments', $data)) {
                throw new Exception('Failed to create comment.');
            }

            setMessage('success', 'Comment added successfully.');
            redirect("movie/movieDetail/$movieId");

        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect("movie/movieDetail/" . ($_POST['movie_id'] ?? 0));
        }
    }

    // Destroy - Delete a comment
    public function destroy($encodedId)
    {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('You must be logged in to delete comments.');
            }

            $comment_id = base64_decode($encodedId);
            $user_id = $_SESSION['user_id'];

            $comment = $this->db->columnFilter('comments', 'id', $comment_id);
            if (!$comment) {
                throw new Exception('Comment not found.');
            }

            if ($comment['user_id'] != $user_id) {
                throw new Exception('Unauthorized action.');
            }

            $movie_id = $comment['movie_id'];

            if (!$this->db->delete('comments', $comment_id)) {
                throw new Exception('Failed to delete comment.');
            }

            setMessage('success', 'Comment deleted successfully.');
            redirect("movie/movieDetail/$movie_id");

        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('movie/nowShowing');
        }
    }
}
