<?php
// app/interface/UserRepositoryInterface.php

interface UserRepositoryInterface
{
    public function getCurrentUser(): ?array;
    public function getUserById(int $id): ?array;
    public function updateUser(int $id, array $data): bool;
    public function updatePassword(int $id, string $hashedPassword): bool;
    public function searchUsersByRole(int $role, string $searchQuery = ''): array;
    public function createUser(array $data): bool;
    public function deleteUser(int $id): bool;
    public function isEmailTaken(string $email, ?int $excludeUserId = null): bool;
    public function uploadProfileImage(array $file = null, string $oldImage): string;
}
