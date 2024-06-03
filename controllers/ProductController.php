<?php
require_once 'models/Product.php';


class ProductController {
    public function index() {
        global $pdo; 
        $stmt = $pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll();
        include 'views/products/index.php';
    }

    public function create() {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $status = $_POST['status'];
            $image = $_FILES['image']['name'];
            $target = 'uploads/' . basename($image);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $stmt = $pdo->prepare("INSERT INTO products (name, description, status, image) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $description, $status, $image]);
                header('Location: index.php');
                exit;
            }
        }
        include 'views/products/create.php';
    }

    public function edit($id) {
        global $pdo;
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $status = isset($_POST['status']) ? 1 : 0; 
    
            $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            $oldImage = $product['image'];
    
            if (!empty($_FILES['image']['name'])) {
                $newImageName = uniqid() . '_' . $_FILES['image']['name'];
                $target = 'uploads/' . $newImageName;
    
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, status = ?, image = ? WHERE id = ?");
                    $stmt->execute([$name, $description, $status, $newImageName, $id]);
    
                    if (!empty($oldImage) && file_exists('uploads/' . $oldImage)) {
                        unlink('uploads/' . $oldImage);
                    }
                }
            } else {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, status = ? WHERE id = ?");
                $stmt->execute([$name, $description, $status, $id]);
            }
    
            header('Location: index.php');
            exit;
        } else {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            include 'views/products/edit.php';
        }
    }
    
    
    
    
    
    public function delete($id) {
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
            $stmt->execute([$id]);
        }
    
        header('Location: index.php');
        exit;
    }    
}
?>

<?php



