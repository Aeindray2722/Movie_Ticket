<?php
require_once __DIR__ . '/../repositories/TrailerRepository.php';

class TrailerService
{
    private $repo;

    public function __construct(Database $db)
    {
        $this->repo = new TrailerRepository($db);
    }

    public function getAllPaged(int $limit, int $page)
    {
        return $this->repo->getAllPaged($limit, $page);
    }

    public function getTypes()
    {
        return $this->repo->getTypes();
    }

    public function getMovies()
    {
        return $this->repo->getMovies();
    }

    public function create(array $data, array $file)
    {
        return $this->repo->create($data, $file);
    }

    public function findById($id)
    {
        return $this->repo->findById($id);
    }

    public function update(int $id, array $data, array $file)
    {
        return $this->repo->update($id, $data, $file);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    public function searchTrailers($type, $search, $limit, $page)
    {
        return $this->repo->searchTrailers($type, $search, $limit, $page);
    }

    public function getMovieDetail($id)
    {
        return $this->repo->getMovieDetail($id);
    }
}
