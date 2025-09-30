<?php
require_once 'BaseModel.php';

class UserModel extends BaseModel {

    /**
     * Find user by ID
     * @param int $id
     * @return array
     */
    public function findUserById($id) {
        $sql = 'SELECT * FROM users WHERE id = ?';
        $stmt = $this->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    /**
     * Find user by keyword
     * @param string $keyword
     * @return array
     */
    public function findUser($keyword) {
        $sql = 'SELECT * FROM users WHERE user_name LIKE ? OR user_email LIKE ?';
        $keyword = "%{$keyword}%";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('ss', $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    /**
     * Authentication user
     * @param string $userName
     * @param string $password
     * @return array
     */
    public function auth($userName, $password) {
        $sql = 'SELECT * FROM users WHERE name = ?';
        $stmt = $this->prepare($sql);
        $stmt->bind_param('s', $userName);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            return [$user]; // Trả về mảng để đồng bộ với code trước
        }
        return [];
    }

    /**
     * Delete user by ID
     * @param int $id
     * @return bool
     */
    public function deleteUserById($id) {
        $sql = 'DELETE FROM users WHERE id = ?';
        $stmt = $this->prepare($sql);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Update user
     * @param array $input
     * @return bool
     */
    public function updateUser($input) {
        $sql = 'UPDATE users SET name = ?, password = ? WHERE id = ?';
        $stmt = $this->prepare($sql);
        $hashedPassword = password_hash($input['password'], PASSWORD_DEFAULT);
        $stmt->bind_param('ssi', $input['name'], $hashedPassword, $input['id']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Insert user
     * @param array $input
     * @return int|null
     */
    public function insertUser($input) {
        $sql = 'INSERT INTO users (name, password) VALUES (?, ?)';
        $stmt = $this->prepare($sql);
        $hashedPassword = password_hash($input['password'], PASSWORD_DEFAULT);
        $stmt->bind_param('ss', $input['name'], $hashedPassword);
        $result = $stmt->execute();
        $lastId = $result ? $this->getLastInsertId() : null;
        $stmt->close();
        return $lastId;
    }

    /**
     * Search users
     * @param array $params
     * @return array
     */
    public function getUsers($params = []) {
        if (!empty($params['keyword'])) {
            $sql = 'SELECT * FROM users WHERE name LIKE ?';
            $keyword = "%{$params['keyword']}%";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('s', $keyword);
            $stmt->execute();
            $result = $stmt->get_result();
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            $stmt->close();
        } else {
            $sql = 'SELECT * FROM users';
            $users = $this->select($sql);
        }
        return $users;
    }
}