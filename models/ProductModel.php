<?php

class ProductModel {
    public function getAllProducts() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    public function getProductById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createProduct() {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $status = $_POST['status'];
            $image = $_FILES['image']['name'];

            $target = 'uploads/' . basename($image);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $stmt = $pdo->prepare("INSERT INTO products (name, description, status, image) VALUES (?, ?, ?, ?)");
                return $stmt->execute([$name, $description, $status, $image]);
            }
            return false;
        }
        return "Invalid request method";
    }

    public function updateProduct($id) {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $status = isset($_POST['status']) ? 1 : 0;

            $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            $oldImage = $product['image'];

            $newImageName = null;
            if (!empty($_FILES['image']['name'])) {
                $newImageName = uniqid() . '_' . $_FILES['image']['name'];
                $target = 'uploads/' . $newImageName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, status = ?, image = ? WHERE id = ?");
                    $stmt->execute([$name, $description, $status, $newImageName, $id]);

                    if (!empty($oldImage) && file_exists('uploads/' . $oldImage)) {
                        unlink('uploads/' . $oldImage);
                    }
                    return true;
                }
            } else {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, status = ? WHERE id = ?");
                $stmt->execute([$name, $description, $status, $id]);
                return true;
            }
        }
        return false;
    }

    public function deleteProduct($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if ($product) {
            $imagePath = 'uploads/' . $product['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            return $stmt->execute([$id]);
        }
        return false;
    }
}
?>
