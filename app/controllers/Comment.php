<?php  
class CommentController extends Controller { 
    private $db; 
    
    public function __construct() { 
        $this->model('CommentModel'); // Assuming you have a CommentModel 
        $this->db = new Database(); 
    } 
    
    // Index - Display all comments for a movie 
    public function index($movie_id) { 
        $comments = $this->db->readByColumn('comments', 'movie_id', $movie_id); // Fetch comments for a specific movie 
        $movie = $this->db->getById('movies', $movie_id); // Fetch movie information 
        
        $data = [ 
            'comments' => $comments, 
            'movie' => $movie 
        ]; 
        $this->view('comment/index', $data); // Load the view to display comments for this movie 
    } 
    
    // Create - Display form to add a new comment for a movie 
    public function create($movie_id) { 
        $movie = $this->db->getById('movies', $movie_id); // Fetch movie details 
        
        $data = [ 
            'movie' => $movie, 
            'movie_id' => $movie_id, 
            'index' => 'comment' 
        ]; 
        $this->view('comment/create', $data); // Display the form to add a new comment 
    } 
    
    // Store - Save the new comment in the database 
    public function store() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            // Get the input data from the form 
            $movie_id = $_POST['movie_id']; 
            $user_id = $_POST['user_id']; // Assuming you get this from session or authentication system 
            $message = $_POST['message']; 

            // Create a new CommentModel object 
            $comment = new CommentModel(); 
            
            // Set comment properties 
            $comment->setMovieId($movie_id); 
            $comment->setUserId($user_id); 
            $comment->setMessage($message); 
            $comment->setCreatedAt(date('Y-m-d H:i:s')); // Set the current timestamp for created_at 
            $comment->setUpdatedAt(date('Y-m-d H:i:s')); // Set the current timestamp for updated_at 

            // Insert the comment into the database 
            $commentCreated = $this->db->create('comments', $comment->toArray()); 

            setMessage('success', 'Comment added successfully!'); 
            redirect('comment/index/' . $movie_id); // Redirect to the movie's comment page 
        } 
    } 
    
    // Edit - Display the form to edit an existing comment 
    public function edit($id) { 
        // Fetch the comment by its ID 
        $comment = $this->db->getById('comments', $id); 

        $data = [ 
            'comment' => $comment 
        ]; 
        
        $this->view('comment/edit', $data); // Display the form with existing comment data 
    } 
    
    // Update - Save the edited comment data 
    public function update() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            // Get updated data 
            $id = $_POST['id']; 
            $message = $_POST['message']; 

            // Create a CommentModel object and set its properties 
            $comment = new CommentModel(); 
            $comment->setId($id); 
            $comment->setMessage($message); 
            $comment->setUpdatedAt(date('Y-m-d H:i:s')); // Update timestamp for updated_at 

            // Update the comment in the database 
            $isUpdated = $this->db->update('comments', $comment->getId(), $comment->toArray()); 

            setMessage('success', 'Comment updated successfully!'); 
            redirect('comment/index/' . $comment->getMovieId()); // Redirect to the movie's comment page 
        } 
    } 
    
    // Destroy - Delete a comment from the database 
    public function destroy($id) { 
        $id = base64_decode($id); // Decode the ID (optional) 

        $comment = new CommentModel(); 
        $comment->setId($id); 

        // Delete the comment 
        $isDeleted = $this->db->delete('comments', $comment->getId()); 

        setMessage('success', 'Comment deleted successfully!'); 
        redirect('comment'); // Redirect to the comments index page 
    } 

    // Fetch comment data as JSON (for AJAX requests) 
    public function commentData() { 
        $comments = $this->db->readAll('comments'); 
        $json = array('data' => $comments); 
        echo json_encode($json); 
    } 
}
?>
