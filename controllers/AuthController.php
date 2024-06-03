<?php
session_start();
require_once 'config/database.php';

class AuthController {
    public function showLoginForm() {
        if (isset($_SESSION['success'])) {
            $success = $_SESSION['success'];
            unset($_SESSION['success']);
        }
        include 'views/auth/login.php';
    }

    public function login() {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && $user['password'] === md5($password)) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: index.php');
                exit;
            } else {
                $error = "Invalid username or password";
                include 'views/auth/login.php';
            }
        }
    }

    public function register() {
        global $pdo;
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $repassword = $_POST['repassword'];
            $phone = $_POST['phone'];
    
            if ($password !== $repassword) {
                $error = "Passwords do not match";
                include 'views/auth/register.php';
                return; 
            }
    
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
    
            if ($user) {
                $error = "Username already exists";
                include 'views/auth/register.php';
                return; 
            }
    
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $existingUser = $stmt->fetch();
    
            if ($existingUser) {
                $error = "Email already exists";
                include 'views/auth/register.php';
                return; 
            }
    
            $hashed_password = md5($password);
    
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, phone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password, $phone]);
    
            $_SESSION['success'] = "Registration successful! Please login.";
            header('Location: index.php');
            exit; 
        }
    
        // Hiển thị form đăng ký
        include 'views/auth/register.php';
    }
    
    

    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
?>
