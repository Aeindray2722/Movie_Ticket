<?php

interface TypeRepositoryInterface
{
    public function getAll(int $limit, int $offset): array;
    public function countAll(): int;
    public function findById(int $id): ?array;
    public function create(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
