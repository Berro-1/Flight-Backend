<?php
class User
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function create($username, $email, $password)
    {
        //validate if the user missed an empty imput
        if (empty($username) || empty($email) || empty($password) ) {
            return ["message" => "All fields are required."];
        }
        //validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["message" => "Invalid email format"];
        }

        // Check if email exists
        $stmt = $this->mysqli->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ["message" => "email or password invalid"];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $this->mysqli->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->bind_param("sssss", $username, $email, $hashedPassword);
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
                return ["message" => "invalid password or email"];
            }
        }
    }

    public function update($id, $username, $email, $password)
    {
        if (empty($username) || empty($email) || empty($password)) {
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
        $stmt = $this->mysqli->prepare('UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?');
        $stmt->bind_param("sssssi", $username,  $email, $hashedPassword, $id);
        $stmt->execute();

        return [
            "message" => "User updated successfully",
            "rowCount" => $stmt->affected_rows
        ];
    }

    public function delete($id)
    {
        if (!isset($id) || !is_numeric($id) || $id <= 0) {
            return ["message" => "Invalid user ID"];
        }

        // Check if user exists
        //$stmt = $this->mysqli->prepare('SELECT password FROM users WHERE id = ?');
        //if (!$stmt) {
            //return ["message" => "Database error: " . $this->mysqli->error];
        //}
        //$stmt->bind_param("i", $id);
        //$stmt->execute();
        //$result = $stmt->get_result();
        //$user = $result->fetch_assoc();

        //if (!$user) {
        //    return ["message" => "User not found"];
        //}

        // Validate the password
        //if (!password_verify($password, $user['password'])) {
          //  return ["message" => "Invalid password"];
        //}

        // Delete the user
        $stmt = $this->mysqli->prepare('DELETE FROM users WHERE id = ?');
        if (!$stmt) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return ["message" => "User deleted successfully"];
        } else {
            return ["message" => "User not found"];
        }
    }

}
