<?php
require_once __DIR__ . '/../services/UserService.php';

class User extends Controller
{
    private $userService;

    public function __construct()
    {
        try {
            $db = new Database();
            $this->userService = new UserService($db);
            $this->model('UserModel');
        } catch (Exception $e) {
            setMessage('error', 'Initialization error: ' . $e->getMessage());
            redirect('error'); // your error page
        }
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

    public function index()
    {
        try {
            $user = $this->userService->getCurrentUser();
            $this->view('admin/profile/account_profile', ['user_info' => $user]);
        } catch (Exception $e) {
            setMessage('error', 'Failed to load profile: ' . $e->getMessage());
            redirect('error');
        }
    }

    public function Userindex()
    {
        try {
            $user = $this->userService->getCurrentUser();
            $this->view('customer/profile/account_profile', ['user_info' => $user]);
        } catch (Exception $e) {
            setMessage('error', 'Failed to load profile: ' . $e->getMessage());
            redirect('error');
        }
    }

    public function editProfile()
    {
        try {
            $user = $this->userService->getCurrentUser();
            $this->view('admin/profile/edit_profile', ['users' => $user]);
        } catch (Exception $e) {
            setMessage('error', 'Failed to load edit profile: ' . $e->getMessage());
            redirect('error');
        }
    }

    public function editUserProfile()
    {
        try {
            $user = $this->userService->getCurrentUser();
            $this->view('customer/profile/edit_profile', ['users' => $user]);
        } catch (Exception $e) {
            setMessage('error', 'Failed to load edit profile: ' . $e->getMessage());
            redirect('error');
        }
    }

    public function update()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('user');
                return;
            }

            $success = $this->userService->updateUser($_POST);

            if ($success) {
                $user = $this->userService->getUserById($_POST['id']);
                $_SESSION['profile_img'] = $user['profile_img'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = (int) $user['role'];

                setMessage('success', 'Update successful!');
                redirect($_SESSION['role'] === 1 ? 'user/Userindex' : 'user');
            } else {
                $role = (int) ($_POST['role'] ?? 0);
                if ($role === 0) {
                    redirect('user/editProfile');
                } elseif ($role === 1) {
                    redirect('user/editUserProfile');
                } else {
                    redirect('user');
                }
            }
        } catch (Exception $e) {
            setMessage('error', 'Update failed: ' . $e->getMessage());
            redirect('user');
        }
    }

    public function updatePassword()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('user');
                return;
            }

            $user = $this->userService->getCurrentUser();
            $errors = $this->userService->updatePassword($user['id'], $_POST);

            if (!empty($errors)) {
                foreach ($errors as $error) {
                    setMessage('error', $error);
                }
                $redirectRoute = ((int) ($user['role'] ?? 0) === 1) ? 'user/UserchangePassword' : 'user/changePassword';
                redirect($redirectRoute);
                return;
            }

            setMessage('success', 'Password updated successfully.');
            $role = (int) ($_POST['role'] ?? $user['role']);
            redirect($role === 1 ? 'user/Userindex' : 'user/index');
        } catch (Exception $e) {
            setMessage('error', 'Failed to update password: ' . $e->getMessage());
            redirect('user/changePassword');
        }
    }

    public function staffList()
    {
        try {
            $searchQuery = trim($_GET['search'] ?? '');
            $staff = $this->userService->searchUsers('0', $searchQuery);

            $this->view('admin/profile/staff_list', [
                'staff_members' => $staff,
                'search_query' => $searchQuery,
            ]);
        } catch (Exception $e) {
            setMessage('error', 'Failed to load staff list: ' . $e->getMessage());
            redirect('error');
        }
    }

    public function userList()
    {
        try {
            $searchQuery = trim($_GET['search'] ?? '');
            $users = $this->userService->searchUsers('1', $searchQuery);

            $this->view('admin/profile/user_list', [
                'user_members' => $users,
                'search_query' => $searchQuery,
            ]);
        } catch (Exception $e) {
            setMessage('error', 'Failed to load user list: ' . $e->getMessage());
            redirect('error');
        }
    }

    public function storeUserOrStaff()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('user');
                return;
            }

            $created = $this->userService->createUserOrStaff($_POST);

            if ($created) {
                setMessage('success', ((int)$_POST['role'] === 1 ? 'User' : 'Staff') . ' created successfully!');
                redirect((int)$_POST['role'] === 1 ? 'user/userList' : 'user/staffList');
            } else {
                redirect((int)($_POST['role'] ?? 1) === 1 ? 'user/addUser' : 'user/addStaff');
            }
        } catch (Exception $e) {
            setMessage('error', 'Failed to create user/staff: ' . $e->getMessage());
            redirect('user');
        }
    }

    public function destroy($encodedId)
    {
        try {
            $id = base64_decode($encodedId);
            $user = $this->userService->getUserById($id);

            if (!$user) {
                setMessage('error', 'User not found.');
                redirect('user');
                return;
            }

            $deleted = $this->userService->deleteUser($id);

            if ($deleted) {
                setMessage('success', ((int)$user['role'] === 1 ? 'User' : 'Staff') . ' deleted successfully!');
            } else {
                setMessage('error', 'Failed to delete user.');
            }

            redirect((int)$user['role'] === 1 ? 'user/userList' : 'user/staffList');
        } catch (Exception $e) {
            setMessage('error', 'Failed to delete user: ' . $e->getMessage());
            redirect('user');
        }
    }

    public function changePassword()
    {
        try {
            $this->view('admin/profile/change_password');
        } catch (Exception $e) {
            setMessage('error', 'Failed to load change password page: ' . $e->getMessage());
            redirect('error');
        }
    }

    public function addStaff()
    {
        try {
            $this->view('admin/profile/create_staff');
        } catch (Exception $e) {
            setMessage('error', 'Failed to load add staff page: ' . $e->getMessage());
            redirect('error');
        }
    }

    public function addUser()
    {
        try {
            $this->view('admin/profile/create_user');
        } catch (Exception $e) {
            setMessage('error', 'Failed to load add user page: ' . $e->getMessage());
            redirect('error');
        }
    }

    public function UserchangePassword()
    {
        try {
            $this->view('customer/profile/change_password');
        } catch (Exception $e) {
            setMessage('error', 'Failed to load change password page: ' . $e->getMessage());
            redirect('error');
        }
    }
}
