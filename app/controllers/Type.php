<?php

class Type extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('TypeModel');
        $this->db = new Database();
    }
    // public function create()
    // {
    //     $types = $this->db->readAll('types');

    //     $data = [
    //         'types' => $types,
    //         'index' => 'type'
    //     ];

    //     $this->view('admin/movie/movie_type', $data);
    // }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['type_name'];  // will be correct value

            $type = new TypeModel();
            $type->setName($name);
            $type->setCreatedAt(date('Y-m-d H:i:s'));
            $type->setUpdatedAt(date('Y-m-d H:i:s'));


            $typeCreated = $this->db->create('types', $type->toArray());

            if ($typeCreated) {
                setMessage('success', 'Create successful!');
            } else {
                setMessage('error', 'Insert failed!');
            }

            redirect('type');
        }
    }
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = $_POST['type_name'];
            $id = $_POST['id'];

            $type = new TypeModel();
            $type->setId($id);
            $type->setName($name);
            $type->setUpdatedAt(date('Y-m-d H:i:s'));
            $data = $type->toArray();
            unset($data['created_at']); // VERY IMPORTANT
            $isUpdated = $this->db->update('types', $type->getId(), $data);

            setMessage('success', 'Update successful!');
            redirect('type');
        }
    }





    public function index()
    {
        $limit = 3; // number of types per page
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1)
            $page = 1;

        $offset = ($page - 1) * $limit;

        // $movieData = $this->db->readAll('types'); 

        // ✅ Correct: only fetch paged data
        $movieData = $this->db->readPaged('types', $limit, $offset);

        // Count total types for pagination
        $totalRecords = count($this->db->readAll('types'));
        $totalPages = ceil($totalRecords / $limit);

        $data = [
            'movieData' => $movieData,
            'page' => $page,
            'totalPages' => $totalPages
        ];

        $this->view('admin/movie/movie_type', $data);
    }


    public function editType($id)
    {
        $editData = $this->db->getById('types', $id);
        if (!$editData) {
            setMessage('error', 'Your Movie id is not have');
            return;
        }
        $data = [
            'editData' => $editData
        ];
        $this->view('admin/movie/edit_movieType', $data);
    }

    public function destroy($id)
    {
        $id = base64_decode($id);

        $types = new TypeModel();
        $types->setId($id);

        $isdestroy = $this->db->delete('types', $types->getId($id));
        redirect('type');
    }
}
