<?php
// services/UserService.php
require_once __DIR__ . '/../interface/UserRepositoryInterface.php';
require_once __DIR__ . '/../helpers/UserValidator.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getCurrentUser(): ?array
    {
        return $this->userRepository->getCurrentUser();
    }

    public function getUserById($id): ?array
    {
        return $this->userRepository->getUserById((int)$id);
    }

    public function updateUser(array $postData): bool
    {
        $id = $postData['id'] ?? null;
        if (!$id) return false;

        $oldProfile = $this->getUserById($id);
        if (!$oldProfile) return false;

        // Validate data
        $validationData = [
            'name' => trim($postData['name'] ?? ''),
            'email' => trim($postData['email'] ?? ''),
            'phone' => trim($postData['phone'] ?? ''),
            // Provide dummy password because update validation might expect it
            'password' => 'dummy_password_for_validation',
        ];
        $validator = new UserValidator($validationData);
        $errors = $validator->validateFormForUpdate();

        if (!empty($errors)) {
            foreach ($errors as $error) {
                setMessage('error', $error);
            }
            return false;
        }

        // Check if email is taken by another user
        $email = trim($postData['email']);
        if ($email !== $oldProfile['email'] && $this->userRepository->isEmailTaken($email, (int)$id)) {
            setMessage('error', 'This email is already registered!');
            return false;
        }

        // Handle profile image upload
        $profile_img = $this->userRepository->uploadProfileImage($_FILES['profile_image'] ?? null, $oldProfile['profile_img']);

        // Prepare user model for update
        $userModel = new UserModel();
        $userModel->setId((int)$id);
        $userModel->setName($validationData['name']);
        $userModel->setEmail($email);
        $userModel->setPhone($validationData['phone']);
        $userModel->setProviderToken($oldProfile['provider_token']);
        $userModel->setIsActive($oldProfile['is_active']);
        $userModel->setIsLogin($oldProfile['is_login']);
        $userModel->setIsConfirmed($oldProfile['is_confirmed']);
        $userModel->setRole(isset($postData['role']) ? (int)$postData['role'] : (int)$oldProfile['role']);
        $userModel->setProfileImg($profile_img);
        $userModel->setPassword($oldProfile['password']);
        $userModel->setUpdatedAt(date('Y-m-d H:i:s'));
        $userModel->setCustomerType($oldProfile['customer_type'] ?: 'Normal');

        $data = $userModel->toArray();
        unset($data['created_at']);

        return $this->userRepository->updateUser((int)$id, $data);
    }

    public function updatePassword(int $userId, array $postData): array
    {
        $user = $this->getUserById($userId);
        if (!$user) return ['error' => 'User not found.'];

        $validator = new UserValidator($postData);
        $errors = $validator->validatePasswordChange();

        if (!password_verify($postData['old_password'] ?? '', $user['password'])) {
            $errors['old_password-err'] = 'Old password is incorrect.';
        }

        if (!empty($errors)) {
            return $errors;
        }

        $hashedPassword = password_hash($postData['new_password'], PASSWORD_DEFAULT);

        $updated = $this->userRepository->updatePassword($userId, $hashedPassword);
        if (!$updated) {
            return ['error' => 'Failed to update password.'];
        }

        return [];
    }

    public function searchUsersByRole(string $role, string $searchQuery = ''): array
    {
        return $this->userRepository->searchUsersByRole((int)$role, $searchQuery);
    }

    public function create(array $postData): bool
    {
        $name = trim($postData['name'] ?? '');
        $email = trim($postData['email'] ?? '');
        $phone = trim($postData['phone'] ?? '');
        $password = $postData['password'] ?? '';
        $role = (int) ($postData['role'] ?? 1);

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

        if ($this->userRepository->isEmailTaken($email)) {
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

        return $this->userRepository->createUser($data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->userRepository->deleteUser($id);
    }
}
