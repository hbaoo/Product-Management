<?php
require_once 'models/ProductModel.php';
require_once 'models/UserModel.php';

class ProductController {
    private $productModel;
    private $userModel;

    public function __construct() {
        if (!$_SESSION['user_id']) {
                header('Location: index.php?module=auth&action=login');
            }
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
    }

    public function index() {
        $products = $this->productModel->getAllProducts();
        include 'views/products/index.php';
    }

    

    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        switch ($action) {
            case 'create':
                $this->create();
                break;
            case 'edit':
                if ($id) {
                    $this->edit($id);
                } else {
                    header('Location: index.php?module=product');
                }
                break;
            case 'delete':
                if ($id) {
                    $this->delete($id);
                } else {
                    header('Location: index.php?module=product');
                }
                break;
            default:
                $this->index();
                break;
        }
    }


    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->productModel->createProduct()) {
                header('Location: index.php?module=product');
                exit;
            } else {
                $error = "Failed to create product";
                include 'views/products/create.php';
            }
        } else {
            include 'views/products/create.php';
        }
    }

    public function edit($id) {
        $module = 'product';

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        if ($user['can_edit']) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->productModel->updateProduct($id)) {
                    header('Location: index.php?module=product');
                    exit;
                } else {
                    $error = "Failed to update product";
                    $product = $this->productModel->getProductById($id);
                    include 'views/products/edit.php';
                }
            } else {
                $product = $this->productModel->getProductById($id);
                include 'views/products/edit.php';
            }
        } else {
            $error = "You do not have permission to edit this product.";
            include 'views/404.php'; 
        }
    }

    public function delete($id) {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        if ($user['can_delete']) {
            if ($this->productModel->deleteProduct($id)) {
                header('Location: index.php?module=product');
                exit;
            } else {
                $error = "Failed to delete product";
                include 'views/404.php';
            }
        } else {
            $error = "You do not have permission to delete this product.";
            include 'views/404.php'; 
        }
    }
    
}
?>
