<?php
require_once 'config/database.php';

class UserModel {

    public function getAllUsers() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public function getUserById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getUserByUsername($username) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function getUserByEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function createUser($username, $email, $password, $phone) {
        global $pdo;
        $hashed_password = md5($password);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, phone) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $email, $hashed_password, $phone]);
    }

    public function loginUser() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            $user = $this->getUserByUsername($username);
            if ($user && $user['password'] === md5($password)) {
                return $user;
            }
        }
        return false;
    }

    public function registerUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $repassword = $_POST['repassword'];
            $phone = $_POST['phone'];
            // $role = $_POST['role'] ?? 'user';

            if ($password !== $repassword) {
                return "Passwords do not match";
            }

            if ($this->getUserByUsername($username)) {
                return "Username already exists";
            }

            if ($this->getUserByEmail($email)) {
                return "Email already exists";
            }

            if ($this->createUser($username, $email, $password, $phone /*$role*/)) {
                $_SESSION['success'] = "Registration successful! Please login.";
                return true;
            }

            return "Registration failed";
        }
        return "Invalid request method";
    }

    public function updateUser($id) {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $role = $_POST['role'];
    
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, phone = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $email, $phone, $role, $id]);
    
            return true;
        }
        return false;
    }

    public function deleteUser($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
    
        if ($user) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            return $stmt->execute([$id]);
        } else {
            return false; 
        }
    }
    
    

    public function logoutUser() {
        session_destroy();
    }
}
?>
