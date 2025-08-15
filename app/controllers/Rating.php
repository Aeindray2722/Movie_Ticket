<?php
class Rating extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('RatingModel'); // optional if you want a model for Rating
        $this->db = new Database();
    }

    public function submit()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $user_id = $_SESSION['user_id'] ?? null;
                $movie_id = (int) $_POST['movie_id'];
                $count = (int) $_POST['count'];
                $source = $_POST['source'] ?? 'movie'; // default to movie

                if (!$user_id || $count < 1 || $count > 5) {
                    setMessage('error', 'Invalid request.');
                    redirect($this->getRedirectUrl($source, $movie_id));
                    return;
                }

                $existingRating = $this->db->getRatingByUserAndMovie($user_id, $movie_id);

                if ($existingRating) {
                    $updateData = [
                        'count' => $count,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->db->update('ratings', $existingRating['id'], $updateData);
                } else {
                    $insertData = [
                        'user_id' => $user_id,
                        'movie_id' => $movie_id,
                        'count' => $count,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->db->create('ratings', $insertData);
                }

                setMessage('success', 'Rating submitted successfully.');
                redirect($this->getRedirectUrl($source, $movie_id));
            }
        } catch (Exception $e) {
            error_log('Rating submission error: ' . $e->getMessage());
            setMessage('error', 'Something went wrong while submitting your rating. Please try again.');
            redirect(isset($movie_id) ? $this->getRedirectUrl($source ?? 'movie', $movie_id) : 'pages/home');
        }
    }

    private function getRedirectUrl($source, $movie_id)
    {
        if ($source === 'trailer') {
            return "trailer/movieDetail/$movie_id";
        }
        // Default to movie detail for now_showing or unknown sources
        return "movie/movieDetail/$movie_id";
    }

}
