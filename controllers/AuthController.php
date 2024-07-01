<?php
session_start();
require_once 'models/UserModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function showLoginForm() {
        if (isset($_SESSION['success'])) {
            $success = $_SESSION['success'];
            unset($_SESSION['success']);
        }
        include 'views/auth/login.php';
    }

    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'login';
        
        switch ($action) {
            case 'login':
                $this->login();
                break;
            case 'logout':
                $this->logout();
                break;
            case 'register':
                $this->register();
                break;
            default:
                header('Location: index.php');
                break;
        }
    }

    public function login() {
        if ($this->userModel->loginUser()) {
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid username or password";
            include 'views/auth/login.php';
        }
    }
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->userModel->registerUser();
            if ($result === true) {
                header('Location: index.php');
                exit;
            } else {
                $error = $result;
                include 'views/auth/register.php';
            }
        } else {
            include 'views/auth/register.php';
        }
    }

    public function logout() {
        $this->userModel->logoutUser();
        header('Location: index.php');
        exit;
    }
}
?>
