<?php
require_once 'models/ProductModel.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function index() {
        $products = $this->productModel->getAllProducts();
        include 'views/products/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $status = $_POST['status'];
            $image = $_FILES['image']['name'];

            if ($this->productModel->createProduct($name, $description, $status, $image)) {
                header('Location: index.php');
                exit;
            }
        } else {
            include 'views/products/create.php';
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $status = isset($_POST['status']) ? 1 : 0;

            $newImageName = null;
            if (!empty($_FILES['image']['name'])) {
                $newImageName = uniqid() . '_' . $_FILES['image']['name'];
            }

            if ($this->productModel->updateProduct($id, $name, $description, $status, $newImageName)) {
                header('Location: index.php');
                exit;
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
