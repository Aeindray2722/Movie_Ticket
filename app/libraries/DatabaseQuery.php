<?php
class DatabaseQuery
{
    private PDO $pdo;
    private $stmt;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function columnFilter($table, $column, $value)
    {
        $sql = "SELECT * FROM $table WHERE $column = :value";
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->bindValue(':value', $value);
        $this->stmt->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function loginCheck($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->bindValue(':email', $email);
        $this->stmt->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readAll($table)
    {
        $sql = "SELECT * FROM $table";
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($table, $id)
    {
        $sql = "SELECT * FROM $table WHERE id = :id";
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->bindValue(':id', $id);
        $this->stmt->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readPaged($table, $limit, $offset)
    {
        $sql = "SELECT * FROM $table LIMIT :limit OFFSET :offset";
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $this->stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $this->stmt->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readWithCondition($table, $condition)
    {
        $sql = "SELECT * FROM $table WHERE $condition";
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search(string $table, array $columns, string $keyword, int $limit, int $offset): array
    {
        $searchTerm = "%$keyword%";
        $likeClauses = [];
        foreach ($columns as $col) {
            $likeClauses[] = "$col LIKE ?";
        }
        $whereClause = implode(' OR ', $likeClauses);

        $sql = "SELECT * FROM $table WHERE $whereClause LIMIT ? OFFSET ?";
        $stmt = $this->pdo->prepare($sql);

        $params = array_fill(0, count($columns), $searchTerm);
        $params[] = $limit;
        $params[] = $offset;

        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sqlCount = "SELECT COUNT(*) FROM $table WHERE $whereClause";
        $stmtCount = $this->pdo->prepare($sqlCount);
        $paramsCount = array_fill(0, count($columns), $searchTerm);
        $stmtCount->execute($paramsCount);
        $total = (int)$stmtCount->fetchColumn();

        return ['data' => $data, 'total' => $total];
    }

    public function getBookingsByMovieDateShowtime($movie_id, $show_time_id, $booking_date)
    {
        $sql = "SELECT * FROM bookings WHERE movie_id = :movie_id AND show_time_id = :show_time_id AND booking_date = :booking_date AND status IN (0, 1)";
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->bindValue(':movie_id', $movie_id);
        $this->stmt->bindValue(':show_time_id', $show_time_id);
        $this->stmt->bindValue(':booking_date', $booking_date);
        $this->stmt->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvgRatingByMovieId($movie_id)
    {
        $sql = "SELECT GetAvgRatingByMovieId(:movie_id) AS avg_rating";
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->bindValue(':movie_id', $movie_id);
        $this->stmt->execute();
        $row = $this->stmt->fetch(PDO::FETCH_ASSOC);
        return $row['avg_rating'] ?? 0;
    }

    public function getSeatNamesByIds(array $seatIds)
    {
        if (empty($seatIds)) return [];

        $seatIds = array_filter($seatIds, fn($id) => is_numeric($id));
        $seatIds = array_values($seatIds);
        if (empty($seatIds)) return [];

        $placeholders = implode(',', array_fill(0, count($seatIds), '?'));
        $sql = "SELECT id, seat_row, seat_number FROM seats WHERE id IN ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($seatIds);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $seatMap = [];
        foreach ($results as $row) {
            $seatMap[$row['id']] = $row['seat_row'] . $row['seat_number'];
        }

        return $seatMap;
    }

    public function getBookingsByUser($user_id)
    {
        $sql = "SELECT * FROM bookings WHERE user_id = :user_id ORDER BY created_at DESC";
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->bindValue(':user_id', $user_id);
        $this->stmt->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->stmt->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function getMonthlySummary()
    {
        $this->stmt = $this->pdo->prepare("CALL generate_monthly_summary()");
        $this->stmt->execute();

        $bookings = $this->stmt->fetch(PDO::FETCH_ASSOC);

        $this->stmt->nextRowset();
        $revenue = $this->stmt->fetch(PDO::FETCH_ASSOC);

        $this->stmt->nextRowset();
        $customers = $this->stmt->fetch(PDO::FETCH_ASSOC);

        $this->stmt->nextRowset();
        $bestMovie = $this->stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total_bookings' => $bookings['total_bookings'] ?? 0,
            'total_revenue' => $revenue['total_revenue'] ?? 0,
            'total_customers' => $customers['total_customers'] ?? 0,
            'best_selling_movie' => $bestMovie['movie_name'] ?? 'N/A',
            'best_selling_count' => $bestMovie['bookings_count'] ?? 0,
        ];
    }

    public function paginateByType(string $tableOrView, int $limit, int $page, ?string $type = null, array $additionalWhere = [], string $orderBy = 'id')
    {
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;
        $params = [];
        $whereClauses = [];

        if ($type !== null) {
            $whereClauses[] = "LOWER(type_name) = :type";
            $params[':type'] = strtolower($type);
        }

        foreach ($additionalWhere as $index => $condition) {
            if (is_array($condition) && count($condition) === 2) {
                [$condStr, $val] = $condition;
                $placeholder = ":param_$index";
                $condStr = str_replace('?', $placeholder, $condStr);
                $whereClauses[] = $condStr;
                $params[$placeholder] = $val;
            } else {
                $whereClauses[] = $condition;
            }
        }

        $whereSql = count($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

        $countSql = "SELECT COUNT(*) AS total FROM $tableOrView $whereSql";
        $this->stmt = $this->pdo->prepare($countSql);
        foreach ($params as $key => $val) {
            $this->stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
        $this->stmt->execute();
        $totalRow = $this->stmt->fetch(PDO::FETCH_ASSOC);
        $total = $totalRow['total'] ?? 0;

        $dataSql = "SELECT * FROM $tableOrView $whereSql ORDER BY $orderBy DESC LIMIT :limit OFFSET :offset";
        $this->stmt = $this->pdo->prepare($dataSql);
        foreach ($params as $key => $val) {
            $this->stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
        $this->stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $this->stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $this->stmt->execute();
        $data = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalPages = ceil($total / $limit);

        return [
            'data' => $data,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ];
    }

    public function getUserMonthlyBookingTotal(int $userId): float
    {
        $startDate = (new DateTime('first day of this month'))->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $endDate = (new DateTime('last day of this month'))->setTime(23, 59, 59)->format('Y-m-d H:i:s');

        $sql = "SELECT SUM(total_amount) as total FROM bookings WHERE user_id = :user_id AND created_at BETWEEN :start_date AND :end_date";
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->bindValue(':user_id', $userId);
        $this->stmt->bindValue(':start_date', $startDate);
        $this->stmt->bindValue(':end_date', $endDate);

        $this->stmt->execute();
        $row = $this->stmt->fetch(PDO::FETCH_ASSOC);

        return (float) ($row['total'] ?? 0);
    }

    public function getRatingByUserAndMovie($user_id, $movie_id)
    {
        $sql = "SELECT * FROM ratings WHERE user_id = :user_id AND movie_id = :movie_id";
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->bindValue(':user_id', $user_id);
        $this->stmt->bindValue(':movie_id', $movie_id);
        $this->stmt->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCurrentUser()
    {
        if (!isset($_SESSION['user_id'])) {
            setMessage('error', 'Please login first.');
            redirect('pages/login');
            exit;
        }
        $user = $this->getById('users', $_SESSION['user_id']);
        if (!$user) {
            setMessage('error', 'User not found.');
            redirect('pages/login');
            exit;
        }
        return $user;
    }
}
