<?php
class User
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function create($first_name, $last_name, $email, $password, $phone)
    {
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($phone)) {
            return ["message" => "All fields are required."];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["message" => "Invalid email format"];
        }

        // Check if email exists
        $stmt = $this->mysqli->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ["message" => "Email already exists"];
        }

        // Check if phone exists
        $stmt = $this->mysqli->prepare('SELECT id FROM users WHERE phone = ?');
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ["message" => "Phone number already exists"];
        }
        if (!preg_match('/^\+?\d{10,15}$/', $phone)) {
            return ["message" => "Invalid phone number format. It should be 10 to 15 digits long and may start with a +"];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $this->mysqli->prepare('INSERT INTO users (first_name, last_name, email, password, phone) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashedPassword, $phone);
        $stmt->execute();

        return ["message" => "User created successfully"];
    }

    public function read()
    {
        $result = $this->mysqli->query('SELECT * FROM users');
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readOne($id)
    {
        $stmt = $this->mysqli->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function login($email, $password)
    {
        if (empty($email) || empty($password)) {
            return ["message" => "All fields are required."];
        }

        $stmt = $this->mysqli->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                return ["message" => "Login successful"];
            } else {
                return ["message" => "Invalid password"];
            }
        } else {
            return ["message" => "User not found"];
        }
    }

    public function update($id, $first_name, $last_name, $email, $password, $phone)
    {
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($phone)) {
            return ["message" => "All fields are required."];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["message" => "Invalid email format"];
        }

        // Check for email duplication against other user ids
        $stmt = $this->mysqli->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ["message" => "Email already exists"];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update user details
        $stmt = $this->mysqli->prepare('UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ?, phone = ? WHERE id = ?');
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $hashedPassword, $phone, $id);
        $stmt->execute();

        return [
            "message" => "User updated successfully",
            "rowCount" => $stmt->affected_rows
        ];
    }

    public function delete($id = null, $email = null)
    {
        if ($id) {
            $stmt = $this->mysqli->prepare('DELETE FROM users WHERE id = ?');
            $stmt->bind_param("i", $id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return ["message" => "User deleted successfully by ID", "rowCount" => $stmt->affected_rows];
            } else {
                return ["message" => "User not found with provided ID"];
            }
        } elseif ($email) {
            $stmt = $this->mysqli->prepare('DELETE FROM users WHERE email = ?');
            $stmt->bind_param("s", $email);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return ["message" => "User deleted successfully by email", "rowCount" => $stmt->affected_rows];
            } else {
                return ["message" => "User not found with provided email"];
            }
        } else {
            return ["message" => "ID or Email is required for deletion"];
        }
    }
}
