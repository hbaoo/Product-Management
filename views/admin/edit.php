<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Edit User</h1>
        <?php if (isset($user) && !empty($user)): ?>
            <form action="index.php?module=user&action=edit&id=<?php echo $user['id']; ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="role">Role:</label>
                    <input type="text" class="form-control" name="role" value="<?php echo htmlspecialchars($user['role']); ?>">
                </div>
                
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="can_edit" name="can_edit" value="1" <?php echo $user['can_edit'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="can_edit">Can Edit</label>
                </div>
                
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="can_delete" name="can_delete" value="1" <?php echo $user['can_delete'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="can_delete">Can Delete</label>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        <?php else: ?>
            <p>User not found.</p>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
