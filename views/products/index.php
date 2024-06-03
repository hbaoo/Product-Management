<?php include 'views/layout/header.php'; ?>
    <h2 class="mb-4">Product List</h2>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['status'] ? 'Active' : 'Inactive'; ?></td>
                    <td><img src="uploads/<?php echo $product['image']; ?>" class="img-thumbnail w-50"></td>
                    <td>
                        <a href="index.php?action=edit&id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="index.php?action=delete&id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php include 'views/layout/footer.php'; ?>
