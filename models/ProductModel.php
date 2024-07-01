<?php
require_once 'config/database.php';

class ProductModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAllProducts() {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    public function getProductById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $status = $_POST['status'];
            $image = $_FILES['image']['name'];

            $target = 'uploads/' . basename($image);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $stmt = $this->pdo->prepare("INSERT INTO products (name, description, status, image) VALUES (?, ?, ?, ?)");
                return $stmt->execute([$name, $description, $status, $image]);
            }
            return false;
        }
        return "Invalid request method";
    }

    public function updateProduct($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $status = isset($_POST['status']) ? 1 : 0;

            $stmt = $this->pdo->prepare("SELECT image FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            $oldImage = $product['image'];

            $newImageName = null;
            if (!empty($_FILES['image']['name'])) {
                $newImageName = uniqid() . '_' . $_FILES['image']['name'];
                $target = 'uploads/' . $newImageName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $stmt = $this->pdo->prepare("UPDATE products SET name = ?, description = ?, status = ?, image = ? WHERE id = ?");
                    $stmt->execute([$name, $description, $status, $newImageName, $id]);

                    if (!empty($oldImage) && file_exists('uploads/' . $oldImage)) {
                        unlink('uploads/' . $oldImage);
                    }
                    return true;
                }
            } else {
                $stmt = $this->pdo->prepare("UPDATE products SET name = ?, description = ?, status = ? WHERE id = ?");
                $stmt->execute([$name, $description, $status, $id]);
                return true;
            }
        }
        return false;
    }

    public function deleteProduct($id) {
        $stmt = $this->pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if ($product) {
            $imagePath = 'uploads/' . $product['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
            return $stmt->execute([$id]);
        }
        return false;
    }
}
?>
