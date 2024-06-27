<?php
require_once 'config/database.php';

class UserModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getUserByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function createUser($username, $email, $password, $phone) {
        $hashed_password = md5($password);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, phone) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $email, $hashed_password, $phone]);
    }

    public function loginUser($username, $password) {
        $user = $this->getUserByUsername($username);
        if ($user && $user['password'] === md5($password)) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        }
        return false;
    }

    public function registerUser($username, $email, $password, $repassword, $phone) {
        if ($password !== $repassword) {
            return "Passwords do not match";
        }

        if ($this->getUserByUsername($username)) {
            return "Username already exists";
        }

        if ($this->getUserByEmail($email)) {
            return "Email already exists";
        }

        if ($this->createUser($username, $email, $password, $phone)) {
            $_SESSION['success'] = "Registration successful! Please login.";
            return true;
        }

        return "Registration failed";
    }

    public function logoutUser() {
        session_destroy();
    }
}
?>
