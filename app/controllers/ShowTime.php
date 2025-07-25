<?php  
class ShowTimeController extends Controller { 
    private $db; 
    
    public function __construct() { 
        $this->model('ShowTimeModel'); // Assuming you have a ShowTimeModel 
        $this->db = new Database(); 
    } 
    
    // Index - Display all showtimes 
    public function index() { 
        $showTimes = $this->db->readAll('show_times'); // Fetch all showtimes 
        $data = [ 
            'show_times' => $showTimes 
        ]; 
        $this->view('showTime/index', $data); // Load the view to display showtimes 
    } 
    
    // Create - Display form to add a new showtime 
    public function create() { 
        $data = [ 
            'index' => 'showTime' 
        ]; 
        $this->view('showTime/create', $data); // Display the create view 
    } 
    
    // Store - Save the showtime in the database 
    public function store() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            // Get the input data from the form 
            $show_time = $_POST['show_time']; 

            // Create a new ShowTimeModel object 
            $showTime = new ShowTimeModel(); 
            
            // Set showtime properties 
            $showTime->setShowTime($show_time); 
            $showTime->setCreatedAt(date('Y-m-d H:i:s')); // Set current timestamp for created_at 
            $showTime->setUpdatedAt(date('Y-m-d H:i:s')); // Set current timestamp for updated_at 

            // Insert the showtime data into the database 
            $showTimeCreated = $this->db->create('show_times', $showTime->toArray()); 

            setMessage('success', 'Showtime added successfully!'); 
            redirect('showTime'); // Redirect to showtime index page 
        } 
    } 
    
    // Edit - Display the form to edit an existing showtime 
    public function edit($id) { 
        // Fetch the showtime by its ID 
        $showTime = $this->db->getById('show_times', $id); 

        $data = [ 
            'show_time' => $showTime 
        ]; 
        
        $this->view('showTime/edit', $data); // Display the edit form with existing showtime data 
    } 
    
    // Update - Save the edited showtime data 
    public function update() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            // Get updated data 
            $id = $_POST['id']; 
            $show_time = $_POST['show_time']; 

            // Create a ShowTimeModel object and set its properties 
            $showTime = new ShowTimeModel(); 
            $showTime->setId($id); 
            $showTime->setShowTime($show_time); 
            $showTime->setUpdatedAt(date('Y-m-d H:i:s')); // Update timestamp for updated_at 

            // Update the showtime in the database 
            $isUpdated = $this->db->update('show_times', $showTime->getId(), $showTime->toArray()); 

            setMessage('success', 'Showtime updated successfully!'); 
            redirect('showTime'); // Redirect to showtime index page 
        } 
    } 
    
    // Destroy - Delete a showtime from the database 
    public function destroy($id) { 
        $id = base64_decode($id); // Decode ID (optional) 

        $showTime = new ShowTimeModel(); 
        $showTime->setId($id); 

        // Delete the showtime 
        $isDeleted = $this->db->delete('show_times', $showTime->getId()); 

        setMessage('success', 'Showtime deleted successfully!'); 
        redirect('showTime'); // Redirect to showtime index page 
    } 

    // Fetch showtime data as JSON (for AJAX requests) 
    public function showTimeData() { 
        $showTimes = $this->db->readAll('show_times'); 
        $json = array('data' => $showTimes); 
        echo json_encode($json); 
    } 
}
?>
