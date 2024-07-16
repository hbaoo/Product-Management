<?php
require_once 'models/ProductModel.php';

class ProductController {
    private $productModel;

    public function __construct() {
        if (!$_SESSION['user_id']) {
                header('Location: index.php?module=auth');
            }
            

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
                    header('Location: index.php');
                }
                break;
            case 'delete':
                if ($id) {
                    $this->delete($id);
                } else {
                    header('Location: index.php');
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
                header('Location: index.php');
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->productModel->updateProduct($id)) {
                header('Location: index.php');
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
    }

    public function delete($id) {
        if ($this->productModel->deleteProduct($id)) {
            header('Location: index.php');
            exit;
        }
    }
}
?>
