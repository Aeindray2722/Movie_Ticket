<?php

class TypeService
{
    private $repo;

    public function __construct(TypeRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function listTypes(int $limit, int $page): array
    {
        $offset = ($page - 1) * $limit;
        $types = $this->repo->getAll($limit, $offset);
        $total = $this->repo->countAll();
        $totalPages = ceil($total / $limit);

        return [
            'movieData' => $types,
            'page' => $page,
            'totalPages' => $totalPages
        ];
    }

    public function createType(string $name): bool
    {
        if (!$name) {
            throw new Exception('Type name is required.');
        }

        $now = date('Y-m-d H:i:s');
        $data = [
            'name' => $name,
            'created_at' => $now,
            'updated_at' => $now
        ];

        return $this->repo->create($data);
    }

    public function updateType(int $id, string $name): bool
    {
        if (!$id || !$name) {
            throw new Exception('Invalid data for update.');
        }

        $data = [
            'name' => $name,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->repo->update($id, $data);
    }

    public function getTypeById(int $id): ?array
    {
        return $this->repo->findById($id);
    }

    public function deleteType(int $id): bool
    {
        return $this->repo->delete($id);
    }
}
