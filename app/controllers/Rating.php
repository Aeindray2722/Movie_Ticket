<?php
class Rating extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('RatingModel'); // optional if you want a model for Rating
        $this->db = new Database();
    }
     public function middleware()
    {
        return [
            'submit' => ['CustomerMiddleware'],
            'storePayment' => ['CustomerMiddleware'],
        ];
    }
    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;
            $movie_id = (int) $_POST['movie_id'];
            $count = (int) $_POST['count'];

            if (!$user_id || $count < 1 || $count > 5) {
                setMessage('error', 'Invalid request.');
                redirect("movie/movieDetail/$movie_id");
                exit;
            }

            // Check if user already rated this movie
            $this->db->query("SELECT * FROM ratings WHERE user_id = :user_id AND movie_id = :movie_id");
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':movie_id', $movie_id);
            $this->db->stmt->execute();
            $existingRating = $this->db->stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingRating) {
                // Update existing rating by id
                $updateData = [
                    'count' => $count,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $this->db->update('ratings', $existingRating['id'], $updateData);
            } else {
                // Insert new rating
                $insertData = [
                    'user_id' => $user_id,
                    'movie_id' => $movie_id,
                    'count' => $count,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $this->db->create('ratings', $insertData);
            }

            redirect("movie/movieDetail/$movie_id");
            exit;
        }
    }
}
