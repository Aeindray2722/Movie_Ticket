<?php

class UserService
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getCurrentUser()
    {
        return $this->db->getCurrentUser();
    }

    public function getUserById($id)
    {
        return $this->db->getById('users', $id);
    }

    public function validateUpdateData(array $postData): array
    {
        require_once __DIR__ . '/../helpers/UserValidator.php';

        $validationData = [
            'name' => trim($postData['name'] ?? ''),
            'email' => trim($postData['email'] ?? ''),
            'phone' => trim($postData['phone'] ?? ''),
            'password' => 'dummy_password_for_validation',
        ];

        $validator = new UserValidator($validationData);
        return $validator->validateFormForUpdate();
    }

    public function isEmailTaken($email, $excludeUserId = null): bool
    {
        $existingUser = $this->db->columnFilter('users', 'email', $email);
        if ($existingUser) {
            if ($excludeUserId === null) {
                return true;
            }
            return $existingUser['id'] != $excludeUserId;
        }
        return false;
    }

    public function handleProfileImageUpload(string $oldImage): string
    {
        $profile_img = $oldImage;

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
            // Delete old image if exists and not default
            $oldImagePath = __DIR__ . '/../../public/images/users/' . $oldImage;
            if (file_exists($oldImagePath) && $oldImage !== 'default_profile.jpg') {
                unlink($oldImagePath);
            }

            // Use Database's uploadImage method
            $uploadedFilename = $this->db->uploadImage($_FILES['profile_image'], '/../../public/images/users/');

            if ($uploadedFilename !== null) {
                $profile_img = $uploadedFilename;
            }
        }

        return $profile_img;
    }


    public function updateUser(array $postData): bool
    {
        $id = $postData['id'] ?? null;
        if (!$id) {
            return false;
        }

        $oldProfile = $this->getUserById($id);
        if (!$oldProfile) {
            return false;
        }

        $errors = $this->validateUpdateData($postData);
        if (!empty($errors)) {
            foreach ($errors as $error) {
                setMessage('error', $error);
            }
            return false;
        }

        $email = trim($postData['email'] ?? '');
        if ($email !== $oldProfile['email'] && $this->isEmailTaken($email, $id)) {
            setMessage('error', 'This email is already registered!');
            return false;
        }

        $profile_img = $this->handleProfileImageUpload($oldProfile['profile_img']);

        $userModel = new UserModel();
        $userModel->setId($id);
        $userModel->setName(trim($postData['name']));
        $userModel->setEmail($email);
        $userModel->setPhone(trim($postData['phone']));
        $userModel->setProviderToken($oldProfile['provider_token']);
        $userModel->setIsActive($oldProfile['is_active']);
        $userModel->setIsLogin($oldProfile['is_login']);
        $userModel->setIsConfirmed($oldProfile['is_confirmed']);
        $userModel->setRole(isset($postData['role']) ? (int) $postData['role'] : $oldProfile['role']);
        $userModel->setProfileImg($profile_img);
        $userModel->setPassword($oldProfile['password']);
        $userModel->setUpdatedAt(date('Y-m-d H:i:s'));
        $userModel->setCustomerType($oldProfile['customer_type'] ?: 'Normal');

        $data = $userModel->toArray();
        unset($data['created_at']);

        return $this->db->update('users', $id, $data);
    }

    public function updatePassword(int $userId, array $postData): array
    {
        $user = $this->getUserById($userId);
        if (!$user) {
            return ['error' => 'User not found.'];
        }

        require_once __DIR__ . '/../helpers/UserValidator.php';
        $validator = new UserValidator($postData);
        $errors = $validator->validatePasswordChange();

        if (!password_verify($postData['old_password'] ?? '', $user['password'])) {
            $errors['old_password-err'] = 'Old password is incorrect.';
        }

        if (!empty($errors)) {
            return $errors;
        }

        $hashedPassword = password_hash($postData['new_password'], PASSWORD_DEFAULT);

        $data = [
            'password' => $hashedPassword,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $updated = $this->db->update('users', $userId, $data);
        if (!$updated) {
            return ['error' => 'Failed to update password.'];
        }

        return [];
    }

    public function searchUsers(string $role, string $searchQuery = ''): array
    {
        if ($searchQuery !== '') {
            $result = $this->db->search(
                'users',
                ['name', 'email', 'phone', 'customer_type'],
                $searchQuery,
                100,
                0
            );
            return array_filter($result['data'], fn($user) => (string) $user['role'] === $role);
        }

        return $this->db->readWithCondition('users', 'role = ' . (int) $role);
    }

    public function createUserOrStaff(array $postData): bool
    {
        $name = trim($postData['name'] ?? '');
        $email = trim($postData['email'] ?? '');
        $password = $postData['password'] ?? '';
        $phone = trim($postData['phone'] ?? '');
        $role = (int) ($postData['role'] ?? 1);

        require_once __DIR__ . '/../helpers/UserValidator.php';
        $validator = new UserValidator([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
        ]);

        $errors = $validator->validateForm();

        if (!empty($errors)) {
            foreach ($errors as $error) {
                setMessage('error', $error);
            }
            return false;
        }

        if ($this->isEmailTaken($email)) {
            setMessage('error', 'This email is already registered!');
            return false;
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
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return $this->db->create('users', $data);
    }

    public function deleteUser(int $id): bool
    {
        $user = $this->getUserById($id);
        if (!$user) {
            return false;
        }

        return $this->db->delete('users', $id);
    }
}
