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

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ($this->userModel->loginUser($username, $password)) {
                header('Location: index.php');
                exit;
            } else {
                $error = "Invalid username or password";
                include 'views/auth/login.php';
            }
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $repassword = $_POST['repassword'];
            $phone = $_POST['phone'];

            $result = $this->userModel->registerUser($username, $email, $password, $repassword, $phone);
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
