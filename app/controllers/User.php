<?php

class User extends Controller
{
    private $db;
    private $userModel;

    public function __construct()
    {
        $this->db = new Database();
        $this->model('UserModel'); // You might want to store this in a property if you use it often
    }
    

    public function middleware()
    {
        return [
            'index' => ['AdminMiddleware'],
            'Userindex' => ['CustomerMiddleware'],
            'create' => ['AdminMiddleware'],
            'store' => ['AdminMiddleware'],
            'editProfile' => ['AdminMiddleware'],
            'editUserProfile' => ['CustomerMiddleware'],
            'update' => ['AuthMiddleware'],
            'destroy' => ['AdminMiddleware'],
            'staffList' => ['AdminMiddleware'],
            'userList' => ['AdminMiddleware'],
            'updatePassword' => ['AuthMiddleware'],
            'storeUserOrStaff' => ['AdminMiddleware'],
            'changePassword' => ['AdminMiddleware'],
            'addStaff' => ['AdminMiddleware'],
            'addUser' => ['AdminMiddleware'],
            'UserchangePassword' => ['CustomerMiddleware'],
        ];
    }

    private function getCurrentUser()
    {
        if (!isset($_SESSION['user_id'])) {
            setMessage('error', 'Please login first.');
            redirect('pages/login');
            exit;
        }
        $user = $this->db->getById('users', $_SESSION['user_id']);
        if (!$user) {
            setMessage('error', 'User not found.');
            redirect('pages/login');
            exit;
        }
        return $user;
    }

    public function index()
    {
        $user = $this->getCurrentUser();
        $this->view('admin/profile/account_profile', ['user_info' => $user]);
    }

    public function Userindex()
    {
        $user = $this->getCurrentUser();
        $this->view('customer/profile/account_profile', ['user_info' => $user]);
    }

    public function editProfile()
    {
        $user = $this->getCurrentUser();
        $this->view('admin/profile/edit_profile', ['users' => $user]);
    }

    public function editUserProfile()
    {
        $user = $this->getCurrentUser();
        $this->view('customer/profile/edit_profile', ['users' => $user]);
    }

    public function update()
    {
        // var_dump($_POST); exit;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user');
            return;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            setMessage('error', 'Invalid user ID.');
            redirect('user/editProfile');
            return;
        }

