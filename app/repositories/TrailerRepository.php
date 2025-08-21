<?php

class TrailerRepository
{
    private $db;
    private $targetDir;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->targetDir = __DIR__ . '/../../public/videos/trailers/';
    }

    public function getAllPaged(int $limit, int $page)
    {
        $offset = ($page - 1) * $limit;
        $trailers = $this->db->readPaged('trailers', $limit, $offset);
        $total = count($this->db->readAll('trailers'));
        return [
            'data' => $trailers,
            'totalPages' => ceil($total / $limit)
        ];
    }

    public function getTypes()
    {
        return $this->db->readAll('types');
    }

    public function getMovies()
    {
        return $this->db->readAll('movies');
    }

    public function createTrailerFile(array $file)
    {
        if ($file['error'] === 0) {
            return $this->db->uploadTrailerFile($file);
        }
        return '';
    }

    public function create(array $data, array $file)
    {
        $data['trailer_vd'] = $this->createTrailerFile($file['trailer_file'] ?? null);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->db->create('trailers', $data);
    }

    public function findById($id)
    {
        return $this->db->getById('trailers', $id);
    }

    public function update(int $id, array $data, array $file)
    {
        $old_trailer = $this->findById($id);
        if (!$old_trailer) {
            return false;
        }

        $trailer_vd = $old_trailer['trailer_vd'];

        if (isset($file['trailer_file']) && $file['trailer_file']['error'] === 0) {
            $oldPath = $this->targetDir . $trailer_vd;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
            $trailer_vd = $this->createTrailerFile($file['trailer_file']);
        }

        $data['trailer_vd'] = $trailer_vd;
        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->db->update('trailers', $id, $data);
    }

    public function delete($id)
    {
        $trailer = $this->findById($id);
        if (!$trailer) return false;

        $filePath = $this->targetDir . $trailer['trailer_vd'];
        if (file_exists($filePath)) unlink($filePath);

        return $this->db->delete('trailers', $id);
    }

public function searchTrailers($type, $search, $limit, $page)
{
    $offset = ($page - 1) * $limit;
    $columnsToSearch = ['movie_name', 'type_name', 'actor_name'];

    // Build extra WHERE for type
    $extraWhere = "";
    $paramsExtra = [];
    if ($type) {
        $extraWhere = " AND LOWER(type_name) = ? ";
        $paramsExtra[] = strtolower($type);
    }

    // Modify search to include type filtering
    $searchTerm = "%$search%";
    $likeClauses = implode(' OR ', array_map(fn($col) => "$col LIKE ?", $columnsToSearch));

    // 1️⃣ Fetch paged data
    $sqlData = "SELECT * 
                FROM trailers 
                JOIN view_movies_info ON trailers.movie_id = view_movies_info.id 
                WHERE ($likeClauses) $extraWhere 
                LIMIT ? OFFSET ?";
    $stmtData = $this->db->pdo->prepare($sqlData);

    // bind search + type + limit/offset
    $paramsData = array_fill(0, count($columnsToSearch), $searchTerm);
    $paramsData = array_merge($paramsData, $paramsExtra, [$limit, $offset]);
    $stmtData->execute($paramsData);
    $data = $stmtData->fetchAll();

    // 2️⃣ Fetch total count
    $sqlCount = "SELECT COUNT(*) 
                 FROM trailers 
                 JOIN view_movies_info ON trailers.movie_id = view_movies_info.id 
                 WHERE ($likeClauses) $extraWhere";
    $stmtCount = $this->db->pdo->prepare($sqlCount);
    $paramsCount = array_fill(0, count($columnsToSearch), $searchTerm);
    $paramsCount = array_merge($paramsCount, $paramsExtra);
    $stmtCount->execute($paramsCount);
    $total = (int) $stmtCount->fetchColumn();

    return [
        'data' => $data,
        'totalPages' => ceil($total / $limit)
    ];
}




    public function getMovieDetail($id)
    {
        $movie = $this->db->getById('view_movies_info', $id);
        if (!$movie) return null;

        $this->db->incrementViewCount($id);
        return [
            'movie' => $movie,
            'avg_rating' => $this->db->getAvgRatingByMovieId($id),
            'comment' => $this->db->getCommentsWithUserInfo($id),
            'trailer' => $this->db->getTrailerByMovieId($id)
        ];
    }
}
