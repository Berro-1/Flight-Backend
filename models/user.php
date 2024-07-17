<?php
require '../../vendor/autoload.php';
require_once '../../config/config.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class User
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function create($user_name, $email, $pass)
    {
        if (!isset($user_name) || !isset($email) || !isset($pass)) {
            return ["message" => "All fields are required."];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["message" => "Invalid email format"];
        }

        // Check if email exists
        $stmt = $this->mysqli->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ["message" => "User already exists"];
        }

        // Check if phone exists
        // $stmt = $this->mysqli->prepare('SELECT id FROM users WHERE phone = ?');
        // $stmt->bind_param("s", $phone);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // if ($result->num_rows > 0) {
        //     return ["message" => "Phone number already exists"];
        // }
        // if (!preg_match('/^\+?\d{10,15}$/', $phone)) {
        //     return ["message" => "Invalid phone number format. It should be 10 to 15 digits long and may start with a +"];
        // }

        $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $this->mysqli->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ? )');
        $stmt->bind_param("sss", $user_name, $email, $hashedPassword);
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
        if (!isset($email) || !isset($password)) {
            return ["success" => false, "message" => "All fields are required."];
        }

        // Check if the email is of an admin
        $stmt = $this->mysqli->prepare('SELECT * FROM admins WHERE admin_email = ?');
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        if ($admin) {
            if (password_verify($password, $admin['admin_password'])) {
                $payload = [
                    'iat' => time(),
                    'exp' => time() + 3600,
                    'sub' => $admin['admin_id'],
                    'role' => 'admin'
                ];
                $jwt = JWT::encode($payload, 'lfneauibfi3q4!#$1@$1241.24!a', 'HS256');
                return [
                    "success" => true,
                    "message" => "Admin login successful",
                    "jwt" => $jwt
                ];
            } else {
                return ["success" => false, "message" => "Invalid password"];
            }
        }

        // Check the users table
        $stmt = $this->mysqli->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($password,$user['password'])) {
                $payload = [
                    'iat' => time(),
                    'exp' => time() + 3600,
                    'sub' => $user['user_id'],
                    'role' => 'user'
                ];
                $jwt = JWT::encode($payload, 'en1iu2pebn1pi2b1o2n31@$?<1241@$1$1$!$!', 'HS256');
                return [
                    "success" => true,
                    "message" => "User login successful",
                    "jwt" => $jwt
                ];
            } else {
                return ["success" => false, "message" => "Invalid password"];
            }
        }

        return ["success" => false, "message" => "User not found"];
    }
        public function update($id, $first_name, $last_name, $email, $password, $phone)
    {
        if (isset($first_name) || isset($last_name) || isset($email) || isset($password) || isset($phone)) {
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