        $old_profile = $this->db->getById('users', $id);
                // var_dump($old_profile); exit;
        if (!$old_profile) {
            setMessage('error', 'User not found.');
            redirect('user');
            return;
        }
        // Prepare data for validation
        $validationData = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'password' => 'dummy_password_for_validation', // For validator
        ];

        require_once __DIR__ . '/../helpers/UserValidator.php';
        $validator = new UserValidator($validationData);
        $errors = $validator->validateFormForUpdate();

        if (!empty($errors)) {
            foreach ($errors as $error) {
                setMessage('error', $error);
            }
            redirect('user/editProfile');
            return;
        }

        $name = $validationData['name'];
        $email = $validationData['email'];
        $phone = $validationData['phone'];
        $role = isset($_POST['role']) ? (int) $_POST['role'] : $old_profile['role'];
        $password = $old_profile['password'];
        $customerType = $old_profile['customer_type'];
        $provider_token = $old_profile['provider_token'];
        $profile_img = $old_profile['profile_img'];

        // Email uniqueness check
        if ($email !== $old_profile['email'] && $this->db->columnFilter('users', 'email', $email)) {
            setMessage('error', 'This email is already registered!');
            redirect('user/editProfile');
            return;
        }

        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
            $oldImagePath = __DIR__ . '/../../public/images/users/' . $profile_img;
            if (file_exists($oldImagePath) && $profile_img !== 'default_profile.jpg') {
                unlink($oldImagePath);
            }

            $newFilename = time() . '_' . basename($_FILES['profile_image']['name']);
            $targetDir = __DIR__ . '/../../public/images/users/';
            $targetFile = $targetDir . $newFilename;

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
                $profile_img = $newFilename;
            }
        }

        // Prepare UserModel for update
        $userModel = new UserModel();
        $userModel->setId($id);
        $userModel->setName($name);
        $userModel->setEmail($email);
        $userModel->setPhone($phone);
        $userModel->setProviderToken($provider_token);
        $userModel->setIsActive($old_profile['is_active']);
        $userModel->setIsLogin($old_profile['is_login']);
        $userModel->setIsConfirmed($old_profile['is_confirmed']);
        $userModel->setRole($role);
        $userModel->setProfileImg($profile_img);
        $userModel->setPassword($password);
        $userModel->setUpdatedAt(date('Y-m-d H:i:s'));
        $customerType = $old_profile['customer_type'] ?: 'Normal';  // fallback if null

        $userModel->setCustomerType($customerType);

        $data = $userModel->toArray();
        unset($data['created_at']);
        // $role = (int) ($_POST['role']);
        // var_dump($role); exit;
        $isUpdated = $this->db->update('users', $id, $data);
        // var_dump($_SESSION[int('role')]); exit;

        if ($isUpdated) {
            $_SESSION['profile_img'] = $profile_img;
            $_SESSION['user_name'] = $name;
            $_SESSION['role'] = (int) $role; // cast to integer
            setMessage('success', 'Update successful!');
            redirect($role === 1 ? 'user/Userindex' : 'user');
           
        } else {
            setMessage('error', 'Failed to update user.');

            // ❗ Error redirect based on role
            if ($role === 0) {
                redirect('user/editProfile');
            } elseif ($role === 1) {
                redirect('user/editUserProfile');
            } else {
                redirect('user');
            }
        }

    }

    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user');
            return;
        }

        $user = $this->getCurrentUser();

        require_once __DIR__ . '/../helpers/UserValidator.php';
        $validator = new UserValidator($_POST);
        $errors = $validator->validatePasswordChange();

        if (!password_verify($_POST['old_password'] ?? '', $user['password'])) {
            $errors['old_password-err'] = 'Old password is incorrect.';
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                setMessage('error', $error);
            }
            $redirectRoute = ((int) ($user['role'] ?? 0) === 1) ? 'user/UserchangePassword' : 'user/changePassword';
            redirect($redirectRoute);
            return;
        }

        $hashedPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        $data = [
            'password' => $hashedPassword,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $isUpdated = $this->db->update('users', $user['id'], $data);

        if ($isUpdated) {
            setMessage('success', 'Password updated successfully.');
            $role = (int) ($_POST['role'] ?? $user['role']);
            redirect($role === 1 ? 'user/Userindex' : 'user/index');
        } else {
            setMessage('error', 'Failed to update password.');
            $role = (int) ($_POST['role'] ?? $user['role']);
            redirect($role === 1 ? 'user/UserchangePassword' : 'user/changePassword');
        }

    }

    public function staffList()
    {
        $searchQuery = trim($_GET['search'] ?? '');
        $staff = [];

        if ($searchQuery !== '') {
            $result = $this->db->search(
                'users',
                ['name', 'email', 'phone'],
                $searchQuery,
                100,
                0
            );

            $staff = array_filter($result['data'], fn($user) => $user['role'] == 0);
        } else {
            $staff = $this->db->readWithCondition('users', 'role = 0');
        }

        $this->view('admin/profile/staff_list', [
            'staff_members' => $staff,
            'search_query' => $searchQuery
        ]);
    }

    public function storeUserOrStaff()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $role = (int) ($_POST['role'] ?? 1);

        require_once __DIR__ . '/../helpers/UserValidator.php';

        $validationData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
        ];
        require_once __DIR__ . '/../helpers/UserValidator.php';

        // Include the UserValidator class
        // require_once '../app/validators/UserValidator.php'; // Adjust path if needed
        $validator = new UserValidator($validationData);
        $errors = $validator->validateForm(); // ✅ This checks password too
        // $validator = new UserValidator($validationData);
        // $errors = $validator->validateFormForCreate();

        if (!empty($errors)) {
            foreach ($errors as $error) {
                setMessage('error', $error);
            }
            redirect($role === 1 ? 'user/addUser' : 'user/addStaff');
            return;
        }

        if ($this->db->columnFilter('users', 'email', $email)) {
            setMessage('error', 'This email is already registered!');
            redirect($role === 1 ? 'user/addUser' : 'user/addStaff');
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $profile_img = 'default_profile.jpg';
        $provider_token = bin2hex(random_bytes(50));

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'phone' => $phone,
            'profile_img' => $profile_img,
            'provider_token' => $provider_token,
            'customer_type' => 'Normal',
            'is_active' => 1,
            'is_login' => 0,
            'is_confirmed' => 1,
            'role' => $role,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $inserted = $this->db->create('users', $data);

        if ($inserted) {
            setMessage('success', ($role === 1 ? 'User' : 'Staff') . ' created successfully!');
            redirect($role === 1 ? 'user/userList' : 'user/staffList');
        } else {
            setMessage('error', 'Failed to create ' . ($role === 1 ? 'user' : 'staff') . '!');
            redirect($role === 1 ? 'user/addUser' : 'user/addStaff');
        }
    }

    public function destroy($encodedId)
    {
        $id = base64_decode($encodedId);
        $user = $this->db->getById('users', $id);

        if (!$user) {
            setMessage('error', 'User not found.');
            redirect('user');
            return;
        }
        $role = (int) ($_POST['role'] ?? $user['role']);
        $this->db->delete('users', $id);
        // var_dump($role); exit;
        setMessage('success', ($role === 1 ? 'User' : 'Staff') . ' deleted successfully!');
            redirect($role === 1 ? 'user/userList' : 'user/staffList');
    }

    public function userList()
    {
        $searchQuery = trim($_GET['search'] ?? '');
        $users = [];

        if ($searchQuery !== '') {
            $result = $this->db->search(
                'users',
                ['name', 'email', 'phone', 'customer_type'],
                $searchQuery,
                100,
                0
            );

            $users = array_filter($result['data'], fn($user) => $user['role'] == 1);
        } else {
            $users = $this->db->readWithCondition('users', 'role = 1');
        }

        $this->view('admin/profile/user_list', [
            'user_members' => $users,
            'search_query' => $searchQuery
        ]);
    }

    public function changePassword()
    {
        $this->view('admin/profile/change_password');
    }

    public function addStaff()
    {
        $this->view('admin/profile/create_staff');
    }

    public function addUser()
    {
        $this->view('admin/profile/create_user');
    }

    public function UserchangePassword()
    {
        $this->view('customer/profile/change_password');
    }
}
