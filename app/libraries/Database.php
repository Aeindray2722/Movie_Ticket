<?php

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    public $pdo;
    public $stmt;
    private $error;

    public function __construct()
    {
        //to connect to the mysql database
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname;
        //mean separate different part, In this  ; means end of the host part and beginning of the database
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false // For General Error
        );

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            // print_r($this->pdo);
            // echo "Success";
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }
    public function create($table, $data)
    {
        try {
            $column = array_keys($data);
            $columnSql = implode(', ', $column);
            $bindingSql = ':' . implode(',:', $column);

            $sql = "INSERT INTO $table ($columnSql) VALUES ($bindingSql)";
            $stm = $this->pdo->prepare($sql);

            foreach ($data as $key => $value) {
                // echo "Binding :$key => $value<br>";
                // exit();
                $stm->bindValue(':' . $key, $value);
            }

            $status = $stm->execute();

            if (!$status) {
                $errorInfo = $stm->errorInfo();
                echo "SQLSTATE error code: " . $errorInfo[0] . "<br>";
                echo "Driver-specific error code: " . $errorInfo[1] . "<br>";
                echo "Driver-specific error message: " . $errorInfo[2] . "<br>";
                return false;
            }

            // echo "Insert successful. Last Insert ID: " . $this->pdo->lastInsertId() . "<br>";
            return $this->pdo->lastInsertId();

        } catch (PDOException $e) {
            echo "PDOException: " . $e->getMessage();
            exit;
            return false;
        }
    }
    // Update Query
    public function update($table, $id, $data)
    {
        // First, we don't want id from category table
        if (isset($data['id'])) {
            unset($data['id']);
        }

        try {
            $columns = array_keys($data);
            function map($item)
            {
                return $item . '=:' . $item;
            }
            $columns = array_map('map', $columns);
            $bindingSql = implode(',', $columns);
            $sql = 'UPDATE ' . $table . ' SET ' . $bindingSql . ' WHERE `id` =:id';

            $stm = $this->pdo->prepare($sql);
            // Now, we assign id to bind
            $data['id'] = $id;
            // var_dump($data['id']); exit;
            foreach ($data as $key => $value) {
                $stm->bindValue(':' . $key, $value);
            }
            $status = $stm->execute();
            // print_r($status);
            return $status;
        } catch (PDOException $e) {
            echo $e;
            exit;
        }
    }
    //delete
    public function delete($table, $id)
    {
        $sql = 'DELETE FROM ' . $table . ' WHERE `id` = :id';
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':id', $id);
        $success = $stm->execute();
        return ($success);
    }

    public function columnFilter($table, $column, $value)
    {
        // $sql = 'SELECT * FROM ' . $table . ' WHERE `' . $column . '` = :value';
        $sql = 'SELECT * FROM ' . $table . ' WHERE `' . str_replace('`', '', $column) . '` = :value';
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':value', $value);
        $success = $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }
    //loginCheck
    public function loginCheck($email, $plainPassword)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':email', $email);
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($plainPassword, $row['password'])) {
            return $row;  // Login success
        }
        return false;  // Login failed
    }

    public function setLogin($id)
    {
        $sql = 'UPDATE users SET is_login = 1, is_confirmed = 1, is_active = 1 WHERE id = :id';
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':id', $id, PDO::PARAM_INT);
        $success = $stm->execute();
        $stm->closeCursor(); // good practice for unbuffered queries
        return $success;
    }
    //unset login
    public function unsetLogin($id)
    {
        try {
            $sql = "UPDATE users SET is_login = :false WHERE id = :id";
            $stm = $this->pdo->prepare($sql);
            $stm->bindValue(':false', '0');  // or 0, but string '0' works fine
            $stm->bindValue(':id', $id);
            $success = $stm->execute();
            return $success;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
    //readAll
    public function readAll($table)
    {
        $sql = 'SELECT * FROM ' . $table;
        // print_r($sql);
        $stm = $this->pdo->prepare($sql);
        $success = $stm->execute();
        $row = $stm->fetchAll(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }
    //getById
    public function getById($table, $id)
    {
        $sql = 'SELECT * FROM ' . $table . ' WHERE `id` =:id';
        // print_r($sql);
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':id', $id);
        $success = $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }
    //getByCategory
    public function getByCategoryId($table, $column)
    {
        $stm = $this->pdo->prepare('SELECT * FROM ' . $table . ' WHERE name =:column');
        $stm->bindValue(':column', $column);
        $success = $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        //  print_r($row);
        return ($success) ? $row : [];
    }
    //pagination
    public function readPaged($table, $limit, $offset)
    {
        $sql = "SELECT * FROM {$table} LIMIT :limit OFFSET :offset";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stm->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $success = $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $success ? $rows : [];
    }
    //readWithCondition
    public function readWithCondition($table, $condition)
    {
        $sql = "SELECT * FROM $table WHERE $condition";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    // Search with pagination and total count
    public function search(string $table, array $columns, string $keyword, int $limit, int $offset): array
    {
        $searchTerm = "%$keyword%";

        // Build WHERE clause for LIKE on multiple columns
        $likeClauses = [];
        foreach ($columns as $col) {
            $likeClauses[] = "$col LIKE ?";
        }
        $whereClause = implode(' OR ', $likeClauses);

        // Fetch data
        $sqlData = "SELECT * FROM $table WHERE $whereClause LIMIT ? OFFSET ?";
        $stmtData = $this->pdo->prepare($sqlData);

        // Bind parameters for LIKE and pagination
        $paramsData = array_fill(0, count($columns), $searchTerm);
        $paramsData[] = $limit;
        $paramsData[] = $offset;
        $stmtData->execute($paramsData);
        $data = $stmtData->fetchAll();

        // Fetch total count (without LIMIT/OFFSET)
        $sqlCount = "SELECT COUNT(*) FROM $table WHERE $whereClause";
        $stmtCount = $this->pdo->prepare($sqlCount);
        $paramsCount = array_fill(0, count($columns), $searchTerm);
        $stmtCount->execute($paramsCount);
        $total = (int) $stmtCount->fetchColumn();

        return [
            'data' => $data,
            'total' => $total
        ];
    }
    public function query($sql)
    {
        $this->stmt = $this->pdo->prepare($sql);
        // $this->stmt->execute();
    }
    public function fetchAll()
    {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }
    public function getBookingsByMovieDateShowtime($movie_id, $show_time_id, $booking_date)
    {
        $this->query("SELECT * FROM bookings 
              WHERE movie_id = :movie_id 
              AND show_time_id = :show_time_id 
              AND booking_date = :booking_date
              AND status IN (0, 1)"); // status 0 or 1 only

        $this->bind(':movie_id', $movie_id);
        $this->bind(':show_time_id', $show_time_id);
        $this->bind(':booking_date', $booking_date);

        $this->stmt->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // public function getAvgRatingByMovieId($movie_id)
    // {
    //     $this->query("SELECT CEIL(AVG(count)) AS avg_rating FROM ratings WHERE movie_id = :movie_id");
    //     $this->bind(':movie_id', $movie_id);
    //     $this->stmt->execute();
    //     $row = $this->stmt->fetch(PDO::FETCH_ASSOC);
    //     return $row['avg_rating'] ?? 0;
    // }
    public function getAvgRatingByMovieId($movie_id)
    {
        $this->query("SELECT GetAvgRatingByMovieId(:movie_id) AS avg_rating");
        $this->bind(':movie_id', $movie_id);
        $this->stmt->execute();
        $row = $this->stmt->fetch(PDO::FETCH_ASSOC);
        return $row['avg_rating'] ?? 0;
    }

    public function getSeatNamesByIds(array $seatIds)
    {
        if (empty($seatIds)) {
            return [];
        }

        // Filter only numeric values
        $seatIds = array_filter($seatIds, fn($id) => is_numeric($id));
        $seatIds = array_values($seatIds); // reindex

        if (empty($seatIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($seatIds), '?'));
        $sql = "SELECT id, seat_row, seat_number FROM seats WHERE id IN ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($seatIds); // now safe
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
        $this->query($sql);
        $this->bind(':user_id', $user_id);
        $this->stmt->execute();
        return $this->fetchAll();
    }
    public function updateStatus($booking_id, $status)
    {
        $sql = "UPDATE bookings SET status = :status WHERE id = :id";
        $this->query($sql);
        $this->bind(':status', $status);
        $this->bind(':id', $booking_id);
        return $this->stmt->execute();
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
        $this->query("CALL generate_monthly_summary()");
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
    // Example pagination function with optional type filter
    public function paginateByType(string $tableOrView, int $limit, int $page, ?string $type = null, array $additionalWhere = [], string $orderBy = 'id')
    {
        if ($page < 1)
            $page = 1;

        $offset = ($page - 1) * $limit;
        $params = [];

        // WHERE clause array
        $whereClauses = [];

        // Type filtering
        if ($type !== null) {
            $whereClauses[] = "LOWER(type_name) = :type";
            $params[':type'] = strtolower($type);
        }

        // Additional custom where conditions (e.g., CURDATE() BETWEEN ...)
        foreach ($additionalWhere as $index => $condition) {
            if (is_array($condition) && count($condition) === 2) {
                // condition with parameter
                [$condStr, $val] = $condition;
                $placeholder = ":param_$index";
                // replace ? in condition with placeholder if needed
                $condStr = str_replace('?', $placeholder, $condStr);
                $whereClauses[] = $condStr;
                $params[$placeholder] = $val;
            } else {
                // Just a plain condition string, no parameter
                $whereClauses[] = $condition;
            }
        }


        $whereSql = count($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

        // Count total
        $countSql = "SELECT COUNT(*) AS total FROM $tableOrView $whereSql";
        $this->query($countSql);
        foreach ($params as $key => $val) {
            $this->stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
        $this->stmt->execute();
        $totalRow = $this->stmt->fetch(PDO::FETCH_ASSOC);
        $total = $totalRow['total'] ?? 0;

        // Select paginated data
        $dataSql = "SELECT * FROM $tableOrView $whereSql ORDER BY $orderBy DESC LIMIT :limit OFFSET :offset";
        $this->query($dataSql);
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

        $sql = "SELECT SUM(total_amount) as total FROM bookings 
            WHERE user_id = :user_id AND created_at BETWEEN :start_date AND :end_date";

        $this->query($sql);
        $this->bind(':user_id', $userId);
        $this->bind(':start_date', $startDate);
        $this->bind(':end_date', $endDate);

        $row = $this->single();
        if (!$row) {
            return 0;
        }

        return (float) ($row['total'] ?? 0);
    }
    public function updatePasswordByEmail($email, $hashedPassword)
    {
        $sql = "UPDATE users SET password = :password, updated_at = NOW() WHERE email = :email";
        $this->query($sql);
        $this->bind(':password', $hashedPassword);
        $this->bind(':email', $email);

        return $this->stmt->execute();
    }




}

