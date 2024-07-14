<?php
class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createUser($username, $email, $password) {
        if (empty($username) || empty($email) || empty($password)) {
            return ["error" => "All fields are required"];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '.com')) {
            return ["error" => "Provide a valid email address ending with \".com\""];
        }

        if (strlen($password) < 8) {
            return ["error" => "Password must be at least 8 characters long"];
        }

        $stmt = $this->conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        if (!$stmt) {
            return ["error" => "Prepare failed: " . $this->conn->error];
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bind_param('sss', $username, $email, $hashed_password);

        try {
            $stmt->execute();
            return ["message" => "User created successfully", "status" => "success"];
        } catch (Exception $e) {
            return ["error" => $stmt->error];
        }
    }

    public function updateUser($user_id, $username, $email, $password = null) {
        if (empty($username) || empty($email)) {
            return ["error" => "Username and email are required"];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '.com')) {
            return ["error" => "Provide a valid email address ending with \".com\""];
        }

        if ($password && strlen($password) < 8) {
            return ["error" => "Password must be at least 8 characters long"];
        }

        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare('UPDATE users SET username = ?, email = ?, password = ? WHERE user_id = ?');
            $stmt->bind_param('sssi', $username, $email, $hashed_password, $user_id);
        } else {
            $stmt = $this->conn->prepare('UPDATE users SET username = ?, email = ? WHERE user_id = ?');
            $stmt->bind_param('ssi', $username, $email, $user_id);
        }

        try {
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return ["message" => "User updated successfully"];
            } else {
                return ["message" => "No changes made"];
            }
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function loginUser($email, $password) {
        if (empty($email) || empty($password)) {
            return ["error" => "Email and password are required"];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["error" => "Provide a valid email address"];
        }

        $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = ?');
        if (!$stmt) {
            return ["error" => "Prepare failed: " . $this->conn->error];
        }
        $stmt->bind_param('s', $email);

        if (!$stmt->execute()) {
            return ["error" => "Execute failed: " . $this->conn->error];
        }

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            return ["message" => "Login successful", "status" => "success"];
        } else {
            return ["error" => "Invalid email or password"];
        }
    }

    public function logoutUser() {
        session_unset();
        session_destroy();
        return ["message" => "Logout successful", "status" => "success"];
    }

    public function getUserById($user_id) {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE user_id = ?');
        if (!$stmt) {
            return ["error" => "Prepare failed: " . $this->conn->error];
        }
        $stmt->bind_param('i', $user_id);

        if (!$stmt->execute()) {
            return ["error" => "Execute failed: " . $this->conn->error];
        }

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user) {
            return $user;
        } else {
            return ["message" => "No record found"];
        }
    }

    // Search for users based on a search term
    public function searchUsers($term) {
        $term = '%' . $term . '%';
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE username LIKE ? OR email LIKE ?');
        if (!$stmt) {
            return ["error" => "Prepare failed: " . $this->conn->error];
        }
        $stmt->bind_param('ss', $term, $term);

        if (!$stmt->execute()) {
            return ["error" => "Execute failed: " . $this->conn->error];
        }

        $result = $stmt->get_result();
        $users = [];
        while ($user = $result->fetch_assoc()) {
            $users[] = $user;
        }

        return $users;
    }

    // Get all users
    public function getAllUsers() {
        $stmt = $this->conn->prepare('SELECT * FROM users');
        if (!$stmt) {
            return ["error" => "Prepare failed: " . $this->conn->error];
        }

        if (!$stmt->execute()) {
            return ["error" => "Execute failed: " . $this->conn->error];
        }

        $result = $stmt->get_result();
        $users = [];
        while ($user = $result->fetch_assoc()) {
            $users[] = $user;
        }

        return $users;
    }
}
?>
