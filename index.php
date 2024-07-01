<?php
require_once 'controllers/ProductController.php';
require_once 'controllers/AuthController.php';

$productController = new ProductController();
$authController = new AuthController();

$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!isset($_SESSION['user_id']) && $action != 'login' && $action != 'register') {
    $authController->showLoginForm();
    exit;
}

// Xác định đường dẫn của trang cần kiểm tra
$page = isset($_GET['page']) ? $_GET['page'] : null;

if ($page && !file_exists($page)) {
    http_response_code(404);
    include('views/404.php');
    exit();
}

if (in_array($action, ['login', 'logout', 'register'])) {
    $authController = new AuthController();
    $authController->handleRequest();
} else {
    $productController = new ProductController();
    $productController->handleRequest();
}
?>
