<?php

require_once __DIR__ . '/../interface/TypeRepositoryInterface.php';

class TypeRepository implements TypeRepositoryInterface
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getAll(int $limit, int $offset): array
    {
        return $this->db->readPaged('types', $limit, $offset);
    }

    public function countAll(): int
    {
        return count($this->db->readAll('types'));
    }

    public function findById(int $id): ?array
    {
        $result = $this->db->getById('types', $id);
        return $result ?: null;
    }

    public function create(array $data): bool
    {
        return $this->db->create('types', $data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->update('types', $id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->db->delete('types', $id);
    }
}
