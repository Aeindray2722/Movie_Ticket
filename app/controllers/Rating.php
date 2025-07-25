<?php  
class RatingController extends Controller { 
    private $db; 
    
    public function __construct() { 
        $this->model('RatingModel'); // Assuming you have a RatingModel 
        $this->db = new Database(); 
    } 
    
    // Index - Display all ratings for a specific movie 
    public function index($movie_id) { 
        $ratings = $this->db->readByColumn('ratings', 'movie_id', $movie_id); // Fetch ratings for a specific movie 
        $movie = $this->db->getById('movies', $movie_id); // Fetch movie details
        
        $data = [ 
            'ratings' => $ratings, 
            'movie' => $movie 
        ]; 
        $this->view('rating/index', $data); // Load the view to display ratings for the movie 
    } 
    
    // Create - Display form to add a new rating for a movie 
    public function create($movie_id) { 
        $movie = $this->db->getById('movies', $movie_id); // Fetch movie details
        
        $data = [ 
            'movie' => $movie, 
            'movie_id' => $movie_id, 
            'index' => 'rating' 
        ]; 
        $this->view('rating/create', $data); // Display the create form to add a rating 
    } 
    
    // Store - Save the rating in the database 
    public function store() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            // Get the input data from the form 
            $movie_id = $_POST['movie_id']; 
            $user_id = $_POST['user_id']; // Assuming you get this from session or authentication system 
            $count = $_POST['count']; // The rating count (e.g., 1-5)

            // Create a new RatingModel object 
            $rating = new RatingModel(); 
            
            // Set rating properties 
            $rating->setMovieId($movie_id); 
            $rating->setUserId($user_id); 
            $rating->setCount($count); 
            $rating->setCreatedAt(date('Y-m-d H:i:s')); // Set current timestamp for created_at 
            $rating->setUpdatedAt(date('Y-m-d H:i:s')); // Set current timestamp for updated_at 

            // Insert the rating data into the database 
            $ratingCreated = $this->db->create('ratings', $rating->toArray()); 

            setMessage('success', 'Rating added successfully!'); 
            redirect('rating/index/' . $movie_id); // Redirect to the movie's rating page 
        } 
    } 
    
    // Edit - Display the form to edit an existing rating 
    public function edit($id) { 
        // Fetch the rating by its ID 
        $rating = $this->db->getById('ratings', $id); 

        $data = [ 
            'rating' => $rating 
        ]; 
        
        $this->view('rating/edit', $data); // Display the edit form with existing rating data 
    } 
    
    // Update - Save the edited rating data 
    public function update() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            // Get updated data 
            $id = $_POST['id']; 
            $count = $_POST['count']; // New rating count

            // Create a RatingModel object and set its properties 
            $rating = new RatingModel(); 
            $rating->setId($id); 
            $rating->setCount($count); 
            $rating->setUpdatedAt(date('Y-m-d H:i:s')); // Update timestamp for updated_at 

            // Update the rating in the database 
            $isUpdated = $this->db->update('ratings', $rating->getId(), $rating->toArray()); 

            setMessage('success', 'Rating updated successfully!'); 
            redirect('rating/index/' . $rating->getMovieId()); // Redirect to the movie's rating page 
        } 
    } 
    
    // Destroy - Delete a rating from the database 
    public function destroy($id) { 
        $id = base64_decode($id); // Decode the ID (optional) 

        $rating = new RatingModel(); 
        $rating->setId($id); 

        // Delete the rating 
        $isDeleted = $this->db->delete('ratings', $rating->getId()); 

        setMessage('success', 'Rating deleted successfully!'); 
        redirect('rating'); // Redirect to the ratings index page 
    } 

    // Fetch rating data as JSON (for AJAX requests) 
    public function ratingData() { 
        $ratings = $this->db->readAll('ratings'); 
        $json = array('data' => $ratings); 
        echo json_encode($json); 
    } 
}
?>
