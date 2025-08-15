<?php

require_once __DIR__ . '/../interface/UserRepositoryInterface.php';

class UserRepository implements UserRepositoryInterface
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getCurrentUser(): ?array
    {
        return $this->db->getCurrentUser();
    }

    public function getUserById(int $id): ?array
    {
        return $this->db->getById('users', $id);
    }

    public function updateUser(int $id, array $data): bool
    {
        return $this->db->update('users', $id, $data);
    }

    public function updatePassword(int $id, string $hashedPassword): bool
    {
        $data = [
            'password' => $hashedPassword,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        return $this->db->update('users', $id, $data);
    }

    public function searchUsersByRole(int $role, string $searchQuery = ''): array
    {
        if ($searchQuery !== '') {
            $result = $this->db->search(
                'users',
                ['name', 'email', 'phone', 'customer_type'],
                $searchQuery,
                100,
                0
            );
            return array_filter($result['data'], fn($user) => (int) $user['role'] === $role);
        }
        return $this->db->readWithCondition('users', 'role = ' . $role);
    }

    public function createUser(array $data): bool
    {
        return $this->db->create('users', $data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->db->delete('users', $id);
    }

    public function isEmailTaken(string $email, ?int $excludeUserId = null): bool
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

    public function uploadProfileImage(array $file = null, string $oldImage): string
    {
        $profile_img = $oldImage;

        if ($file && $file['error'] === 0) {
            $oldImagePath = __DIR__ . '/../../public/images/users/' . $oldImage;
            if (file_exists($oldImagePath) && $oldImage !== 'default_profile.jpg') {
                unlink($oldImagePath);
            }

            $uploadedFilename = $this->db->uploadImage($file, '/../../public/images/users/');

            if ($uploadedFilename !== null) {
                $profile_img = $uploadedFilename;
            }
        }

        return $profile_img;
    }
}
