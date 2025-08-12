<?php

class Type extends Controller
{
    private $db;

    public function __construct()
    {
        try {
            $this->model('TypeModel');
            $this->db = new Database();
        } catch (Exception $e) {
            setMessage('error', 'Initialization error: ' . $e->getMessage());
            redirect('error'); // or any error page you have
        }
    }

    public function middleware()
    {
        return [
            'index' => ['AdminMiddleware'],
            'create' => ['AdminMiddleware'],
            'store' => ['AdminMiddleware'],
            'editType' => ['AdminMiddleware'],
            'update' => ['AdminMiddleware'],
            'destroy' => ['AdminMiddleware'],
        ];
    }

    public function store()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                redirect('type');
                return;
            }

            $name = $_POST['type_name'] ?? null;
            if (!$name) {
                throw new Exception('Type name is required.');
            }

            $type = new TypeModel();
            $type->setName($name);
            $type->setCreatedAt(date('Y-m-d H:i:s'));
            $type->setUpdatedAt(date('Y-m-d H:i:s'));

            $typeCreated = $this->db->create('types', $type->toArray());

            if (!$typeCreated) {
                throw new Exception('Failed to add new type.');
            }

            setMessage('success', "Type added successfully!");
            redirect('type');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('type');
        }
    }

    public function update()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                redirect('type');
                return;
            }

            $id = $_POST['id'] ?? null;
            $name = $_POST['type_name'] ?? null;

            if (!$id || !$name) {
                throw new Exception('Invalid data provided for update.');
            }

            $type = new TypeModel();
            $type->setId($id);
            $type->setName($name);
            $type->setUpdatedAt(date('Y-m-d H:i:s'));

            $data = $type->toArray();
            unset($data['created_at']); // do not update created_at

            $isUpdated = $this->db->update('types', $type->getId(), $data);

            if (!$isUpdated) {
                throw new Exception('Update failed.');
            }

            setMessage('success', 'Update successful!');
            redirect('type');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('type');
        }
    }

    public function index()
    {
        try {
            $limit = 3; // number of types per page
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            if ($page < 1) {
                $page = 1;
            }

            $offset = ($page - 1) * $limit;

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
        } catch (Exception $e) {
            setMessage('error', 'Failed to load types: ' . $e->getMessage());
            redirect('type');
        }
    }

    public function editType($id)
    {
        try {
            $editData = $this->db->getById('types', $id);
            if (!$editData) {
                throw new Exception('Type not found.');
            }

            $data = [
                'editData' => $editData
            ];
            $this->view('admin/movie/edit_movieType', $data);
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('type');
        }
    }

    public function destroy($id)
    {
        try {
            $id = base64_decode($id);
            $id = (int)$id;
            if (!$id) {
                throw new Exception('Invalid type ID.');
            }

            $types = new TypeModel();
            $types->setId($id);

            $isdestroy = $this->db->delete('types', $types->getId());

            if (!$isdestroy) {
                throw new Exception('Failed to delete type.');
            }

            setMessage('success', 'Type deleted successfully!');
            redirect('type');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('type');
        }
    }
}
