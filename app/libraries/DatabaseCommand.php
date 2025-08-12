<?php
class DatabaseCommand
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($table, $data)
    {
        try {
            $this->pdo->beginTransaction();

            $columns = array_keys($data);
            $columnsSql = implode(', ', $columns);
            $bindingsSql = ':' . implode(',:', $columns);

            $sql = "INSERT INTO $table ($columnsSql) VALUES ($bindingsSql)";
            $stm = $this->pdo->prepare($sql);

            foreach ($data as $key => $value) {
                $stm->bindValue(':' . $key, $value);
            }

            $success = $stm->execute();

            if (!$success) {
                $this->pdo->rollBack();
                return false;
            }

            $lastId = $this->pdo->lastInsertId();
            $this->pdo->commit();

            return $lastId;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            echo "PDOException (create): " . $e->getMessage();
            return false;
        }
    }

    public function update($table, $id, $data)
    {
        if (isset($data['id'])) unset($data['id']);

        try {
            $this->pdo->beginTransaction();

            $columns = array_keys($data);
            $setParts = array_map(fn($col) => "$col = :$col", $columns);
            $setSql = implode(', ', $setParts);

            $sql = "UPDATE $table SET $setSql WHERE id = :id";
            $stm = $this->pdo->prepare($sql);

            $data['id'] = $id;
            foreach ($data as $key => $value) {
                $stm->bindValue(":$key", $value);
            }

            $success = $stm->execute();
            if (!$success) {
                $this->pdo->rollBack();
                return false;
            }

            $this->pdo->commit();
            return $success;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            echo "PDOException (update): " . $e->getMessage();
            return false;
        }
    }

    public function delete($table, $id)
    {
        try {
            $this->pdo->beginTransaction();
            $sql = "DELETE FROM $table WHERE id = :id";
            $stm = $this->pdo->prepare($sql);
            $stm->bindValue(':id', $id);
            $success = $stm->execute();

            if (!$success) {
                $this->pdo->rollBack();
                return false;
            }

            $this->pdo->commit();
            return $success;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            echo "PDOException (delete): " . $e->getMessage();
            return false;
        }
    }

    public function setLogin($id)
    {
        $sql = 'UPDATE users SET is_login = 1, is_confirmed = 1, is_active = 1 WHERE id = :id';
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':id', $id, PDO::PARAM_INT);
        return $stm->execute();
    }

    public function unsetLogin($id)
    {
        try {
            $sql = "UPDATE users SET is_login = 0 WHERE id = :id";
            $stm = $this->pdo->prepare($sql);
            $stm->bindValue(':id', $id);
            return $stm->execute();
        } catch (PDOException $e) {
            echo "PDOException (unsetLogin): " . $e->getMessage();
            return false;
        }
    }

    public function updateStatus($booking_id, $status)
    {
        $sql = "UPDATE bookings SET status = :status WHERE id = :id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':status', $status);
        $stm->bindValue(':id', $booking_id);
        return $stm->execute();
    }

    public function updatePasswordByEmail($email, $hashedPassword)
    {
        $sql = "UPDATE users SET password = :password, updated_at = NOW() WHERE email = :email";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':password', $hashedPassword);
        $stm->bindValue(':email', $email);
        return $stm->execute();
    }

    public function incrementViewCount($id)
    {
        $sql = "UPDATE movies SET view_count = view_count + 1 WHERE id = :id";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':id', $id);
        $stm->execute();
        return $stm->rowCount();
    }

    public function uploadImage($file, $relativeDir)
    {
        $uploadDir = __DIR__ . $relativeDir;
        $filename = time() . '_' . basename($file['name']);
        $target = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            return $filename;
        }
        return null;
    }

    public function uploadTrailerFile(array $file): string
    {
        $targetDir = __DIR__ . '/../../public/videos/trailers/';
        $filename = time() . '_' . basename($file['name']);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $filename;
        }
        return '';
    }
}
