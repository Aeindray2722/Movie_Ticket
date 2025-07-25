<?php

class Seat extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->model('SeatModel');
    }

    public function index()
    {
        $limit = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $page = ($page < 1) ? 1 : $page;
        $offset = ($page - 1) * $limit;

        $seats = $this->db->readPaged('seats', $limit, $offset);
        $totalSeats = count($this->db->readAll('seats'));
        $totalPages = ceil($totalSeats / $limit);

        $data = [
            'seats' => $seats,
            'page' => $page,
            'totalPages' => $totalPages
        ];

        $this->view('admin/seat/add_seat', $data);
    }

    public function create()
    {
        $seats = $this->db->readAll('seats');

        $data = [
            'seats' => $seats,
            'index' => 'movie'
        ];

        $this->view('admin/seat/add_seat', $data);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $seat_row = trim($_POST['seat_row']);
            $seat_number = trim($_POST['seat_number']);
            $price = trim($_POST['price']);

            // Optional: Validate values here

            $seatData = [
                'seat_row' => $seat_row,
                'seat_number' => $seat_number,
                'price' => $price,
                'status' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->create('seats', $seatData);
            $_SESSION['success'] = "Seat added successfully!";
            header("Location: " . URLROOT . "/seat");
            exit;
        }
    }

    public function edit($id)
    {

        $seat = $this->db->getById('seats', $id);
        if (!$seat) {
            setMessage('error', 'Invalid seat ID!');
            redirect('seat');
            return;
        }

        $data = [
            'seats' => $seat
        ];

        $this->view('admin/seat/edit_seat', $data);
    }

    // Uncomment this if you want to enable updates
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $seat_row = $_POST['seat_row'];
            $seat_number = $_POST['seat_number'];
            $price = $_POST['price'];

            $seatData = [
                'seat_row' => $seat_row,
                'seat_number' => $seat_number,
                'price' => $price,
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $isUpdated = $this->db->update('seats', $id, $seatData);

            if ($isUpdated) {
                setMessage('success', 'Seat updated successfully!');
            } else {
                setMessage('error', 'Failed to update seat!');
            }

            redirect('seat');
        }
    }

    public function destroy($id)
    {
        $id = base64_decode($id);

        $isDeleted = $this->db->delete('seats', $id);

        if ($isDeleted) {
            setMessage('success', 'Seat deleted successfully!');
        } else {
            setMessage('error', 'Failed to delete seat!');
        }

        redirect('seat');
    }
}
