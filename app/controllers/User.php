<?php

class User extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
        $this->model('UserModel');
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
            'update' => ['AdminMiddleware'],
            'destroy' => ['AdminMiddleware'],
            'staffList' => ['AdminMiddleware'],
            'userList' => ['AdminMiddleware'],
            'updatePassword' => ['AuthMiddleware'],
            'storeUserOrStaff' => ['AdminMiddleware'],
            'changePassword' => ['AdminMiddleware'],
            'addStaff' => ['AdminMiddleware'],
            'addUser' => ['AdminMiddleware'],
            'UserchangePassword' => ['CustomerMiddle']
        ];
    }
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            setMessage('error', 'Please login first.');
            redirect('pages/login');
        }
        $userId = $_SESSION['user_id'];
        $user = $this->db->getById('users', $userId);
        if (!$user) {
            setMessage('error', 'User not found.');
            redirect('pages/login');
        }

        $data = [
            'user_info' => $user
        ];

        // Redirect based on role
            $this->view('admin/profile/account_profile', $data);
    }
    public function Userindex()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            setMessage('error', 'Please login first.');
            redirect('pages/login');
        }
        $userId = $_SESSION['user_id'];
        $user = $this->db->getById('users', $userId);
        if (!$user) {
            setMessage('error', 'User not found.');
            redirect('pages/login');
        }

        $data = [
            'user_info' => $user
        ];

        // Redirect based on role
            $this->view('customer/profile/account_profile', $data);
    }
    public function editProfile()
    {
        if (!isset($_SESSION['user_id'])) {
            setMessage('error', 'Please login first.');
            redirect('pages/login');
        }

        $userId = $_SESSION['user_id'];
        $user = $this->db->getById('users', $userId);

        if (!$user) {
            setMessage('error', 'User not found.');
            redirect('pages/login');
        }

        // Validate role-based routing
        $data = [
            'users' => $user
        ];
            $this->view('admin/profile/edit_profile', $data);

    }
    public function editUserProfile()
    {
        if (!isset($_SESSION['user_id'])) {
            setMessage('error', 'Please login first.');
            redirect('pages/login');
        }

        $userId = $_SESSION['user_id'];
        $user = $this->db->getById('users', $userId);

        if (!$user) {
            setMessage('error', 'User not found.');
            redirect('pages/login');
        }

        // Validate role-based routing
        $data = [
            'users' => $user
        ];
            $this->view('customer/profile/edit_profile', $data);
    }

    // Update - Save the edited movie data
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];

            // Get old profile
            $old_profile = $this->db->getById('users', $id);
            if (!$old_profile) {
                setMessage('error', 'User not found.');
                redirect('user');
            }

            // --- Start Validation ---
            // Create a temporary array for validation that doesn't include password for update
            $validationData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                // Add a dummy password field for the validator, it won't be used for update
                'password' => 'dummy_password_for_validation'
            ];

            require_once __DIR__ . '/../helpers/UserValidator.php';

            // Include the UserValidator class
            // require_once '../app/validators/UserValidator.php'; // Adjust path if needed
            $validator = new UserValidator($validationData);
            $errors = $validator->validateFormForUpdate(); // Use a new method for update validation

            if (!empty($errors)) {
                // If there are validation errors, set messages and redirect
                foreach ($errors as $error) {
                    setMessage('error', $error);
                }
                // Redirect back to the edit profile page, retaining data if possible
                redirect('user/editProfile');
                return;
            }
            // --- End Validation ---

            $password = $old_profile['password'];
            $provider_token = $old_profile['provider_token'];
            $profile_img = $old_profile['profile_img'];
            $role = (int) ($_POST['role'] ?? $old_profile['role']);


            // Check if email is changed and already exists for other users
            if ($email !== $old_profile['email']) {
                $isExist = $this->db->columnFilter('users', 'email', $email);
                if ($isExist) {
                    setMessage('error', 'This email is already registered!');
                    redirect('user/editProfile');
                    return;
                }
            }
            // Handle image upload
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
                $oldImagePath = __DIR__ . '/../../public/images/users/' . $profile_img;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                $newFilename = time() . '_' . basename($_FILES['profile_image']['name']);
                $targetDir = __DIR__ . '/../../public/images/users/';
                $targetFile = $targetDir . $newFilename;

                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
                    $profile_img = $newFilename;
                }
            }

            // Populate model
            $user = new UserModel();
            $user->setId($id);
            $user->setName($name);
            $user->setEmail($email);
            $user->setPhone($phone);
            $user->setProviderToken($provider_token);
            $user->setIsActive($old_profile['is_active']);
            $user->setIsLogin($old_profile['is_login']);
            $user->setIsConfirmed($old_profile['is_confirmed']);
            $user->setRole($role);
            $user->setProfileImg($profile_img);
            $user->setPassword($password);
            $user->setUpdatedAt(date('Y-m-d H:i:s'));

            $data = $user->toArray();
            unset($data['created_at']);

            $isUpdated = $this->db->update('users', $user->getId(), $data);

            if ($isUpdated) {
                $_SESSION['profile_img'] = $profile_img;
                $_SESSION['user_name'] = $name;
                setMessage('success', 'Update successful!');

                // ✅ Redirect based on role
                if ($role === 0) {
                    redirect('user');
                } elseif ($role === 1) {
                    redirect('user');
                } else {
                    redirect('user');
                }
            } else {
                setMessage('error', 'Failed to update user.');

                // ❗ Error redirect based on role
                if ($role === 0) {
                    redirect('user/editProfile');
                } elseif ($role === 1) {
                    redirect('user/editProfile');
                } else {
                    redirect('user');
                }
            }
        }
    }

    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if user is logged in
            if (!isset($_SESSION['user_id'])) {
                setMessage('error', 'Please login first.');
                redirect('pages/login');
                exit;
            }

            $userId = $_SESSION['user_id']; // Use directly as integer

            // Get user from DB
            $user = $this->db->getById('users', $userId);

            if (!$user) {
                setMessage('error', 'User not found.');
                redirect('pages/login');
                exit;
            }

            // Load your validator helper
            require_once __DIR__ . '/../helpers/UserValidator.php';

            $validator = new UserValidator($_POST);
            $errors = $validator->validatePasswordChange();

            // Check if old password matches
            if (!password_verify($_POST['old_password'], $user['password'])) {
                $errors['old_password-err'] = 'Old password is incorrect.';
            }

            if (!empty($errors)) {
                // Show all errors as messages
                foreach ($errors as $error) {
                    setMessage('error', $error);
                }
                // Redirect to the correct change password page based on role
                $role = (int) ($_POST['role'] ?? $user['role']);
                redirect($role === 1 ? 'user/UserchangePassword' : 'user/changePassword');
                exit;
            }

            // Hash new password
            $hashedPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

            // Prepare data for update
            $data = [
                'password' => $hashedPassword,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Update user password in DB
            $isUpdated = $this->db->update('users', $userId, $data);

            if ($isUpdated) {
                setMessage('success', 'Password updated successfully.');
                redirect('user');
            } else {
                setMessage('error', 'Failed to update password.');
                $role = (int) ($_POST['role'] ?? $user['role']);
                redirect($role === 1 ? 'user/UserchangePassword' : 'user/changePassword');
            }
        } else {
            // If not POST request, redirect somewhere (optional)
            redirect('user');
        }
    }



    public function staffList()
    {
        $searchQuery = $_GET['search'] ?? '';
        $staff = [];

        if (!empty($searchQuery)) {
            // Search across name, email, phone
            $result = $this->db->search(
                'users',
                ['name', 'email', 'phone'],
                $searchQuery,
                100, // limit
                0    // offset
            );

            // Only staff (role = 0)
            $staff = array_filter($result['data'], fn($user) => $user['role'] == 0);
        } else {
            $staff = $this->db->readWithCondition('users', 'role = 0');
        }

        $data = [
            'staff_members' => $staff,
            'search_query' => $searchQuery
        ];


        $this->view('admin/profile/staff_list', $data);
    }

    public function storeUserOrStaff()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $phone = trim($_POST['phone'] ?? '');
            $role = (int) ($_POST['role'] ?? 1); // Cast to integer

            // --- Start Validation ---
            // Create a temporary array for validation that doesn't include password for update
            $validationData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                // Add a dummy password field for the validator, it won't be used for update
                'password' => 'dummy_password_for_validation'
            ];

            require_once __DIR__ . '/../helpers/UserValidator.php';

            // Include the UserValidator class
            // require_once '../app/validators/UserValidator.php'; // Adjust path if needed
            $validator = new UserValidator($validationData);
            $errors = $validator->validateFormForUpdate(); // Use a new method for update validation

            if (!empty($errors)) {
                // If there are validation errors, set messages and redirect
                foreach ($errors as $error) {
                    setMessage('error', $error);
                }
                // Redirect back to the edit profile page, retaining data if possible
                redirect($role === 1 ? 'user/addUser' : 'user/addStaff');
                return;
            }
            // --- End Validation ---

            // Check if email already exists
            $isExist = $this->db->columnFilter('users', 'email', $email);
            if ($isExist) {
                setMessage('error', 'This email is already registered!');
                redirect($role === 1 ? 'user/addUser' : 'user/addStaff');
                return;
            }

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Profile image default
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
        } else {
            redirect('user');
        }
    }


    public function destroy($id)
    {
        $id = base64_decode($id);

        // Get user by ID first to determine role (user or staff)
        $user = $this->db->getById('users', $id);
        if (!$user) {
            setMessage('error', 'User not found.');
            redirect('user');
            return;
        }

        $role = $user['role']; // 0 for staff, 1 for user

        // Proceed to delete
        $isdestroy = $this->db->delete('users', $id);

        redirect($role === 1 ? 'user/userList' : 'user/staffList');
    }



    public function userList()
    {
        $searchQuery = $_GET['search'] ?? '';
        $users = [];

        if (!empty($searchQuery)) {
            // Search across name, email, phone
            $result = $this->db->search(
                'users',
                ['name', 'email', 'phone','customer_type'],
                $searchQuery,
                100, // limit
                0    // offset
            );

            // Only users (role = 1)
            $users = array_filter($result['data'], fn($user) => $user['role'] == 1);
        } else {
            $users = $this->db->readWithCondition('users', 'role = 1');
        }

        $data = [
            'user_members' => $users,
            'search_query' => $searchQuery
        ];

        $this->view('admin/profile/user_list', $data);
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
