<?php

require_once 'BaseModel.php';

class UserModel extends BaseModel {

    public function findUserById($id) {
        $stmt = self::$_connection->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $user;
    }

    public function findUser($keyword) {
        $like = "%" . $keyword . "%";
        $stmt = self::$_connection->prepare('SELECT * FROM users WHERE user_name LIKE ? OR user_email LIKE ?');
        $stmt->bind_param('ss', $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $user;
    }

    /**
     * Authentication user
     * @param $userName
     * @param $password
     * @return array
     */
    public function auth($userName, $password) {
        $md5Password = md5($password);
        $stmt = self::$_connection->prepare('SELECT * FROM users WHERE name = ? AND password = ?');
        $stmt->bind_param('ss', $userName, $md5Password);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $user;
    }

    /**
     * Delete user by id
     * @param $id
     * @return mixed
     */
    public function deleteUserById($id) {
        $stmt = self::$_connection->prepare('DELETE FROM users WHERE id = ?');
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Update user
     * @param $input
     * @return mixed
     */
 public function updateUser($input) {
    $fields = 'name = ?, fullname = ?, email = ?, type = ?, version = ?';
    $params = [
        $input['name'],
        $input['fullname'],
        $input['email'],
        $input['type'],
        (int)$input['version']
    ];
    $types = 'ssssi';
    if (!empty($input['password'])) {
        $fields .= ', password = ?';
        $params[] = md5($input['password']);
        $types .= 's';
    }
    $params[] = (int)$input['id'];
    $types .= 'i';
    $sql = 'UPDATE users SET ' . $fields . ' WHERE id = ?';
    $stmt = self::$_connection->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

 public function insertUser($input) {
    $sql = "INSERT INTO users (name, fullname, email, type, password, version) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = self::$_connection->prepare($sql);
    $password = md5($input['password']);
    $stmt->bind_param('sssssi', $input['name'], $input['fullname'], $input['email'], $input['type'], $password, $input['version']);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}


    /**
     * Search users
     * @param array $params
     * @return array
     */
    public function getUsers($params = []) {
        if (!empty($params['keyword'])) {
            $like = "%" . $params['keyword'] . "%";
            $stmt = self::$_connection->prepare('SELECT * FROM users WHERE name LIKE ?');
            $stmt->bind_param('s', $like);
            $stmt->execute();
            $result = $stmt->get_result();
            $users = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } else {
            $sql = 'SELECT * FROM users';
            $result = self::$_connection->query($sql);
            $users = [];
            if (!empty($result)) {
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
            }
        }
        return $users;
    }
}