<?php
require_once 'db.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = getDbConnection();
    }

    public function getAllProducts() {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    public function getProductById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createProduct($name, $description, $status, $image) {
        $stmt = $this->db->prepare("INSERT INTO products (name, description, status, image) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $description, $status, $image]);
    }

    public function updateProduct($id, $name, $description, $status, $image) {
        $stmt = $this->db->prepare("UPDATE products SET name = ?, description = ?, status = ?, image = ? WHERE id = ?");
        return $stmt->execute([$name, $description, $status, $image, $id]);
    }

    public function deleteProduct($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
