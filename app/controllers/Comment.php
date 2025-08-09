<?php
class Comment extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('CommentModel'); // Assuming you have a CommentModel 
        $this->db = new Database();
    }
  
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate user login and input
            if (!isset($_SESSION['user_id'])) {
                setMessage('error', 'You must be logged in to comment.');
                redirect('movie/nowShowing');
                exit;
            }

            $commentText = trim($_POST['comment_text']);
            $movieId = (int) $_POST['movie_id'];
            $userId = $_SESSION['user_id'] ?? null;

            if (empty($commentText)) {
                setMessage('error', 'Comment cannot be empty.');
                redirect('movie/movieDetail/' . $movieId);
                exit;
            }

            // Prepare data to insert
            $data = [
                'movie_id' => $movieId,
                'user_id' => $userId,
                'message' => $commentText,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // $commentModel = new CommentModel();
            $commentCreated = $this->db->create('comments', $data);
            // redirect('movie/movieDetail/' . $movieId);
            redirect("movie/movieDetail/$movieId");
        }
    }

    // Destroy - Delete a comment from the database 
    public function destroy($encodedId)
    {
        $comment_id = base64_decode($encodedId);
        $user_id = $_SESSION['user_id'];

        // Check ownership
        $comment = $this->db->columnFilter('comments', 'id', $comment_id);
        if (!$comment || $comment['user_id'] != $user_id) {
            setMessage('error', 'Unauthorized');
            redirect('movie/nowShowing');
            exit;
        }
        $movie_id = $comment['movie_id'];

        // Delete comment using your Database delete function
        $this->db->delete('comments', $comment_id);

        redirect("movie/movieDetail/$movie_id");
    }



}
?>