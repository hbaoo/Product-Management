<?php
require 'config/database.php';

try {
    $stmt = $pdo->query('SELECT 1');
    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
